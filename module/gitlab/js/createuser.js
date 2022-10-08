$(document).ready(function()
{
    setAvatar();
    $('#account').change(function()
    {
        var account = $(this).val();
        var user    = users[account];
        var name    = '';
        var email   = '';
        if(account && user)
        {
            name    = user.realname;
            account = user.account;
            email   = user.email;
        }
        $('#name').val(name);
        $('#username').val(account);
        $('#email').val(email);
    })
});
