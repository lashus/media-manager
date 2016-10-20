var AjaxHelper = {


    /**
     * Ajax method for requesting data
     *
     * @param url
     * @param method 'POST', 'PUT', 'GET', 'PATCH'
     * @param sendData
     * @param headers Additional headers to be attached
     * @param successCb Success Callback - receives data and status
     * @param errorCb Error Callback - receives data and status
     * @param completeCb Completed callback - receives data and status
     */
    request: function(url, method, sendData, headers, successCb, errorCb, completeCb) {


        /**
         * Check allowed methods
         */
        let allowedMethods = ["PUT", "POST", 'PATCH', 'GET'];
        let normalizedMethod = String(method).toLocaleUpperCase();
        if(allowedMethods.indexOf(normalizedMethod) == -1) {
            console.error('Unsupported method: '+method);
            return false;
        }

        let request = new XMLHttpRequest();
        request.open(method, url, true);

        if(headers != undefined && headers.length > 0) {
            for(key in headers) {
                request.setRequestHeader(key.toLocaleLowerCase(), headers[key]);
            }
        }

        /**
         * Perform request
         */
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

        request.onerror = errorCb;
        if(sendData != undefined) {
            request.send(sendData);
        } else {
            request.send();
        }
    }
};
