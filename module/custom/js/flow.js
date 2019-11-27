$(document).ready(function()
{ 
    $('#mainMenu #flowTab').addClass('btn-active-text');
    $('#ajaxForm').ajaxForm(
    {
        finish:function(response)
        {
            if(response.result == 'success')
            {
                bootbox.alert(response.notice, function()
                {
                    location.href = response.locate;
                });
            }
            return false;
        }
    });
});
