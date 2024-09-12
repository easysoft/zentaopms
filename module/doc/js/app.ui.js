let lastAppUrl = '';
let originalDocumentTitle = document.title;
let documentTitleSuffix = ' - ' + originalDocumentTitle.split(' - ').pop();

function getDocApp()
{
    const docApp = $('#docApp').zui('docApp');
    return docApp ? docApp.$ : null;
}

function processDocAppAction(action, docApp)
{
    docApp = docApp || getDocApp();

    if(typeof action === 'string') action = {call: action};
    if(Array.isArray(action))
    {
        if(typeof action[0] !== 'string')
        {
            action.forEach((act) => processDocAppAction(act, docApp));
            return;
        }
        action = {call: action[0], args: action.slice(1)};
    }

    const method = zui.deepGet(docApp, action.call);
    if(typeof method === 'function') method.apply(docApp, action.args);
    if(config.debug) console.log('DocApp.action', {action, method, docApp});
}

zui.AjaxForm.DEFAULT.onResult = function(res)
{
    const docApp = getDocApp();
    if(docApp && res.docApp)
    {
        processDocAppAction(res.docApp, docApp);

        delete res.docApp;
        delete res.load;
        delete res.callback;
    }
};

function handleSwitchView(view, info)
{
    const url = $.createLink('doc', 'app', `type=${info.spaceType}&spaceID=${info.spaceID}&libID=${info.libID}&moduleID=${info.moduleID}&docID=${info.docID}&docMode=${view}`.replace(`&spaceID=${info.spaceType === 'mine' ? -1 : 0}&libID=0&moduleID=0&docID=0&docMode=list`, ''));
    if(url === lastAppUrl) return;
    if(lastAppUrl && !$.apps.getAppUrl().endsWith(url)) $.apps.updateAppUrl(url, info.title ? (info.title + documentTitleSuffix) : originalDocumentTitle);
    lastAppUrl = url;
}

function handleCreateSpace()
{
    const url = $.createLink('doc', 'createSpace');
    zui.Modal.open({size: 'sm', url: url});
}

function handleEditSpace(space)
{
    const url = $.createLink('doc', 'editLib', `libID=${space.id}`);
    zui.Modal.open({size: 'sm', url: url});
}

function handleCreateLib(space)
{
    const url = $.createLink('doc', 'createLib', space ? `type=${space.type}&objectID=${space.id}` : 'type=mine');
    zui.Modal.open({size: 'sm', url: url});
}

function handleEditLib(lib)
{
    const url = $.createLink('doc', 'editLib', `libID=${lib.id}`);
    zui.Modal.open({size: 'sm', url: url});
}

function handleDeleteLib(lib)
{
    $.post($.createLink('doc', 'deleteLib', `libID=${lib.id}`), function ()
    {
        getDocApp().delete('lib', lib.id);
    });
}

function handleMoveLib(lib)
{
    const url = $.createLink('doc', 'moveLib', `libID=${lib.id}`);
    zui.Modal.open({size: 'sm', url: url});
}

function handleCreateModule(module)
{
    const url = $.createLink('tree', 'ajaxCreateModule');
    const data = {
        name: module.name,
        libID: module.lib,
        parentID: module.parent,
        objectID: module.parent,
        moduleType: 'doc',
        isUpdate: false,
        createType: 'child',
    };
    $.ajaxSubmit(
    {
        load: false,
        url: url,
        data: data,
        onSuccess: (res) =>
        {
            if(!res.module) return;
            const docApp = getDocApp();
            docApp.update('module', res.module);
            docApp.selectModule(res.module.id);
        }
    })
}

function handleEditModule(module)
{
    const url = $.createLink('doc', 'editCatalog', `moduleID=${module.id}&type=doc`);
    zui.Modal.open({size: 'sm', url: url});
}

