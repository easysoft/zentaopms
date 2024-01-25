$(function()
{
    loadProjectExecutions(copyProjectID);

    if(isStage)
    {
        $(document).on('change', '#attribute', function(e)
        {
            let attribute = $(this).val();
            hidePlanBox(attribute);
        })
    }

    if(copyExecutionID != 0 || projectID != 0) loadMembers();

    setWhite();
});

/**
 * Refresh page.
 *
 * @access public
 * @return void
 */
function refreshPage()
{
    const projectID = $('[name=project]').val();
    loadPage($.createLink('execution', 'create', 'projectID=' + projectID));
}

/**
 * Refresh page.
 *
 * @access public
 * @return void
 */
function setType()
{
    const type = $('input[name=type]').val();
    loadPage($.createLink('execution', 'create', 'projectID=' + projectID + '&executionID=0&copyExecutionID=&planID=0&confirm=no&productID=0&extra=type=' + type));
}

/**
 * Load team members.
 *
 * @access public
 * @return void
 */
function loadMembers()
{
    let objectID = $('input[name=teams]').val() ? $('input[name=teams]').val() : projectID;
    $.getJSON($.createLink('execution', 'ajaxGetTeamMembers', 'objectID=' + objectID), function(data)
    {
        let membersPicker = $('[name^=teamMembers]').zui('picker');
        membersPicker.$.setValue(data);
    });
}

/**
 * Load copy executions box.
 *
 * @access public
 * @return void
 */
function loadProjectExecutions(projectID)
{
    projectID = parseInt(projectID) ? projectID : $('#copyExecutionModal input[name=project]').val();
    projectID = parseInt(projectID);
    loadTarget($.createLink('execution', 'ajaxGetCopyProjectExecutions', 'projectID=' + projectID + '&copyExecutionID=' + copyExecutionID), '#copyExecutions');
}

$(document).off('click', '#copyExecutions button.execution-block').on('click', '#copyExecutions button.execution-block', function(e)
{
    $(this).toggleClass('primary-outline');
    $('.execution-block').not(this).removeClass('primary-outline');
});

/**
 * Set copy execution.
 *
 * @access public
 * @return void
 */
function setCopyExecution()
{
    const executionID = $('.execution-block').hasClass('primary-outline') ? $('.execution-block.primary-outline').data('id') : 0;
    if(!executionID) projectID = 0;

    loadPage($.createLink('execution', 'create', 'projectID=' + projectID + '&executionID=0&copyExecutionID=' + executionID));
    zui.Modal.hide();
}

window.toggleCopyTeam = function(e)
{
    $this = $(e.target);
    if($this.prop('checked'))
    {
        $('[data-name=team]').removeClass('w-full').addClass('w-1/2');
        $('[data-name=teams]').removeClass('hidden');
    }
    else
    {
        $('[data-name=team]').removeClass('w-1/2').addClass('w-full');
        $('[data-name=teams]').addClass('hidden');
    }
}
