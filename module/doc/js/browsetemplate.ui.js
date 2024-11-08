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

    templateCols = [];
    templateCols.push(options.cols.find(col => col.name == 'id'));
    templateCols.push(options.cols.find(col => col.name == 'title'));
    templateCols.push({name: 'type', title: lang.tableCols.type, type: 'string', sort: true});
    templateCols.push(options.cols.find(col => col.name == 'addedBy'));
    templateCols.push(options.cols.find(col => col.name == 'addedDate'));
    templateCols.push(options.cols.find(col => col.name == 'editedBy'));
    templateCols.push(options.cols.find(col => col.name == 'editedDate'));
    templateCols.push({name: 'views', title: lang.tableCols.views, type: 'number', sort: true});
    templateCols.push(options.cols.find(col => col.name == 'actions'));

    options.cols = templateCols;
    options.cols.forEach(col =>
    {
        if(col.name === 'actions' && col.actionsMap)
        {
            col.actions = col.actions.filter(action => action !== 'move');
            delete col.actionsMap.move;

            const actionHints = {edit: lang.editTemplate, delete: lang.deleteTemplate};
            $.each(col.actionsMap, (key, value) =>
            {
                if(typeof actionHints[key] === 'string') value.hint = actionHints[key];
                if(key == 'edit')   value.command = 'editTemplate/{id}';
                if(key == 'delete') value.command = 'deleteTemplate/{id}';
            });

        }
    });

    return options;
}

const customRenders =
{
    /**
     * 定义 API 文档列表工具栏渲染。
     * Define the API doc list toolbar render.
     */
    toolbar: function()
    {
        if(this.mode === 'list')
        {
            const items = [];
            const {libID, filterType, docID, orderBy, pager, mode} = this.location;
            const {recTotal, recPerPage, pageID} = pager;
            const url = $.createLink('doc', 'browseTemplate', `libID=${libID}&filterType=${filterType}&docID=${docID}&orderBy=${orderBy}&recTotal=${recTotal}&recPerPage=${recPerPage}&pageID=${pageID}&mode=create`);
            items.push({text: getDocAppLang('createTemplate'), icon: 'plus', btnType: 'primary', url: url});
            return {component: 'toolbar', props: {items: items}};
        }
    }
}

/**
 * 定义文档模板各个视图和 UI 元素的上的操作方法。
 * Define the operation methods on the views and UI elements of the doc template.
 */
const actionsMap =
{
    /**
     * 定义分组的操作按钮。
     * Define the actions on the type group.
     */
    module: function(info)
    {
        const items  = [];
        const module = info.data;

        items.push({text: getDocAppLang('addModule'), command: `addModule/${module.lib}`});
        if(module.grade == 1) items.push({text: getDocAppLang('addSubModule'), command: `addSubModule/${module.id}`});
        items.push({text: getDocAppLang('editModule'), command: `editModule/${module.id}`});
        items.push({text: getDocAppLang('deleteModule'), command: `deleteModule/${module.id}`});

        if(info.ui === 'sidebar')
        {
            return [
                items.length ? {icon: 'ellipsis-v', caret: false, placement: 'bottom-end', size: 'xs', items: items} : null,
            ];
        }

        return items;
    },
};

function getActions(type, info)
{
    const builder = actionsMap[type];
    if(builder) return builder.call(this, info);
}

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
        getTableOptions : getTableOptions,
        getActions      : getActions,
        customRenders   : customRenders
    });
};

/* 扩展文档模板命令定义。 Extend the doc app command definition. */
$.extend(window.docAppCommands,
{
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
    saveNewDoc: function(_, args)
    {
        const docApp    = getDocApp();
        const spaceType = docApp.spaceType;
        const spaceID   = docApp.spaceID;
        const libID     = docApp.libID;
        const moduleID  = docApp.moduleID;
        const url       = $.createLink('doc', 'setDocBasic', `objectType=template&objectID=${spaceID}&libID=${libID}&moduleID=${moduleID}&docID=0&isDraft=no`);
        zui.Modal.open({url: url});
        return new Promise((resolve) => {window.docBasicModalResolver = resolve;});
    }
});
