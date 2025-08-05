/**
 * 获取附件的操作按钮。
 * Get file actions.
 */
window.getDeliverableFileActions = function(file, deliverable)
{
    let actions = [];
    if(canDownload && typeof file.id != 'undefined') actions[0] = {icon: 'download', key: 'download', url: $.createLink('file', 'download', 'id=' + file.id), target: '_blank'};

    if(!onlyShow)
    {
        actions[1] = {icon: 'edit',  key: 'rename'};
        if(!isTemplate) actions[2] = {icon: 'trash', key: 'delete'};
    }

    /* 可以预览的文件。 */
    if(['txt', 'jpg', 'jpeg', 'gif', 'png', 'bmp', 'mp4'].includes(file.extension) && canDownload && typeof file.id != 'undefined')
    {
        actions[3] = {icon: 'eye', key: 'view', url: $.createLink('file', 'download', `fileID=${file.id}&mouse=left`), 'data-toggle' : 'modal', 'data-size' : 'lg'};
    }

    return actions;
}

/**
 * 获取文档的操作按钮。
 * Get file actions.
 */
window.getDocActions = function(doc, deliverable)
{
    let actions = [];
    const url   = $.createLink('doc', 'view', `docID=${doc.id}`);
    if(isTemplate)
    {
        if(doc.canView) actions[0] = {icon: 'eye',   key: 'view', url, target: '_blank'};
    }
    else
    {
        actions[0] = {icon: 'eye',   key: 'view', url};
        actions[1] = {icon: 'edit',  key: 'rename'};
        actions[2] = {icon: 'trash', key: 'delete'};
    }
    return actions;
}

/**
 * 获取交付物的操作按钮。
 * Get deliverable actions.
 */
window.getDeliverableActions = function(deliverable)
{
    let actions = [];
    actions[0] = {text: addFile, icon: 'file', key: 'selectFile'};
    if(!isTemplate && deliverable.category != otherLang && (canCreateDoc || canCreateDoc)) actions[1] = {text: createByTemplate, icon: 'plus', key: 'selectTemplate'};
    return actions;
}

/**
 * 获取从模板创建模版菜单。
 * Get template menu.
 */
window.getTemplateMenu = function(item, category)
{
    return {items: category.template || []};
}