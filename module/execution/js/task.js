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

    toggleFold('#executionTaskForm', unfoldTasks, executionID, 'execution');

    adjustTableFooter();
    $('body').on('click', '#toggleFold', adjustTableFooter);
    $('body').on('click', '.icon.icon-angle-right', adjustTableFooter);

    /* The display of the adjusting sidebarHeader is synchronized with the sidebar. */
    $(".sidebar-toggle").click(function()
    {
        $("#sidebarHeader").toggle("fast");
    });
    if($("main").is(".hide-sidebar")) $("#sidebarHeader").hide();
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
        $('.table-footer').css({'left': 0, 'bottom': 0, 'width': 'unset'});
    }
}

/**
 * Ajax refresh.
 *
 * @access public
 * @return void
 */
function ajaxRefresh()
{
    var $table = $('#executionTaskForm').closest('[data-ride="table"]');
    if($table.length)
    {
        var table = $table.data('zui.table');
        if(table)
        {
            table.options.replaceId = 'executionTaskForm';
            table.reload();
        }
    }
    $.get(location.href, function(data)
    {
        var $data = $(data);
        $('#mainMenu > div.btn-toolbar.pull-left').html($data.find('#mainMenu > div.btn-toolbar.pull-left').html());
        if($data.find('#mainContent .main-col .table-empty-tip').length) $('#mainContent .main-col').html($data.find('#mainContent .main-col'));
    });
}
