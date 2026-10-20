/**
 * 获取附件的操作按钮。
 * Get file actions.
 */
window.getDeliverableFileActions = function(file, deliverable, category)
{
    let actions = [];
    /* 可以预览的文件。 */
    if(['txt', 'jpg', 'jpeg', 'gif', 'png', 'bmp', 'mp4'].includes(file.extension) && canPreview && typeof file.id != 'undefined')
    {
        actions[0] = {icon: 'eye', key: 'view', url: $.createLink('file', 'preview', `fileID=${file.id}&mouse=left`), 'data-toggle' : 'modal', 'data-size' : 'lg'};
    }

    if(canDownload && typeof file.id != 'undefined') actions[1] = {icon: 'download', key: 'download', url: $.createLink('file', 'download', 'id=' + file.id), target: '_blank'};

    if(!onlyShow)
    {
        actions[2] = {icon: 'edit',  key: 'rename'};
        actions[3] = {icon: 'trash', key: 'remove', hint: deleteItem};
    }

    if(!isTemplate && typeof deliverableCheckList !== 'undefined' && category && category.systemList != 1) actions.push({icon: 'refresh', key: 'refreshBtn', size: 'sm', class: 'ghost', 'onClick': (event) => refreshDeliverable(event, deliverable, category)});

    return actions;
}

/**
 * 获取文档的操作按钮。
 * Get file actions.
 */
window.getDocActions = function(doc, deliverable, category)
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

        const readonly = !closeTask && (deliverable?.sourceType == 'task' || (deliverable?.sourceType == 'execution' && closeProject));

        if(!readonly && doc.editable) actions[1] = {icon: 'edit',  key: 'rename'};

        if(!readonly && (deliverable?.status == undefined || (deliverable?.status == 'draft' && deliverable?.review == 0))) actions[2] = {icon: 'trash', key: 'remove', hint: deleteItem};

        if(!isTemplate && typeof deliverableCheckList !== 'undefined' && category && category.systemList != 1) actions.push({icon: 'refresh', key: 'refreshBtn', size: 'sm', class: 'ghost', 'onClick': (event) => refreshDeliverable(event, deliverable, category)});
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
            if(!category.hasData) actions[0] = {icon: 'plus', key: 'submitBtn', size: 'sm', class: 'ghost', url: category.editLink, target: '_blank'};
            if(category.hasData)  actions[0] = {icon: 'eye',  key: 'submitBtn', size: 'sm', class: 'ghost', url: $.createLink('project', 'viewDeliverable', 'id=' + deliverable.itemID), target: '_blank'};
            actions[1] = {icon: 'refresh', key: 'refreshBtn', size: 'sm', class: 'ghost', 'onClick' : (event) => refreshDeliverable(event, deliverable, category)};
        }
    }

    return actions;
}

