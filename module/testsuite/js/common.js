$(document).ready(function()
{
    if(typeof(onlyTest) != 'undefined' && onlyTest)
    {
        $('#mainmenu > .nav > li').removeClass('active');
        $('#mainmenu > .nav > li[data-id=testcase]').addClass('active');
    }
})
