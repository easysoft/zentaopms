/**
 * Delete memeber of project team.
 *
 * @param  projectID $projectID
 * @param  account   $account
 * @param  userID    $userID
 * @access public
 * @return void
 */
function deleteMemeber(projectID, account, userID)
{
    bootbox.confirm(confirmUnlinkMember, function(result)
    {
        if(!result) return true;

        var removeConfirm = '';
        var tipsLink      = createLink('project', 'ajaxGetUnlinkTips', 'projectID=' + projectID + '&account=' + account);
        $.get(tipsLink, function(tips)
        {
            var unlinkURL = createLink('project', 'unlinkMember', 'projectID=' + projectID + '&userID=' + userID + '&confirm=yes');

            if(!tips) unlinkMember(unlinkURL);
            if(tips)
            {
                bootbox.confirm(tips, function(result)
                {
                    if(result) unlinkURL = createLink('project', 'unlinkMember', 'projectID=' + projectID + '&userID=' + userID + '&confirm=yes&removeExecution=yes');
                    unlinkMember(unlinkURL);
                })
            }

        })
    })
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
