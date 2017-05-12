$(document).ready(function()
{
    if(flow == 'onlyTest')
    {
        $('#modulemenu > .nav > li').removeClass('active');
        $('#modulemenu > .nav > li[data-id=' + status + ']').addClass('active');
    }
});
