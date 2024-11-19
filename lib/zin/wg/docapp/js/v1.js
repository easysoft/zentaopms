let lastAppUrl            = '';
let originalDocumentTitle = document.title;
let documentTitleSuffix   = ' - ' + originalDocumentTitle.split(' - ').pop();
let currentDocApp         = null;
let currentUser           = null;
let currentSpaceType      = '';
let userPrivs             = {};
let docLangData           = {};
const savingDocData       = {};

/**
 * Get doc app instance.
 * 获取文档应用组件实例。
 *
 * @returns {object}
 */
function getDocApp()
{
    if(!currentDocApp)
    {
        const docApp = $('#docApp').data('zui.DocApp');
        currentDocApp = docApp ? docApp.$ : null;
    }
    return currentDocApp;
}

/**
 * 获取语言项，如果没有设置 key，则获取整个语言项对象，语言项定义在 module/doc/ui/app.html.php 的 $langData 中。
 * Get language item, if key is not set, return the whole language object, language items are defined in $langData in module/doc/ui/app.html.php.
 *
 * @param {string} key
 * @param {array|string|object}  args
 */
window.getLang = function(key, args)
{
    if(!docLangData)
    {
        const docApp = getDocApp();
        docLangData = docApp ? docApp.props.langData : {};
    }
    if(typeof key !== 'string') return docLangData;
    const value = docLangData[key];
    if(value === undefined) return;
    if(args)
    {
        args = Array.isArray(args) ? args : [args];
        args.unshift(value);
        return zui.formatString.apply(null, args);
    }
    return value;
}

/**
 * 检查是否有指定的权限，权限定义在 module/doc/ui/app.html.php 的 $privs 中。
 * Check if the specified privilege exists, privileges are defined in $privs in module/doc/ui/app.html.php.
 * @param {string} priv
 */
function hasPriv(priv, value)
{
    const setting = userPrivs[priv];
    return value !== undefined ? setting === value : !!setting;
}

function isApiLib(lib, docApp)
{
    if(typeof lib === 'number') lib = getDocApp().getLib(lib);
    if(!lib)
    {
        docApp = docApp || getDocApp();
        if(!docApp) return false;
        lib = docApp.lib;
    }
    return lib && lib.data.type === 'api';
}

function isApiDoc(doc)
{
    const docApp = getDocApp();
    if(typeof doc !== 'object' && docApp) doc = docApp.getDoc(doc);
    if(doc && doc.api !== undefined) return doc.api;
    const id = typeof doc === 'object' ? doc.id : doc;
    return String(id).startsWith('api.');
}

/**
 * 处理对话框的操作，执行文档应用实例上的方法。
 * Process dialog actions, execute methods on the doc app instance.
 *
 * @param {string|array|object} action
 * @param {object} docApp
 */
function processDocAppAction(action, docApp)
{
    docApp = docApp || getDocApp();

    if(typeof action === 'string')
    {
        action = {call: action};
    }
    if(Array.isArray(action))
    {
        if(typeof action[0] !== 'string')
        {
            action.forEach((act) => processDocAppAction(act, docApp));
            return;
        }
        action = {call: action[0], args: action.slice(1)};
    }

    if(!action.mode && action.call.includes(':'))
    {
        const parts = action.call.split(':');
        action.mode = parts[0];
        action.call = parts[1];
    }
    if(action.mode && action.mode !== docApp.mode) return;
    const method = zui.deepGet(docApp, action.call);
    if(typeof method === 'function') method.apply(docApp, action.args);
}

/**
 * 检查空间是否可以进行修改操作，如果不指定空间则判断当前空间。
 * Check if the space can be modified, if the space is not specified, check the current space.
 *
 * @param {object|number} space
 */
function canModifySpace(space)
{
    const docApp = getDocApp();
    if(!docApp && !space) return false;
    space = typeof space !== 'object' ? docApp.getSpace(space) : space;
    return space && space.canModify !== false;
}

/*
 * 修改 Ajax 表单提交后的默认处理方法，改为处理文档应用的操作。
 * Modify the default processing method after Ajax form submission to process the actions of the doc app.
 */
zui.AjaxForm.DEFAULT.onResult = function(res)
{
    const docApp = getDocApp();
    if(docApp && res.docApp)
    {
        /* 请求响应对象中的 docApp 属性定义了如何进行后续操作。 */
        processDocAppAction(res.docApp, docApp);

        delete res.docApp;
        delete res.load;
        delete res.locate;
        delete res.callback;
    }
};

/**
 * 处理文档应用界面切换事件，修改浏览器地址栏 URL 和标题。
 * Handle the doc app view switch event, modify the browser address bar URL and title.
 *
 * @param {string} mode     Doc App ui mode.
 * @param {object} location Doc App location info.
 * @param {object} info     Doc App view info.
 * @returns
 */
function handleSwitchView(mode, location, info)
{
    const rawModule     = config.rawModule.toLowerCase();
    const rawMethod     = config.rawMethod.toLowerCase();
    const formatUrl     = this.props.viewModeUrl;
    const formatOptions = $.extend({recTotal: 0, recPerPage: 20, page: 1}, location, location.pager,
    {
        mode      : mode,
        rawModule : rawModule,
        rawMethod : rawMethod,
        search    : encodeURIComponent(location.search || ''),
        noSpace   : location.noSpace || 0,
        filterType: encodeURIComponent(location.filterType || '')
    });
    let url;
    if(typeof formatUrl === 'function')
    {
        url = formatUrl.call(this, formatOptions);
    }
    else if(mode === 'view')
    {
        url = $.createLink('doc', 'view', `docID=${location.docID}` + (info.data && location.docVersion && location.docVersion !== info.data.version ? `&version=${location.docVersion}` : ''));
    }
    else
    {
        url = zui.formatString(formatUrl, formatOptions).replace('&libID=0&moduleID=0&docID=0&mode=home&orderBy=id_desc&recTotal=0&recPerPage=0&pageID=1&filterType=&search=&noSpace=false', '').replace('&libID=0&moduleID=0&browseType=all&param=&orderBy=&recTotal=0&recPerPage=20&pageID=1&mode=home&docID=0&search=', '');
    }
    if(url === lastAppUrl) return;
    if(lastAppUrl && !$.apps.getAppUrl().endsWith(url)) $.apps.updateAppUrl(url, info.title ? (info.title + documentTitleSuffix) : originalDocumentTitle);
    lastAppUrl = url;
}

