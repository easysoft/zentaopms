$(document).ready(function()
{
    if(typeof(flow) != 'undefined' && flow == 'onlyTest')
    {
        toggleSearch();

        if(config.currentMethod != 'library')
        {
            $('#mainmenu .nav li').removeClass('active');
            $('#mainmenu .nav li[data-id=testsuite]').addClass('active');
        }
    }
})
