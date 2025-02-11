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
    const type        = $('input[name=type]').val();
    const parentStage = $('input[name=parent]').val();
    loadPage($.createLink('execution', 'create', 'projectID=' + projectID + '&executionID=0&copyExecutionID=&planID=0&confirm=no&productID=0&extra=type=' + type + ',parentStage=' + parentStage));
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
    projectID = projectID == undefined ? 0 : parseInt(projectID);
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
    $('[data-name=team]').hasClass('is-pinned') && !$('[data-name=teams]').hasClass('hidden') ? $('[data-name=teams]').addClass('is-pinned') : $('[data-name=teams]').removeClass('is-pinned');
}

/**
 * Toggle ops tip.
 *
 * @access public
 * @return void
 */
function toggleOpsTip()
{
    $(this).closest('.form-group').find('.form-tip').remove();
    if($(this).val() == 'ops')
    {
        $(this).closest('.form-group').append('<div class="form-tip">' + typeDesc + '</div>');
    }
}
