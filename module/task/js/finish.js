$(function()
{
    $('#currentConsumed').keyup(function()
    {
        var currentConsumed = $(this).val();
        if(!parseFloat(currentConsumed)) currentConsumed = 0;
        var totalConsumed = parseFloat(currentConsumed) + parseFloat(consumed);
        totalConsumed = Math.round(totalConsumed * 1000) / 1000;
        $('#totalConsumed').html(totalConsumed);
        $('#consumed').val(totalConsumed);
    })        
})
