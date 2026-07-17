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
        actions[3] = {icon: 'trash', key: 'remove', hint: deleteItem};
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
        if(!deliverable?.hiddenDelete) actions[2] = {icon: 'trash', key: 'remove'};
    }
    else
    {
        actions[0] = {icon: 'eye',   key: 'view', url, target: '_blank'};

        if(doc.editable) actions[1] = {icon: 'edit',  key: 'rename'};

        if(deliverable?.status == undefined || (deliverable?.status == 'draft' && deliverable?.review == 0)) actions[2] = {icon: 'trash', key: 'remove', hint: deleteItem};
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
        if(category.systemList != 1 && canCreateDoc) actions[0] = {text: createDoc, icon: 'doc', key: 'createDoc', 'onClick' : (event) => showBtnModal(deliverable, category, $(event.target).closest('.deliverable-item').z('gid'))}; // 前台交付物有三个创建文档的操作。
        if(category.systemList == 1)
        {
            if(!category.hasData) actions[0] = {icon: 'plus', key: 'submitBtn', url: category.editLink, target: '_blank'};
            if(category.hasData)  actions[0] = {icon: 'eye', key: 'submitBtn', url: $.createLink('project', 'viewDeliverable', 'id=' + deliverable.itemID), target: '_blank'};
            actions[1] = {icon: 'refresh', key: 'refreshBtn', 'onClick' : (event) => refreshDeliverable(event, deliverable)};
        }
    }

    return actions;
}

window.refreshDeliverable = function(event, deliverable)
{
    $.getJSON($.createLink('project', 'ajaxCheckDataAndGetLink', 'deliverableID=' + deliverable.id + '&projectID=' + deliverable.project), function(result)
    {
        $('#submitTip-' + deliverable.id).text(result.hasData ? submittedLang : notSubmitLang);

        const link = result.hasData ? $.createLink('project', 'viewDeliverable', 'id=' + deliverable.itemID) : result.editLink;
        const icon = result.hasData ? '<i class="icon icon-eye"></i>' : '<i class="icon icon-plus"></i>';
        $(event.target).closest('nav.toolbar').find('a[z-key="submitBtn"]').attr('href', link).html(icon);
    });
}

window.showBtnModal = function(deliverable, category, gid)
{
    zui.store.session.set('creating-deliverable-doc-target', gid);

    const actions  = [];
    const btnClass = config.clientLang == 'en' ? 'btn btn-sm btn-primary w-36' : 'btn btn-sm btn-primary w-28';

    actions.push(`<div class="flex flex-row gap-3 justify-center items-center h-20">`);
    actions.push(`<a class="${btnClass}" target='_blank' href="${createDocUrl}" onclick="closeModal()"><i class="icon icon-doc"></i>${createDoc}</a>`);
    actions.push(`<a class="${btnClass}" data-toggle="modal" data-dismiss="modal" href="${uploadDocUrl}"><i class="icon icon-file"></i>${uploadFile}</a>`);
    if(typeof createLinkDocUrl !== 'undefined' && createLinkDocUrl) actions.push(`<a class="${btnClass}" data-toggle='modal' data-dismiss="modal" href="${createLinkDocUrl}"><i class="icon icon-link"></i>${createLinkDoc}</a>`);

    if(typeof category.template != 'undefined' && category.template.length > 0)
    {
        actions.push(`<button class="${btnClass}" data-toggle="dropdown"><i class="icon icon-plus"></i>${createByTemplate} <span class="caret"></span></button>`);
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

window.closeModal = function()
{
    zui.Modal.hide('#createDoc');
}

window.selectNewDoc = function(newDoc)
{
    const gid = zui.store.session.get('creating-deliverable-doc-target');
    const deliverableList = zui.DeliverableList.query().$;

    deliverableList.selectDoc(gid, {
        id: newDoc.id.toString(),
        title: newDoc.title,
        version: newDoc.version
    });
}

window.handleRenderDeliverableItem = function(item, category)
{
    if(category.systemList == 1 && category.hasData)  return {html: '<div id="submitTip-' + category.id + '">' + submittedLang + '</div>'};
    if(category.systemList == 1 && !category.hasData) return {html: '<div id="submitTip-' + category.id + '">' + notSubmitLang + '</div>'};
}
