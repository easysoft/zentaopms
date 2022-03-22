if(window.navigator.userAgent.indexOf('xuanxuan') > 0)
{
    $('html').addClass('xxc-embed');

    /** Update zentao client app menu */
    function updateAppMenu()
    {
        const menuItems = appsMenuItems.map(function(item)
        {
            if (item === 'divider') return '-';
            var $title = $('<div>' + item.title + '</div>');
            return [item.code, ($title.find('.icon').attr('class') || '').replace('icon ', ''),$title.text().trim()];
        });
        window.open('xxc://extension.zentao-integrated.updateAppMenu/' + encodeURIComponent(JSON.stringify(menuItems)));
    }

    updateAppMenu();

    $(document).on('showapp', function(e, app)
    {
        window.open('xxc://extension.zentao-integrated.activeAppMenuItem/' + encodeURIComponent(JSON.stringify({id: app.code, openedApps:Object.keys($.apps.openedApps)})));
    });
    $(document).on('hideapp', function(e, app)
    {
        window.open('xxc://extension.zentao-integrated.hideAppMenuItem/' + encodeURIComponent(JSON.stringify({id: app.code, openedApps:Object.keys($.apps.openedApps)})));
    });
}
