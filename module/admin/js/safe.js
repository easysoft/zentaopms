$(function()
{
    $("#mainHeader #navbar li[data-id='system']").addClass('active');
})

/**
 * When changeWeask change.
 *
 * @param  bool   changeWeak
 * @access public
 * @return void
 */
function changeWeakChange(changeWeak)
{
    $('#mainContent .table td.notice').html(changeWeak == 1 ? adminLang.safe.noticeWeakMode : adminLang.safe.noticeMode);
}
