$(function()
{
    if(result == 'success')
    {
        $.ajax(
        {
            type: "get",
            url: createLink('upgrade', 'ajaxCheckExtension'),
            success: function(data){$('#checkExtension').html(data);},
            complete: function(){$('#tohome').html(tohome);}
        });
    }
});
