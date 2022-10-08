<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'xuanxuan') != false): ?>
<style>
#appsBar, #apps {left: 0!important;}
</style>
<script>
$('html').addClass('xxc-embed');

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
$this->loadModel('im');
$xxdStatus = $this->im->getXxdStatus();
if(isset($this->config->xuanxuan->turnon) && $this->config->xuanxuan->turnon && $xxdStatus == 'online')
{
    $xuanConfig  = new stdclass();
    $token       = $this->im->userGetAuthToken($this->app->user->id, 'zentaoweb');
    $clientUrl   = isset($this->config->webClientUrl) ? $this->config->webClientUrl : 'data/xuanxuan/web/index.html';
    $backendUrl  = $this->im->getServer('zentao');

    $xuanConfig->clientUrl = $clientUrl;
    $xuanConfig->server    = ($this->config->xuanxuan->https == 'on' ? 'https' : 'http') . '://' . parse_url($backendUrl, PHP_URL_HOST) . ':' . $this->config->xuanxuan->commonPort;
    $xuanConfig->account   = $this->app->user->account;
    $xuanConfig->authKey   = $token->token;
    $xuanConfig->debug     = $this->config->debug;
}
?>
<?php if(isset($xuanConfig)): ?>
<style>
#chatBtn {padding: 3px; position: relative;}
#chatBtn .icon {font-size: 24px;}
#chatNoticeBadge {position: absolute; top: -4px; right: -2px; line-height: 14px; height: 14px; min-width: 14px; text-align: center; display: inline-block; font-size: 12px; border-radius: 7px; opacity: 0; transform: scale(0); transition: .2s; transition-property: transform, opacity; padding: 0 2px;}
#chatNoticeBadge.show {opacity: 1; transform: scale(1);}
#xx-embed-container {bottom: 40px!important; z-index: 1010!important;}
#xx-embed-container .xx-embed-has-animation {transition: min-width .5s ease-out, min-height .5s ease-out, transform, opacity!important;}
#xx-embed-container .xx-embed {width: 280px; height: 100%; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06)!important;}
#xx-embed-container .xx-embed.xx-embed-hidden,
#xx-embed-container .xx-embed.xx-embed-collapsed {width: 280px!important; height: 100%!important; opacity: .7; pointer-events: none; transform: translateY(100%); display: block!important;}
#xx-embed-container .xx-embed.has-chat-view {min-width: 1000px!important;}
#xx-embed-container .xx-embed-body {min-height: initial!important;}
</style>
<?php js::import($webRoot . 'data/xuanxuan/sdk/sdk.min.js'); ?>
<?php js::set('xuanConfig', $xuanConfig); ?>
<?php js::set('lang', $this->app->getClientLang()); ?>
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

/* Set client global options*/
Xuanxuan.setGlobalOptions(
{
    position:      'right',
    width:         280,
    preload:       true,
    showHeader:    false,
    onNotice:      handleXuanNoticeChange,
    onRouteChange: handleXuanRouteChange,
    lang:          lang
});

$(function() {
    /* Create chat button */
    var $chatBtn = $('<a href="javascript:void(0)" id="chatBtn" class="btn btn-link"><i class="text-primary icon icon-chat-solid"></i><span class="badge bg-red" id="chatNoticeBadge"></span></a>');
    $chatBtn.insertBefore('#globalSearchDiv').on('click', toggleXuanClient);
});

/* Create client instance */
window.xuan = new Xuanxuan(xuanConfig);

/* Hide xuan popover on click page */
$(document).on('click', function(e)
{
    if(!window.xuan.shown || $(e.target).closest('#xx-embed-container,#chatBtn').length) return;
    window.xuan.hide();
});
</script>
<?php endif; ?>
<?php endif; ?>
