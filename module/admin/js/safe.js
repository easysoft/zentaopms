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
    if(changeWeak == 1) $('#mainContent .table td.notice').html(adminLang.safe.noticeWeakMode);
    if(changeWeak == 0) $('#mainContent .table td.notice').html(adminLang.safe.noticeMode);
}
