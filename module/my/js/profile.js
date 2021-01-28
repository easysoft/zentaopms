$(document).ready(function()
{
    $('#files').change(function(){$('#avatarForm').submit();});

    $.setAjaxForm('#avatarForm', function(response)
    {   
        if(response.result == 'success')
        {   
            setTimeout(function()
            {   
                $('#avatarUploadBtn').popover('destroy');
                $('#ajaxModal').load(response.locate);
            }, 800);
        }   
    }); 

    $('#avatarUploadBtn').on('click', function()
    {   
        $('#files').click();
    });
});

function uploadAvatar()
{
    $('#avatarUploadBtn').trigger('click');
}
