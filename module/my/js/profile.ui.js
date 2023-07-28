$(document).ready(function()
{
    $('#avatarForm input[type=file]').on('change', function()
    {
        $('#avatarForm button[type=submit]').trigger('click');
    });

    $('.avatar').on('click', function()
    {
        $('#avatarForm input[type=file]').trigger('click');
    });
});

window.uploadAvatar = function()
{
    $('#avatarForm input[type=file]').trigger('click');
}
