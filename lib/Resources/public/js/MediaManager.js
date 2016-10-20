var MediaManager = function(updateUrl, endpoints) {

    let defaultEndpoints = {
        "loadLibraries": {path: "/libraries", method: 'GET'},
        "loadLibrary": {path: "/library/{id}", method: 'GET'},
        "loadFile": {path: "/file/{id}", method: 'GET'},
        "filter": {path: "/library/{id}/filter", method: 'POST'},
        "addLibrary": {path: "/library/add", method: 'POST'},
        "addFile": {path: "/file/add", method: 'POST'}
    };

    this.libraryUrl = updateUrl;
    this.endpoints = (endpoints != undefined) ? endpoints : defaultEndpoints;

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
        url = url.replace(/\{id\}/i, id);

        AjaxHelper.request.call(this, url, endpoint.method, null, null, successCb, errorCb, completeCb);
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

        AjaxHelper.request.call(this, url, endpoint.method, null, null, successCb, errorCb, completeCb);
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
        url = url.replace(/\{id\}/i, id);

        AjaxHelper.request.call(this, url, endpoint.method, null, null, successCb, errorCb, completeCb);
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
        url = url.replace(/\{id\}/i, id);

        let out = [];
        for (key in filters) {
            out.push(key + '=' + encodeURIComponent(filters[key]));
        }
        out.join('&');

        AjaxHelper.request.call(this, url, endpoint.method, out, null, successCb, errorCb, completeCb);
    }.bind(this);

    /**
     * Important! Call function with library context (it requires access to libraryUrl and endpoints variables)
     *
     * @param id
     */
    var addLibrary = function(name, successCb, errorCb, completeCb) {
        let endpoint = this.endpoints['addLibrary'];

        if(!endpoint) {
            throw new ReferenceError('Endpoint doesnt exist.');
        }

        let url = this.libraryUrl + endpoint.path;
        let formData = new FormData();
        formData.append('library_name', name);

        AjaxHelper.request.call(this, url, endpoint.method, formData, null, successCb, errorCb, completeCb);
    }.bind(this);

    /**
     * Important! Call function with library context (it requires access to libraryUrl and endpoints variables)
     *
     * @param id
     */
    var addFile = function(name, file, library, successCb, errorCb, completeCb) {
        let endpoint = this.endpoints['addFile'];

        if(!endpoint) {
            throw new ReferenceError('Endpoint doesnt exist.');
        }

        let url = this.libraryUrl + endpoint.path;

        let formData = new FormData();
        formData.append('file_name', name);
        formData.append('file_file', file);
        formData.append('file_library', library);

        AjaxHelper.request.call(this, url, endpoint.method, formData, null, successCb, errorCb, completeCb);
    }.bind(this);

    return {
        "loadLibraries": loadLibraries,
        "loadLibrary": loadLibrary,
        "addLibrary": addLibrary,
        "addFile": addFile,
        "loadFile": loadFile,
        "filter": filter
    };

};