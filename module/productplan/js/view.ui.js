window.unlinkObject = function(objectType, objectID)
{
    zui.Modal.confirm({message: confirmLang[objectType], icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({'url': unlinkURL[objectType].replace('%s', objectID)});
    });
};

window.renderStoryCell = function(result, info)
{
    const story = info.row.data;
    if(info.col.name == 'title' && result)
    {
        let html = '';
        if(story.parent) html += "<span class='label gray-pale rounded-xl'>" + childrenAB + "</span>";
        if(html) result.unshift({html});
    }
    return result;
};

window.ajaxConfirmLoad = function(obj)
{
    var $this   = $(obj);
    var action = $this.data('action');
    zui.Modal.confirm({message: confirmLang[action], icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.get($this.data('url'), function(){loadCurrentPage()});
    });
};

window.showLink = function(obj)
{
    var $this  = $(obj);
    var idName = $this.data('type') == 'story' ? '#stories' : '#bugs';
    $(idName).load($this.data('linkurl'));
};

$(document).off('click', '.batch-btn > a, .batch-btn').on('click', '.batch-btn > a, .batch-btn', function()
{
    const $this  = $(this);
    const type   = $this.data('type');
    const dtable = zui.DTable.query($('#' + type + 'DTable'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    checkedList.forEach((id) => postData.append(type + 'IdList[]', id));
    if($(this).data('account')) postData.append('assignedTo', $(this).data('account'));

    if($(this).data('page') == 'batch')
    {
        postAndLoadPage($(this).data('url'), postData);
    }
    else
    {
        $.ajaxSubmit({"url": $(this).data('url'), "data": postData});
    }
});

$(document).on('click', '.linkObjectBtn', function()
{
    const $this  = $(this);
    const type   = $this.data('type');
    const dtable = zui.DTable.query($this);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postKey  = type == 'story' ? 'stories' : 'bugs';
    const postData = new FormData();
    checkedList.forEach((id) => postData.append(postKey + '[]', id));

    $.ajaxSubmit({"url": $(this).data('url'), "data": postData});
});

if(initLink == 'true')
{
    var idName = type == 'story' ? '#stories' : '#bugs';
    window.showLink($(idName).find('.link'));
}
