window.unlinkObject = function(objectType, objectID)
{
    if(window.confirm(confirmLang[objectType])) $.get(unlinkURL[objectType].replace('%s', objectID), function(){loadPage(locateURL[objectType])});
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
    if(window.confirm(confirmLang[action])) $.get($this.data('url'), function(){loadPage(locateURL[type])});
};

window.showLink = function(obj)
{
    var $this  = $(obj);
    var idName = $this.data('type') == 'story' ? '#stories' : '#bugs';
    $(idName).load($this.data('linkurl'));
};

$(document).on('click', '.batch-btn', function()
{
    const $this  = $(this);
    const type   = $this.data('type');
    const dtable = zui.DTable.query($this);
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

    $.ajaxSubmit({"url": $(this).data('url'), "data": postData, "callback": loadPage(locateURL[type])});
});
