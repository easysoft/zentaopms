const savingDocData = {};
let docBasicModal   = {};
let currentUser     = null;
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
    const lang = getDocAppLang();

    options.data.sort((a, b) => a.addedDate > b.addedDate ? -1 : 1);

    templateCols = [];
    templateCols.push({...options.cols.find(col => col.name === 'rawID'), type: 'id'});

    let title      = options.cols.find(col => col.name == 'title');
    let addedBy    = options.cols.find(col => col.name == 'addedBy');
    let addedDate  = options.cols.find(col => col.name == 'addedDate');
    let editedBy   = options.cols.find(col => col.name == 'editedBy');
    let editedDate = options.cols.find(col => col.name == 'editedDate');
    let actionsCol = options.cols.find(col => col.name == 'actions');
    let typeCol    = {name: 'moduleName', title: lang.tableCols.type, type: 'string', sort: true};
    let viewsCol   = {name: 'views', title: lang.tableCols.views, type: 'number', width: '80px', sort: true};
    let descCol    = {name: 'templateDesc', title: lang.tableCols.desc, type: 'desc', sort: true}

    title.title      = lang.tableCols.title;
    addedBy.title    = lang.tableCols.addedBy;
    addedDate.title  = lang.tableCols.addedDate;
    editedBy.title   = lang.tableCols.editedBy;
    editedDate.title = lang.tableCols.editedDate;
    actionsCol.title = lang.tableCols.actions;

    templateCols.push(title);
    templateCols.push(typeCol);
    templateCols.push(addedBy);
    templateCols.push(addedDate);
    templateCols.push(editedBy);
    templateCols.push(editedDate);
    templateCols.push(viewsCol);
    templateCols.push(descCol);
    templateCols.push(actionsCol);

    options.cols = templateCols;
    options.cols.forEach(col =>
    {
        if(col.name === 'actions' && col.actionsMap)
        {
            col.actions = col.actions.filter(action => (action !== 'move' && docAppHasPriv(action)));
            delete col.actionsMap.move;

            const privMap     = {edit: docAppHasPriv('edit'), delete: docAppHasPriv('delete')};
            const actionHints = {edit: lang.editTemplate, delete: lang.deleteTemplate};
            $.each(col.actionsMap, (key, value) =>
            {
                if(typeof actionHints[key] === 'string') value.hint = actionHints[key];
                value.disabled = !privMap[key];
            });
        }
    });

    options.checkable = false;

    options.footer = options.footer.filter(f => f !== 'checkbox');
    options.footer = options.footer.filter(f => f !== 'checkedInfo');

    return options;
}

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

function showDocBasicModal(parentID, docID, isDraft, modalType = 'doc')
{
    const docApp    = getDocApp();
    const spaceID   = docApp.spaceID;
    const spaceType = docApp.spaceType;
    const libID     = docApp.libID;
    const moduleID  = docApp.moduleID;
    const url       = $.createLink('doc', 'setDocBasic', `objectType=template&objectID=${spaceID}&libID=${libID}&moduleID=${moduleID}&parentID=${parentID || 0}&docID=${docID || 0}&isDraft=${isDraft ? 'yes' : 'no'}&modalType=${modalType}`);

    if(docBasicModal.doc === docID && docBasicModal.current)
    {
        docBasicModal.current.show();
    }
    else
    {
        if(docBasicModal.current) docBasicModal.current.destroy();
        docBasicModal.doc = docID;
        zui.Modal.open(
        {
            key          : `${config.currentModule}-${config.currentMethod}-${spaceType}-${docID}`,
            ref          : docBasicModal,
            url          : url,
            destroyOnHide: !docID,
            cache        : true,
            request: {
                error: error => {
                    if(docBasicModal.current) docBasicModal.current.hide();
                    showSaveFailedAlert(error);
                }
            },
            onHidden: () => {
                if(!docBasicModal.keepOnHide && docBasicModal.current) docBasicModal.current.destroy();
            },
            $onDestroy: () => {
                if(docBasicModal.current) docBasicModal = {};
            }
        });
    }
    return new Promise((resolve) => {window.docBasicModalResolver = resolve;});
}

window.beforeSetDocBasicInfo = function(_, form)
{
    docBasicModal.keepOnHide = true;

    if (window.docBasicModalResolver) window.docBasicModalResolver(new FormData(form));
    zui.Modal.query('#setDocBasicForm').hide();
    return false;
};

