var endpoints = {
    "loadLibraries": {path: "/data.php?p=libraries", method: 'GET'},
    "loadLibrary": {path: "/data.php?p=library&id={id}", method: 'GET'},
    "loadFile": {path: "/data.php?p=file&id={id}", method: 'GET'},
    "filter": {path: "/data.php?p=library&id={id}", method: 'POST'},
    "addLibrary": {path: "/data.php?p=add_library", method: 'POST'},
    "addFile": {path: "/data.php?p=add_file", method: 'POST'}
};

var mm = new MediaManager('http://mediamanager.local', endpoints);
var mmd = new MediaManagerDecorator('lib');
var currentView = 'libraries';
var previousView = '';

var currentLibraryId = null;
var currentFileId = null;


function initLibrary() {
    var html = mmd.render('modal');
    var menu = mmd.render('menu');
    showButtons(['close']);

    // init modal
    $('body').append($(html));

    // load menu
    loadMenu();

    // set base view
    changeLeftContent(menu);
    changeRightContent('');

    $('#media-manager-modal').modal('show');

    librariesView();
    bindActions();
}

function bindActions() {
    $('#media-manager-modal .add-library').unbind('click').click(addLibraryView);
    $('#media-manager-modal .add-file').unbind('click').click(addFileView);
    $('#media-manager-modal .library-element, #media-manager-modal .library-link').unbind('click').click(function(){
        currentLibraryId = $(this).data('id');
        showLibraryView();
    });
    $('#media-manager-modal .file-element').unbind('click').click(function(){
        currentFileId = $(this).data('id');
        showFileView();
    });
}

function addFileView() {
    var view = $(mmd.render('add_file'));
    changeRightContent(view);

    showButtons(['save', 'close', 'cancel']);

    $('#media-manager-modal .save-btn').unbind('click').click(function(){
        var name = $('#mm_file_name').val();
        var file = $('#mm_file_file')[0].files[0];

        mm.addFile(name, file, currentLibraryId, function(resp){
            showLibraryView();
            bindActions();

        }, displayError);
    });

    $('#media-manager-modal .cancel-btn').unbind('click').click(function(){
        showLibraryView.call(this);
    });

    changeView(currentView, 'addFile');
    bindActions();
}

function addLibraryView() {
    var view = $(mmd.render('add_library'));
    changeRightContent(view);

    showButtons(['save', 'close', 'cancel']);

    $('#media-manager-modal .save-btn').unbind('click').click(function(){
        var name = $('#mm_library_name').val();
        mm.addLibrary(name, function(resp){

            librariesView();
            bindActions();
            loadMenu();

        }, displayError);
    });

    $('#media-manager-modal .cancel-btn').unbind('click').click(function(){
        librariesView();
    });

    changeView(currentView, 'addLibrary');
    bindActions();
}

function librariesView() {

    showButtons(['close']);

    mm.loadLibraries(function(resp){

        try {
            resp = $.parseJSON(resp);
        } catch(error) {}

        var view = mmd.render('libraries', {"libraries": resp});
        changeRightContent(view);

        showButtons(['close']);
        bindActions();

    }, displayError);

    changeView(currentView, 'libraries');
}

function showFileView() {

    var id = currentFileId;
    showButtons(['close', 'cancel']);

    mm.loadFile(id, function(resp){

        try {
            resp = $.parseJSON(resp);
        } catch(error) {}

        var view = mmd.render('file', resp);
        changeRightContent(view);
        bindActions();

        $('#media-manager-modal .cancel-btn').unbind('click').click(function(){
            showLibraryView.call(this);
        });

    }, displayError);

    changeView(currentView, 'file');
}

function showLibraryView() {

    var id = currentLibraryId;
    showButtons(['close', 'cancel']);

    mm.loadLibrary(id, function(resp){

        try {
            resp = $.parseJSON(resp);
        } catch(error) {}

        var view = mmd.render('library', resp);
        changeRightContent(view);

        bindActions();

    }, displayError);

    $('#media-manager-modal .cancel-btn').unbind('click').click(function(){
        librariesView.call(this);
    });

    changeView(currentView, 'library');
}

function displayError(msg) {
    alert(msg);
}

function displaySuccess(msg) {
    alert(msg);
}

function changeLeftContent(view) {
    $('#media-manager-modal .left').html('');
    $('#media-manager-modal .left').append(view);
}

function changeRightContent(view) {
    $('#media-manager-modal .right').html('');
    $('#media-manager-modal .right').append(view);
}

function showButtons(buttons) {

    $('#media-manager-modal .modal-footer .btn').addClass('hidden');

    for(key in buttons) {
        var btn = buttons[key];
        $('#media-manager-modal .modal-footer .'+btn+'-btn').removeClass('hidden');
    }
}

function changeView(previous, next) {
    currentView = next;
    previousView = previous;
}

function loadMenu() {

    mm.loadLibraries(function(resp){

        try {
            resp = $.parseJSON(resp);
        } catch(error) {}

        var view = mmd.render('menu', {"libraries": resp});
        changeLeftContent(view);
        bindActions();

    }, displayError);

}