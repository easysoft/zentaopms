window.appendLinkBtn = function()
{
    $('.right-menu').find('.btn').eq(0).remove();

    const tabID = $('.tab-pane.active').attr('id');
    if(tabID == 'story')
    {
        $('.right-menu').append($('.link-story')[0].outerHTML);
    }
    else if(tabID == 'bug')
    {
        $('.right-menu').append($('.link-bug')[0].outerHTML);
    }
}

window.appendLinkBtn();

$(document).off('click','.dtable-footer .batch-btn').on('click', '.dtable-footer .batch-btn', function(e)
{
    const dtable = zui.DTable.query(e.target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const tabType  = $(this).data('type');
    const postData = [];
    postData[`${tabType}IdList[]`] = checkedList;

    $.ajaxSubmit({
        url:  $(this).data('url'),
        data: postData
    });
}).on('click', '.nav-tabs .nav-item a', function()
{
    if($(this).hasClass('active')) return;

    window.appendLinkBtn();
});

/**
 * 移除关联的对象。
 * Remove linked object.
 *
 * @param  sting objectType
 * @param  int   objectID
 * @access public
 * @return void
 */
window.unlinkObject = function(objectType, objectID)
{
    zui.Modal.confirm({message: eval(`confirmUnlink${objectType}`), icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: eval(`unlink${objectType}URL`).replace('%s', objectID)});
    });
}

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
