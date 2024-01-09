$(document).off('click','.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('storyIdList[]', id));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data: form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
}).on('click', '#taskModal button[type="submit"]', function()
{
    const formData = new FormData($("#toTaskForm")[0]);
    postAndLoadPage($('#toTaskForm').attr('action'), formData);

    return false;
});

$(document).off('click', '#linkStoryByPlan button[type="submit"]').on('click', '#linkStoryByPlan button[type="submit"]', function()
{
    var planID = $('[name=plan]').val();
    if(planID)
    {
        $.ajaxSubmit({url: $.createLink('execution', 'importPlanStories', 'executionID=' + executionID + '&planID=' + planID)});
    }

    return false;
})

/**
 * 计算表格信息的统计。
 * Set summary for table footer.
 *
 * @param  element element
 * @param  array   checkedIdList
 * @access public
 * @return object
 */
window.setStatistics = function(element, checkedIdList)
{
    const checkedTotal = checkedIdList.length;
    if(checkedTotal == 0) return {html: summary};

    $('#storyIdList').val(checkedIdList.join(','));

    let checkedEstimate = 0;
    let checkedCase     = 0;

    checkedIdList.forEach((rowID) => {
        const task = element.getRowInfo(rowID);
        if(task)
        {
            checkedEstimate += parseFloat(task.data.estimate);
            if(cases[rowID]) checkedCase += 1;
        }
    })

    const rate = Math.round(checkedCase / checkedTotal * 10000) / 100 + '' + '%';
    return {
        html: checkedSummary.replace('%total%', checkedTotal)
            .replace('%estimate%', checkedEstimate)
            .replace('%rate%', rate)
    };
}

window.renderStoryCell = function(result, info)
{
    const story = info.row.data;
    if(info.col.name == 'title' && result)
    {
        let html = '';
        if(typeof modulePairs[story.moduleID] != 'undefined') html += "<span class='label gray-pale rounded-xl clip'>" + modulePairs[story.moduleID] + "</span> ";
        if(story.isChild) html += "<span class='label gray-pale rounded-xl'>" + childrenAB + "</span>";
        if(html) result.unshift({html});
    }
    return result;
};