function showDocSettingModal(_, args)
{
    const docApp     = getDocApp();
    const doc        = docApp.doc;
    const docID      = args[0] || doc.id;
    const docType    = args[1] || doc.contentType;
    const saveEdited = args[2] || 0;
    showDocBasicModal(doc.data.parent, docID).then(formData => {
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
        }
    });
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
function submitNewDoc(doc, spaceID, libID, moduleID, formData, afterCreate)
{
    const docApp = getDocApp();

    if(docApp.isSavingDoc) return;
    docApp.isSavingDoc = true;
    $(docApp.element).find('[zui-command^="saveDoc"],[zui-command^="saveNewDoc"]').attr('disabled', true);

    const spaceType = docApp.signals.spaceType.value;
    const module    = docApp.treeMap.modules.get(formData && moduleID === 0 ? parseInt(formData.get('module')) : moduleID);
    const url       = $.createLink('doc', 'createTemplate', `libID=${libID}&moduleID=${moduleID}`);
    const docData   =
    {
        rawContent   : doc.content,
        content      : doc.html,
        status       : doc.status || 'normal',
        contentType  : doc.contentType,
        type         : doc.type || 'text',
        lib          : libID,
        module       : moduleID,
        title        : doc.title,
        keywords     : doc.keywords,
        templateDesc : doc.templateDesc,
        acl          : 'private',
        space        : spaceType,
        project      : 0,
        templateType : module && module.data ? module.data.short : '',
        uid          : (doc.uid || `doc${doc.id}`),
        parent       : doc.parent
    };
    if(formData) mergeDocFormData(docData, formData);
    docApp.props.fetcher = $.createLink('doc', 'ajaxGetSpaceData', `type=template&spaceID=${spaceID}&picks={picks}&libID=${libID}`);
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
                if(!checkResponse(data)) return;
                if(typeof data !== 'object' || data.result === 'fail')
                {
                    throw new Error(getErrorMessage(data));
                }
                const newDoc = $.extend(doc, {id: data.id}, docData, data.doc, {status: doc.status || data.status});
                if (!newDoc.space.includes('.')) {
                    newDoc.space = `${spaceType}.${docData[spaceType]}`;
                }
                resolve(newDoc);
                if(afterCreate)
                {
                    docApp.load(null, null, null, {noLoading: true, picks: 'doc'}).then(() => {
                        afterCreate(newDoc);
                    });
                }
            }
            catch (error)
            {
                showSaveFailedAlert(error);
                reject(error);
            }
        }).fail(error => {
            resolve(false);
            showSaveFailedAlert(error);
        }).complete(() => {
            docApp.isSavingDoc = false;
            $(docApp.element).find('[zui-command^="saveDoc"],[zui-command^="saveNewDoc"]').removeAttr('disabled');
        });
    });
}

/**
 * 向服务器提交编辑文档内容。
 * Submit edit doc to server.
 *
 * @param {FormData} formData
 * @access public
 * @return void
 */
function submitEditDoc(formData)
{
    const docApp  = getDocApp();
    const doc     = docApp.doc.data;
    const url     = $.createLink('doc', 'editTemplate', `templateID=${doc.id}`);
    if(formData) mergeDocFormData(doc, formData);

    return new Promise((resolve) => {
        $.post(url, doc, (res) =>
        {
            try
            {
                const data = JSON.parse(res);
                if(!checkResponse(data)) return;
                if(typeof data !== 'object' || data.result === 'fail')
                {
                    let message = data.message || data.error || getLang('errorOccurred');
                    if(typeof message === 'object') message = Object.values(message).map(x => Array.isArray(x) ? x.join('\n') : x).join('\n');
                    throw new Error(message);
                }
                resolve(doc);
            }
            catch (error)
            {
                resolve(false);
                showSaveFailedAlert(error);
                if(!error.message) return;
            }
        }).fail(error => {
            resolve(false);
            showSaveFailedAlert(error);
        }).complete(() => {
            docApp.load(null, null, null, {noLoading: true, picks: 'doc'});
        });
    });
}

/**
 * 处理创建文档的操作请求，向服务器发送请求并返回创建的文档对象。
 * Handle the create doc operation request, send a request to the server and return the created doc object.
 */
