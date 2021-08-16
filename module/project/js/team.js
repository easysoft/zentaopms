function checkUserDept(userID)
{
    alert(noAccess);
}

/**
 * Delete memeber of project team.
 *
 * @param  projectID $projectID
 * @param  account $account
 * @param  userID $userID
 * @access public
 * @return void
 */
function deleteMemeber(projectID, account, userID)
{
    if(confirm(confirmUnlinkMember))
    {
        var removeConfirm = '';
        var tipsLink      = createLink('project', 'ajaxGetUnlinkTips', 'projectID=' + projectID + '&account=' + account);
        $.get(tipsLink, function(tips)
        {
            if(confirm(tips))
            {
                var unlinkURL = createLink('project', 'unlinkMember', 'projectID=' + projectID + '&userID=' + userID + '&confirm=yes&removeExecution=yes');
            }
            else
            {
                var unlinkURL = createLink('project', 'unlinkMember', 'projectID=' + projectID + '&userID=' + userID + '&confirm=yes&removeExecution=no');
            }

            $.get(unlinkURL, function(data)
            {
                data = JSON.parse(data);
                if(data.result == 'success') window.location.reload();
            });
        })
    }
}
