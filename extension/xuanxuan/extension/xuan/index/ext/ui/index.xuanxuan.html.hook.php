<?php

use function zin\jsVar;

 if(strpos($_SERVER['HTTP_USER_AGENT'], 'xuanxuan') != false): ?>
<?php global $app; $app->setClientTheme('blue'); ?>
<style>
</style>
<script>
$('html').addClass('xxc-embed');

const appsMenuItems = JSON.parse(`<?php echo json_encode(commonModel::getMainNavList($app->rawModule)); ?>`);

/** Update zentao client app menu */
function updateAppMenu()
{
    var menuItems = appsMenuItems.map(function(item)
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
</script>
<?php endif; ?>
