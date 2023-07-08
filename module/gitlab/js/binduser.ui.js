$('table').on('change', '.pick-value', function()
{
    var user = zentaoUsers[$(this).val()];
    $('#' + $(this).attr('id').replace('users', 'zentaoEmail')).text(user.email);
})