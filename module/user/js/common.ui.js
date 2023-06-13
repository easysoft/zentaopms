window.switchAccount = function(account)
{
    link = $.createLink('user', method, 'account=' + account);
    if(method == 'dynamic') link = $.createLink('user', method, 'account=' + account + '&period=' + pageParams.period);
    if(method == 'todo')    link = $.createLink('user', method, 'account=' + account + '&type=' + pageParams.type);
    if(method == 'story')   link = $.createLink('user', method, 'account=' + account + '&storyType=' + pageParams.storyType);

    loadPage(link);
};
