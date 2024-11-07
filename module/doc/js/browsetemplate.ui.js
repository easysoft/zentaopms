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

    options.cols = options.cols.filter(col => col.name !== 'collects');

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
        getTableOptions : getTableOptions
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
    }
});
