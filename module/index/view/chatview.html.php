<?php
/**
 * Chat container view of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     index
 * @link        https://www.zentao.net
 */
?>
<?php
  $isXuanAvailable = isset($this->config->xuanxuan->turnon) && $this->config->xuanxuan->turnon && $this->loadModel('im') && $this->im->getXxdStatus() == 'online';
  $isAIConfigured  = $this->loadModel('ai')->isModelConfigured();
  $hasAIChatPriv   = commonModel::hasPriv('ai', 'chat');
  js::set('isXuanAvailable', $isXuanAvailable);
  js::set('isAIConfigured', $isAIConfigured);
  js::set('hasAIChatPriv', $hasAIChatPriv);
?>
<style>
  #chat-container {position: fixed; left: 96px; right: 0; height: calc(100% - 40px); display: none;}
  .menu-hide #chat-container {left: 40px;}
  #chat-switch {position: absolute; width: 330px; height: 52px; right: 0; background: #fff; display: flex; justify-content: center; align-items: center; border-bottom: 1px solid #eee; z-index: 20;}
  .chat-switch-bg {display: flex; justify-content: center; align-items: center; background-color: #eff5ff; border-radius: 16px;}
  .chat-switch-item {width: 96px; padding: 4px 0; border-radius: 16px; text-align: center; color: #838a9c; position: relative; user-select: none;}
  .chat-switch-item:hover {color: #838a9c;}
  .chat-switch-item.active {font-weight: bold; color: #fff; background-color: #5999fc;}
  .chat-switch-item.has-notice::after {content: ''; position: absolute; right: 26px; top: 4px; width: 6px; height: 6px; border-radius: 50%; background-color: #ff535d;}
  #xuan-chat-view {position: absolute; width: 100%; height: 100%; z-index: 10; display: none;}
  #xuan-chat-view #xx-embed-container {position: absolute; bottom: 0; left: 0; right: 0; top: 0;}
  #ai-chat-view {position: fixed; right: 0; width: 330px; bottom: 40px; top: 50px; outline: 1px solid #eee;}
  #ai-chat-frame {height: 100%; width: 100%;}
  .unconfigured {position: absolute; width: 330px; padding: 20px; right: 0; top: 0; bottom: 0; background: #fff; outline: 1px solid #eee;}
  .unconfigured > div {margin-bottom: 10px;}
  #xuan-chat-view .unconfigured {top: 50px;}
  #reload-ai-chat.disabled {cursor: wait; color: #999!important;}
</style>
<div id="chat-container">
  <div id="chat-switch">
    <div class="chat-switch-bg">
      <a class="chat-switch-item" data-value="chat"><?php echo $lang->index->chat->chat; ?></a>
      <a class="chat-switch-item active" data-value="ai"><?php echo $lang->index->chat->ai; ?></a>
    </div>
  </div>
  <div id="xuan-chat-view">
    <?php if(!$isXuanAvailable): ?>
      <div class="unconfigured text-gray"><?php echo sprintf($lang->index->chat->unconfiguredFormat, $lang->index->chat->chat, (common::hasPriv('setting', 'xuanxuan') ? sprintf($lang->index->chat->goConfigureFormat, helper::createLink('setting', 'xuanxuan'), $lang->index->chat->chat) : $lang->index->chat->contactAdminForHelp)); ?></div>
    <?php endif; ?>
  </div>
  <div id="ai-chat-view" class="ai-chat-view">
    <?php if(!$isAIConfigured || !$hasAIChatPriv): ?>
      <div class="unconfigured text-gray">
        <div>
          <?php if(!$isAIConfigured): ?>
            <?php echo sprintf($lang->index->chat->unconfiguredFormat, $lang->index->chat->ai, (common::hasPriv('ai', 'models') ? sprintf($lang->index->chat->goConfigureFormat, helper::createLink('ai', 'models') . '#app=admin', $lang->index->chat->ai) : $lang->index->chat->contactAdminForHelp)); ?>
          <?php elseif(!$hasAIChatPriv): ?>
            <?php echo $lang->index->chat->unauthorized; ?>
          <?php endif; ?>
          </div>
        <div><?php echo $lang->index->chat->reloadTip; ?></div>
      </div>
    <?php else: ?>
      <iframe id="ai-chat-frame" src="<?php echo helper::createLink('ai', 'chat'); ?>" frameborder="no" allowtransparency="true" scrolling="auto" hidefocus></iframe>
    <?php endif; ?>
  </div>
</div>
<script>
  $(function()
  {
    /* Move xuan web client into #xuan-chat-view. */
    if(window.xuan)
    {
      document.querySelector('#xuan-chat-view').prepend(document.querySelector('#xx-embed-container'));

      /* Update badge on chat. */
      window.xuan._options.onNotice = function(notice)
      {
        window.handleXuanNoticeChange(notice);
        $('.chat-switch-item[data-value="chat"]').toggleClass('has-notice', !!notice.count);
      };

      /* Set style into xuan frame. */
      let tries = 0;
      const xuanFrameWaitLoop = setInterval(function()
      {
        if(document.querySelector('#xx-embed-container iframe'))
        {
          clearInterval(xuanFrameWaitLoop);
          tries = 0;
          const chatsViewWaitLoop = setInterval(function()
          {
            if(document.querySelector('#xx-embed-container iframe').contentDocument.querySelector('.app-chats-menu'))
            {
              clearInterval(chatsViewWaitLoop);
              document.querySelector('#xx-embed-container iframe').contentDocument.querySelector('.app-chats-menu').style.cssText = 'width: 330px !important; top: 48px;';
              document.querySelector('#xx-embed-container iframe').contentDocument.querySelector('.app-chats-cache').style.cssText = 'right: 330px !important;';
              document.querySelector('#xx-embed-container iframe').contentDocument.querySelector('.app-chats-menu-search').style.cssText = 'padding: 10px 8px !important;';
              document.querySelector('#xx-embed-container iframe').contentDocument.querySelector('.app-chats-menu-search .btn:first-child').style.cssText = 'display: none;';
            }
            else
            {
              if(++tries > 20) clearInterval(chatsViewWaitLoop);
            }
          }, 200);
        }
        else
        {
          if(++tries > 20) clearInterval(xuanFrameWaitLoop);
        }
      }, 200);
    }

    /* Create ai chat button */
    const $chatBtn = $('<a href="javascript:void(0)" id="chatBtn" class="btn btn-link"><span class="badge bg-red" id="chatNoticeBadge"></span></a>');
    const $aiChatIcon = $('<svg xmlns="http://www.w3.org/2000/svg" width="28" height="20"><defs><linearGradient id="a" x1="-130%" x2="135%" y1="136.25%" y2="8.75%"><stop offset="0%" stop-color="#2E7FFF"/><stop offset="100%" stop-color="#5DBDFF"/></linearGradient><linearGradient id="b" x1="-11.364%" x2="100.45%" y1="88.636%" y2="9.99%"><stop offset="0%" stop-color="#2E7FFF"/><stop offset="100%" stop-color="#6CDCFF"/></linearGradient><linearGradient id="c" x1="-847.379%" x2="115.023%" y1="460.717%" y2="-128.435%"><stop offset="0%" stop-color="#2E7FFF"/><stop offset="100%" stop-color="#6CDCFF"/></linearGradient></defs><g fill="none" fill-rule="evenodd"><path fill="url(#a)" d="M24.281 7.75h1.172L22.778.433a.279.279 0 0 0-.262-.183H20.99a.279.279 0 0 0-.262.181L18 7.75h1.16a.837.837 0 0 0 .795-.577l.373-1.142h2.777l.382 1.146a.837.837 0 0 0 .794.573Zm-2.428-5.554.868 2.6h-2.038l.874-2.619a2.73 2.73 0 0 0 .133-.634h.044c.023.27.063.489.118.653h.001ZM28 6.913V.25h-1.628v7.5h.791A.837.837 0 0 0 28 6.913Z"/><path fill="url(#b)" d="M18.849 3.239A4.57 4.57 0 0 0 17.387 3H4.613C2.07 3 0 5.08 0 7.636v10.105c0 .773.355 1.486 1.005 1.902.355.238.77.357 1.183.357.355 0 .65-.119.946-.238l3.963-1.783a9.711 9.711 0 0 1 4.08-.892h6.21c2.543 0 4.613-2.08 4.613-4.636V7.636c0-.28-.025-.556-.073-.823h-1.032l-.198.603a1.618 1.618 0 0 1-1.538 1.115H18a.781.781 0 0 1-.732-1.054l1.58-4.238h.001Zm-13.64 7.47a1.015 1.015 0 0 1 .544-1.32 1 1 0 0 1 1.314.547 1.015 1.015 0 0 1-.544 1.32 1 1 0 0 1-1.314-.546v-.001Zm4.516 0a1.015 1.015 0 0 1 .544-1.32 1.001 1.001 0 0 1 1.314.547 1.016 1.016 0 0 1-.544 1.32 1 1 0 0 1-1.314-.546v-.001Zm4.94-.386a1.005 1.005 0 0 0 2.01 0c0-.268-.106-.525-.295-.715a1.003 1.003 0 0 0-1.716.715h.001Z"/><path fill="url(#c)" fill-rule="nonzero" d="M19.16 7.75a.837.837 0 0 0 .795-.577l.373-1.142h1.387-1.387l-.373 1.142a.837.837 0 0 1-.796.577h.001Z"/></g></svg>');
    $chatBtn.prepend($aiChatIcon).insertBefore('#globalSearchDiv').on('click', function()
    {
      /* Make sure xuan is shown. */
      if(window.xuan) window.xuan[window.xuan.shown ? 'expand' : 'show']();
      $('#chat-container').toggle();
    });

    /* Handle switch events. */
    $('#chat-switch .chat-switch-item').on('click', function()
    {
      $(this).addClass('active').siblings().removeClass('active');
      $('#ai-chat-view').toggle($(this).data('value') == 'ai');
      $('#xuan-chat-view').toggle($(this).data('value') == 'chat');
    });

    /* Switch to xuan chat view if AI is unavailable. */
    if(isXuanAvailable && !(isAIConfigured && hasAIChatPriv)) $('#chat-switch .chat-switch-item[data-value="chat"]').trigger('click');

    /* Handle backdrop click, hide chat view. */
    $(document).on('click', function(e)
    {
      if($('#chat-container').is(':hidden')) return;
      if($(e.target).closest('#chat-switch,#ai-chat-view,#xx-embed,#chatBtn').length) return;
      $('#chat-container').hide();
    });

    const registerUnconfiguredClickHandlers = function()
    {
      /* Handle configure link click, use $.apps.open() instead. */
      $('.configure-chat-button').click(function(e)
      {
        e.preventDefault();
        $.apps.open($(this).attr('href'));
      });

      /* Handle AI chat reload. */
      $('#reload-ai-chat').click(function(e)
      {
        e.preventDefault();
        if($(this).hasClass('disabled')) return;
        $(this).addClass('disabled');

        setTimeout(function()
        {
          $.ajax({
            url: createLink('index', 'index'),
            dataType: 'html',
            success: function(response)
            {
              const $indexView = $($.parseHTML(response));
              const $chatContainer = $indexView.filter('#chat-container');
              if(!$chatContainer.length) return;
              const $aiChatView = $chatContainer.children().filter('#ai-chat-view');
              if(!$aiChatView.length) return;
              $('#ai-chat-view').html($aiChatView.html());
              registerUnconfiguredClickHandlers();
            }
          });
        }, 1000);
      });
    }
    registerUnconfiguredClickHandlers();
  });
</script>
