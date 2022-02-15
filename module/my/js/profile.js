$(document).ready(function()
{
    $('#files').change(function()
    {
        $('#avatarForm').submit();
    });

    $('#avatarUploadBtn').on('click', function()
    {
        $('#files').click();
    });

    if(avatar)
    {
        window.parent.$('#userNav .avatar, #menu-avatar').html('<img src="' + avatar + '"/>');
        window.parent.$('#mainContent>.cell>.main-header>.avatar').html('<img src="' + avatar + '"/>');
        window.parent.$('#mainContent .avatar-' + userID).html('<img src="' + avatar + '"/>');
    }

    $('#avatarUploadBtn').tooltip();
});

function uploadAvatar()
{
    $('#avatarUploadBtn').trigger('click');
}
