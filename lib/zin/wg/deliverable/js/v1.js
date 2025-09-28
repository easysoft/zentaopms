/**
 * 获取附件的操作按钮。
 * Get file actions.
 */
window.getDeliverableFileActions = function(file, deliverable)
{
    let actions = [];
    /* 可以预览的文件。 */
    if(['txt', 'jpg', 'jpeg', 'gif', 'png', 'bmp', 'mp4'].includes(file.extension) && canDownload && typeof file.id != 'undefined')
    {
        actions[0] = {icon: 'eye', key: 'view', url: $.createLink('file', 'download', `fileID=${file.id}&mouse=left`), 'data-toggle' : 'modal', 'data-size' : 'lg'};
    }

    if(canDownload && typeof file.id != 'undefined') actions[1] = {icon: 'download', key: 'download', url: $.createLink('file', 'download', 'id=' + file.id), target: '_blank'};

    if(!onlyShow)
    {
        actions[2] = {icon: 'edit',  key: 'rename'};
        if(!isTemplate) actions[3] = {icon: 'trash', key: 'delete'};
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
        actions[0] = {icon: 'eye',   key: 'view', url, target: '_blank'};
        if(doc.editable) actions[1] = {icon: 'edit',  key: 'rename'};
        actions[2] = {icon: 'trash', key: 'delete'};
    }
    return actions;
}

/**
 * 获取交付物的操作按钮。
 * Get deliverable actions.
 */
window.getDeliverableActions = function(deliverable, category)
{
    let actions = [];
    if(isTemplate)
    {
        actions[0] = {text: addFile, icon: 'file', key: 'selectFile'}; // 后台交付物只有上传文件操作。
    }
    else
    {
        if(canCreateDoc) actions[0] = {text: createDoc, icon: 'doc', key: 'createDoc', 'onClick' : () => showBtnModal(deliverable, category)}; // 前台交付物有三个创建文档的操作。
    }

    return actions;
}

window.showBtnModal = function(deliverable, category)
{
    const actions = [];

    actions.push(`<div class="flex flex-row gap-4 justify-center items-center h-20">`);
    actions.push(`<a class="btn btn-sm btn-primary w-28" target='_blank' href="${createDocUrl}"><i class="icon icon-doc"></i>${createDoc}</a>`);
    actions.push(`<a class="btn btn-sm btn-primary w-28" data-toggle="modal" href="${uploadDocUrl}"><i class="icon icon-file"></i>${uploadFile}</a>`);

    if(typeof category.template != 'undefined' && category.template.length > 0)
    {
        actions.push(`<button class="btn btn-sm btn-primary w-28" data-toggle="dropdown"><i class="icon icon-plus"></i>${createByTemplate} <span class="caret"></span></button>`);
        actions.push(`<menu class="dropdown-menu menu">`);
        for(const template of category.template)
        {
            actions.push(`<li class="menu-item"><a href="${template.url}" target="_blank">${template.text}</a></li>`);
        }
        actions.push(`</menu>`);
    }

    actions.push(`</div>`);

    zui.Modal.open({
        id: 'createDoc',
        title: createDoc,
        type: 'custom',
        size: 'sm',
        content: {html: actions.join('')},
    });
}