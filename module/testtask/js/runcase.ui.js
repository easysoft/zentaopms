var custom = false;
$(function()
{
    loadResult();
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
    var $preSelect = $(event.target).closest('table').closest('tr').find('select');
    if($target.val() == '' && $preSelect.val() == 'fail' && !custom)
    {
        $preSelect.val('pass');
    }
    else if($target.val() != '' && $preSelect.val() == 'pass' && !custom)
    {
        $preSelect.val('fail').parent().addClass('has-error');
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
