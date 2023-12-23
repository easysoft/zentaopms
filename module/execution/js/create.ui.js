$(function()
{
    loadProjectExecutions(copyProjectID);

    if($('#methodHover').length) new zui.Tooltip('#methodHover', {title: methodTip, trigger: 'hover', placement: 'right', type: 'white', 'className': 'text-gray border border-light methodTip'});

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
    const projectID = $('#project').zui('picker').$.value;
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
    const type = $('#type').val();
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
        if(data)
        {
            let membersPicker = $('select[name^=teamMembers]').zui('picker');
            membersPicker.$.setValue(data);
        }
    });
}

$(document).on('change', '#begin', function()
{
    $("#days").val('');
    $('#end').zui('datePicker').$.changeState({value: ''});
    $("input[name='delta']").prop('checked', false);
});

$(document).on('change', '#end', function(e)
{
    $("input[name='delta']").prop('checked', false);
    computeWorkDays();
});

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

window.branchChange = function(e)
{
    let $product = $(e.target).closest('.form-row').find("[name^='products']");
    loadPlans($product, $(e.target));
}
