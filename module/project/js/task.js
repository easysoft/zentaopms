$(function()
{
    if($('#taskList thead th.c-name').width() < 150) $('#taskList thead th.c-name').width(150);
    $('#taskList td.has-child .task-toggle').each(function()
    {
        $td = $(this).closest('td');
        if($td.find('.label').length == 0) return false;
        $td.find('a').eq(0).css('max-width', $td.width() - $td.find('.label').width() - 40);
    });
});

$('#module' + moduleID).closest('li').addClass('active');
$('#product' + productID).closest('li').addClass('active');
