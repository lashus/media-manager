var MediaManagerDecorator = function(basePath, availableFilters) {

    this.basePath = basePath;
    this.templatesPaths = {
        'libraries': '/template/libraries.mustache',
        'library': '/template/library.mustache',
        'file': '/template/file.mustache',
        'filters': '/template/filters.mustache',
        'menu': '/template/menu.mustache',
        'modal': '/template/modal.mustache',
        'add_library': '/template/add_library.mustache',
        'add_file': '/template/add_file.mustache'
    };
    this.templates = {};

    this.availableFilters = [
        {"title": MediaManagerI18N.filters.name.title, "type": "text", "field": "name"},
        {"title": MediaManagerI18N.filters.size.title, "help_text": MediaManagerI18N.filters.size.help_text, "type": "range", "field": "size"},
        {
            "title": MediaManagerI18N.filters.mimetype.title,
            "type": "choice",
            "field": "mimetype",
            "choices": [
                {"label": "Images", "value": "image/*"},
                {"label": "PDF files", "value": "application/pdf"},
                {"label": "Video files", "value": "video/*"},
                {"label": "Audio files", "value": "audio/*"},
                {"label": "Text files", "value": "text/plain"}
            ]
        }
    ];
    if(availableFilters != undefined) {
        this.availableFilters = availableFilters;
    }

    if(typeof Mustache === undefined) {
        throw new ReferenceError('This library required Mustache decorator');
    }

    /**
     * Date formatter
     *
     * @type {function(this:MediaManagerDecorator)}
     */
    var displayDate = function(date) {
        var dt = new Date(date);

        return dt.getFullYear()+'-'+(parseInt(dt.getMonth())+1)+'-'+dt.getDate()+' '+dt.getHours()+':'+dt.getMinutes();

    }.bind(this);

    /**
     * Size and unit formatted
     *
     * @type {function(this:MediaManagerDecorator)}
     */
    var sizeWithUnit = function(size) {

        size = parseFloat(size);

        var gb = size / (1024*1024*1024);
        var mb = size / (1024*1024);
        var kb = size / (1024);

        if(gb > 0.8) {
            return gb.toFixed(2) + ' GB';
        }

        if(mb > 0.8) {
            return mb.toFixed(2) + ' MB';
        }

        if(kb > 0.8) {
            return kb.toFixed(2) + ' KB';
        }

        return size.toFixed(2) + ' B';

    }.bind(this);

    /**
     * Type by mimetype
     *
     * @type {function(this:MediaManagerDecorator)}
     */
    var typeByMimetype = function(mimetype) {
        switch(mimetype) {
            case 'application/pdf':
                return 'pdf';
            break;

            case 'image/jpeg':
            case 'image/png':
            case 'image/gif':
            case 'image/bmp':
            case 'image/tiff':
                return 'image';
            break;

            case 'audio/mpeg':
            case 'audio/x-mpeg':
            case 'audio/mpeg3':
            case 'audio/x-mpeg-3':
            case 'audio/x-mpequrl':
            case 'audio/wav':
            case 'audio/ogg':
            case 'audio/webm':
                return 'audio';
            break;

            case 'video/mp4':
            case 'video/mpeg':
            case 'video/avi':
            case 'video/msvideo':
            case 'video/x-msvideo':
            case 'video/ogg':
            case 'video/webm':
                return 'video';
            break;

            default:
                return 'fike';
        }

    }.bind(this);


    /**
     * Load defined templates from files
     */
    var loadTemplates = function() {
        for(key in this.templatesPaths) {
            let url = this.basePath + this.templatesPaths[key];
            AjaxHelper.request(url, 'GET', null, null, function(key, resp){
                this.templates[key] = resp;
            }.bind(this, key), function(key, resp, code) {
                throw new ReferenceError("Unable to load specified template files. Check if they exist and structure. Code:" + code);
            }.bind(this, key));
        }

        console.log('Templates loaded.');

    }.bind(this);

    var render = function(name, data) {

        if(!this.templates.hasOwnProperty(name)) {
            throw new ReferenceError('Template not found');
        }
        if(data == undefined) {
            data = {};
        }

        let textFilters = [];
        let selectFilters = [];
        let rangeFilters = [];
        for(key in this.availableFilters) {
            let obj = this.availableFilters[key];

            switch(obj.type) {
                case 'text':
                    textFilters.push(obj);
                break;

                case 'range':
                    rangeFilters.push(obj);
                break;

                case 'choice':
                    selectFilters.push(obj);
                break;
            }
        }

        let template = this.templates[name];
        data.i18n = MediaManagerI18N;
        data.textFilters = textFilters;
        data.selectFilters = selectFilters;
        data.rangeFilters = rangeFilters;

        if(data.libraries) {
            data.libraries.forEach(function(displayDate, item, idx){
                item.date = displayDate(item.created);
                return item;
            }.bind(this, displayDate));
        }

        if(data.files) {
            data.files.forEach(function(sizeWithUnit, typeByMimetype, displayDate, item, idx){

                item.sizeWithUnit = sizeWithUnit(item.size);
                item.type = typeByMimetype(item.mimetype);
                item.date = displayDate(item.created);

                return item;

            }.bind(this, sizeWithUnit, typeByMimetype, displayDate));
        }

        if(data.file) {

            data.file.sizeWithUnit = sizeWithUnit(data.file.size);
            data.file.type = typeByMimetype(data.file.mimetype);
            data.file.date = displayDate(data.file.created);

            switch(data.file.type) {
                case 'audio':
                    data.file.audioPreview = [data.file];
                break;
                case 'video':
                    data.file.videoPreview = [data.file];
                break;
                case 'image':
                    data.file.imagePreview = [data.file];
                break;
                default:
                    data.file.noPreview = [data.file];
            }

        }

        return Mustache.render(template, data);

    }.bind(this);

    var getFilters = function() {
        return this.availableFilters;
    }.bind(this);

    // initially load templates
    loadTemplates();

    return {
        "loadTemplates": loadTemplates,
        "render": render,
        "getFilters": getFilters
    };

};