function handleDeleteModule(module)
{
    $.post($.createLink('doc', 'deleteCatalog', 'moduleID=' + module.id), {}, function ()
    {
        getDocApp().delete('module', module.id);
    });
}

function handleCreateDoc(doc, spaceID, libID, moduleID)
{
    const docApp = getDocApp();
    const spaceType = docApp.signals.spaceType.value
    const url = $.createLink('doc', 'create', `objectType=${spaceType}&objectID=${spaceID}&libID=${libID}&moduleID=${moduleID}`);
    return new Promise((resolve) =>
    {
        $.post(url,
        {
            content: doc.content,
            status: 'normal',
            contentType: doc.contentType,
            type: 'text',
            lib: libID,
            module: moduleID,
            title: doc.title,
            keywords: '',
            contactList: '',
            acl: 'private',
            space: spaceType,
        }, (res) => {
            const data = JSON.parse(res);
            resolve($.extend(doc, {id: data.id}, data.doc));
        });
    });
}

function handleSaveDoc(doc)
{
    const docApp = getDocApp();
    const spaceType = docApp.signals.spaceType.value;
    const libID = docApp.signals.libID.value;
    const moduleID = docApp.signals.moduleID.value;
    const url = $.createLink('doc', 'edit', `docID=${doc.id}`);
    $.post(url,
    {
        content: doc.content,
        status: 'normal',
        contentType: doc.contentType,
        type: 'text',
        lib: libID,
        module: moduleID,
        title: doc.title,
        keywords: '',
        contactList: '',
        acl: 'private',
        space: spaceType,
    }, (res) => {
        console.log('handleSaveDoc.res', res);
        docApp.update('doc', doc);
    });
}

function handleMoveDoc(doc)
{
    const docApp = getDocApp();
    const libID = docApp.signals.libID.value;
    const spaceID = docApp.signals.spaceID.value;
    const url = $.createLink('doc', 'moveDoc', `docID=${doc.id}&libID=${libID}&space=${spaceID > 0 ? spaceID : 'mine'}`);
    zui.Modal.open({size: 'sm', url: url});
}

function canMoveDoc(doc)
{
    const docApp         = getDocApp();
    const spaceType      = docApp.signals.spaceType.value;
    const hasDocMovePriv = docApp.props.hasDocMovePriv;
    const currentUser    = docApp.props.currentUser;
    return hasDocMovePriv && (spaceType === 'custom' || spaceType === 'mine') && doc.addedBy === currentUser;
}

function handleDeleteDoc(doc)
{
    $.post($.createLink('doc', 'delete', 'docID=' + doc.id), {}, function ()
    {
        getDocApp().delete('doc', doc.id);
    });
}

function handleCollectDoc(doc)
{
    $.post($.createLink('doc', 'collect', `objectID=${doc.id}&objectType=doc`), {}, function(res)
    {
        if(typeof res == 'string') res = JSON.parse(res);
        getDocApp().update('doc', $.extend(doc, {isCollector: res.status === 'yes'}));
    });
}

window.setDocAppOptions = function(_, options)
{
    const newOptions =
    {
        onCreateSpace : options.spaceType === 'custom' ? handleCreateSpace: null,
        onEditSpace   : options.spaceType === 'custom' ? handleEditSpace  : null,
        onCreateLib   : handleCreateLib,
        onEditLib     : handleEditLib,
        onDeleteLib   : handleDeleteLib,
        onMoveLib     : handleMoveLib,
        onCreateModule: handleCreateModule,
        onEditModule  : handleEditModule,
        onDeleteModule: handleDeleteModule,
        onCreateDoc   : handleCreateDoc,
        onSaveDoc     : handleSaveDoc,
        onMoveDoc     : handleMoveDoc,
        canMoveDoc    : canMoveDoc,
        onDeleteDoc   : handleDeleteDoc,
        onCollectDoc  : handleCollectDoc,
        onSwitchView  : handleSwitchView,
    };
    return newOptions;
};
