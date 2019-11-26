$(document).ready(function()
{ 
    $('#mainMenu #conceptTab').addClass('btn-active-text');
    $('#ajaxForm').ajaxForm(
    {
        finish:function(response)
        {
            if(response.result == 'success')
            {
                bootbox.alert(response.notice, function()
                {
                    location.reload();
                });
            }
            return false;
        }
    });
});
