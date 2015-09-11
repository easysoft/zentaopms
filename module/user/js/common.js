/**
 * Switch account 
 * 
 * @param  string $account 
 * @param  string $method 
 * @access public
 * @return void
 */
function switchAccount(account, method)
{
    if(method == 'dynamic')
    {
        link = createLink('user', method, 'period=' + period + '&account=' + account);
    }
    else if(method == 'todo')
    {
        link = createLink('user', method, 'account=' + account + '&type=' + type);
    }
    else
    {
        link = createLink('user', method, 'account=' + account);
    }
    location.href=link;
}

var mailsuffix = '';
var account    = new Array();
function setDefaultEmail(num)
{
    var mailValue = $('.email_' + num).val();
    if(mailValue.indexOf('@') <= 0) return;
    if(mailValue.indexOf('@') > 0) mailValue = mailValue.substr(mailValue.indexOf('@'));
    mailsuffix = mailValue;
}

function changeEmail(num)
{
    var mailValue = $('.email_' + num).val();
    if(mailsuffix != '' && (mailValue == '' || mailValue == account[num] + mailsuffix)) $('.email_' + num).val($('.account_' + num).val() + mailsuffix);
    account[num] = $('.account_' + num).val();
}

function checkPassword(password)
{
    $('#passwordStrength').html(password == '' ? '' : passwordStrengthList[computePasswordStrength(password)]);
    $('#passwordStrength').css('display', password == '' ? 'none' : 'table-cell');
}
