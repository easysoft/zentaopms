$(function()
{
    if(browseType != 'bysearch') $('#module' + param).closest('li').addClass('active');
    if($('#bugList thead th.c-title').width() < 150) $('#bugList thead th.c-title').width(150);
});
