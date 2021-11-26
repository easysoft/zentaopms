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

<?php if(isset($xuanConfig)): ?>
<style>
#chatBtn {padding: 3px;}
#chatBtn .icon {font-size: 24px;}
</style>
<?php js::import($webRoot . 'data/xuanxuan/sdk/sdk.min.js'); ?>
<?php js::import($jsRoot . 'md5.js'); ?>
<?php js::set('xuanConfig', $xuanConfig); ?>
<script>
function showXuanClient()
{
    window.xuan.show();
}

var $chatBtn = $('<a href="javascript:void(0)" id="chatBtn" class="btn btn-link"><i class="text-primary icon icon-chat"></i></a>');
$chatBtn.insertBefore('#globalSearchDiv').on('click', showXuanClient);
window.xuan = new Xuanxuan($.extend(xuanConfig, {preload: true}));
</script>
<?php endif; ?>
