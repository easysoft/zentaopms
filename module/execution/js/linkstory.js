$(function()
{
    $('#navbar .nav li').removeClass('active');
    $("#navbar .nav li[data-id=" + storyType + ']').addClass('active');
    $('#subNavbar li[data-id="' + storyType + '"]').addClass('active');

    if($('#navbar .nav>li[data-id=story] .dropdown-menu').length)
    {
        $('#navbar .nav li[data-id=story]').addClass('active');
        $("#navbar>ul>li[data-id='story']>ul>li[data-id!='" + storyType + "']").removeClass('active');
        $("#navbar>ul>li[data-id='story']>ul>li[data-id='" + storyType + "']").addClass('active');
        $('#navbar .nav>li[data-id=story]>a').html($('#navbar .nav li.active [data-id=' + storyType + ']').text() + '<span class="caret"></span>');
    }
})