function showDocBasicModal(docID, isDraft)
{
    const docApp    = getDocApp();
    const spaceType = docApp.spaceType;
    const spaceID   = docApp.spaceID;
    const libID     = docApp.libID;
    const moduleID  = docApp.moduleID;
    const url       = $.createLink('doc', 'setDocBasic', `objectType=${spaceType}&objectID=${spaceID}&libID=${libID}&moduleID=${moduleID}&docID=${docID || 0}&isDraft=${isDraft ? 'yes' : 'no'}`);
    zui.Modal.open({url: url});
    return new Promise((resolve) => {window.docBasicModalResolver = resolve;});
}

window.beforeSetDocBasicInfo = function(_, form)
{
    if (window.docBasicModalResolver) window.docBasicModalResolver(new FormData(form));
    zui.Modal.query('#setDocBasicForm').hide();
    return false;
};

function mergeDocFormData(doc, formData)
{
    if(!doc || !formData) return;
    const keys = new Set(formData.keys());
    for(const key of keys)
    {
        const values = formData.getAll(key);
        if(!values.length) continue;
        if(key === 'module' || key === 'lib') doc[key] = +values[0];
        else doc[key] = values.length > 1 ? values : values[0];
    }
    return doc;
}

/**
 * 向服务器提交新文档。
 * Submit new doc to server.
 *
 * @param {object}   doc
 * @param {number}   spaceID
 * @param {number}   libID
 * @param {number}   moduleID
 * @param {FormData} formData
 * @returns
 */
function submitNewDoc(doc, spaceID, libID, moduleID, formData)
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
        acl        : 'private',
        space      : spaceType,
        uid        : (doc.uid || `doc${doc.id}`),
    };
    if(formData) mergeDocFormData(docData, formData);
    const getErrorMessage = (res) => {
        if(typeof res.message === 'string') return res.message;
        if(typeof res.message === 'object' && res.message && Object.values(res.message).length)
        {
            for(const x of Object.values(res.message))
            {
                if(typeof x === 'string') return x;
                if(Array.isArray(x) && x.length) return x[0];
            }
        }
        if(typeof data.error === 'string') return data.error;
        return getLang('errorOccurred');
    };
    return new Promise((resolve, reject) =>
    {
        $.post(url, docData, (res) =>
        {
            try
            {
                const data = JSON.parse(res);
                if(typeof data !== 'object' || data.result === 'fail')
                {
                    throw new Error(getErrorMessage(data));
                }
                const newDoc = $.extend(doc, {id: data.id}, docData, data.doc, {status: doc.status || data.status});
                if (!newDoc.space.includes('.')) {
                    newDoc.space = `${spaceType}.${docData[spaceType]}`;
                }
                resolve(newDoc);
            }
            catch (error)
            {
                zui.Modal.alert(error.message);
                reject(error);
            }
        });
    });
}

/**
 * 处理创建文档的操作请求，向服务器发送请求并返回创建的文档对象。
 * Handle the create doc operation request, send a request to the server and return the created doc object.
 */
function handleCreateDoc(doc, spaceID, libID, moduleID)
{
    return showDocBasicModal(0, doc.status === 'draft').then((formData) => {
        return submitNewDoc(doc, spaceID, libID, moduleID, formData);
    });
}

/**
 * 处理保存文档的操作请求，向服务器发送请求并返回保存的文档对象。
 * Handle the save doc operation request, send a request to the server and return the saved doc object.
 */
function handleSaveDoc(doc)
{
    const docApp    = getDocApp();
    const spaceType = docApp.signals.spaceType.value;
    const libID     = docApp.signals.libID.value;
    const moduleID  = docApp.signals.moduleID.value;
    const url       = $.createLink('doc', 'edit', `docID=${doc.id}`);
    const docData   = {
        content    : doc.content,
        status     : doc.status || 'normal',
        contentType: doc.contentType,
        type       : 'text',
        lib        : libID,
        module     : moduleID,
        title      : doc.title,
        keywords   : doc.keywords,
        acl        : doc.acl,
        space      : spaceType,
        uid        : (doc.uid || `doc${doc.id}`),
    };

    const docAppData = docApp.doc.data;
    if (doc.id === docAppData.id) docData.mailto = docAppData.mailto;

    if(savingDocData[doc.id])
    {
        mergeDocFormData(docData, savingDocData[doc.id]);
        delete savingDocData[doc.id];
    }

    return new Promise((resolve) => {
        $.post(url, docData, (res) =>
        {
            try
            {
                const data = JSON.parse(res);
                if(typeof data !== 'object' || data.result === 'fail')
                {
                    let message = data.message || data.error || getLang('errorOccurred');
                    if(typeof message === 'object') message = Object.values(message).map(x => Array.isArray(x) ? x.join('\n') : x).join('\n');
                    throw new Error(message);
                }
                doc = $.extend({}, doc, docData, data.doc);
                delete doc.title;
                delete doc.keywords;
                delete doc.content;
                resolve(doc);
            }
            catch (error)
            {
                resolve(false);
                if(!error.message) return;
                zui.Modal.alert(error.message);
            }
        });
    });
}

/**
 * 检查给定的文档是否可以进行移动操作。
 * Check if the given doc can be moved.
 */
function canMoveDoc(doc)
{
    return !String(doc.id).startsWith('api.') && getDocApp().props.privs.moveDoc;
}

/**
 * 处理删除文档文件的请求。
 * Handle the request to delete the doc file.
 * @param {object} file File info object.
 * @param {object} doc  Doc info object.
 */
function deleteDocFile(file, doc)
{
    zui.Modal.confirm(getLang('fileConfirmDelete')).then(result =>
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

/**
 * 处理重命名文件的请求。
 * Handle the request to rename the file.
 *
 * @param {object} file
 * @param {object} doc
 */
function renameDocFile(file, doc)
{
    let fileName = file.title;
    let extension = file.extension;
    if(extension && fileName.endsWith(`.${extension}`)) fileName = fileName.substring(0, fileName.length - extension.length - 1);
    zui.Modal.prompt({message: getLang('fileRename'), defaultValue: fileName, placeholder: fileName}).then(newName =>
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
                if(!res || typeof res !== 'object') return;
                const updatedFile = $.extend({}, file, {title: extension ? `${fileName}.${extension}` : fileName, extension}, res.id ? res : {});
                getDocApp().update('doc', $.extend({}, doc, {files: doc.files.map(f => f.id === file.id ? updatedFile : f)}));
            }
        });
    });
}

/**
 * 定义文档各个试图和 UI 元素的上的操作方法。
 * Define the operation methods on the views and UI elements of the doc.
 */
