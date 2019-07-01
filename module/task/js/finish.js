$(function()
{
    $('#currentConsumed').keyup(function()
    {
        var currentConsumed = $(this).val();
        if(!parseInt(currentConsumed)) currentConsumed = 0;
        var totalConsumed = parseInt(currentConsumed) + parseInt(consumed);
        $('#totalConsumed').html(totalConsumed);
        $('#consumed').val(totalConsumed);
    })        
})
