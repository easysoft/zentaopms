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
}).off('click', '#taskModal button[type="submit"]').on('click', '#taskModal button[type="submit"]', function()
{
    const taskType = $('[name=type]').val();
    if(taskType.length == 0)
    {
        zui.Modal.alert(typeNotEmpty);
        return false;
    }

    if($('[name=hourPointValue]').length)
    {
        let hourPoint = $('[name=hourPointValue]').val();
        if(typeof(hourPoint) == 'undefined') hourPoint = 0;

        if(hourPoint == 0)
        {
            zui.Modal.alert(hourPointNotEmpty);
            return false;
        }

        if(typeof(hourPoint) != 'undefined' && (isNaN(hourPoint) || hourPoint < 0))
        {
            zui.Modal.alert(hourPointNotError);
            return false;
        }
    }

    const checkedIdList  = $('#storyIdList').val().split(',');
    let linkedTaskIdList = '';
    let unlinkTaskIdList = '';
    checkedIdList.forEach(function(storyID)
    {
        if(linkedTaskStories[storyID])
        {
            linkedTaskIdList += '[' + storyID +']';
        }
        else
        {
            unlinkTaskIdList += storyID + ',';
        }
    });

    if(linkedTaskIdList)
    {
        confirmStoryToTaskTip = confirmStoryToTask.replace('%s', linkedTaskIdList);

        if(confirm(confirmStoryToTaskTip))
        {
            console.log('ddd');
            $('#storyIdList').val(checkedIdList);
        }
        else
        {
            if(!unlinkTaskIdList)
            {
                loadCurrentPage();
                return false;
            }

            $('#storyIdList').val(unlinkTaskIdList);
        }
    }
    console.log('ccc');

    zui.Modal.hide('#taskModal');
    console.log('eee');

    const formData = new FormData($("#toTaskForm")[0]);
    console.log(formData);
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
    if(!checkedTotal) return {html: summary};

    $('#storyIdList').val(checkedIdList.join(','));

    let checkedEstimate = 0;
    let checkedCase     = 0;
    let SRTotal         = 0;
    let total           = 0;

    let rateCount = 0;
    checkedIdList.forEach((rowID) => {
        const story = element.getRowInfo(rowID);
        if(storyType == 'requirement' && story.data.type == 'story') SRTotal += 1;
        if(storyType == story.data.type) total += 1;
        if(story)
        {
            if(cases[rowID]) checkedCase += 1;
            if(story.data.isParent == '0')
            {
                checkedEstimate += parseFloat(story.data.estimate);
                rateCount += 1;
            }
        }
    })

    const rate = rateCount ? Math.round(checkedCase / rateCount * 10000) / 100 + '' + '%' : 0 + '%';
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
        let gradeLabel = gradeGroup[story.type][story.grade];
        if(typeof modulePairs[story.moduleID] != 'undefined') html += "<span class='label gray-pale rounded-xl clip'>" + modulePairs[story.moduleID] + "</span> ";
        if(gradeLabel) html += "<span class='label gray-pale rounded-xl'>" + gradeLabel + "</span>";
        if(html) result.unshift({html});
    }
    if(info.col.name == 'status' && result)
    {
        if(info.row.data.URChanged == '1') result[0] = {html: "<span class='status-changed'>" + URChanged + "</span>"};
    }
    return result;
};

window.setShowGrades = function()
{
    const showGrades = $('[name^=showGrades]').zui('picker').$.state.value;
    if(oldShowGrades == showGrades) return;

    const link = $.createLink('product', 'ajaxSetShowGrades', 'module=execution&showGrades=' + showGrades);
    $.get(link, function() { loadCurrentPage(); });
}

window.firstRendered = false;
window.toggleCheckRows = function(idList)
{
    if(!idList?.length || firstRendered) return;
    firstRendered = true;
    const dtable = zui.DTable.query($('#table-execution-story'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}
