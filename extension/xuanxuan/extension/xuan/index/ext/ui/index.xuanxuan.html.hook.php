<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'xuanxuan') != false): ?>
<?php global $app; $app->setClientTheme('blue'); ?>
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
<?php else: ?>
<?php
global $app;
$im        = $app->control->loadModel('im');
$xxdStatus = $im->getXxdStatus();
if(isset($app->config->xuanxuan->turnon) && $app->config->xuanxuan->turnon && $xxdStatus == 'online')
{
    $xuanConfig  = new stdclass();
    $token       = $im->userGetAuthToken($app->user->id, 'zentaoweb');
    $clientUrl   = isset($app->config->webClientUrl) ? $app->config->webClientUrl : 'data/xuanxuan/web/index.html';
    $backendUrl  = $im->getServer('zentao');

    $xuanConfig->clientUrl = $clientUrl;
    $xuanConfig->server    = ($app->config->xuanxuan->https == 'on' ? 'https' : 'http') . '://' . parse_url($backendUrl, PHP_URL_HOST) . ':' . $app->config->xuanxuan->commonPort;
    $xuanConfig->account   = $app->user->account;
    $xuanConfig->authKey   = $token->token;
    $xuanConfig->debug     = $app->config->debug;
}
?>
<?php if(isset($xuanConfig)): ?>
<style>
#xx-embed-container {z-index: 1010!important;}
#xx-embed-container .xx-embed-has-animation {transition: min-width .5s ease-out, min-height .5s ease-out, transform, opacity!important;}
#xx-embed-container .xx-embed {width: 330px; height: 100%; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06)!important;}
#xx-embed-container .xx-embed.xx-embed-hidden,
#xx-embed-container .xx-embed.xx-embed-collapsed {width: 330px!important; height: 100%!important; opacity: .7; pointer-events: none; transform: translateY(100%); display: block!important;}
#xx-embed-container .xx-embed.has-chat-view {min-width: 1000px!important;}
#xx-embed-container .xx-embed-body {min-height: initial!important;}
</style>
<?php js::import($app->getWebRoot() . 'data/xuanxuan/sdk/sdk.min.js'); ?>
<script>
/* Toggle xuan client popover */
function toggleXuanClient()
{
    if(!window.xuan.shown) window.xuan.show();
    else window.xuan.toggleCollapse();
}

/* Handle chat notice change */
function handleXuanNoticeChange(notice)
{
    $('#chatNoticeBadge').toggleClass('show', !!notice.count).text(notice.count);
}

/* Handle client route change */
function handleXuanRouteChange(route)
{
    var hasShowChatView = route.indexOf('#/chats/') === 0 && !!route.split('/')[3];
    $('#' + window.xuan.id).toggleClass('has-chat-view', hasShowChatView);
}

$(function()
{
    const xuanConfig = <?php echo json_encode($xuanConfig); ?>;
    if(typeof Xuanxuan !== 'undefined')
    {
        /* Set client global options*/
        Xuanxuan.setGlobalOptions(
        {
            position:      'right',
            width:         280,
            preload:       true,
            showHeader:    false,
            onNotice:      handleXuanNoticeChange,
            onRouteChange: handleXuanRouteChange,
            lang:          '<?php echo $app->getClientLang()?>'
        });

        /* Create client instance */
        window.xuan = new Xuanxuan(xuanConfig);

        /* Setup xuan web chat, see zin/wg/chatbtn. */
        window.setupXuan();
    }
})
</script>
<?php endif; ?>
<?php endif; ?>
