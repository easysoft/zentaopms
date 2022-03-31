$(function()
{
    adjustTableFooter();
});

/**
 * Adjust the table footer style.
 *
 * @access public
 * @return void
 */
function adjustTableFooter()
{
    if($('#mainContent').height() < $(window).height())
    {
        $('.table').removeClass('with-footer-fixed');
        $('.table-footer').removeClass('fixed-footer');
        $('.table-footer').css({'left': 0, 'bottom': 0, 'width': 'unset'});
    }
}
