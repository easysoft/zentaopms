$(function()
{
    type = type == 'bysearch' ? 'all' : type;
    $('#mainNavbar .nav a').removeClass('active');
    $('#mainNavbar .nav a[data-id=' + type + ']').addClass('active');

    $('#mainNavbar .nav li > a').each(function()
    {
        let type = $(this).attr('data-id');
        if(type == 'more')
        {
            $(this).attr('href', '###');
            return;
        }

        let href = $.createLink('design', 'browse', 'projectID=' + projectID + '&productID=' + productID + '&type=' + type);
        $(this).attr('href', href);
    });

    if($('#mainNavbar .dropdown-menu a[data-id=' + type +']').hasClass('active'))
    {
        let typeName = $('#mainNavbar a[data-id=' + type +']').html();

        $('#mainNavbar').find(".dropdown-hover > a").html(typeName + "<span class='caret'></span>");
        $("#mainNavbar li[data-id='more']").addClass('active');
    }
})
