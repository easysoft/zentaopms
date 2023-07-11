$('table').on('change', '.pick-value', function()
{
    var user = zentaoUsers[$(this).val()];
    console.log(111, $(this).val(), user, $(this).attr('id'))
    $('#' + $(this).attr('id').replace('users', 'zentaoEmail')).text(user.email);
})