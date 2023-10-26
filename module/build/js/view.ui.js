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

/**
 * 生成列表的排序链接。
 * Create sort link for table.
 *
 * @param  object col
 * @access public
 * @return string
 */
window.createSortLink = function(col)
{
    let tabType = $('.tab-pane.active').attr('id');
    let sort    = `${col.name}_asc`;

    if(sort == orderBy) sort = col.name + '_desc';
    return sortLink.replace('{type}', tabType).replace('{orderBy}', sort);
}

window.showLink = function(obj)
{
    var $this  = $(obj);
    var idName = $this.data('type') == 'story' ? '#story' : '#bug';
    $(idName).load($this.data('linkurl'));
};

$(document).on('click', '.linkObjectBtn', function()
{
    const $this       = $(this);
    const type        = $this.data('type');
    const dtable      = zui.DTable.query($this);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postKey  = type == 'story' ? 'stories' : 'bugs';
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

if(initLink == 'true')
{
    var idName = type == 'story' ? '#story' : '#bug';
    window.showLink($(idName).find('.link'));
}
