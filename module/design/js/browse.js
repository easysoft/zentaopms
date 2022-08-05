$(function()
{
    $('#subNavbar .nav li').removeClass('active');       
    $('#subNavbar .nav li[data-id=' + type + ']').addClass('active');

    $('#subNavbar .nav li > a').each(function()
    {
        var type = $(this).parent().attr('data-id');
        if(type == 'bysearch') return;

        type     = type == 'all' ? type : type.toUpperCase();
        var href = createLink('design', 'browse', 'projectID=' + projectID + '&productID=' + productID + '&type=' + type);
        $(this).attr('href', href);
    });
})
