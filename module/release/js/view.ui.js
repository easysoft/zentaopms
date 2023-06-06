$(document).off('click','.dtable-footer .batch-btn').on('click', '.dtable-footer .batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = [];
    postData[`${type}IdList[]`] = checkedList;

    $.ajaxSubmit({
        url:  $(this).data('url'),
        data: postData
    });
});

/**
 * 计算表格任务信息的统计。
 * set task summary for table footer.
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
 * @access public
 * @return string
 */
window.createSortLink = function(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';
    return sortLink.replace('{orderBy}', sort);
}

/**
 * 移除关联的需求。
 * Remove linked story.
 *
 * @param  int    storyID
 * @access public
 * @return void
 */
window.unlinkStory = function(storyID)
{
    if(window.confirm(confirmUnlinkStory))
    {
        $.ajaxSubmit({url: unlinkStoryUrl.replace('%s', storyID)});
    }
}
