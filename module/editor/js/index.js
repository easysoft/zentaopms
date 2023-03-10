$(function()
{
    $('.side-col a').click(function()
    {
        $(this).closest('.side-col').find('a.text-primary').removeClass('text-primary');
        $(this).addClass('text-primary');
    });
    setHeight();
    $(window).resize(setHeight);
});

/**
 * Set pane height.
 *
 * @access public
 * @return void
 */
function setHeight()
{
    var paneHeight = $(window).height() - 120;
    $('#sidebar .moduleTree,#mainContent .module-col,#extendWin').css('height', paneHeight);
    $(' #mainContent .module-content, #editWin').css('height', paneHeight - 6);
}
