/**
 * Delete memeber of execution team.
 *
 * @param  int    $projectID
 * @param  string $account
 * @param  int    $userID
 * @access public
 * @return void
 */
window.deleteMember = function(projectID, account, userID)
{
    let removeConfirm   = '';
    let tipsLink        = $.createLink('project', 'ajaxGetRemoveMemberTips', 'projectID=' + projectID + '&account=' + account);
    let removeExecution = 'no';
    zui.Modal.confirm({message: confirmUnlinkMember, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res)
        {
            $.get(tipsLink, function(tips)
            {
                if(tips && window.confirm(tips)) removeExecution = 'yes';
                $.ajaxSubmit({url: $.createLink('project', 'unlinkMember', 'projectID=' + projectID + '&userID=' + userID + '&removeExecution=' + removeExecution)});
            })
        }
    })
}

/**
 * Set team summary for table footer.
 *
 * @access public
 * @return object
 */
window.setStatistics = function()
{
    const rows     = this.layout.allRows;
    let totalHours = 0;
    rows.forEach(function(row)
    {
        totalHours += parseFloat(row.data.totalHours);
    });

    return {html: pageSummary.replace('%totalHours%', totalHours)};
}

window.renderCell = function(result, {col, row})
{
    if(col.name == 'realname' && !deptUsers[row.data.userID])
    {
        result[0] = {html: "<a href='javascript:checkUserDept();'>" + row.data.realname + '</a>'};
        return result;
    }

    return result;
}

window.checkUserDept = function()
{
    zui.Modal.alert(noAccess);
}
