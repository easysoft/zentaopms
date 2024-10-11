let lastAppUrl            = '';
let originalDocumentTitle = document.title;
let documentTitleSuffix   = ' - ' + originalDocumentTitle.split(' - ').pop();

function getDocApp()
{
    const docApp = $('#docApp').zui('docApp');
    return docApp ? docApp.$ : null;
}

function getLang(key)
{
    const docApp = getDocApp();
    return docApp ? docApp.props.lang[key] : '';
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

function handleSwitchView(view, location, info)
{
    const spaceID = Math.max(0, location.spaceID);
    const url = $.createLink('doc', 'app', `type=${location.spaceType}&spaceID=${spaceID}&libID=${location.libID}&moduleID=${location.moduleID}&docID=${location.docID}&docMode=${view}`.replace(`&spaceID=${location.spaceType === 'mine' ? -1 : 0}&libID=0&moduleID=0&docID=0&docMode=list`, ''));
    if(url === lastAppUrl) return;
    if(lastAppUrl && !$.apps.getAppUrl().endsWith(url)) $.apps.updateAppUrl(url, info.title ? (info.title + documentTitleSuffix) : originalDocumentTitle);
    lastAppUrl = url;
}

function handleCreateSpace()
{
    const docApp = getDocApp();
    const params = docApp.spaceType == 'mine' ? 'type=mine' : '';
    const url    = $.createLink('doc', 'createSpace', params);
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
    const url  = $.createLink('tree', 'ajaxCreateModule');
    const data =
    {
        name:       module.name,
        libID:      module.lib,
        parentID:   module.parent,
        objectID:   module.parent,
        moduleType: 'doc',
        isUpdate:   false,
        createType: 'child',
    };
    $.ajaxSubmit(
    {
        load: false,
        url:  url,
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
    const docApp    = getDocApp();
    const spaceType = docApp.signals.spaceType.value;
    const url       = $.createLink('doc', 'create', `objectType=${spaceType}&objectID=${Math.max(spaceID, 0)}&libID=${libID}&moduleID=${moduleID}`);
    const docData   =
    {
        content    : doc.content,
        status     : doc.status || 'normal',
        contentType: doc.contentType,
        type       : 'text',
        lib        : libID,
        module     : moduleID,
        title      : doc.title,
        keywords   : doc.keywords,
        contactList: '',
        acl        : 'private',
        space      : spaceType,
        uid        : doc.contentType === 'doc' ? '' : (doc.uid || `doc${doc.id}`),
    };
    return new Promise((resolve) =>
    {
        $.post(url, docData, (res) =>
        {
            const data = JSON.parse(res);
            resolve($.extend(doc, {id: data.id}, data.doc, {status: doc.status || data.status}));
        });
    });
}

function handleSaveDoc(doc)
{
    const docApp    = getDocApp();
    const spaceType = docApp.signals.spaceType.value;
    const libID     = docApp.signals.libID.value;
    const moduleID  = docApp.signals.moduleID.value;
    const url       = $.createLink('doc', 'edit', `docID=${doc.id}`);
    $.post(url,
    {
        content    : doc.content,
        status     : doc.status || 'normal',
        contentType: doc.contentType,
        type       : 'text',
        lib        : libID,
        module     : moduleID,
        title      : doc.title,
        keywords   : doc.keywords,
        contactList: '',
        acl        : 'private',
        space      : spaceType,
        uid        : doc.contentType === 'doc' ? '' : (doc.uid || `doc${doc.id}`),
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
    const hasDocMovePriv = docApp.props.privs.moveDoc;
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
        const collected = res.status === 'yes';
        getDocApp().update('doc', $.extend(doc, {isCollector: collected, collects: collected ? doc.collects + 1 : doc.collects - 1}));
    });
}

function deleteDocFile(file, doc)
{
    zui.Modal.confirm(getDocApp().props.lang.fileConfirmDelete).then(result =>
    {
        if(!result) return;

        $.ajaxSubmit(
        {
            url:      $.createLink('doc', 'deleteFile', `docID=${doc.id}&fileID=${file.id}&confirm=yes`),
            load:     false,
            callback: null,
            onSuccess: (res) =>
            {
                if(res && typeof res === 'object' && res.result === 'success')
                {
                    getDocApp().update('doc', $.extend({}, doc, {files: doc.files.filter(f => f.id !== file.id)}));
                }
            }
        })
    });
}

function renameDocFile(file, doc)
{
    let fileName = file.title;
    let extension = file.extension;
    if(extension && fileName.endsWith(`.${extension}`)) fileName = fileName.substring(0, fileName.length - extension.length - 1);
    zui.Modal.prompt({message: getDocApp().props.lang.fileRename, defaultValue: fileName, placeholder: fileName}).then(newName =>
    {
        if(!newName) return;

        fileName = newName;
        if(extension && fileName.endsWith(`.${extension}`))
        {
            fileName = fileName.substring(0, fileName.length - extension.length - 1);
        }
        else if(!extension && fileName.includes('.'))
        {
            extension = fileName.split('.').pop();
            fileName = fileName.substring(0, fileName.length - extension.length - 1);
        }
        $.ajaxSubmit(
        {
            url:  $.createLink('file', 'edit', `fileID=${file.id}`),
            data: {fileName: newName, extension : file.extension},
            load: false,
            onComplete: (res) =>
            {
                if(res && typeof res === 'object')
                {
                    const updatedFile = $.extend({}, file, {title: extension ? `${fileName}.${extension}` : fileName, extension}, res.id ? res : {});
                    getDocApp().update('doc', $.extend({}, doc, {files: doc.files.map(f => f.id === file.id ? updatedFile : f)}));
                }
            }
        });
    });
}

function getFileActions(file, doc)
{
    const docApp  = getDocApp();
    const privs   = docApp.props.privs;
    const lang    = docApp.props.lang;
    const canEdit = privs.edit && (!doc.privs || doc.privs.edit !== false);
    return [
        {'data-toggle': 'modal', 'data-size': 'lg', url: $.createLink('file', 'download', `fileID=${file.id}&mouse=left`), hint: lang.filePreview, icon: 'eye'},
        {target: '_blank', url: zui.formatString(docApp.props.fileUrl, file), hint: lang.fileDownload, icon: 'download'},
        canEdit ? {hint: lang.fileRename, icon: 'pencil-alt', onClick: renameDocFile.bind(this, file, doc)} : null,
        canEdit ? {hint: lang.fileDelete, icon: 'trash', onClick: deleteDocFile.bind(this, file, doc)} : null,
    ].filter(Boolean);
}

/**
 * Get actions of foot toolbar in doc table.
 * 获取文档列表页底部工具栏的操作项。
 */
function getDocTableActions(info)
{
    return [{
        text: getLang('moveTo'),
        onClick: function()
        {
            /* Get all selected doc id list. */
            const selections = info.dtable.getChecks();
            console.log('Batch move doc', selections, info);
        },
    }];
}

const actionsMap =
{
    'doc-table': getDocTableActions
};

function getActions(type, info)
{
    const builder = actionsMap[type];
    if(builder) return builder.call(this, info);
}

window.setDocAppOptions = function(_, options)
{
    const privs          = options.privs;
    const canCustomSpace = options.spaceType === 'custom' || options.spaceType === 'mine';
    const newOptions     =
    {
        onCreateSpace : (privs.createSpace && canCustomSpace) ? handleCreateSpace: null,
        onEditSpace   : (privs.editSpace && canCustomSpace) ? handleEditSpace  : null,
        onCreateLib   : privs.createLib ? handleCreateLib : null,
        onEditLib     : privs.editLib ? handleEditLib : null,
        onDeleteLib   : privs.deleteLib ? handleDeleteLib : null,
        onMoveLib     : privs.moveLib ? handleMoveLib : null,
        onCreateModule: privs.addModule ? handleCreateModule : null,
        onEditModule  : privs.editModule ? handleEditModule : null,
        onDeleteModule: privs.deleteModule ? handleDeleteModule : null,
        onCreateDoc   : privs.create ? handleCreateDoc : null,
        onSaveDoc     : privs.edit ? handleSaveDoc : null,
        onMoveDoc     : privs.moveDoc ? handleMoveDoc : null,
        canMoveDoc    : canMoveDoc,
        onDeleteDoc   : privs.delete ? handleDeleteDoc : null,
        onCollectDoc  : privs.collect ? handleCollectDoc : null,
        onSwitchView  : handleSwitchView,
        fileActions   : getFileActions,
        getActions    : getActions,
    };
    return newOptions;
};

window.beforeRequestContent = function(options)
{
    const url              = $.parseLink(options.url);
    const vars             = url.vars.map(x => x[1]);
    const spaceType        = vars[0];
    const docApp           = getDocApp();
    const currentSpaceType = docApp.signals.spaceType.value;
    if(spaceType !== currentSpaceType) return;

    docApp.switchView(
    {
        spaceType: spaceType,
        spaceID  : parseInt(vars[1]),
        libID    : parseInt(vars[2]),
        moduleID : parseInt(vars[3]),
        docID    : parseInt(vars[4]),
    }, vars[5]);

    return false;
};

window.goToOldDocPage = function()
{
    const docApp = getDocApp();
    if(!docApp) return;

    zui.store.set('docAppEnabled', false);
    const spaceType = docApp.signals.spaceType.value
    const map       = {mine: 'myspace', custom: 'teamspace', product: 'productspace', project: 'projectspace'};
    const method    = map[spaceType];
    $.apps.openUrl($.createLink('doc', method));
};
