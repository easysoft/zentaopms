/**
 * 获取附件的操作按钮。
 * Get file actions.
 */
window.getFileActions = function(file, deliverable)
{
    let actions = [];
    actions[0] = {icon: 'download', key: 'download'};
    actions[1] = {icon: 'edit',     key: 'edit'};
    actions[2] = {icon: 'trash',    key: 'delete'};
    return actions;
}

/**
 * 获取文档的操作按钮。
 * Get file actions.
 */
window.getDocActions = function(doc, deliverable)
{
    let actions = [];
    actions[0] = {icon: 'eye',   key: 'view'};
    actions[1] = {icon: 'edit',  key: 'edit'};
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
    actions[0] = {text: addFile,          icon: 'file',     key: 'selectFile'};
    actions[1] = {text: downloadTemplate, icon: 'download', key: 'downloadTemplate'};
    return actions;
}
