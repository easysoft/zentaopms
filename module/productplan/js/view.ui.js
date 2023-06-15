window.unlinkObject = function(objectType, objectID)
{
    if(window.confirm(confirmLang[objectType])) $.get(unlinkURL[objectType].replace('%s', objectID), function(){loadPage(locateURL[objectType])});
}

window.ajaxConfirmLoad = function(obj)
{
    var $this   = $(obj);
    var action = $this.data('action');
    if(window.confirm(confirmLang[action])) $.get($this.data('url'), function(){loadPage(locateURL[type])});
}

$(document).on('click', '.batch-btn', function()
{
    const type   = $(this).data('type');
    const dtable = zui.DTable.query($('#' + type + 'DTable'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    checkedList.forEach((id) => postData.append(type + 'IdList[]', id));
    if($(this).data('account')) postData.append('assignedTo', $(this).data('account'));

    if($(this).data('page') == 'batch')
    {
        postAndLoadPage($(this).data('formaction'), postData);
    }
    else
    {
        $.ajaxSubmit({"url": $(this).data('formaction'), "data": postData, "callback": loadPage(locateURL[type])});
    }
});
