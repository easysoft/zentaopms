$(function()
{
    loadCurrentPage
    ({
        url: resultsLink,
        selector: '#casesResults',
        partial: true,
        success : function()
        {
            var $resultTrs = $('#resultsContainer').find('#casesResults tr');
            if($resultTrs.length == 0) return false;
            if($resultTrs.first().data('status') == 'ready') $resultTrs.first().trigger('click');
        }
    });

    $('#resultsContainer').off('click', '.result-item').on('click', '.result-item', toggleShowResults);
    $('#resultsContainer').off('click', '.check-all .checkbox-primary').on('click', '.check-all .checkbox-primary', toggleCheckAll);
    $('#resultsContainer').off('click', '.check-item').on('click', '.check-item', toggleCheckChildItem);
    $('#resultsContainer').off('click', '.to-bug-button').on('click', '.to-bug-button', createBug);
});

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
    $target.find('.collapse-handle').toggleClass('icon-angle-down', !show).toggleClass('icon-angle-top', show);
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

    const formData = new FormData();
    formData.append('stepIdList', stepIdList);

    var link = $.createLink('bug', 'create', $form.data('params'));
    postAndLoadPage(link, formData, '', {app: tab == 'my' ? 'qa' : tab});
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
