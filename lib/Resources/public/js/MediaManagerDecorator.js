import { AjaxHelper } from "AjaxHelper";

export default function(basePath) {

    this.basePath = basePath;
    this.templatesPaths = {
        'libraries': '/../template/libraries.mustache',
        'library': '/../template/library.mustache',
        'file': '/../template/file.mustache',
        'filters': '/../template/filters.mustache'
    };
    this.templates = {};

    if(typeof Mustache === undefined) {
        throw new ReferenceError('This library required Mustache decorator');
    }

    /**
     * Load defined templates from files
     */
    var loadTemplates = function() {

        for(key in this.templatesPaths) {
            let url = this.basePath + this.templatesPaths[key];
            AjaxHelper.request(url, 'GET', null, null, function(resp){
                this.templates[key] = resp;
            }.bind(this), function(resp, code) {
                throw new ReferenceError("Unable to load specified template files. Check if they exist and structure. Code:" + code);
            }.bind(this));
        }

    }.bind(this);

    var render = function(name, data) {

        if(!templates.hasOwnProperty(name)) {
            throw new ReferenceError('Template not found');
        }
        let template = this.templates[name];

        return Mustache.render(template, data);

    }.bind(this);

    // initially load templates
    loadTemplates();

    return {
        "loadTemplates": loadTemplates,
        "render": render
    };

};