function handleCreateDoc(doc, spaceID, libID, moduleID)
{
    return showDocBasicModal(0, 0, doc.status === 'draft').then((formData) => {
        moduleID = parseInt(formData.get('module'));
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
    const url       = $.createLink('doc', 'editTemplate', `docID=${doc.id}`);
    const docData   = {
        rawContent    : doc.content,
        status        : doc.status || 'normal',
        contentType   : doc.contentType,
        type          : 'text',
        lib           : libID,
        module        : moduleID,
        title         : doc.title,
        keywords      : doc.keywords,
        templateDesc  : doc.templateDesc,
        acl           : doc.acl,
        content       : doc.html,
        isDeliverable : doc.isDeliverable || '0',
        space         : spaceType,
        uid           : (doc.uid || `doc${doc.id}`),
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
                if(!checkResponse(data)) return;
                if(typeof data !== 'object' || data.result === 'fail')
                {
                    let message = data.message || data.error || getLang('errorOccurred');
                    if(typeof message === 'object') message = Object.values(message).map(x => Array.isArray(x) ? x.join('\n') : x).join('\n');
                    throw new Error(message);
                }

                doc = $.extend({}, doc, docData, data.doc);
                const libID = +doc.lib;
                const lib = docApp.getLib(libID);
                if(!lib)
                {
                    resolve(false);
                    return loadPage($.createLink('doc', 'view', `docID=${doc.id}`));
                }

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
        }).fail(error => {
            resolve(false);
            showSaveFailedAlert(error);
        }).complete(() => {
            docApp.isSavingDoc = false;
            docApp.props.fetcher = $.createLink('doc', 'ajaxGetSpaceData', `type=template&spaceID=${docAppData.space}&picks={picks}&libID=${docAppData.lib}`);
            $(docApp.element).find('[zui-command^="saveDoc"],[zui-command^="saveNewDoc"]').removeAttr('disabled');
            const {id, acl, users, addedBy} = doc;
            if(acl == 'private' && addedBy !== currentUser)
            {
                if(docApp.hasEditingDoc)
                {
                    docApp.cancelEditDoc().then(() => {
                        docApp.delete('doc', id);
                    });
                }
                else
                {
                    docApp.delete('doc', id);
                }
            }
            docApp.load(null, null, null, {noLoading: false, picks: 'doc'});
        });
    });
}

const customRenders =
{
    home: function()
    {
        const homeViewUrl = $.createLink('doc', 'browseTemplate', `libID=0&filterType=all&docID=0&orderBy=&recPerPage=20&pageID=1&mode=home`);
        return {fetcher: homeViewUrl, clearBeforeLoad: false, className: 'doc-template-home h-full', class: 'h-full col',htmlRender: (element, props) => $(element).morphInner(`<div class="lazy-content doc-template-home h-full">${props.html}</div>`)};
    },

    /**
     * 定义 API 文档列表工具栏渲染。
     * Define the API doc list toolbar render.
     */
    toolbar: function()
    {
        if(this.mode === 'list')
        {
            const items = [];
            if(docAppHasPriv('create')) items.push({text: getDocAppLang('createTemplate'), icon: 'plus', btnType: 'primary', command: 'startCreateTemplate'});
            return {component: 'toolbar', props: {items: items}};
        }
    },
    'sidebar-footer-hint': function()
    {
        return '';
    }
}

/**
 * 定义文档模板各个视图和 UI 元素的上的操作方法。
 * Define the operation methods on the views and UI elements of the doc template.
 */
