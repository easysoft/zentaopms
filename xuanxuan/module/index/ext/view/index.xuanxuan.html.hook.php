<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'xuanxuan') != false): ?>
<style>
#menu {display: none!important;}
#appsBar,
#apps {left: 0!important;}
</style>
<script>
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

$(function()
{
    $(document).on('showapp', function(e, app)
    {
        window.open('xxc://extension.zentao-integrated.activeAppMenuItem/' + app.code);
    });
});
</script>
<?php endif; ?>
