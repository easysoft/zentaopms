$(function()
{
    $('#currentConsumed').keyup(function()
    {
        var currentConsumed = $(this).val();
        if(!parseInt(currentConsumed)) currentConsumed = 0;
        var total = parseInt(currentConsumed) + parseInt(consumed);
        $('#totalConsumed').html(total);
        $('#consumed').val(total);
    })        
})