$.extend(window.docAppActions,
{
    /**
     * 定义分组的操作按钮。
     * Define the actions on the type group.
     */
    module: function(info)
    {
        const items  = [];
        const module = info.data;
        const lang   = getLang();

        if(docAppHasPriv('addModule')) items.push({text: lang.actions.addSameModule, command: `addModule/${module.root}/${module.parent}`});
        if(module.grade == 1 && docAppHasPriv('editModule')) items.push({text: lang.actions.addSubModule, command: `addModule/${module.root}/${module.id}`});
        if(docAppHasPriv('editModule')) items.push({text: lang.actions.editModule, command: `editModule/${module.id}`});
        if(docAppHasPriv('deleteModule')) items.push({text: lang.actions.deleteModule, command: `deleteModule/${module.id}`});

        if(info.ui === 'sidebar')
        {
            return [
                items.length ? {icon: 'ellipsis-v', caret: false, placement: 'bottom-end', size: 'xs', items: items} : null,
            ];
        }

        return items;
    },
    /**
     * 定义文档库的操作按钮。
     * Define the actions of modules.
     */
    lib: function(info)
    {
        const lang  = getLang();
        const items = [];
        const lib   = info.data;
        const libID = lib?.data == undefined ? lib?.id : lib?.data.id;

        /* 获取侧边栏没有模块时的操作按钮。 Get actions when sidebar no module. */
        if(info.ui === 'sidebar-no-module')
        {
            if(!docAppHasPriv('addModule')) return;
            return [{text: lang.actions.addModule, command: `addModule/${libID}/0/${libID}/child`, icon: 'plus', type: 'primary-pale'}];
        }

        if(docAppHasPriv('addModule')) items.push({text: lang.actions.addModule, command: `addModule/${libID}/0/${libID}/child`});
        if(!items.length) return;

        return [{type: 'dropdown', icon: 'cog-outline', square: true, caret: false, placement: 'top-end', items: items}];
    },
    /**
     * 定义文档编辑时的操作按钮。
     * Define the actions on toolbar of the doc editing page.
     */
    'doc-edit': function(info)
    {
        const doc = info.data;
        if(!doc) return;

        const lang      = getLang();
        const isToolbar = info.ui === 'toolbar';
        const docApp    = this;
        return [
            isToolbar ? {hint: docApp.fullscreen ? lang.exitFullscreen : lang.enterFullscreen, icon: docApp.fullscreen ? 'fullscreen-exit' : 'fullscreen', command: 'toggleFullscreen'} : null,
            (isToolbar && docApp.props.showDocHistory !== false) ? {hint: lang.history, icon: 'file-log', command: 'toggleViewSideTab/history'} : null,
            doc.status === 'draft' ? {text: lang.saveDraft, size: 'md', className: 'btn-wide', type: 'secondary', command: 'saveDoc/draft'} : null,
            {text: lang.release, size: 'md', className: 'btn-wide', type: 'primary', command: 'saveDoc'},
            {text: lang.cancel, size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelEditDoc'},
            {text: lang.settings, size: 'md', type: 'ghost', command: `showDocSettingModal/${doc.id}/template/1`, icon: 'cog-outline'},
        ];
    },
    /**
     * 定义模板列表的操作按钮。
     * Define the actions on the template list.
     */
    'doc-list': function(info)
    {
        const lang         = getLang();
        const canCreateDoc = hasPriv('create');

        /* 文档列表没有文档时的按钮。Actions for empty doc list. */
        if(info.ui === 'doc-list-empty')
        {
            return canCreateDoc ? [{icon: 'plus', text: lang.createTemplate, btnType: 'primary', command: 'startCreateTemplate'}] : null;
        }

        const items = [];
        if(canCreateDoc) items.push({text: lang.createTemplate, icon: 'plus', btnType: 'primary', command: 'startCreateTemplate'});
        return items.length ? {component: 'toolbar', props: {items: items}} : null;
    }
});

/**
 * 处理文档应用销毁事件，销毁文档基本信息对话框。
 * Handle the doc app destroy event, destroy the doc basic info dialog.
 */
function handleDocAppDestroy()
{
    if(docBasicModal.current)
    {
        docBasicModal.current.destroy();
        docBasicModal = {};
    }
}

/**
 * 重写文档应用的配置选项方法。
 * Override the method to set the doc app options.
 */
window._setDocAppOptions = window.setDocAppOptions; // Save the original method.
window.setDocAppOptions = function(_, options) // Override the method.
{
    options     = window._setDocAppOptions(_, options);
    currentUser = options.currentUser;
    return $.extend(options,
    {
        onCreateDoc     : handleCreateDoc,
        onSaveDoc       : handleSaveDoc,
        getTableOptions : getTableOptions,
        customRenders   : $.extend(docAppCustomRenders, customRenders),
        $onDestroy      : handleDocAppDestroy,
    });
};

/* 扩展文档模板命令定义。 Extend the doc app command definition. */
$.extend(window.docAppCommands,
{
    startCreateTemplate: function(_, args)
    {
        const docApp = getDocApp();
        const lib    = docApp.lib;
        if(!lib || !lib.modules.length) return zui.Modal.alert(getLang('createTypeFirst'));

        const {spaceID, libID, moduleID} = docApp;
        return showDocBasicModal(args?.[0] ?? 0, 0, true, 'doc').then((formData) => {
            zui.Editor.loadModule().then(() => {
                const emptyDoc = zui.Editor.createEmptyDoc();
                const snapshot = zui.Editor.toSnapshot(emptyDoc);
                const doc = {contentType: 'doc', status: 'draft', content: JSON.stringify(snapshot)};
                return submitNewDoc(doc, 0, libID, moduleID, formData, (newDoc) => {
                    docApp.selectDoc(newDoc.id);
                    docApp.startEditDoc(newDoc.id);
                });
            });
        });
    },
    addChapter: function(_, args)
    {
        const docApp = getDocApp();
        const {spaceID, libID, moduleID} = docApp;
        return showDocBasicModal(args[0], 0, false, 'chapter').then((formData) => {
            const doc = {type: 'chapter', status: 'normal', parent: args[0]};
            return submitNewDoc(doc, spaceID, libID, moduleID, formData).then(() => {
                docApp.load(null, null, null, {noLoading: false, picks: 'doc'});
                if(docBasicModal.current)
                {
                    docBasicModal.current.destroy();
                    docBasicModal = {};
                }
            });
        });
    },
    editChapter: function(_, args)
    {
        const docApp = getDocApp();
        return showDocBasicModal(docApp.doc.data.parent, args[0], false, 'chapter').then((formData) => {
            return submitEditDoc(formData);
        });
    },
    deleteDoc: function(_, args)
    {
        const docApp    = getDocApp();
        const docID     = args[0] || docApp.docID;
        const docInfo   = docApp.treeMap.docs.get(docID)
        const doc       = docInfo.data;
        const isChapter = doc?.type === 'chapter';

        $.ajaxSubmit(
        {
            confirm: getLang(isChapter ? (docInfo.docs.length > 0 ? 'confirmDeleteChapterWithSub' : 'confirmDeleteChapter') : (docInfo.docs.length > 0 ? 'confirmDeleteWithSub' : 'confirmDelete')),
            url:     $.createLink('doc', 'deleteTemplate', `docID=${docID}`),
            load:    false,
            onSuccess: function()
            {
                getDocApp().delete('doc', docID);
            }
        });
    },
    moveDoc: function(_, args)
    {
        const docApp = getDocApp();
        const docID  = args[0] || docApp.docID;
        const url    = $.createLink('doc', 'moveTemplate', `docID=${docID}`);
        zui.Modal.open({size: 'sm', url: url});
    },
    addModule: function(_, args)
    {
        const docApp = getDocApp();
        const scope = args[0];
        const parentModule = args[1];
        const url = $.createLink('doc', 'addTemplateType', `scope=${scope}&parentModule=${parentModule}`);
        zui.Modal.open({size: 'sm', url: url});
    },
    editModule: function(_, args)
    {
        const docApp   = getDocApp();
        const moduleID = args[0] || docApp.moduleID;
        const url      = $.createLink('doc', 'editTemplateType', `moduleID=${moduleID}`);
        zui.Modal.open({size: 'sm', url: url});
    },
    deleteModule: function(_, args)
    {
        const docApp   = getDocApp();
        const moduleID = args[0] || docApp.moduleID;
        $.ajaxSubmit(
        {
            url: $.createLink('doc', 'deleteTemplateType', `moduleID=${moduleID}`),
            load: false,
            onSuccess: function()
            {
                getDocApp().delete('module', moduleID);
            }
        })
    },
    /**
     * 编辑文档模板。
     * Edit doc template.
     */
    editTemplate: function(_, args)
    {
        const docApp     = getDocApp();
        const templateID = args[0] || docApp.docID;
        const url = $.createLink('doc', 'editTemplate', `templateID=${templateID}`);
        zui.Modal.open({size: 'sm', url: url});
    },
    /**
     * 删除文档模板。
     * Delete doc template.
     */
    deleteTemplate: function(_, args)
    {
        const docApp = getDocApp();
        const lang   = getDocAppLang();
        const templateID = args[0] || docApp.docID;
        $.ajaxSubmit(
        {
            confirm: lang.confirmDelete,
            url: $.createLink('doc', 'deleteTemplate', `templateID=${templateID}`),
            load:false,
            onSunccess: function()
            {
                getDocApp().delete('doc', templateID);
            }
        })
    },
    showDocSettingModal: showDocSettingModal
});

window.clickTemplateCard = function(event, url)
{
    const target = $(event.target);
    if(target.hasClass('icon') || target.hasClass('dropdown') || target.hasClass('toolbar')) return;

    openUrl(url);
}
