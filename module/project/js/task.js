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

    toggleFold('#projectTaskForm', unfoldTasks, projectID, 'project');
});

$('#module' + moduleID).closest('li').addClass('active');
$('#product' + productID).closest('li').addClass('active');
