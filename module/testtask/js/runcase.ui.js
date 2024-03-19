var custom = false;
$(function()
{
    loadResult();
});

$('#runCaseModal').closest('.modal').on('hide.zui.modal', function()
{
    if($('#casesResults').length == 0) loadCurrentPage();
});

/**
 * Load result.
 *
 * @access public
 * @return void
 */
function loadResult()
{
    loadCurrentPage({url: resultsLink, selector: '#casesResults', partial: true});
}

/**
 * When real change.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function realChange(event)
{
    var $target    = $(event.target);
    var $preSelect = $(event.target).closest('table').closest('tr').find('[name^="result"]');
    if($target.val() == '' && $preSelect.val() == 'fail')
    {
        $preSelect.zui('picker').$.changeState({value: 'pass'});
    }
    else if($target.val() != '' && $preSelect.val() == 'pass')
    {
        $preSelect.zui('picker').$.changeState({value: 'fail'});
        $preSelect.parent().addClass('has-error');
        setTimeout(function(){$preSelect.parent().removeClass('has-error');},'1000');
    }
}

/**
 * Check step value is changed.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function checkStepValue(event)
{
    if($(event.target).val() == 'pass') custom = true;
}
/**
 * Set height of the file modal.
 *
 * @access public
 * @return void
 */
function setFileModalHeight()
{
    $($(this).data('target')).find('.modal-content').css('max-height', $('#runCaseModal').height() + 'px');
}
