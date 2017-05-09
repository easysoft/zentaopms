$(document).ready(function()
{
    if(onlyTest)
    {
        $('#mainmenu > .nav > li').removeClass('active');
        $('#mainmenu > .nav > li[data-id=testcase]').addClass('active');
    }
})
