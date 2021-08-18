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

    window.parent.$('#main-avatar, #menu-avatar').html('<img src="' + avatar + '"/>');
    window.parent.$('#mainContent>.cell>.main-header>.avatar').html('<img src="' + avatar + '"/>');
    window.parent.$('#mainContent .avatar-' + account).html('<img src="' + avatar + '"/>');
});

function uploadAvatar()
{
    $('#avatarUploadBtn').trigger('click');
}
