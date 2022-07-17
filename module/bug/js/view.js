$(document).ready(function()
{
    limitIframeLevel();
    if(config.onlybody == 'yes') $('.main-actions').css({width: '100%', minWidth: '100%'});
});

$('#tostory').click(function()
{
    if(!confirm(confrimToStory)) return false;
});

/**
 * Load Product executions in html.
 *
 * @param  int    productID
 * @param  int    projectID
 * @access public
 * @return void
 */
function loadProductExecutions(productID, projectID)
{
    var link = createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&projectID=' + projectID +'&branch=' + branchID + '&number=&executionID=0&from=bugToTask');

    $('#executionBox').load(link, function()
    {
        $(this).find('select').chosen();
    });
}

$('#toTaskButton').on('click', function()
{
    var projectID   = $('#taskProjects').val();
    var executionID = $('#execution').val();
    var executionID = executionID ? executionID : 0;

    if(systemMode == 'new' && projectID && executionID != 0)
    {
        $('#cancelButton').click();
        link = createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=0&extra=projectID=' + projectID + '&bugID=' + bugID);
        window.parent.$.apps.open(link, 'execution');
    }
    else if(systemMode == 'classic' && executionID)
    {
        $('#cancelButton').click();
        var link = createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=0&extra=projectID=0&bugID=' + bugID);
        window.parent.$.apps.open(link, 'execution');
    }
    else if(systemMode == 'new' && projectID == 0)
    {
        alert(errorNoProject);
    }
    else
    {
        alert(errorNoExecution);
    }
});
