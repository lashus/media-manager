export default function() {

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
    this.request = function(url, method, data, headers, successCb, errorCb, completeCb) {
        
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
}