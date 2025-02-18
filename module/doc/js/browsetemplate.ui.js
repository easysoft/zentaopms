const savingDocData = {};
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
    let viewsCol   = {name: 'views', title: lang.tableCols.views, type: 'number', sort: true};

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
    templateCols.push(actionsCol);

    options.cols = templateCols;
    options.cols.forEach(col =>
    {
        if(col.name === 'actions' && col.actionsMap)
        {
            col.actions = col.actions.filter(action => action !== 'move');
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

    options.footer = options.footer.filter(f => f !== 'checkbox');

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

function showDocBasicModal(docID, isDraft = false)
{
    const docApp    = getDocApp();
    const spaceType = docApp.spaceType;
    const spaceID   = docApp.spaceID;
    const libID     = docApp.libID;
    const moduleID  = docApp.moduleID;
    isDraft = isDraft ? 'yes' : 'no';
    const url = $.createLink('doc', 'setDocBasic', `objectType=template&objectID=${spaceID}&libID=${libID}&moduleID=${moduleID}&docID=${docID}&isDraft=${isDraft}`);
    zui.Modal.open({url: url});
    return new Promise((resolve) => {window.docBasicModalResolver = resolve;});
}

function showDocSettingModal(_, args)
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
            $.post($.createLink('doc', 'editTemplate', `docID=${docID}`), docData);
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
function submitNewDoc(doc, spaceID, libID, moduleID, formData)
{
    const docApp    = getDocApp();
    const spaceType = docApp.signals.spaceType.value;
    const module    = docApp.treeMap.modules.get(moduleID);
    const url       = $.createLink('doc', 'createTemplate', `libID=${libID}&moduleID=${moduleID}`);
    const docData   =
    {
        content     : doc.content,
        status      : doc.status || 'normal',
        contentType : doc.contentType,
        type        : 'text',
        lib         : libID,
        module      : moduleID,
        title       : doc.title,
        keywords    : doc.keywords,
        acl         : 'private',
        space       : spaceType,
        project     : 0,
        templateType: module.data.short,
        uid         : (doc.uid || `doc${doc.id}`),
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
                if(typeof data !== 'object' || data.result === 'fail')
                {
                    throw new Error(getErrorMessage(data));
                }
                resolve($.extend(doc, {id: data.id}, docData, data.doc, {status: doc.status || data.status}));
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
                docApp.update('doc', $.extend({}, doc, docData, data.doc));
                resolve(true);
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

const customRenders =
{
    home: function()
    {
        const homeViewUrl = $.createLink('doc', 'browseTemplate', `libID=0&filterType=all&docID=0&orderBy=&recTotal=&recPerPage=20&pageID=1&mode=home`);
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

        /* 获取侧边栏没有模块时的操作按钮。 Get actions when sidebar no module. */
        if(info.ui === 'sidebar-no-module')
        {
            if(!canAddModule) return;
            return [{text: lang.actions.addModule, command: `addModule/${lib.id}/0/${lib.id}/child`, icon: 'plus', type: 'primary-pale'}];
        }

        if(docAppHasPriv('addModule')) items.push({text: lang.actions.addModule, command: `addModule/${lib.id}/0/${lib.id}/child`});
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

        const lang = getLang();
        return [
            {text: lang.saveDraft, size: 'md', className: 'btn-wide', type: 'secondary', command: 'saveDoc/draft'},
            {text: lang.release, size: 'md', className: 'btn-wide', type: 'primary', command: 'saveDoc'},
            {text: lang.cancel, size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelEditDoc'},
            {text: lang.settings, size: 'md', type: 'ghost', command: `showDocSettingModal/${doc.id}/template/1`, icon: 'cog-outline'},
        ];
    }
});

/**
 * 重写文档应用的配置选项方法。
 * Override the method to set the doc app options.
 */
window._setDocAppOptions = window.setDocAppOptions; // Save the original method.
window.setDocAppOptions = function(_, options) // Override the method.
{
    options = window._setDocAppOptions(_, options);
    return $.extend(options,
    {
        onCreateDoc     : handleCreateDoc,
        onSaveDoc       : handleSaveDoc,
        getTableOptions : getTableOptions,
        customRenders   : $.extend(docAppCustomRenders, customRenders)
    });
};

/* 扩展文档模板命令定义。 Extend the doc app command definition. */
$.extend(window.docAppCommands,
{
    startCreateTemplate: function()
    {
        const docApp = getDocApp();
        docApp.startCreateDoc();
    },
    deleteDoc: function(_, args)
    {
        const docApp  = getDocApp();
        const docID   = args[0] || docApp.docID;

        $.ajaxSubmit(
        {
            confirm: getLang('confirmDelete'),
            url:     $.createLink('doc', 'deleteTemplate', `docID=${docID}`),
            load:    false,
            onSuccess: function()
            {
                getDocApp().delete('doc', docID);
            }
        });
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
