$(function()
{
    $('#subNavbar .nav li').removeClass('active');       
    $('#subNavbar .nav li[data-id=' + type + ']').addClass('active');
})
