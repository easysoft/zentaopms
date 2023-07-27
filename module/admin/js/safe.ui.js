/**
 * When changeWeak change.
 *
 * @param  event  event
 * @access public
 * @return void
 */
function changeWeakChange(event)
{
    $('#mainContent .admin-safe-form .safe-notice span').html($(event.target).val() == 1 ? adminLang.safe.noticeWeakMode : adminLang.safe.noticeMode);
}

/**
 * Show current mode rule.
 *
 * @param  event  event
 * @access public
 * @return void
 */
function showModeRule(event)
{
    var mode = $(event.target).val();
    if(mode == 0)
    {
        $('#mode1Rule').addClass('hidden');
        $('#mode2Rule').addClass('hidden');
    }
    else
    {
        mode == 1 ? $('#mode1Rule').removeClass('hidden') : $('#mode1Rule').addClass('hidden');
        mode == 2 ? $('#mode2Rule').removeClass('hidden') : $('#mode2Rule').addClass('hidden');
    }
}
