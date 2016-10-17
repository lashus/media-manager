import { AjaxHelper } from "AjaxHelper";

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
        url.replace(/\{id\}/i, id);

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
        url.replace(/\{id\}/i, id);

        let out = [];
        for (key in filters) {
            out.push(key + '=' + encodeURIComponent(filters[key]));
        }
        out.join('&');

        AjaxHelper.request.call(this, url, endpoint.method, out, null, successCb, errorCb, completeCb);
    }.bind(this);

    return {
        "loadLibraries": loadLibraries,
        "loadLibrary": loadLibrary,
        "loadFile": loadFile,
        "filter": filter
    };

};