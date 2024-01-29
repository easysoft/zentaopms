$(function()
{
    $('#currentConsumed').on('keyup', function()
    {
        var currentConsumed = $(this).val();
        if(!parseFloat(currentConsumed)) currentConsumed = 0;
        var totalConsumed = parseFloat(currentConsumed) + parseFloat(consumed);
        totalConsumed = Math.round(totalConsumed * 1000) / 1000;
        $('#totalConsumed').html(totalConsumed);
    })
})

window.clickSubmit = function()
{
    if(task.consumed != 0 && $('#currentConsumed').val() == 0 && $('#currentConsumed').val() != '')
    {
        zui.Modal.confirm(consumedEmpty).then(function(result)
        {
            if(result)
            {
                $.ajaxSubmit({
                    url:  $('#finishForm form').attr('action'),
                    data: new FormData($('#finishForm form')[0])
                });
            }
        });
        return false;
    }
}
