$(function()
{
    if($('#taskList thead th.c-name').width() < 150) $('#taskList thead th.c-name').width(150);
});

$('#module' + moduleID).closest('li').addClass('active');
$('#product' + productID).closest('li').addClass('active');
