/**
 * unlink member.
 * 
 * @param  int    projectID 
 * @param  string account 
 * @access public
 * @return void
 */
function unlinkMember(projectID, account)
{
    if(confirm(confirmUnlinkMember))
    {
        url = createLink('project', 'unlinkMember','projectID=' + projectID + '&account=' + account + '&confrim=yes');
        $.ajax(
        {
            type:     'GET', 
            url:      url,
            dataType: 'json', 
            success:  function(data) 
            {
                if(data.result == 'success')
                {
                    url = createLink('project', 'team', 'projectID=' + projectID);
                    $('.outer').load(url + ' #memberlist', function(){sortTable();});
                }
            }
        });
    }
}
