$(document).ready(function()
{
    $("a.iframe").colorbox({width:640, height:480, iframe:true, transition:'none'});
});

/**
 * Change form action.
 * 
 * @param  formName   $formName 
 * @param  actionName $actionName 
 * @param  actionLink $actionLink 
 * @access public
 * @return void
 */
function changeAction(formName, actionName, actionLink)
{
    $('#' + formName).attr('action', actionLink).submit();
}

/**
 * unlink story.
 * 
 * @param  int    $projectID 
 * @param  int    $storyID 
 * @access public
 * @return void
 */
function unlink(projectID, storyID)
{
    if(confirm(confirmUnlinkStory))
    {
        url = createLink('project', 'unlinkStory','projectID=' + projectID + '&storyID=' + storyID + '&confrim=yes');
        $.ajax(
        {
            type:     'GET', 
            url:      url,
            dataType: 'json', 
            success:  function(data) 
            {
                if(data.result == 'success') 
                {
                    $('#story' + storyID).remove();
                }
            }
        });
    }
}
