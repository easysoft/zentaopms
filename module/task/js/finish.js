$(function()
{
    $('#currentConsumed').keyup(function()
    {
        var currentConsumed = $(this).val();
        if(!parseFloat(currentConsumed)) currentConsumed = 0;
        var totalConsumed = parseFloat(currentConsumed) + parseFloat(consumed);
        $('#totalConsumed').html(totalConsumed);
        $('#consumed').val(totalConsumed);
    })        
})
