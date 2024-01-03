$(function()
{
    $('#resultsContainer').off('click', '.result-item').on('click', '.result-item', toggleShowResults);
    $('#resultsContainer').off('click', '.check-all .checkbox-primary').on('click', '.check-all .checkbox-primary', toggleCheckAll);
    $('#resultsContainer').off('click', '.check-item').on('click', '.check-item', toggleCheckChildItem);
    $('#resultsContainer').off('click', '.to-bug-button').on('click', '.to-bug-button', createBug);
});

/**
 * Load execution related
 *
 * @access public
 * @return void
 */
function loadExecutionRelated()
{
    loadExecutionBuilds();
}

/**
 * Load execution builds.
 *
 * @param  int $executionID
 * @access public
 * @return void
 */
window.loadExecutionBuilds = function()
{
    const productID   = $('[name=product]').val();
    const executionID = $('[name=execution]').val();
    var selectedBuild = $('[name=build]').val();
    if(!selectedBuild) selectedBuild = 0;

    var link = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + '&varName=testTaskBuild&build=' + selectedBuild);
    if(executionID == 0) link = $.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=build&build=' + selectedBuild + '&branch=&needCreate=&type=noempty,notrunk,withexecution');
    if(executionID == 0 && projectID == 0) link = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=build&build=&branch=all&index=&type=notrunk,withexecution');

    $.getJSON(link, function(data)
    {
        let $buildPicker = $('[name="build"]').zui('picker');
        let oldBuild     = $('[name="build"]').val();
        $buildPicker.render({items: data});
        $buildPicker.$.setValue(oldBuild);
        if(data.length == 0)
        {
            const url = $.createLink('build', 'create', "executionID=" + executionID + "&productID=" + productID + "&projectID=" + projectID);
            $('[name="build"]').closest('.input-group').find('.input-group-addon a').attr('href', url);
            $('[name="build"]').closest('.input-group').find('.input-group-addon').removeClass('hidden');
        }
        else
        {
            $('[name="build"]').closest('.input-group').find('.input-group-addon').addClass('hidden');
        }
    });
}

/**
 * Load test report.
 *
 * @param  int    productID
 * @access public
 * @return void
 */
function loadTestReports(productID)
{
    $.getJSON($.createLink('testtask', 'ajaxGetTestReports', 'productID=' + productID), function(result)
    {
        let $testreportPicker = $('[name="testreport"]').zui('picker');
        if(result.reports)
        {
            $testreportPicker.render({items: result.reports});
            $testreportPicker.$.setValue('');
        }
    });
}

/**
 * when begin date input change and end date input is null
 * change end date input to begin's after day
 *
 * @access public
 * @return void
 */
function suitEndDate()
{
    beginDate = $('#beginDate').zui('datePicker').$.state.value;
    if(!beginDate) return;
    endDate = $('#endDate').zui('datePicker').$.state.value;
    if(endDate) return;

    endDate = getNextDay(beginDate);
    $('#endDate').zui('datePicker').$.changeState({value: endDate});
}

function getNextDay(dateString)
{
    var currentDate = new Date(dateString);
    currentDate.setDate(currentDate.getDate() + 1);

    var nextYear = currentDate.getFullYear();
    var nextMonth = ('0' + (currentDate.getMonth() + 1)).slice(-2);
    var nextDay = ('0' + currentDate.getDate()).slice(-2);

    var nextDate = nextYear + '-' + nextMonth + '-' + nextDay;
    return nextDate;
}

/**
 * Create bug from fail case.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function createBug(event)
{
    var $form      = $(event.target).closest('form');
    var stepIdList = '';
    $form.find('.step .step-id input[type="checkbox"]:checked').each(function()
    {
        if($(this).prop('checked')) stepIdList += $(this).val() + '_';
    });

    var link = $.createLink('bug', 'create', $form.data('params') + ',stepIdList=' + stepIdList);
    loadPage(link);
}

/**
 * Toggle results show or hide.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function toggleShowResults(event)
{
    var $target = $(event.target).closest('tr');
    if($target.data('status') == 'running') return;

    $target.toggleClass('is-collapsed');
    $target.toggleClass('is-expanded');
    $target.toggleClass('show-detail');
    var show = $target.hasClass('show-detail');
    $target.next('.result-detail').toggleClass('hidden', !show);
    $target.find('.collapse-handle').toggleClass('icon-angle-down', !show).toggleClass('icon-angle-top', show);;
}

/**
 * Toggle check all.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function toggleCheckAll(event)
{
    var $checkAll = $(event.target).closest('.check-all').find("input[type='checkbox']");
    var isChecked = $checkAll.prop('checked');

    $checkAll.closest('.resultSteps').find('.step').find('input[type=checkbox]').prop('checked', isChecked);
}

/**
 * Toggle child item check status.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function toggleCheckChildItem(event)
{
    var $target = $(event.target).closest('.step-id').find("input[type='checkbox']");
    var $step   = $target.closest('.step');
    var isChecked = $target.prop('checked');

    var grade = $step.data('grade');
    var $next = $target.closest('.step').next();
    while(grade < $next.data('grade'))
    {
        $next.find("input[type='checkbox']").prop('checked', isChecked);
        $next = $next.next();
    }

    var parentStepID = $step.data('parent');
    if($step.data('id') != 0 && parentStepID != 0)
    {
        var allSiblings     = $target.closest('.steps-body').find('[data-parent="' + parentStepID + '"]').length
        var checkedSiblings = $target.closest('.steps-body').find('[data-parent="' + parentStepID + '"]' + ' input[type=checkbox]:checked').length
        var $parentStep     = $target.closest('.step').prevAll('[data-id="' + parentStepID + '"]').find('input[type=checkbox]');
        var siblingsChecked = !!(allSiblings == checkedSiblings);
        if(!$parentStep.prop('checked') && siblingsChecked)
        {
            $parentStep.trigger('click');
        }
        else
        {
            $parentStep.prop('checked', false);
            if($parentStep.closest('.step').data('parent') != 0) $target.closest('.step').prevAll('[data-id="' + $parentStep.closest('.step').data('parent') + '"]').find('input[type=checkbox]').prop('checked', false);
        }
    }

    var $resultSteps = $target.closest('.resultSteps');
    $resultSteps.find('.check-all input[type=checkbox]').prop('checked', $resultSteps.find('.check-item input[type=checkbox]').length == $resultSteps.find('.check-item input[type=checkbox]:checked').length);
}

function setExecutionByBuild()
{
    const buildID = $('[name=build]').val();
    link = $.createLink('testtask', 'ajaxGetExecutionByBuild', 'buildID=' + buildID);
    $.get(link, function(data)
    {
        if(data != '0') $('[name="execution"]').zui('picker').$.setValue(data);
    });
}
