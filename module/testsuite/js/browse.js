$(document).ready(function()
{
    if(flow == 'onlyTest')
    {
        $('#mainmenu .nav li').removeClass('active');
        $('#mainmenu .nav li[data-id=testsuite]').addClass('active');
    }
})
