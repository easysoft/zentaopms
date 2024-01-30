let batchIgnoreTips = [];

/**
 * 更新可用工作日天数。
 * Update work days.
 *
 * @access public
 * @return void
 */
function batchComputeWorkDays()
{
    const $tr = $(this).closest('tr');
    if($tr.find('div[data-longtime="1"]').length > 0) return false;

    const beginDate = $tr.find('[name^=begin]').val();
    const endDate   = $tr.find('[name^=end]').val();
    $tr.find('[name^=days]').val(computeDaysDelta(beginDate, endDate));
}

/**
 * 检查项目起止日期是否超出父项目集起止日期。
 * Check whether the start and end dates of the project exceed the start and end dates of the parent program.
 *
 * @access public
 * @return void
 */
function batchCheckDate()
{
    const $tr   = $(this).closest('tr');
    const index = $tr.data('index');
    if(batchIgnoreTips[index]) return;

    const end   = $tr.find('[name^=end]').val();
    const begin = $tr.find('[name^=begin]').val();
    if(!begin || !end) return;

    const selectedProgramID = $tr.find('[name^=parent]').val();
    if(selectedProgramID == 0 || selectedProgramID == undefined)
    {
        $tr.next('tr.dateTip').remove();
        return;
    }

    const projectID = $tr.find('[name^=id]').val();
    $.get($.createLink('project', 'ajaxGetProjectFormInfo', 'objectType=project&objectID=' + projectID + '&selectedProgramID=' + selectedProgramID), function(response)
    {
        const data         = JSON.parse(response);
        const parentEnd    = new Date(data.selectedProgramEnd);
        const parentBegin  = new Date(data.selectedProgramBegin);
        const projectEnd   = new Date(end);
        const projectBegin = new Date(begin);

        if(projectBegin >= parentBegin && projectEnd <= parentEnd)
        {
            $tr.next('tr.dateTip').remove();
            return;
        }

        if($tr.next('tr.dateTip').length == 0) $tr.after($('#dateTipTemplate tr').clone());
        $tr.next('tr.dateTip').find('.beginLess').toggleClass('hidden', projectBegin >= parentBegin).text(beginLessThanParent.replace('%s', data.selectedProgramBegin));
        $tr.next('tr.dateTip').find('.endGreater').toggleClass('hidden', projectEnd <= parentEnd).text(endGreatThanParent.replace('%s', data.selectedProgramEnd));
        $tr.next('tr.dateTip').find('a').attr('onclick', 'batchIgnoreTip(' + index + ')');
    });
}

/**
 * 忽略日期提示。
 * Ignore date tips.
 *
 * @access public
 * @param  int    index
 * @return void
 */
batchIgnoreTip = function(index)
{
    $('tr[data-index="' + index + '"]').next('tr.dateTip').remove();
    batchIgnoreTips[index] = true;
}

window.renderRowData = function($row, index, row)
{
    /* 如果某个项目是长期项目，更新计划完成日期和可用工作日的样式。*/
    /* If a project is a long-term project, update the style of the planned completion date and available working days. */
    if(row.end == LONG_TIME)
    {
        $row.find('[data-name="end"] [id^=end]').attr('data-longTime', 1).addClass('hidden').next().removeClass('hidden');
        $row.find('[name^=days]').val(0).attr('readonly', true);
    }

    const aclList = !disabledprograms && row.parent ? programAclList : projectAclList;
    $row.find('[data-name="acl"]').find('.picker-box').on('inited', function(e, info)
    {
        let $acl = info[0];
        $acl.render({items: aclList, required: true});
    });
}
