/**
 * 获取附件的操作按钮。
 * Get file actions.
 */
window.getFileActions = function(file, deliverable)
{
    let actions = [];
    actions[0] = {icon: 'edit',  key: 'rename'};
    actions[1] = {icon: 'trash', key: 'delete'};
    return actions;
}

/**
 * 获取文档的操作按钮。
 * Get file actions.
 */
window.getDocActions = function(doc, deliverable)
{
    let actions = [];
    actions[0] = {icon: 'eye',   key: 'view', url: $.createLink('doc', 'view', 'docID=' + doc.id)};
    actions[1] = {icon: 'edit',  key: 'rename'};
    actions[2] = {icon: 'trash', key: 'delete'};
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
    if(deliverable.template > 0) actions[1] = {text: downloadTemplate, icon: 'download', key: 'downloadTemplate'};
    return actions;
}
