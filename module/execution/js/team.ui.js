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
    if(window.confirm(confirmUnlinkMember))
    {
        $.ajaxSubmit({url: $.createLink('execution', 'unlinkMember', 'executionID=' + executionID + '&userID=' + userID)});
    }
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
        totalHours += row.data.totalHours;
    });

    return {html: pageSummary.replace('%totalHours%', totalHours)};
}
