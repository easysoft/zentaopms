/**
 * Delete memeber of execution team.
 *
 * @param  int $executionID
 * @param  int $userID
 * @access public
 * @return void
 */
window.deleteMember = function(executionID, userID)
{
    zui.Modal.confirm({message: confirmUnlinkMember, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('execution', 'unlinkMember', 'executionID=' + executionID + '&userID=' + userID)});
    });
}

/**
 * Set team summary for table footer.
 *
 * @access public
 * @return object
 */
window.setTeamStatistics = function()
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
    alert(noAccess);
}
