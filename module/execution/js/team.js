function checkUserDept(userID)
{
    alert(noAccess);
}

/**
 * Delete memeber of project team.
 *
 * @param  int $executionID
 * @param  int $userID
 * @access public
 * @return void
 */
function deleteMember(executionID, userID)
{
    bootbox.confirm(confirmUnlinkMember, function(result)
    {
        if(!result) return true;
        var unlinkURL = createLink('execution', 'unlinkMember', 'executionID=' + executionID + '&userID=' + userID + '&confirm=yes');
        unlinkMember(unlinkURL);
    });
}

/**
 * Unlink member from project.
 *
 * @param  unlinkURL $unlinkURL
 * @access public
 * @return void
 */
function unlinkMember(unlinkURL)
{
    $.get(unlinkURL, function(data)
    {
        data = JSON.parse(data);
        if(data.result == 'success') window.location.reload();
    });
}
