$(function()
{
    $('#resultsContainer').off('click', '.result-item').on('click', '.result-item', toggleShowResults);
    $('#resultsContainer').off('click', '.check-all').on('click', '.check-all', toggleCheckAll);
    $('#resultsContainer').off('click', '.check-item').on('click', '.check-item', toggleCheckChildItem);
    $('#resultsContainer').off('click', '.to-bug-button').on('click', '.to-bug-button', toggleCheckChildItem);
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
function loadExecutionBuilds()
{
    const productID   = $('[name=product]').val();
    const executionID = $('[name=execution]').val();
    var selectedBuild = $('[name=build]').val();
    if(!selectedBuild) selectedBuild = 0;

    var link = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + productID + '&varName=testTaskBuild&build=' + selectedBuild);
    if(executionID == 0) link = $.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=build&build=' + selectedBuild + '&branch=&index=&needCreate=&type=noempty,notrunk,withexecution');

    $.get(link, function(data)
    {
        let $buildPicker = $('[name="build"]').zui('picker');
        if(data)
        {
            data = JSON.parse(data);
            $buildPicker.render({items: data});
            $buildPicker.$.changeState({value: '0'});
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
    $.get($.createLink('testtask', 'ajaxGetTestReports', 'productID=' + productID), function(data)
    {
        let $testreportPicker = $('[name="testreport"]').zui('picker');
        if(data)
        {
            data = JSON.parse(data);
            $testreportPicker.render({items: data});
            $testreportPicker.$.changeState({value: '0'});
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
    beginDate = $('#begin').val();
    if(!beginDate) return;
    endDate = $('#end').val();
    if(endDate) return;

    endDate = $.zui.formatDate(convertStringToDate(beginDate).addDays(1), 'yyyy-MM-dd');
    $('#end').val(endDate);
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
    var $form  = $(event.target).closest('form');
    var stepIdList = '';
    $form.find('.step .step-id input[type="checkbox"]:checked').each(function()
    {
        if($(this).prop('checked')) stepIdList += $(this).val() + '_';
    });

    $form.attr('action', $.createLink('bug', 'create', bugCreateParams + ',stepIdList=' + stepIdList));
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

    var stepID = $step.data('id');
    var $next = $target.closest('.step').next();
    while(stepID == $next.data('parent'))
    {
        $next.find("input[type='checkbox']").prop('checked', isChecked);
        $next = $next.next();
    }

    if(stepID != 0)
    {
        var parentStepID   = $step.data('parent');
        var allSiblings     = $target.closest('.steps-body').find('[data-parent="' + parentStepID + '"]').length
        var checkedSiblings = $target.closest('.steps-body').find('[data-parent="' + parentStepID + '"]' + ' input[type=checkbox]:checked').length
        $target.closest('.step').prevAll('[data-id="' + parentStepID + '"]').find('input[type=checkbox]').prop('checked', allSiblings == checkedSiblings);
    }

    var $stepsBody = $target.closest('.steps-body');
    $stepsBody.find('.check-all input[type=checkbox]').prop('checked', $stepsBody.find('.check-item input[type=checkbox]').length == $stepsBody.find('.check-item input[type=checkbox]:checked').length);
}
