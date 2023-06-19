window.appendLinkBtn = function()
{
    $('.right-menu').find('.btn').eq(1).remove();

    const tabID   = $('.tab-pane.active').attr('id');
    if(tabID == 'finishedStory')
    {
        $('.right-menu').append($('.link-story')[0].outerHTML);
    }
    else if(tabID == 'resolvedBug')
    {
        $('.right-menu').append($('.link-bug')[0].outerHTML);
    }
    else if(tabID == 'leftBug')
    {
        $('.right-menu').append($('.link-left-bug')[0].outerHTML);
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
    window.appendLinkBtn();
}).on('click', '.linkObjectBtn', function()
{
    const type   = $(this).data('type');
    const dtable = zui.DTable.query($(this));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postKey  = type == 'story' ? 'stories' : 'bugs';
    const postData = new FormData();
    checkedList.forEach((id) => postData.append(postKey + '[]', id));

    $.ajaxSubmit({"url": $(this).data('url'), "data": postData, "callback": loadPage($.createLink('release', 'view', `releaseID=${releaseID}&type=${type}`))});
}).on('click', '.link-story', function()
{
    $(this).hide();
    $('#finishedStory').load($(this).data('url'));
}).on('click', '.link-bug', function()
{
    $(this).hide();
    $('#resolvedBug').load($(this).data('url'));
}).on('click', '.link-left-bug', function()
{
    $(this).hide();
    $('#leftBug').load($(this).data('url'));
});

/**
 * 计算表格任务信息的统计。
 * Set task summary for table footer.
 *
 * @param  element element
 * @param  array   checkedidlist
 * @access public
 * @return object
 */
window.setStoryStatistics = function(element, checkedIDList)
{
    const checkedTotal = checkedIDList.length;
    if(checkedTotal == 0) return {html: summary};

    let checkedEstimate = 0;
    let checkedCase     = 0;
    let rateCount       = checkedTotal;

    const rows = element.layout.allRows;
    rows.forEach((row) => {
        if(checkedIDList.includes(row.id))
        {
            const story = row.data;
            const cases = storyCases[row.id];
            checkedEstimate += story.estimate;

            if(cases > 0)
            {
                checkedCase ++;
            }
            else if(story.children != undefined && story.children > 0)
            {
                rateCount --;
            }
        }
    })

    let rate = '0%';
    if(rateCount) rate = Math.round(checkedCase / rateCount * 100) + '%';

    return {
        html: checkedSummary.replace('%total%', checkedTotal).replace('%estimate%', checkedEstimate.toFixed(1)).replace('%rate%', rate)
    };
}

/**
 * 生成列表的排序链接。
 * Create sort link for table.
 *
 * @param  object col
 * @param  string tabType
 * @access public
 * @return string
 */
window.createSortLink = function(col, sortType)
{
    const tabID   = $('.tab-pane.active').attr('id');
    let   tabType = '';
    switch(tabID)
    {
        case 'resolvedBug':
            tabType = 'bug';
            break;
        case 'leftBug':
            tabType = 'leftBug';
            break;
        default:
            tabType = 'story';
            break;
    }

    let sort = `${col.name}_asc`;
    if(sort == orderBy) sort = col.name + '_desc';
    return sortLink.replace('{type}', tabType).replace('{orderBy}', sort);
}

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
    objectType = objectType.toLowerCase();

    if(window.confirm(eval(`confirmunlink${objectType}`)))
    {
        $.ajaxSubmit({url: eval(`unlink${objectType}url`).replace('%s', objectID)});
    }
}
