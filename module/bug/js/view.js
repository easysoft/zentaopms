$(document).ready(function()
{
    limitIframeLevel();
    if(config.onlybody == 'yes') $('.main-actions').css({width: '100%', minWidth: '100%'});

    if(config.onlybody == 'yes')
    {
        /* Fix bug#38422. */
        $('.histories-list a[data-app=devops]').each(function()
        {
            var href = $(this).attr('href');
            $(this).data('url', href.replace('onlybody=yes', ''));
            $(this).attr('href', '###');
            $(this).click(function()
            {
                window.parent.$.apps.open($(this).data('url'), 'devops');
            });
        });
    }
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
        $select = $(this).find('select');
        $tr     = $(this).closest('tr');
        $tr.removeClass('hidden');
        if($select.data('multiple') == 0)
        {
            $tr.addClass('hidden');
            $select.find('option:last').prop('selected', true);
        }
        $select.chosen();
    });
}

$('#toTaskButton').on('click', function()
{
    var projectID   = $('#taskProjects').val();
    var executionID = $('#execution').val();
    var executionID = executionID ? executionID : 0;

    if(projectID && executionID != 0)
    {
        $('#cancelButton').click();
        var link = createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=0&extra=projectID=' + projectID + '&bugID=' + bugID);
        window.parent.$.apps.open(link, $('#execution').data('multiple') == 0 ? 'project' : 'execution');
    }
    else if(projectID == 0)
    {
        alert(errorNoProject);
    }
    else
    {
        alert(errorNoExecution);
    }
});