const actionsMap =
{
    /**
     * 定义首页的操作按钮。
     * Define the actions on the home page.
     *
     * @returns {array}
     */
    home: function()
    {
        return [
            hasPriv('createSpace') ? {icon: 'plus', btnType: 'primary', text: getLang('createSpace'), command: 'createSpace'} : null,
        ];
    },

    /**
     * 定义空间的操作按钮。
     * Define the actions of space.
     */
    space: function(info)
    {
        const lang      = getLang();
        const items     = [];
        const space     = info.data; // Space info object.

        /* 获取侧边栏没有文档库时的操作按钮。 Get actions when sidebar no lib. */
        if(info.ui === 'sidebar-no-lib')
        {
            if(!canModifySpace(space) || !hasPriv('createLib')) return null;
            return [{icon: 'plus', text: lang.createLib, command: 'createLib', btnType: 'primary-pale'}];
        }

        if(hasPriv('editSpace'))   items.push({text: lang.actions.editSpace, command: `editSpace/${space.id}`});
        if(hasPriv('deleteSpace')) items.push({text: lang.actions.deleteSpace, command: `deleteSpace/${space.id}`});
        if(!items.length) return;
        return [
            {type: 'dropdown', icon: 'cog-outline', square: true, caret: false, placement: info.ui === 'space-card' ? 'bottom-end' : 'top-end', items: items},
        ];
    },

    /**
     * 定义文档的操作按钮。
     * Define the actions of doc.
     */
    doc: function(info)
    {
        const lang       = getLang();
        const doc        = info.data;
        const canModify  = canModifySpace();
        const isApi      = isApiDoc(doc);
        const canEditDoc = canModify && hasPriv(isApi ? 'editApi' : 'edit');

        /* 侧边栏上的文档操作按钮。Doc actions in sidebar. */
        if(info.ui === 'sidebar')
        {
            if(!canModify) return [];
            const moreItems =
            [
                canMoveDoc(doc) ? {icon: 'folder-move', text: lang.moveDoc, command: `moveDoc/${doc.id}`} : null,
                hasPriv('delete') ? {icon: 'trash', text: lang.delete, command: `deleteDoc/${doc.id}`} : null,
            ];
            return [
                hasPriv('sortDoc') ? {icon: 'move cursor-move', className: 'sort-handler', hint: lang.sortDoc, size: 'xs'} : null,
                moreItems.length ? {icon: 'ellipsis-v', caret: false, placement: 'bottom-end', size: 'xs', items: moreItems} : null,
            ];
        }

        const moreItems = [];
        if(canModify && canMoveDoc(doc))   moreItems.push({icon: 'folder-move', text: lang.moveDoc, command: `moveDoc/${doc.id}`});
        if(canModify && hasPriv('delete')) moreItems.push({icon: 'trash', text: lang.delete, command: `deleteDoc/${doc.id}`});
        if(!isApi && canModify && hasPriv('effort')) moreItems.push({icon: 'time', text: lang.effort, command: `effortDoc/${doc.id}`});
        if(!isApi && hasPriv('exportDoc')) moreItems.push({icon: 'export', text: lang.export, command: 'exportDoc'});

        const actions = [
            isApi ? null : (hasPriv('collect', 'no') ? null : {icon: doc.isCollector ? 'star text-warning' : 'star-empty', text: doc.collects || null, hint: doc.isCollector ? lang.cancelCollection : lang.collect, rounded: 'lg', textClass: 'text-gray', type: 'ghost', command: hasPriv('collect') ? `collectDoc/${doc.id}` : null}),
            canEditDoc ? {icon: 'edit', type: 'ghost text-primary', hint: lang.edit, rounded: 'lg', command: 'startEditDoc'} : null,
            moreItems.length ? {icon: 'icon-ellipsis-v', type: 'dropdown', rounded: 'lg', placement: 'bottom-end', caret: false, items: moreItems} : null,
        ];

        return actions;
    },

    /**
     * 定义文档库的操作按钮。
     * Define the actions of modules.
     */
    lib: function(info)
    {
        const lang      = getLang();
        const items     = [];
        const lib       = info.data;
        const canModify = canModifySpace();
        const canAddModule = canModify && hasPriv('addModule');

        /* 获取侧边栏没有模块时的操作按钮。 Get actions when sidebar no module. */
        if(canAddModule && info.ui === 'sidebar-no-module')
        {
            return [{text: lang.actions.addModule, command: `addModule/${lib.id}/0/${lib.id}/child`, icon: 'plus', type: 'primary-pale'}];
        }

        if(canAddModule && info.ui !== 'space-card' && info.ui !== 'sidebar') items.push({text: lang.actions.addModule, command: `addModule/${lib.id}/0/${lib.id}/child`});
        if(canModify && hasPriv('editLib'))   items.push({text: lang.actions.editLib, command: `editLib/${lib.id}`});
        if(canModify && lib.type !== 'api' && hasPriv('moveLib') && info.ui !== 'space-card' && info.ui !== 'sidebar-toolbar') items.push({text: lang.moveTo, command: `moveLib/${lib.id}`});
        if(canModify && hasPriv('deleteLib')) items.push({text: lang.actions.deleteLib, command: `deleteLib/${lib.id}`});

        if(!items.length) return;
        if(info.ui === 'sidebar')
        {
            return [
                hasPriv('sortDoclib') ? {icon: 'move cursor-move', className: 'sort-handler', hint: lang.sortDoclib, size: 'xs'} : null,
                items.length ? {icon: 'ellipsis-v', caret: false, placement: 'bottom-end', size: 'xs', items: items} : null,
            ];
        }

        return [
            {type: 'dropdown', icon: info.ui === 'space-card' ? 'ellipsis-v' : 'cog-outline', square: true, caret: false, placement: info.ui === 'space-card' ? 'bottom-end' : 'top-end', items: items},
        ];
    },

    /**
     * 定义目录的操作按钮。
     * Define the actions on the lib page.
     */
    module: function(info)
    {
        const lang   = getLang();
        const items  = [];
        const module = info.data;

        if(hasPriv('addModule')) items.push({text: lang.actions.addSameModule, command: `addModule/${module.lib}/${module.parent || module.lib}/${module.id}/same`}, {text: lang.actions.addSubModule, command: `addModule/${module.lib}/${module.id}/${module.id}/child`});
        if(hasPriv('editModule')) items.push({text: lang.actions.editModule, command: `editModule/${module.id}`});
        if(hasPriv('deleteModule')) items.push({text: lang.actions.delModule, command: `deleteModule/${module.id}`});

        if(info.ui === 'sidebar')
        {
            return [
                hasPriv('sortModule') ? {icon: 'move cursor-move', className: 'sort-handler', hint: lang.sortLib, size: 'xs'} : null,
                items.length ? {icon: 'ellipsis-v', caret: false, placement: 'bottom-end', size: 'xs', items: items} : null,
            ];
        }

        return items;
    },

    /**
     * 定义文档表格操作栏的操作按钮。
     * Define the actions on the doc table footer.
     */
    'doc-table': function(info)
    {
        const docApp = this;
        return [hasPriv('batchMoveDoc') ? {
            text: getLang('batchMove'),
            onClick: function()
            {
                /* Get all selected doc id list. */
                const selections = info.dtable.getChecks();
                if(!selections.length) return;

                const docIdList = window.btoa(JSON.stringify(selections));
                const spaceType = docApp.spaceType;
                const spaceID   = docApp.spaceID;
                const libID     = docApp.libID;
                const moduleID  = docApp.moduleID;
                const url = $.createLink('doc', 'batchMoveDoc', 'type=' + spaceType + '&docIdList=' + docIdList + '&spaceID=' + spaceID + '&libID=' + libID + '&moduleID=' + moduleID);

                zui.Modal.open({size: 'sm', url: url});
            }
        } : null];
    },

    /**
     * 定义文档列表的操作按钮。
     * Define the actions on the doc list.
     */
    'doc-list': function(info)
    {
        const docApp    = this;
        const lang      = getLang();
        const canModify = canModifySpace();
        const libID     = docApp.libID;

        if(isApiLib(docApp.lib))
        {
            if(!canModify) return;

            const canCreateApi = hasPriv('createApi');
            if(info.ui === 'doc-list-empty')
            {
                return canCreateApi ? [{icon: 'plus', text: lang.createApi, btnType: 'primary',  command: 'startCreateDoc'}] : null;
            }

            const canCreateLib = hasPriv('createLib');
            const items =
            [
                canCreateApi ? {icon: 'plus', text: lang.createApi, command: 'startCreateDoc'} : null,
                (canCreateApi && canCreateLib) ? {type: 'divider'} : null,
                canCreateLib ? {icon: 'wiki-lib', text: lang.createLib, command: 'createLib'} : null,
            ].filter(Boolean);
            const canExportApi = hasPriv('exportApi') && libID;
            return [
                canExportApi ? {type: 'divider', style: {margin: '6px 0'}} : null,
                canExportApi ? {icon: 'export', text: lang.export, command: 'exportApi'} : null,
                items.length ? {icon: 'plus', type: 'dropdown', btnType: 'primary',  size: 'md', text: lang.create, items: items} : null,
            ];
        }

        const canCreateDoc    = canModify && hasPriv('create');
        const canCreateLib    = canModify && hasPriv('createLib');
        const canCreateSpace  = canModify && hasPriv('createSpace');
        const canCreateOffice = canModify && hasPriv('createOffice');
        const canUploadDoc    = canModify && hasPriv('uploadFile');
        const canExportDoc    = hasPriv('exportDoc');

        /* 文档列表没有文档时的按钮。Actions for empty doc list. */
        if(info.ui === 'doc-list-empty')
        {
            return canCreateDoc ? [{icon: 'plus', text: lang.createDoc, btnType: 'primary', command: 'startCreateDoc'}] : null;
        }

        const items = canModify ?
        [
            canCreateDoc ? {icon: 'plus', text: lang.createDoc, command: 'startCreateDoc'} : null,
            canCreateDoc ? {type: 'divider'} : null,
            canCreateOffice ? {icon: 'file-word', text: lang.createList.word, command: 'startCreateOffice/word'} : null,
            canCreateOffice ? {icon: 'file-powerpoint', text: lang.createList.ppt, command: 'startCreateOffice/ppt'} : null,
            canCreateOffice ? {icon: 'file-excel', text: lang.createList.excel, command: 'startCreateOffice/excel'} : null,
            (canCreateOffice && (canCreateLib || canCreateSpace)) ? {type: 'divider'} : null,
            canCreateSpace ? {icon: 'cube', text: lang.createSpace, command: 'createSpace'} : null,
            canCreateLib ? {icon: 'wiki-lib', text: lang.createLib, command: 'createLib'} : null,
        ].filter(Boolean) : [];
        return [
            ((canExportDoc && libID > 0) || canCreateDoc) ? {type: 'divider', style: {margin: '6px 0'}} : null,
            canExportDoc && libID > 0 ? {icon: 'export', text: lang.export, command: 'exportDoc'} : null, // 不在库中，无法导出
            canUploadDoc ? {icon: 'import', text: lang.uploadDoc, command: 'uploadDoc'} : null,
            items.length ? {icon: 'plus', type: 'dropdown', btnType: 'primary',  size: 'md', text: lang.create, items: items} : null,
        ];
    },

    /**
     * 定义文档编辑时的操作按钮。
     * Define the actions on toolbar of the doc editing page.
     */
    'doc-edit': function(info)
    {
        const doc = info.data;
        if(!doc) return;

        const lang = getLang();

        if(isApiLib())
        {
            return [
                {text: lang.save, size: 'md', className: 'btn-wide', type: 'primary', command: 'saveApiDoc'},
                {text: lang.cancel, size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelEditDoc'},
            ];
        }

        return [
            doc.status === 'draft' ? {text: lang.saveDraft, size: 'md', className: 'btn-wide', type: 'secondary', command: 'saveDoc/draft'} : null,
            {text: lang.release, size: 'md', className: 'btn-wide', type: 'primary', command: 'saveDoc'},
            {text: lang.cancel, size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelEditDoc'},
            {text: lang.settings, size: 'md', type: 'ghost', command: `showDocSettingModal/${doc.id}/${doc.contentType}/1`, icon: 'cog-outline'},
        ];
    },

    /**
     * 定义文档创建时的操作按钮。
     * Define the actions on toolbar of the doc creating page.
     */
    'doc-create': function()
    {
        const lang = getLang();
        if(isApiLib())
        {
            return [
                {text: lang.save, size: 'md', className: 'btn-wide', type: 'primary', command: 'saveApiDoc'},
                {text: lang.cancel, size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelCreateDoc'},
            ];
        }
        return [
            {text: lang.saveDraft, size: 'md', className: 'btn-wide', type: 'secondary', command: 'saveNewDoc/draft'},
            {text: lang.release, size: 'md', className: 'btn-wide', type: 'primary', command: 'saveNewDoc'},
            {text: lang.cancel, size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelCreateDoc'},
        ];
    },

    /**
     * 定义文件的操作按钮。
     * Define the actions of file.
     */
    file: function(info)
    {
        const doc          = info.doc;
        const file         = info.data;
        const docApp       = getDocApp();
        const privs        = docApp.props.privs;
        const lang         = getLang();
        const canEdit      = privs.edit && (!doc.privs || doc.privs.edit !== false);
        const hasExtension = file.title.lastIndexOf('.' + file.extension) == (file.title.length - file.extension.length - 1);
        const canPreview   = hasExtension && 'mp4,txt,jpg,jpeg,gif,png,bmp'.split(',').includes(file.extension);
        return [
            canPreview ? {'data-toggle': 'modal', 'data-size': 'lg', url: $.createLink('file', 'download', `fileID=${file.id}&mouse=left`), hint: lang.filePreview, icon: 'eye'} : {target: '_blank', url: $.createLink('file', 'download', `fileID=${file.id}&mouse=left`), hint: lang.filePreview, icon: 'eye'},
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

/**
 * 定义界面操作命令。文档中的大部分操作按钮都会调用这里定义的命令。
 * Define the UI commands. Most of the action buttons in the doc will call the commands defined here.
 */
const commands =
{
    /* 创建文档。 Create doc. */
    startCreateDoc: function()
    {
        const docApp = getDocApp();
        if(!docApp.libList.length) return zui.Modal.alert(getLang('createLibFirst'));
        docApp.startCreateDoc();
    },
    /** 上传文档。Upload Doc. */
    uploadDoc: function()
    {
        const docApp = getDocApp();
        const type   = docApp.lib ? docApp.lib.data.type : docApp.spaceType;
        const url = $.createLink('doc', 'uploadDocs', `objectType=${type}&objectID=${docApp.spaceID}&libID=${docApp.libID}&moduleID=${docApp.moduleID}&type=attachment`);
        zui.Modal.open({url: url});
    },
    /** 创建 Office 文件。 Start create office file. */
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
        const url = $.createLink('doc', 'editSpace', `libID=${spaceID}`);
        zui.Modal.open({size: 'sm', url: url});
    },
    deleteSpace: function(_, args)
    {
        const docApp  = getDocApp();
        const spaceID = args[0] || docApp.spaceID;
        $.ajaxSubmit(
        {
            confirm: getLang('confirmDeleteSpace'),
            url:     $.createLink('doc', 'deleteSpace', `libID=${spaceID}`),
            load:    false,
            onSuccess: function(res)
            {
                if(res && res.result === 'success')
                {
                    const docApp = getDocApp();
                    if(spaceID === docApp.spaceID && docApp.mode !== 'home')
                    {
                        const spaceList = docApp.spaceList;
                        if(spaceList.length)
                        {
                            docApp.selectSpace(spaceList[0].id);
                            return;
                        }
                    }
                    docApp.delete('space', spaceID);
                }
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
                const docApp = getDocApp();
                docApp.delete('lib', libID);
                if(docApp.mode === 'home') docApp.loadHomeLibs();
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
                moduleType: isApiLib(libID) ? 'api' : 'doc',
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
                    if(!docApp.hasEditingDoc) docApp.selectModule(res.module.id);
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
            url:     $.createLink('doc', 'deleteCatalog', `moduleID=${moduleID}`),
            load:    false,
            onSuccess: function(res)
            {
                if(!res) return;
                if(res.result === 'success')
                {
                    const docApp = getDocApp();
                    const moduleInfo = docApp.treeMap.modules.get(+moduleID);
                    if(moduleInfo)
                    {
                        const parent = moduleInfo.parent;
                        docApp.changeState(
                        {
                            libID: parent.type === 'lib' ? parent.data.id : docApp.libID,
                            module: parent.type === 'lib' ? 0 : parent.data.id,
                        })
                    }
                    else if(docApp.mode === 'list')
                    {
                        docApp.selectLib(docApp.libID, 0);
                    }
                    docApp.delete('module', moduleID);
                }
                else if(res.message)
                {
                    zui.Modal.alert(res.message);
                }
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
    handleMovedDoc(_, args)
    {
        const docID   = +args[0];
        const space   = args[1];
        const spaceID = +(space.includes('.') ? space.split('.').pop() : space);
        const docApp  = getDocApp();
        if(docApp.mode === 'view')
        {
            if(spaceID === docApp.spaceID) docApp.loadDoc(docID).then(() => docApp.selectDoc(docID));
            else if(space.startsWith(docApp.spaceType)) docApp.loadDoc(docID).then(() => docApp.selectLib(docApp.libID));
            else loadPage($.createLink('doc', 'view', `docID=${docID}`));
        }
        else
        {
            docApp.load(null, null, null, {noLoading: true, picks: 'doc'});
        }
    },
    deleteDoc: function(_, args)
    {
        const docApp  = getDocApp();
        const docID   = args[0] || docApp.docID;
        const isApi   = isApiDoc(docID);
        $.ajaxSubmit(
        {
            confirm: getLang('confirmDelete'),
            url:     $.createLink(isApi ? 'api' : 'doc', 'delete', `${isApi ? 'apiID' : 'docID'}=${isApi ? getApiID(docID) : docID}`),
            load:    false,
            onSuccess: function()
            {
                getDocApp().delete('doc', docID);
            }
        });
    },
    effortDoc: function(_,args)
    {
        const docApp = getDocApp();
        const docID  = args[0] || docApp.docID;
        const url = $.createLink('effort', 'createForObject', `objectType=doc&objectID=${docID}`);

        zui.Modal.open({url: url, size: 'sm'});
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
    },
    exportApi: function(_, args)
    {
        const docApp   = getDocApp();
        const libID    = args[0] || docApp.libID;
        const release  = args[1] || docApp.signals.libReleaseMap.value[libID] || 0;
        const moduleID = args[2] || docApp.moduleID;
        const url = $.createLink('api', 'export', `libID=${libID}&version=0&release=${release}&moduleID=${moduleID}`);
        zui.Modal.open({url: url, size: 'sm'});
    },
    showDocSettingModal: function(_, args)
    {
        const docApp     = getDocApp();
        const doc        = docApp.doc;
        const docID      = args[0] || doc.id;
        const docType    = args[1] || doc.contentType;
        const saveEdited = args[2] || 0;
        showDocBasicModal(docID).then(formData => {
            savingDocData[docID] = formData;
            if(saveEdited == 1)
            {
                const docData = {
                    content    : doc.data.content,
                    status     : doc.data.status,
                    contentType: doc.data.contentType,
                    type       : doc.data.type,
                    lib        : doc.data.lib,
                    module     : doc.data.module,
                    title      : doc.data.title,
                    keywords   : doc.data.keywords,
                    acl        : doc.data.acl,
                    space      : doc.data.space,
                    uid        : doc.data.uid,
                };
                mergeDocFormData(docData, formData);
                $.post($.createLink('doc', 'edit', `docID=${docID}`), docData);
            }
        });
    },

    /**
     * 保存 API 文档数据。
     * Save the API doc data.
     */
    saveApiDoc: function()
    {
        /* 触发 API 表单的提交事件。 */
        $('#docApp .doc-view form').trigger('submit');
    },

    /**
     * 加载指定的 API 文档。
     * Load the specified API doc.
     */
    loadApi: function(_, args)
    {
        const apiID   = getApiID(args[0] || this.docID);
        const version = args[1] || 0;
        const release = args[2] || 0;
        const select  = !!args[3];
        $.getJSON($.createLink('api', 'ajaxGetApi', `apiID=${apiID}&version=${version}&release=${release}`), function(result)
        {
            if(!result || typeof result !== 'object') return;
            result.id = `api.${apiID}`;
            const docApp = getDocApp();
            docApp.update('doc', result);
            if(select) docApp.selectDoc(result.id);
        });
    },

    /**
     * 选择 API 文档。
     * Select the specified API doc.
     */
    selectApi: function(_, args)
    {
        const apiID = args[0];
        getDocApp().selectDoc(`api.${getApiID(apiID)}`);
    },

    /**
     * 显示结构列表。
     * Show the struct list.
     */
    showStructs: function()
    {
        getDocApp().changeState(
        {
            mode: 'list',
            moduleID: 0,
            docID: 0,
            listType: 'structs',
        });
    },

    /**
     * 显示版本列表。
     * Show the release list.
     */
    showReleases: function()
    {
        getDocApp().changeState(
        {
            mode: 'list',
            moduleID: 0,
            docID: 0,
            listType: 'releases',
        });
    },

    /**
     * 显示API 目录。
     * Show the api catalog.
     */
    showModules: function()
    {
        getDocApp().changeState(
        {
            mode: 'list',
            moduleID: 0,
            docID: 0,
            listType: '',
        });
    },

    /**
     * 获取库的 API 列表。
     * Get the API list of the library.
     */
    loadLibApi: function(_, args)
    {
        const docApp        = getDocApp();
        const libReleaseMap = docApp.signals.libReleaseMap.value;
        const libID         = args[0] || docApp.libID;
        if(!libID) return;

        const releaseID = args[1] || libReleaseMap[libID] || 0;
        const url       = $.createLink('api', 'ajaxGetLibApiList', `libID=${libID}&releaseID=${releaseID}`);
        $.getJSON(url, function(data)
        {
            const lib = docApp.getLib(libID);
            if(lib && lib.docs && lib.docs.length) docApp.delete('doc', lib.docs.map(x => x.data.id));
            data.forEach((x) => x.id = `api.${x.id}`);
            docApp.update('doc', data);
        });
    },

    /**
     * 更改当前库的发布版本。
     * Change the release of the current library.
     */
    changeLibRelease: function(_, args)
    {
        const docApp = getDocApp();
        const libID = args[0];
        const release = args[1];
        const libReleaseMap = docApp.signals.libReleaseMap.value;
        if(libReleaseMap[libID] === release) return;
        libReleaseMap[libID] = release;
        docApp.signals.libReleaseMap.value = $.extend({}, libReleaseMap);
        docApp.executeCommand('loadLibApi', [libID, release]);
        const doc = docApp.doc;
        if(doc && doc.data.lib === libID) docApp.executeCommand('loadApi', [doc.data.id, 0, release]);
    },

    /**
     * 加载懒加载内容。
     * Load the lazy content.
     */
    loadLazyContent: function(_, args)
    {
        const selector = args[0];
        if(!selector) return;
        $(selector).closest('.lazy-content').trigger('loadContent');
    },

    /**
     * 更新懒加载内容。
     * Update the lazy content.
     */
    updateLazyContent: function(context, args)
    {
        const event    = context.event;
        const $element = $(event.currentTarget);
        const selector = args[0] || $element.data('lazyTarget');
        const $lazy    = (selector ? $(selector) : $element).closest('.lazy-content');
        const url      = $element.data('url') || $element.attr('href');
        if(url) $lazy.trigger('loadContent', url);
        event.preventDefault();
        event.stopPropagation();
    },
};

function getApiID(id)
{
    if(typeof id === 'number') return id;
    if(String(+id) === id) return +id;
    return +id.replace('api.', '');
}

/**
 * 定义页面上的自定义渲染器。
 * Define the custom renderers on the page.
 */
const customRenders =
{
    /**
     * 定义 API 文档编辑器渲染，包括查看、编辑和创建。
     * Define the API doc editor render, including view, edit and create.
     */
    editor: function()
    {
        const mode = this.mode;
        if(!isApiLib())
        {
            const docInfo = this.doc;
            const doc = docInfo ? docInfo.data : null;
            if(mode === 'view' && !hasPriv('view')) return {html: `<h1>${doc ? doc.title : ''}</h1><p>${getDocAppLang('accessDenied')}</p>`};
            return;
        }

        if(mode === 'create')
        {
            const libID = this.libID;
            const moduleID = this.moduleID;
            return {fetcher: $.createLink('api', 'create', `libID=${libID}&moduleID=${moduleID}&space=api`), loadingText: getDocAppLang('loading')};
        }
        if(mode === 'edit')
        {
            const doc = this.doc.data;
            return {fetcher: $.createLink('api', 'edit', `apiID=${getApiID(doc.id)}`), loadingText: getDocAppLang('loading')};
        }
        const doc = this.doc.data;
        if(!hasPriv('viewApi')) return {html: `<h1>${doc ? doc.title : ''}</h1><p>${getDocAppLang('accessDenied')}</p>`};

        const release = this.signals.libReleaseMap.value[doc.lib] || 0;
        const version = this.signals.docVersion.value || 0;
        return {fetcher: $.createLink('api', 'view', `libID=${doc.lib}&apiID=${getApiID(doc.id)}&moduleID=${doc.module}&version=${version}&release=${release}`), loadingText: getDocAppLang('loadingDocsData'), className: 'doc-editor-content'};
    },

    /**
     * 定义 API 文档列表渲染，包括结构和版本。
     * Define the API doc list render, including structs and releases.
     */
    list: function()
    {
        if(!isApiLib()) return;
        const listType = this.signals.listType.value;
        const libID = getApiID(this.libID);
        const releaseID = this.signals.libReleaseMap.value[libID] || 0;
        if(listType === 'structs') return {fetcher: $.createLink('api', 'struct', `libID=${this.libID}&releaseID=${releaseID}`), loadingText: getDocAppLang('loading'), className: 'api-struct-list'};
        if(listType === 'releases') return {fetcher: $.createLink('api', 'releases', `libID=${this.libID}`), loadingText: getDocAppLang('loading'), className: 'api-release-list'};
    },

    /**
     * 定义 API 文档列表筛选菜单渲染，当展示结构和版本时不显示筛选菜单。
     * Define the API doc list filter menu render, not show the filter menu when showing structs and releases.
     */
    filters: function()
    {
        if(!isApiLib(null, this)) return;
        const listType = this.signals.listType.value;
        if(this.mode !== 'list' || !listType) return;
        return null;
    },

    /**
     * 定义 API 文档列表工具栏渲染。
     * Define the API doc list toolbar render.
     */
    toolbar: function()
    {
        if(!isApiLib()) return;
        const listType = this.signals.listType.value;
        if(this.mode === 'list' && listType)
        {
            const libID = getApiID(this.libID);
            const items = [];
            if(hasPriv('createStruct') && listType === 'structs') items.push({text: getDocAppLang('createStruct'), icon: 'plus', btnType: 'primary', 'data-toggle': 'modal', 'data-size': 'lg', url: $.createLink('api', 'createStruct', `libID=${libID}`)});
            if(hasPriv('createRelease') && listType === 'releases') items.push({text: getDocAppLang('createRelease'), icon: 'plus', btnType: 'primary', 'data-toggle': 'modal', url: $.createLink('api', 'createRelease', `libID=${libID}`)});
            if(!items.length) return null;
            return {component: 'toolbar', props: {items: items}};
        }
    },

    /**
     * 定义顶部面包屑导航渲染。
     * Define the top breadcrumb render.
     */
    'app-nav': function(items)
    {
        const lib = this.lib;
        if(!lib || !isApiLib(lib)) return items;
        const versions = lib.data.versions;
        if(versions && versions.length)
        {
            const viewIndex = items.findIndex(item => item[0] === 'lib');
            if(viewIndex >= 0)
            {
                const libView = items[viewIndex][1];
                const libID   = getApiID(lib.data.id);
                const release = this.signals.libReleaseMap.value[libID] || 0;
                const options = [{text: getDocAppLang('latestVersion'), value: 0, selected: !release, command: `changeLibRelease/${libID}/0`}];
                versions.forEach(version => options.push({selected: release === version.id, text: `v${version.version}`, value: version.id, command: `changeLibRelease/${libID}/${version.id}`}));
                const currentVersion = versions.find(x => x.id === release);
                const versionPicker = zui.renderCustomContent(
                {
                    content:
                    {
                        component: 'DropdownButton',
                        props:
                        {
                            text     : currentVersion ? currentVersion.version : getDocAppLang('latestVersion'),
                            size     : 'xs',
                            type     : 'gray-pale',
                            rounded  : 'full',
                            className: 'h-4 gap-1 mr-2',
                            items    : options,
                        }
                    }
                });
                items[viewIndex] = ['lib', [libView, versionPicker]];
            }
        }
        const listType = this.signals.listType.value;
        if(this.mode !== 'list' || !listType) return items;
        if(listType === 'structs')  items.push([listType, zui.renderCustomContent({className: 'mx-2', content: getDocAppLang('struct')})]);
        if(listType === 'releases') items.push([listType, zui.renderCustomContent({className: 'mx-2', content: getDocAppLang('releases')})]);
        return items;
    },

    /**
     * 定义侧边栏渲染，显示结构、版本和模块。
     * Define the sidebar render, show structs, releases and modules.
     */
    'sidebar-before': function()
    {
        if(!isApiLib()) return;
        const canViewStructs  = docAppHasPriv('struct');
        const canViewReleases = docAppHasPriv('releases');
        if(!canViewStructs && !canViewReleases) return;

        const isListMode = this.mode === 'list';
        const lang       = getDocAppLang();
        const listType   = this.signals.listType.value;
        const items      = [];
        if(canViewStructs) items.push({text: lang.struct, selected: listType === 'structs' && isListMode, icon: 'treemap muted', command: 'showStructs'}, {type: 'divider', className: 'my-1'});
        if(canViewReleases) items.push({text: lang.releases, selected: listType === 'releases' && isListMode, icon: 'version muted', command: 'showReleases'}, {type: 'divider', className: 'my-1'});
        items.push({text: lang.module, icon: 'list muted', command: 'showModules'});

        return {
            component: 'tree',
            className: 'p-2 pb-0 api-lib-menu',
            props: {items: items, itemProps: {className: 'state'}}
        };
    }
};

/**
 * 获取文档界面上的表格初始化选项。
 * Get the table initialization options on the doc UI.
 *
 * @param {object} options
 * @param {object} info
 * @returns {object}
 */
function getTableOptions(options, info)
{
    const lang = getLang();
    if(info.type === 'doc-list')
    {
        const tableCols = lang.tableCols;
        const canModify = canModifySpace(this.space);
        options.cols.forEach(col =>
        {
            if(typeof tableCols[col.name] === 'string') col.title = tableCols[col.name];
            if(col.name === 'actions' && col.actionsMap)
            {
                if(!canModify) col.actions = [];
                const actionHints = {edit: lang.editDoc, move: lang.moveDoc, delete: lang.deleteDoc};
                const privMap = {edit: hasPriv('edit'), move: hasPriv('moveDoc'), delete: hasPriv('delete')};
                $.each(col.actionsMap, (key, value) =>
                {
                    if(typeof actionHints[key] === 'string') value.hint = actionHints[key];
                    value.disabled = !privMap[key];
                });
            }
        });
        options.onCheckChange = function(changes)
        {
            const checkedList = this.getChecks();
            $.cookie.set('checkedItem', checkedList, {expires:config.cookieLife, path:config.webRoot});
        }
    }
    if(info.type === 'file-list')
    {
        const tableCols = lang.fileTableCols;
        options.cols.forEach(col =>
        {
            if(typeof tableCols[col.name] === 'string') col.title = tableCols[col.name];
        });
    }
    return options;
}

/**
 * 获取文档界面上的列表筛选菜单。
 * Get the filter menu on the doc UI.
 *
 * @param {string} mode
 * @returns {[key: string, text: string][]}
 */
function getFilterTypes(mode)
{
    if(mode === 'home')
    {
        if(currentSpaceType === 'project') return getLang('projectFilterTypes');
        if(currentSpaceType === 'product') return getLang('productFilterTypes');
        return getLang('spaceFilterTypes');
    }
    if(mode === 'list')
    {
        const lib = this.lib;
        if(lib && lib.data.type === 'api') return getLang('apifilterTypes');
        return getLang('filterTypes');
    }
    if(mode === 'files')   return getLang('fileFilterTypes');
}

/**
 * 检查文档是否符合筛选条件。
 * Check if the doc matches the filter condition.
 *
 * @param {string} type
 */
function isMatchFilter(type, filterType, item)
{
    if(type === 'doc')
    {
        if(filterType === 'createdByMe') return item.addedBy === currentUser;
        if(filterType === 'editedByMe')  return item.editedBy === currentUser;
        if(filterType === 'draft')       return item.status === 'draft';
        if(filterType === 'collect')     return item.isCollector;
    }
    if(type === 'project' || type === 'product')
    {
        return filterType !== 'mine' || item.isMine;
    }
    return true;
}

/**
 * 将 [key, ...] 形式的顺序列表转换为 {key: order, ...} 形式。
 * Convert the order list in the form of [key, ...] to {key: order, ...}.
 *
 * @param {number[]} orders
 * @returns {object}
 */
function formatOrders(orders, useIndex)
{
    const orderedKeys = useIndex ? [] : Array.from(orders).sort();
    return orders.reduce((map, key, index) =>
    {
        map[key] = useIndex ? index : orderedKeys[index];
        return map;
    }, {});
}

/**
 * 对文档条目进行排序。
 * Sort the doc items.
 *
 * @param {string}       type   'doc' | 'module' | 'lib' | 'space'
 * @param {object|array} orders {key: order, ...} | [key, ...]
 */
function sortItems(type, orders)
{
    if(Array.isArray(orders)) orders = formatOrders(orders, type === 'lib');

    const sortMethods = {doc: 'sortDoc', module: 'sortCatalog', lib: 'sortDoclib'};
    if(!sortMethods[type]) return console.error(`[DocApp] Invalid sort type: ${type}`);

    const url = $.createLink('doc', sortMethods[type]);
    return $.ajaxSubmit({
        url,
        data: {orders: JSON.stringify(orders)},
        onSuccess: function()
        {
            const items = Object.keys(orders).map(id => ({id: +id, order: orders[id]}));
            getDocApp().update(type, items);
        }
    });
}

/**
 * 获取界面上条目排序选项。
 * Get the sortable options on the doc UI.
 *
 * @param {string} type 'doc' | 'doc-module' | 'module' | 'lib' | 'space'
 */
function getSortableOptions(type)
{
    const canSortDocModule = hasPriv('sortModule') || hasPriv('sortDoc');
    if((type === 'doc-module' && canSortDocModule) || (type === 'lib' && hasPriv('sortDoclib')))
    {
        const getItemType = (key) => key[0] === 'm' ? 'module' : (key[0] === 'l' ? 'lib' : 'doc');
        return {
            sortable: {handle: '.sort-handler'},
            canSortTo: function(event)
            {
                let fromKey = event.dragged.getAttribute('z-key');
                let toKey = event.related.getAttribute('z-key');
                if(!fromKey || !toKey) return false;
                const fromType = getItemType(fromKey);
                const toType = getItemType(toKey);
                if (fromType !== toType) return false;
                return true;
            },
            onSort: function(event, orders)
            {
                const fromKey = event.item.getAttribute('z-key');
                if(!fromKey || !orders.length) return;
                const fromType = getItemType(fromKey);
                const orderedList = [];
                orders.forEach((key, index) =>
                {
                    const keyType = getItemType(key);
                    if(keyType !== fromType) return;
                    if(fromType !== 'doc') key = key.substring(1);
                    orderedList.push(+key);
                });
                sortItems(fromType, orderedList);
            }
        }
    }
}

/**
 * 获取文档详情侧边栏标签页定义。
 * Get the doc view sidebar tabs.
 *
 * @param {object} doc
 */
window.getDocViewSidebarTabs = function(doc, info)
{
    if(isApiLib())
    {
        const lang = getDocAppLang();
        if(info.isNewDoc || info.mode === 'create') return [];
        return [
            {key: 'info',    icon: 'info',     title: lang.docInfo},
            info.mode === 'edit' ? null : {key: 'outline', icon: 'list-box', title: lang.docOutline},
            {key: 'history', icon: 'history',  title: lang.history},
        ].filter(Boolean);
    }

    if(info.isNewDoc) return [{key: 'outline', icon: 'list-box', title: getLang('docOutline')}];
    return [
        {key: 'info',    icon: 'info',     title: getLang('docInfo')},
        {key: 'outline', icon: 'list-box', title: getLang('docOutline')},
        {key: 'history', icon: 'history',  title: getLang('history')},
        {
            key   : 'editors',
            icon  : 'format-list-numbers',
            title : getLang('updateHistory'),
            render: function(doc)
            {
                const docApp  = getDocApp();
                const editors = doc.editors || [];
                const html =
                [
                    '<div class="col gap-2">',
                        `<div class="font-bold px-1">${getLang('updateHistory')}</div>`,
                        editors.length ? [
                            '<ul class="space-y-1">',
                                editors.map(({account, date}) =>
                                {
                                    const user = docApp.getUserInfo(account);
                                    return `<li>${getLang('updateInfoFormat', [{name: user ? user.realname : account, time: zui.formatDate(date, 'yyyy-M-d')}])}</li>`;
                                }).join(''),
                            '</ul>'
                        ].join('\n') : `<div class="text-gray p-1">${getLang('noUpdateInfo')}</div>`,
                    '</div>'
                ].join('\n');
                return {html: html};
            }
        }
    ]
}

function getSpaceFetcher(spaceType, spaceID = 0)
{
    if(spaceType === 'product')
    {
        return $.createLink('product', 'ajaxGetDropMenu', `productID=${spaceID}&module=doc&method=app&extra=&from=&useLink=0`);
    }

    if(spaceType === 'project')
    {
        return $.createLink('project', 'ajaxGetDropMenu', `projectID=${spaceID}&module=doc&method=app&extra=&useLink=0`);
    }
}

/**
 * 设置文档应用组件选项。
 * Set the doc app options.
 */
window.setDocAppOptions = function(_, options)
{
    currentUser      = options.currentUser;
    currentSpaceType = options.spaceType;
    userPrivs        = options.privs;
    docLangData      = options.langData;

    return $.extend(
    {
        defaultState         : {listType: '', libReleaseMap: {}},
        commands             : commands,
        customRenders        : customRenders,
        onCreateDoc          : userPrivs.create ? handleCreateDoc : null,
        onSaveDoc            : userPrivs.edit ? handleSaveDoc : null,
        canMoveDoc           : canMoveDoc,
        onSwitchView         : handleSwitchView,
        getActions           : getActions,
        getTableOptions      : getTableOptions,
        getFilterTypes       : getFilterTypes,
        isMatchFilter        : isMatchFilter,
        getSortableOptions   : getSortableOptions,
        getDocViewSidebarTabs: getDocViewSidebarTabs,
        getSpaceFetcher      : getSpaceFetcher,
    }, window.docAppOptions, options);
};

window.docAppActions       = actionsMap;
window.docAppCommands      = commands;
window.docAppCustomRenders = customRenders;
window.getDocApp           = getDocApp;
window.getDocAppLang       = getLang;
window.docAppHasPriv       = hasPriv;
