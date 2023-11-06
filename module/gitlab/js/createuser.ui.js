$(document).ready(function()
{
    setAvatar();
});

function onAccountChange(event)
{
    var account = $(event.target).val();
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
}
