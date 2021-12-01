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

$(document).on('showapp', function(e, app)
{
    window.open('xxc://extension.zentao-integrated.activeAppMenuItem/' + app.code);
});
</script>
<?php endif; ?>

<?php if(isset($xuanConfig)): ?>
<style>
#chatBtn {padding: 3px; position: relative;}
#chatBtn .icon {font-size: 24px;}
#chatNoticeBadge {position: absolute; top: -4px; right: -2px; line-height: 14px; height: 14px; width: 14px; text-align: center; display: block; font-size: 12px; border-radius: 50%; opacity: 0; transform: scale(0); transition: .2s; transition-property: transform, opacity;}
#chatNoticeBadge.show {opacity: 1; transform: scale(1);}
#xx-embed-container {bottom: 40px!important; z-index: 1010!important;}
#xx-embed-container .xx-embed-has-animation {transition-property: transform, opacity!important;}
#xx-embed-container .xx-embed {width: 280px; height: 100%;}
#xx-embed-container .xx-embed.xx-embed-collapsed {width: 280px!important; height: 100%!important; opacity: 0; pointer-events: none; transform: translateY(100%);}
</style>
<?php js::import($webRoot . 'data/xuanxuan/sdk/sdk.min.js'); ?>
<?php js::import($jsRoot . 'md5.js'); ?>
<?php js::set('xuanConfig', $xuanConfig); ?>
<script>
function showXuanClient()
{
    if(!window.xuan.shown) window.xuan.show();
    else window.xuan.toggleCollapse();
}

function handleXuanNoticeChange(notice)
{
    $('#chatNoticeBadge').toggleClass('show', !!notice.count).text(notice.count);
}

Xuanxuan.setGlobalOptions(
{
    position:   'right',
    width:      280,
    preload:    true,
    showHeader: false,
    onNotice:   handleXuanNoticeChange
});

var $chatBtn = $('<a href="javascript:void(0)" id="chatBtn" class="btn btn-link"><i class="text-primary icon icon-chat"></i><span class="badge bg-red" id="chatNoticeBadge"></span></a>');
$chatBtn.insertBefore('#globalSearchDiv').on('click', showXuanClient);
window.xuan = new Xuanxuan(xuanConfig);
</script>
<?php endif; ?>
