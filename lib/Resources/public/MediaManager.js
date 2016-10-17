export default function(updateUrl, endpoints) {

    let defaultEndpoints = {
        "loadLibraries": {path: "/libraries", method: 'GET'},
        "loadLibrary": {path: "/library/{id}", method: 'GET'},
        "loadFile": {path: "/file/{id}", method: 'GET'},
        "filter": {path: "/library/{id}/filter", method: 'POST'}
    };

    this.libraryUrl = updateUrl;
    this.endpoints = (endpoints != undefined) ? endpoints : defaultEndpoints;

    /**
     * Ajax method for requesting data
     *
     * @param url
     * @param method 'POST', 'PUT', 'GET', 'PATCH'
     * @param data
     * @param headers Additional headers to be attached
     * @param successCb Success Callback - receives data and status
     * @param errorCb Error Callback - receives data and status
     * @param completeCb Completed callback - receives data and status
     */
    this.ajaxRequest = function(url, method, data, headers, successCb, errorCb, completeCb) {
        let request = new XMLHttpRequest();

        /**
         * Check allowed methods
         */
        let allowedMethods = ["PUT", "POST", 'PATCH', 'GET'];
        let normalizedMethod = String(method).toLocaleUpperCase();
        if(allowedMethods.indexOf(normalizedMethod) == -1) {
            console.error('Unsupported method: '+method);
            return false;
        }

        if(headers != undefined && headers.length > 0) {
            for(key in headers) {
                request.setRequestHeader(key.toLocaleLowerCase(), headers[key]);
            }
        }

        switch(normalizedMethod) {
            case 'PUT':
                if(!headers.hasOwnProperty('content-type')) {
                    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                }
            break;

            case 'PATCH':
                if(!headers.hasOwnProperty('content-type')) {
                    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                }
            break;

            case 'POST':
                if(!headers.hasOwnProperty('content-type')) {
                    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                }
            break;
        }

        /**
         * Perform request
         */
        request.open(method, url, true);
        request.onload = function() {
            var data = request.responseText;

            if (request.status >= 200 && request.status < 400) {
                if(successCb != undefined) {
                    successCb(data, request.status);
                }
            } else {
                if(errorCb != undefined) {
                    errorCb(data, request.status);
                }
            }

            if(completeCb != undefined) {
                completeCb(data, request.status);
            }
        };

        request.onerror = errorCb(null, request.status);

        if(data != undefined && data.length > 0) {
            request.send(data);
        } else {
            request.send();
        }
    };

    /**
     * Important! Call function with library context (it requires access to libraryUrl and endpoints variables)
     *
     * @param id
     */
    var loadLibrary = function(id, successCb, errorCb, completeCb) {
        let endpoint = this.endpoints['loadLibrary'];

        if(!endpoint) {
            throw new ReferenceError('Endpoint doesnt exist.');
        }

        let url = this.libraryUrl + endpoint.path;
        url.replace(/\{id\}/i, id);

        this.ajaxRequest.call(this, url, endpoint.method, null, null, successCb, errorCb, completeCb);
    }.bind(this);

    /**
     * Important! Call function with library context (it requires access to libraryUrl and endpoints variables)
     *
     * @param id
     */
    var loadLibraries = function(successCb, errorCb, completeCb) {
        let endpoint = this.endpoints['loadLibraries'];

        if(!endpoint) {
            throw new ReferenceError('Endpoint doesnt exist.');
        }

        let url = this.libraryUrl + endpoint.path;

        this.ajaxRequest.call(this, url, endpoint.method, null, null, successCb, errorCb, completeCb);
    }.bind(this);

    /**
     * Important! Call function with library context (it requires access to libraryUrl and endpoints variables)
     *
     * @param id
     */
    var loadFile = function(id, successCb, errorCb, completeCb) {
        let endpoint = this.endpoints['loadFile'];

        if(!endpoint) {
            throw new ReferenceError('Endpoint doesnt exist.');
        }

        let url = this.libraryUrl + endpoint.path;
        url.replace(/\{id\}/i, id);

        this.ajaxRequest.call(this, url, endpoint.method, null, null, successCb, errorCb, completeCb);
    }.bind(this);

    /**
     * Important! Call function with library context (it requires access to libraryUrl and endpoints variables)
     *
     * @param id
     */
    var filter = function(id, filters, successCb, errorCb, completeCb) {
        let endpoint = this.endpoints['loadFile'];

        if(!endpoint) {
            throw new ReferenceError('Endpoint doesnt exist.');
        }

        let url = this.libraryUrl + endpoint.path;
        url.replace(/\{id\}/i, id);

        let out = [];
        for (key in filters) {
            out.push(key + '=' + encodeURIComponent(filters[key]));
        }
        out.join('&');

        this.ajaxRequest.call(this, url, endpoint.method, out, null, successCb, errorCb, completeCb);
    }.bind(this);

    return {
        "loadLibraries": loadLibraries,
        "loadLibrary": loadLibrary,
        "loadFile": loadFile,
        "filter": filter
    };

};