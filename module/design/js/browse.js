$(function()
{
    $('#subNavbar .nav li').removeClass('active');
    $('#subNavbar .nav li[data-id=' + type + ']').addClass('active');

    $('#subNavbar .nav li > a').each(function()
    {
        var type = $(this).parent().attr('data-id');
        if(type == 'bysearch') return;
        if(type == 'more')
        {
            $(this).attr('href', '###');
            return;
        }

        type     = type == 'all' ? type : type.toLowerCase();
        var href = createLink('design', 'browse', 'projectID=' + projectID + '&productID=' + productID + '&type=' + type);
        $(this).attr('href', href);
    });

    if($('#subNavbar .dropdown-menu li[data-id=' + type +']').hasClass('active'))
    {
        var typeName = $('#subNavbar li[data-id=' + type +'] > a').html();

        $('#subNavbar').find(".dropdown-hover > a").html(typeName + "<span class='caret'></span>");
        $("#subNavbar li[data-id='more']").addClass('active');
    }
})
