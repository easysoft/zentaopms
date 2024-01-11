$(document).off('click','.dtable-footer .batch-btn').on('click', '.dtable-footer .batch-btn', function(e)
{
    const dtable = zui.DTable.query(e.target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const tabType  = $(this).data('type');
    const form = new FormData();
    checkedList.forEach((id) => form.append(`${tabType}IdList[]`, id));

    const url = $(this).data('url')

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({
            url:  url,
            data: form
        });
    }
    else
    {
        postAndLoadPage(url, form);
    }
}).on('click', '.nav-tabs .nav-item a', function()
{
    if($(this).hasClass('active')) return;

    window.appendLinkBtn();
});

window.showLink = function(type, params, onlyUpdateTable)
{
    const url = $.createLink(buildModule, type === 'linkStory' ? 'linkStory' : 'linkBug', 'buildID=' + buildID + (params || '&browseType=&param='));
    if(onlyUpdateTable)
    {
        loadComponent($('#' + type).find('.dtable').attr('id'), {url: url, component: 'dtable', partial: true});
        return;
    }
    loadTarget({url: url, target: type});
};

window.onSearchLinks = function(type, result)
{
    const params = $.parseLink(result.load).vars[3];
    showLink(type, params ? atob(params[1]) : null, true);
    return false;
};

$(document).off('click', '.linkObjectBtn').on('click', '.linkObjectBtn', function()
{
    const $this       = $(this);
    const type        = $this.data('type');
    const dtable      = zui.DTable.query($this);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postKey  = type == 'linkStory' ? 'stories' : 'bugs';
    const postData = new FormData();
    checkedList.forEach(function(id)
    {
        postData.append(postKey + '[]', id)
        if(type == 'bug')
        {
            const formData = dtable.$.getFormData();
            let resolvedBy = formData['resolvedByControl[' +  id + ']'];
            if(typeof resolvedBy == 'undefined') resolvedBy = currentAccount;
            if(resolvedBy) postData.append('resolvedBy[' + id + ']', resolvedBy);
        }
    });

    $.ajaxSubmit({"url": $(this).data('url'), "data": postData});
});

$(function()
{
    if(initLink == 'true') window.showLink(type, linkParams);
});
