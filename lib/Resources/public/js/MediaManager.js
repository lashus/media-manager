var MediaManager = function(updateUrl, endpoints) {

    let defaultEndpoints = {
        "loadLibraries": {path: "/libraries", method: 'GET'},
        "loadLibrary": {path: "/library/{id}", method: 'GET'},
        "loadFile": {path: "/file/{id}", method: 'GET'},
        "filter": {path: "/library/{id}/filter", method: 'POST'},
        "addLibrary": {path: "/library/add", method: 'POST'},
        "addFile": {path: "/file/add", method: 'POST'}
    };

    this.activeFilters = [];
    this.libraryUrl = updateUrl;
    this.endpoints = (endpoints != undefined) ? endpoints : defaultEndpoints;

    var getFiltersAsParams = function(url, activeFilters) {

        let out = [];
        for(key in activeFilters) {
            let row = activeFilters[key];
            let name = row.name;
            let param = '';

            if(row.type != 'range') {
                param = row.value;
            } else {
                param = row.min+'|AND|'+row.max;
            }

            out.push("filters["+name+']='+encodeURIComponent(param));
        }

        return url + '&' + out.join('&');

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
    var loadLibrary = function(id, successCb, errorCb, completeCb) {

        let endpoint = this.endpoints['loadLibrary'];

        if(!endpoint) {
            throw new ReferenceError('Endpoint doesnt exist.');
        }

        let url = this.libraryUrl + endpoint.path;
        url = url.replace(/\{id\}/i, id);
        if(this.activeFilters.length) {
            url = getFiltersAsParams(url, this.activeFilters);
        }

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

        // if any active filter - send params
        let url = this.libraryUrl + endpoint.path;
        if(this.activeFilters.length) {
            url = getFiltersAsParams(url, this.activeFilters);
        }

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

    var saveFilters = function(filters) {

        this.activeFilters = [];
        for(key in filters) {

            var row = filters[key];

            if(row.type != "range") {
                if(row.value.length > 0) {
                    this.activeFilters.push(row);
                }
            } else {
                if(row.min.length > 0 || row.max.length > 0) {
                    this.activeFilters.push(row);
                }
            }

        }

    }.bind(this);

    return {
        "loadLibraries": loadLibraries,
        "loadLibrary": loadLibrary,
        "addLibrary": addLibrary,
        "addFile": addFile,
        "loadFile": loadFile,
        "filter": filter,
        "saveFilters": saveFilters
    };

};