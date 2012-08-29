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

