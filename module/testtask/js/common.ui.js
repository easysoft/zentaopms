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
    loadExecutionBuilds($('#execution').val());
}

/**
 * Load execution builds.
 *
 * @param  int $executionID
 * @access public
 * @return void
 */
function loadExecutionBuilds(executionID)
{
    var selectedBuild = $('#build').val();
    if(!selectedBuild) selectedBuild = 0;

    var link = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + $('#product').val() + '&varName=testTaskBuild&build=' + selectedBuild);
    if(executionID == 0) link = $.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + $('#product').val() + '&varName=build&build=' + selectedBuild + '&branch=&index=&needCreate=&type=noempty,notrunk,withexecution');

    $.get(link, function(data)
    {
        $('#build').replaceWith(data);
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
    link = $.createLink('testtask', 'ajaxGetTestReports', 'productID=' + productID);
    $.get(link, function(data)
    {
        if(!data) data = '<select id="testreport" name="testreport" class="form-control"></select>';
        $('#testreport').replaceWith(data);
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
    var $checkAll = $(event.target).closest('td').find("input[type='checkbox']");
    var isChecked = !$checkAll.prop('checked');
    $checkAll.prop('checked', isChecked);

    $checkAll.closest('tbody').children('tr').find('input[type=checkbox]').prop('checked', isChecked);
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
    var $target = $(event.target).closest('td').find("input[type='checkbox']");
    var isChecked = !$target.prop('checked');
    $target.prop('checked', isChecked);

    var $next = $target.closest('tr').next();

    while($target.closest('tr').hasClass('step-group') && $next.length && $next.hasClass('step-item'))
    {
        $next.find("input[type='checkbox']").prop('checked', isChecked);
        $next = $next.next();
    }

    if($target.closest('tr').hasClass('step-item'))
    {
        var parentStepKey   = $target.closest('tr').data('parent');
        var allSiblings     = $target.closest('tbody').find('.step-item.group-' + parentStepKey).length
        var checkedSiblings = $target.closest('tbody').find('.step-item.group-' + parentStepKey + ' input[type=checkbox]:checked').length

        $target.closest('tr').prevAll('.step-group').first().find('input[type=checkbox]').prop('checked', allSiblings == checkedSiblings);
    }

    var $tbody = $target.closest('tbody');
    $tbody.find('.check-all input[type=checkbox]').prop('checked', $tbody.find('.check-item input[type=checkbox]').length == $tbody.find('.check-item input[type=checkbox]:checked').length);
}
