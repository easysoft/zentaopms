let lastAppUrl            = '';
let originalDocumentTitle = document.title;
let documentTitleSuffix   = ' - ' + originalDocumentTitle.split(' - ').pop();

function getDocApp()
{
    const docApp = $('#docApp').data('zui.DocApp');
    return docApp ? docApp.$ : null;
}

function getLang(key)
{
    const docApp = getDocApp();
    const lang = docApp ? docApp.props.lang : {};
    return typeof key === 'string' ? lang[key] : lang;
}

function hasPriv(priv)
{
    const docApp = getDocApp();
    return docApp ? !!docApp.props.privs[priv] : false;
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

function handleSwitchView(mode, location, info)
{
    const rawModule = config.rawModule.toLowerCase();
    const rawMethod = config.rawMethod.toLowerCase();
    if(rawModule === 'doc' && rawMethod === 'view' && mode === 'view' && !lastAppUrl)
    {
        top.document.title = info.title ? (info.title + documentTitleSuffix) : originalDocumentTitle;
        return;
    }

    let url;
    const pager      = location.pager || {recTotal: 0, recPerPage: 20, pageID: 1};
    const search     = encodeURIComponent(location.search || '');
    const params     = encodeURIComponent(location.params || '');
    const filterType = encodeURIComponent(location.filterType || '');
    if(rawModule === 'doc' && ['myspace', 'teamspace', 'productspace', 'projectspace'].includes(rawMethod))
    {
        url = $.createLink('doc', config.rawMethod, `objectID=${location.spaceID}&libID=${location.libID}&moduleID=${location.moduleID}&browseType=${filterType}&param=${params}&orderBy=${location.orderBy}&recTotal=${pager.recTotal}&recPerPage=${pager.recPerPage}&pageID=${pager.pageID}&mode=${mode}&docID=${location.docID}&search=${search}`).replace('&libID=0&moduleID=0&browseType=all&param=&orderBy=&recTotal=0&recPerPage=20&pageID=1&mode=list&docID=0&search=', '');
    }
    else
    {
        url = $.createLink('doc', 'app', `type=${location.spaceType}&spaceID=${spaceID}&libID=${location.libID}&moduleID=${location.moduleID}&docID=${location.docID}&mode=${mode}&orderBy=${location.orderBy}&recTotal=${pager.recTotal}&recPerPage=${pager.recPerPage}&pageID=${pager.pageID}&filterType=${filterType}&search=${search}&params=${params}`).replace('&libID=0&moduleID=0&docID=0&mode=list&orderBy=id_desc&recTotal=0&recPerPage=0&pageID=1&filterType=&search=&params=', '');
    }
    if(url === lastAppUrl) return;
    if(lastAppUrl && !$.apps.getAppUrl().endsWith(url)) $.apps.updateAppUrl(url, info.title ? (info.title + documentTitleSuffix) : originalDocumentTitle);
    lastAppUrl = url;
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

function canMoveDoc(doc)
{
    const docApp         = getDocApp();
    const hasDocMovePriv = docApp.props.privs.moveDoc;

    return hasDocMovePriv;
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

function getDocCreateActions() {
    return [
        {text: '存为草稿', size: 'md', className: 'btn-wide', type: 'secondary', command: 'saveNewDoc/draft'},
        {text: '发布', size: 'md', className: 'btn-wide', type: 'primary', command: 'saveNewDoc'},
        {text: '取消', size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelCreateDoc'},
    ];
}

const actionsMap =
{
    home: function(info)
    {
        return [
            hasPriv('createSpace') ? {icon: 'cube', btnType: 'primary', text: getLang('createSpace'), command: 'createSpace'} : null,
        ];
    },
    space: function(info)
    {
        const lang  = getLang();
        const items = [];
        const space = info.data;
        if(hasPriv('editSpace')) items.push({text: lang.actions.editSpace, command: `editSpace/${space.id}`});
        if(hasPriv('deleteSpace')) items.push({text: lang.actions.deleteSpace, command: `deleteSpace/${space.id}`});
        if(!items.length) return;
        return [
            {type: 'dropdown', icon: 'cog-outline', square: true, caret: false, placement: info.ui === 'space-card' ? 'bottom-end' : 'top-end', items: items},
        ];
    },
    doc: function(info)
    {
        const lang      = getLang();
        const doc       = info.data;

        if(info.ui === 'sidebar')
        {
            return [
                hasPriv('edit') ? {icon: 'edit', text: lang.edit, command: `startEditDoc/${doc.id}`} : null,
                canMoveDoc(doc) ? {icon: 'folder-move', text: lang.moveDoc, command: `moveDoc/${doc.id}`} : null,
                hasPriv('delete') ? {icon: 'trash', text: lang.delete, command: `deleteDoc/${doc.id}`} : null,
            ];
        }

        const moreItems = [];
        if(canMoveDoc(doc))      moreItems.push({icon: 'folder-move', text: lang.moveDoc, command: `moveDoc/${doc.id}`});
        if(hasPriv('delete'))    moreItems.push({icon: 'trash', text: lang.delete, command: `deleteDoc/${doc.id}`});
        if(hasPriv('exportDoc')) moreItems.push({icon: 'export', text: lang.export, command: 'exportDoc'});

        return [
            {icon: doc.isCollector ? 'star text-warning' : 'star-empty', text: doc.collects || null, hint: doc.isCollector ? lang.cancelCollection : lang.collect, rounded: 'lg', textClass: 'text-gray', type: 'ghost', command: hasPriv('collect') ? `collectDoc/${doc.id}` : null},
            hasPriv('edit') ? {icon: 'edit', type: 'ghost text-primary', hint: lang.edit, rounded: 'lg', command: 'startEditDoc'} : null,
            moreItems.length ? {icon: 'icon-ellipsis-v', type: 'dropdown', rounded: 'lg', placement: 'bottom-end', caret: false, items: moreItems} : null,
        ];
    },
    lib: function(info)
    {
        const lang  = getLang();
        const items = [];
        const lib   = info.data;

        if(hasPriv('addModule')) items.push({text: lang.actions.addModule, command: `addModule/${lib.id}/0/${lib.id}/child`});
        if(hasPriv('editLib'))   items.push({text: lang.actions.editLib, command: `editLib/${lib.id}`});
        if(hasPriv('moveLib'))   items.push({text: lang.moveTo, command: `moveLib/${lib.id}`});
        if(hasPriv('deleteLib')) items.push({text: lang.actions.deleteLib, command: `deleteLib/${lib.id}`});

        if(!items.length) return;
        if(info.ui === 'sidebar') return items;

        return [
            {type: 'dropdown', icon: 'cog-outline', square: true, caret: false, placement: 'top-end', items: items},
        ];
    },
    module: function(info)
    {
        const lang   = getLang();
        const items  = [];
        const module = info.data;

        if(hasPriv('addModule')) items.push({text: lang.actions.addSameModule, command: `addModule/${module.lib}/${module.parent || module.lib}/${module.id}/same`}, {text: lang.actions.addModule, command: `addModule/${module.lib}/${module.id}/${module.id}/child`});
        if(hasPriv('editModule')) items.push({text: lang.actions.editModule, command: `editModule/${module.id}`});
        if(hasPriv('deleteModule')) items.push({text: lang.actions.delModule, command: `deleteModule/${module.id}`});

        return items;
    },
    'doc-table': function(info)
    {
        return [{
            text: getLang('batchMove'),
            onClick: function()
            {
                /* Get all selected doc id list. */
                const selections = info.dtable.getChecks();
                if(!selections.length) return;

                const docIdList = window.btoa(JSON.stringify(selections));
                const url = $.createLink('doc', 'batchMoveDoc', 'docIdList=' + docIdList + '&spaceID=' + spaceID + '&libID=' + libID + '&moduleID=' + moduleID);

                zui.Modal.open({size: 'sm', url: url});
            }
        }];
    },
    'doc-list': function()
    {
        const lang           = getLang();
        const canCreateDoc   = hasPriv('create');
        const canCreateLib   = hasPriv('createLib');
        const canCreateSpace = hasPriv('createSpace');
        const canExportDoc   = hasPriv('exportDoc');
        const items =
        [
            canCreateDoc ? {icon: 'plus', text: lang.createDoc, command: 'startCreateDoc'} : null,
            canCreateDoc ? {type: 'divider'} : null,
            {icon: 'file-word', text: lang.createList.word, command: 'startCreateOffice/word'},
            {icon: 'file-powerpoint', text: lang.createList.ppt, command: 'startCreateOffice/ppt'},
            {icon: 'file-excel', text: lang.createList.excel, command: 'startCreateOffice/excel'},
            (canCreateLib || canCreateSpace) ? {type: 'divider'} : null,
            canCreateSpace ? {icon: 'cube', text: lang.createSpace, command: 'createSpace'} : null,
            canCreateLib ? {icon: 'wiki-lib', text: lang.createLib, command: 'createLib'} : null,
        ].filter(Boolean);
        return [
            (canExportDoc || canCreateDoc) ? {type: 'divider', style: {margin: '6px 0'}} : null,
            canExportDoc ? {icon: 'export', text: lang.export, command: 'exportDoc'} : null,
            canCreateDoc ? {icon: 'import', text: lang.uploadDoc, command: 'uploadDoc'} : null,
            items.length ? {icon: 'plus', type: 'dropdown', btnType: 'primary',  size: 'md', text: lang.create, items: items} : null,
        ];
    },
    'doc-edit': function(info)
    {
        const doc = info.data;
        const lang = getLang();
        return [
            doc.status === 'draft' ? {text: lang.saveDraft, size: 'md', className: 'btn-wide', type: 'secondary', command: 'saveDoc/draft'} : null,
            {text: lang.release, size: 'md', className: 'btn-wide', type: 'primary', command: 'saveDoc'},
            {text: lang.cancel, size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelEditDoc'},
        ];
    },
    'doc-create': function()
    {
        const lang = getLang();
        return [
            {text: lang.saveDraft, size: 'md', className: 'btn-wide', type: 'secondary', command: 'saveNewDoc/draft'},
            {text: lang.release, size: 'md', className: 'btn-wide', type: 'primary', command: 'saveNewDoc'},
            {text: lang.cancel, size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelCreateDoc'},
        ];
    },
    file: function(info)
    {
        const doc     = info.doc;
        const file    = info.data;
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
};

function getActions(type, info)
{
    const builder = actionsMap[type];
    if(builder) return builder.call(this, info);
}

const commands =
{
    uploadDoc: function()
    {
        const docApp = getDocApp();
        const url = $.createLink('doc', 'uploadDocs', `objectType=${docApp.spaceType}&objectID=${docApp.spaceID}&libID=${docApp.libID}&moduleID=${docApp.moduleID}&type=attachment`);
        zui.Modal.open({url: url});
    },
    startCreateOffice: function(_, args)
    {
        const type = args[0];
        const docApp = getDocApp();
        const url = $.createLink('doc', 'create', `objectType=${docApp.spaceType}&objectID=${docApp.spaceID}&libID=${docApp.libID}&moduleID=${docApp.moduleID}&type=${type}`);
        zui.Modal.open({url: url});
    },
    createSpace: function()
    {
        const docApp = getDocApp();
        const params = docApp.spaceType == 'mine' ? 'type=mine' : '';
        const url    = $.createLink('doc', 'createSpace', params);
        zui.Modal.open({size: 'sm', url: url});
    },
    editSpace: function(_, args)
    {
        const docApp  = getDocApp();
        const spaceID = args[0] || docApp.spaceID;
        const url = $.createLink('doc', 'editLib', `libID=${spaceID}`);
        zui.Modal.open({size: 'sm', url: url});
    },
    deleteSpace: function(_, args)
    {
        const docApp  = getDocApp();
        const spaceID = args[0] || docApp.spaceID;
        $.ajaxSubmit(
        {
            confirm: getLang('confirmDeleteSpace'),
            url:     $.createLink('doc', 'deleteLib', `libID=${spaceID}`),
            load:    false,
            onSuccess: function()
            {
                getDocApp().delete('space', spaceID);
            }
        });
    },
    createLib: function(_, args)
    {
        const docApp  = getDocApp();
        const spaceID = args[0] || docApp.spaceID;
        const url     = $.createLink('doc', 'createLib', spaceID ? `type=${docApp.spaceType}&objectID=${spaceID}` : `type=${docApp.spaceType}`);
        zui.Modal.open({size: 'sm', url: url});
    },
    moveLib: function(_, args)
    {
        const docApp  = getDocApp();
        const libID = args[0] || docApp.libID;
        const url = $.createLink('doc', 'moveLib', `libID=${libID}`);
        zui.Modal.open({size: 'sm', url: url});
    },
    editLib: function(_, args)
    {
        const docApp  = getDocApp();
        const libID = args[0] || docApp.libID;
        const url = $.createLink('doc', 'editLib', `libID=${libID}`);
        zui.Modal.open({size: 'sm', url: url});
    },
    deleteLib: function(_, args)
    {
        const docApp  = getDocApp();
        const libID = args[0] || docApp.libID;
        $.ajaxSubmit(
        {
            confirm: getLang('confirmDeleteLib'),
            url:     $.createLink('doc', 'deleteLib', `libID=${libID}`),
            load:    false,
            onSuccess: function()
            {
                getDocApp().delete('lib', libID);
            }
        });
    },
    addModule: function(_, args)
    {
        zui.Modal.prompt({message: getLang('moduleName')}).then(newMoudleName =>
        {
            if(!newMoudleName) return;

            const docApp     = getDocApp();
            const libID      = args[0] || docApp.libID;
            const parentID   = args[1] || 0;
            const objectID   = args[2] || 0;
            const createType = args[3] || 'child';
            const data =
            {
                name:       newMoudleName,
                libID:      libID,
                parentID:   parentID,
                objectID:   objectID,
                moduleType: 'doc',
                isUpdate:   false,
                createType: createType,
            };
            $.ajaxSubmit(
            {
                load: false,
                url:  $.createLink('tree', 'ajaxCreateModule'),
                data: data,
                onSuccess: (res) =>
                {
                    if(!res.module) return;
                    const docApp = getDocApp();
                    docApp.update('module', res.module);
                    docApp.selectModule(res.module.id);
                }
            })
        });
    },
    editModule: function(_, args)
    {
        const docApp   = getDocApp();
        const moduleID = args[0] || docApp.moduleID;
        const url      = $.createLink('doc', 'editCatalog', `moduleID=${moduleID}&type=doc`);
        zui.Modal.open({size: 'sm', url: url});
    },
    deleteModule: function(_, args)
    {
        const docApp   = getDocApp();
        const moduleID = args[0] || docApp.moduleID;

        $.ajaxSubmit(
        {
            confirm: getLang('confirmDeleteModule'),
            url:     $.createLink('doc', 'editCatalog', `moduleID=${moduleID}&type=doc`),
            load:    false,
            onSuccess: function()
            {
                getDocApp().delete('lib', libID);
                getDocApp().delete('module', moduleID);
            }
        });
    },
    collectDoc: function(_, args)
    {
        const docApp = getDocApp();
        const docID  = args[0] || docApp.docID;
        $.post($.createLink('doc', 'collect', `objectID=${docID}&objectType=doc`), {}, function(res)
        {
            if(typeof res == 'string') res = JSON.parse(res);
            const collected = res.status === 'yes';
            const doc       = docApp.getDoc(docID);
            getDocApp().update('doc', $.extend(doc, {isCollector: collected, collects: collected ? doc.collects + 1 : doc.collects - 1}));
        });
    },
    moveDoc: function(_, args)
    {
        const docApp    = getDocApp();
        const docID     = args[0] || docApp.docID;
        const libID     = docApp.libID;
        const spaceType = docApp.spaceType;
        const spaceID   = docApp.spaceID;
        const url       = $.createLink('doc', 'moveDoc', `docID=${docID}&libID=${libID}&spaceType=${spaceType}&space=${spaceID}`);
        zui.Modal.open({size: 'sm', url: url});
    },
    deleteDoc: function(_, args)
    {
        const docApp  = getDocApp();
        const docID   = args[0] || docApp.docID;

        $.ajaxSubmit(
        {
            confirm: getLang('confirmDelete'),
            url:     $.createLink('doc', 'delete', `docID=${docID}`),
            load:    false,
            onSuccess: function()
            {
                getDocApp().delete('doc', docID);
            }
        });
    },
    exportDoc: function(_, args)
    {
        const docApp   = getDocApp();
        const type     = docApp.spaceType;
        const libID    = args[0] || docApp.libID;
        const moduleID = args[1] || docApp.moduleID;
        const docID    = args[2] || docApp.docID;

        const url = $.createLink('doc', `${type}2export`, `libID=${libID}&moduleID=${moduleID}&docID=${docID}`);
        if(docID)
        {
            window.open(url, '_self');
            return;
        }

        zui.Modal.open({url: url, size: 'sm'});
    }
};

window.setDocAppOptions = function(_, options)
{
    const privs          = options.privs;
    const canCustomSpace = options.spaceType === 'custom' || options.spaceType === 'mine';
    const newOptions     =
    {
        commands      : commands,
        onCreateDoc   : privs.create ? handleCreateDoc : null,
        onSaveDoc     : privs.edit ? handleSaveDoc : null,
        canMoveDoc    : canMoveDoc,
        onSwitchView  : handleSwitchView,
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
