window.waitDom('.uploadFileBox .file-selector-list', function(){ addFileUploadMutationObserver();})

function addFileUploadMutationObserver()
{
    let fileUpload = document.querySelector('.uploadFileBox .file-selector-list');
    observer = new MutationObserver(function(mutationsList, observer)
    {
        let title  = $('.uploadFileBox .file-selector-list .file-selector-item:nth-child(1) .item-title').text();
        let dotIdx = title.lastIndexOf('.');
        if(dotIdx != '-1') title = title.substring(0, dotIdx);
        if(docID == 0)
        {
            $('.titleBox [name=title]').val(title);
            $('.uploadFormatBox').toggleClass('hidden', $('.uploadFileBox .file-selector-list .file-selector-item').length <= 1);
        }
    });

    observer.observe(fileUpload, {childList: true, subtree: true});
}

window.loadExecutions = function(e)
{
    const $projectElement   = $('.projectBox input[name="project"]');
    const $executionElement = $('.executionBox input[name="execution"]');
    const projectID         = $projectElement.val();
    if($executionElement)
    {
        const executionID = $executionElement.val();
        const link        = $.createLink('project', 'ajaxGetExecutions', "projectID=" + projectID + "&mode=multiple,leaf,noprefix");
        $.getJSON(link, function(data)
        {
            let $picker = $executionElement.zui('picker');
            $picker.render({items: data.items, disabled: !data.multiple});
            $picker.$.setValue(executionID);
        });
    }

    const link = $.createLink('doc', 'ajaxGetModules', 'objectType=project&objectID=' + projectID + '&type=doc');
    $.getJSON(link, function(data)
    {
        const $libPicker = $("[name='lib']").zui('picker');
        $libPicker.render({items: data.libs});
        $libPicker.$.setValue('');

        const $modulePicker = $("[name='parent']").zui('picker');
        $modulePicker.render({items: data.modules});
        $modulePicker.$.setValue('');
    });
}

window.openEditURL = function(docID, fileID)
{
    let editUrl = $.createLink('file', 'download', "fileID=" + fileID + "&mouse=left");
    window.open(editUrl);
    loadPage($.createLink('doc', 'view', "docID=" + docID));
}

window.toggleDocTitle = function()
{
    const uploadFormat = $('.uploadFormatBox input[name=uploadFormat]:checked').val();
    $('.titleBox').toggleClass('hidden', uploadFormat != 'combinedDocs');
}

window.titleChanged = function()
{
    if(observer) observer.disconnect();
}
