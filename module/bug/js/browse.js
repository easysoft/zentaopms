$(function()
{
    if($('#bugList thead th.c-title').width() < 150) $('#bugList thead th.c-title').width(150);
    $('#mainMenu .pull-left').width($('#mainMenu').width() - $('#sidebarHeader').width() - $('#mainMenu .pull-right').width() - 45);
});
