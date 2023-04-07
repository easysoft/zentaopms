$(document).ready(function()
{
    $('.gitlab-user-bind').change(function()
    {
        var user = zentaoUsers[$(this).val()];
        if(user !== undefined)
        {
            $(this).parent().parent().find('.email').text(user.email)
        }
    });
});