window.refreshDeliverable = function(event, deliverable, category)
{
    const deliverableID = deliverable.itemID;
    const projectID     = deliverable.project;

    /* 更新普通交付物的行级对错勾，判定与关闭判断一致。*/
    const updateCheckIcon = function(passed, tip = deliverableCheckList[deliverable.id]?.tip || '')
    {
        const html = '<span class="deliverable-check-icon"' + (tip ? ' title="' + tip + '"' : '') + '>'
                   + '<i class="icon ' + (passed ? 'icon-check text-success' : 'icon-close text-danger') + '"></i></span>';

        const $row = $('.deliverable-item[z-gid="' + deliverable._gid + '"]').find('.deliverable-item-row').first();
        if(!$row.length) return;

        const $icon = $row.children('.deliverable-check-icon');
        if($icon.length) $icon.replaceWith(html);
        else $row.prepend(html);
    };

    /* 未保存到项目交付物表的文档刷新获取最新的标题。*/
    const refreshDoc = function(doc)
    {
        if(!doc?.id) return;

        $.getJSON($.createLink('project', 'ajaxCheckDataAndGetLink', 'deliverableID=0&projectID=0&stage=&docID=' + doc.id), function(result)
        {
            if(result.result != 'success' || !result.doc) return;

            const list = zui.DeliverableList.query()?.$;
            if(!list) return;

            list.selectDoc(deliverable._gid, Object.assign({}, doc, {title: result.doc.title, version: result.doc.version}));
        });
    };

    /* 普通交付物创建文档后还没有保存到项目交付物表，服务端没有可查记录，按当前状态判定：已选文档/附件即已提交，required==2 尚未评审则不通过。 */
    if(category.systemList != 1 && (!deliverableID || !projectID))
    {
        if(!deliverable?.doc && !deliverable?.file) return;
        updateCheckIcon(category.required != 2);
        refreshDoc(deliverable.doc);
        return;
    }

    if(!deliverableID || !projectID) return;

    $.getJSON($.createLink('project', 'ajaxCheckDataAndGetLink', 'deliverableID=' + deliverableID + '&projectID=' + projectID + '&stage=' + category.stageType), function(result)
    {
        /* 普通交付物：刷新行级对错勾，判定与关闭判断一致。*/
        if(category.systemList != 1)
        {
            if(result.result != 'success') return;

            /* required 取服务端最新值，避免后台改了检查要求后刷新不到。*/
            updateCheckIcon(result.required != 2 || (result.submitted && result.passed), result.tip);

            /* 同步刷新行内文档标题与版本*/
            if(result.doc)
            {
                const list = zui.DeliverableList.query().$;
                list.selectDoc(deliverable._gid, Object.assign({}, deliverable.doc, {title: result.doc.title, version: result.doc.version}));
            }
            return;
        }

        $('#submitTip-' + deliverable.id).text(result.hasData ? submittedLang : notSubmitLang);

        /* 同步刷新内置交付物*/
        const checkIcon = result.hasData ? '<i class="icon ' + (result.passed ? 'icon-check text-success' : 'icon-close text-danger') + '"></i>' : '';
        $('.deliverable-item[z-gid="' + deliverable._gid + '"] .deliverable-submit-status .deliverable-check-icon').html(checkIcon);

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
    const check = (typeof deliverableCheckList !== 'undefined') ? deliverableCheckList[category.id] : null;
    const tip   = check ? check.tip : '';
    const buildIcon = function(passed)
    {
        return '<span class="deliverable-check-icon"' + (tip ? ' title="' + tip + '"' : '') + '>'
             + '<i class="icon ' + (passed ? 'icon-check text-success' : 'icon-close text-danger') + '"></i></span>';
    };

    /* 来源类型为任务/执行时，生成来源标签。 */
    const buildSourceLabel = function(item)
    {
        if(!item.sourceID) return '';

        const sourceMap = {
            task:      {label: sourceTaskLabel, canView: canViewTask,      show: !closeTask,   url: $.createLink('task',      'view', 'taskID=' + item.sourceID)},
            execution: {label: sourceExecLabel, canView: canViewExecution, show: closeProject, url: $.createLink('execution', 'task', 'executionID=' + item.sourceID)}
        };
        const source = sourceMap[item.sourceType];
        if(!source || !source.show) return '';

        const cls = 'label size-sm gray-pale rounded-full ml-1 deliverable-source-label';
        if(source.canView) return '<a class="' + cls + '" target="_blank" href="' + source.url + '">' + source.label + '</a>';
        return '<span class="' + cls + '">' + source.label + '</span>';
    };

    if(category.systemList == 1)
    {
        const icon       = (check && check.submitted) ? buildIcon(check.passed) : '<span class="deliverable-check-icon"></span>';
        const submitText = category.hasData ? submittedLang : notSubmitLang;
        return {html: '<div class="deliverable-submit-status flex items-center">' + icon + '<div class="submitTip" id="submitTip-' + category.id + '">' + submitText + '</div></div>'};
    }

    const sourceLabel = buildSourceLabel(item);

    /* 逐条判断评审状态（item.checkPassed），仅对已提交（有文档或附件）的条目注入*/
    if(item.file || item.doc)
    {
        const passed = (typeof item.checkPassed !== 'undefined' && item.itemID) ? item.checkPassed : (category.required != 2);
        const icon   = buildIcon(passed);
        setTimeout(function()
        {
            const $row = $('.deliverable-item[z-gid="' + item._gid + '"]').find('.deliverable-item-row').first();
            if(!$row.length) return;
            if(!$row.children('.deliverable-check-icon').length) $row.prepend(icon);

            /* 在交付物名称和版本后面追加来源任务标签。 */
            if(sourceLabel)
            {
                const $version = $row.find('.deliverable-item-doc > span.muted, .deliverable-item-file > span.muted');
                if($version.length && !$version.next('.deliverable-source-label').length) $version.after(sourceLabel);
            }
        });
    }
}
