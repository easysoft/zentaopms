$(function()
{
    if($('#taskList thead th.c-name').width() < 150) $('#taskList thead th.c-name').width(150);
    $('#taskList td.has-child .task-toggle').each(function()
    {
        var $td = $(this).closest('td');
        var labelWidth = 0;
        if($td.find('.label').length > 0) labelWidth = $td.find('.label').width();
        $td.find('a').eq(0).css('max-width', $td.width() - labelWidth - 60);
    });

    toggleFold('#projectTaskForm', unfoldTasks, executionID, 'project');

    adjustTableFooter();
    $('body').on('click', '#toggleFold', adjustTableFooter);
    $('body').on('click', '.icon.icon-angle-double-right', adjustTableFooter);
});

$('#module' + moduleID).closest('li').addClass('active');
$('#product' + productID).closest('li').addClass('active');

/**
 * Adjust the table footer style.
 *
 * @access public
 * @return void
 */
function adjustTableFooter()
{
    if($('.main-col').height() < $(window).height())
    {
        $('.table.with-footer-fixed').css('margin-bottom', '0');
        $('.table-footer').removeClass('fixed-footer');
        $('.table-footer').css({"left":"0", "bottom":"0", "width":"unset"});
    }
}
