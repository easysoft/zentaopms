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
<style>
  #chat-container {position: fixed; left: 96px; right: 0; height: calc(100% - 40px); display: none;}
  .menu-hide #chat-container {left: 40px;}
  #chat-switch {position: absolute; width: 330px; height: 52px; right: 0; background: #fff; display: flex; justify-content: center; align-items: center; border-bottom: 1px solid #eee; z-index: 20;}
  .chat-switch-bg {display: flex; justify-content: center; align-items: center; background-color: #eff5ff; border-radius: 16px;}
  .chat-switch-item {width: 96px; padding: 4px 0; border-radius: 16px; text-align: center; color: #838a9c; position: relative; user-select: none;}
  .chat-switch-item.active {font-weight: bold; color: #fff; background-color: #5999fc;}
  #xuan-chat-view {position: absolute; width: 100%; height: 100%; z-index: 10; display: none;}
  #xuan-chat-view #xx-embed-container {position: absolute; bottom: 0; left: 0; right: 0; top: 0;}
  #ai-chat-view {position: fixed; right: 0; width: 330px; bottom: 40px; top: 50px; outline: 1px solid #eee;}
  #ai-chat-frame {height: 100%; width: 100%;}
  .unconfigured {position: absolute; width: 330px; padding: 20px; right: 0; top: 50px; bottom: 0; background: #fff; outline: 1px solid #eee;}
</style>
<div id="chat-container">
  <div id="chat-switch">
    <div class="chat-switch-bg">
      <a class="chat-switch-item" data-value="chat"><?php echo $lang->index->chat->chat; ?></a>
      <a class="chat-switch-item active" data-value="ai"><?php echo $lang->index->chat->ai; ?></a>
    </div>
  </div>
  <div id="xuan-chat-view">
    <?php if(!isset($this->config->xuanxuan->turnon) || !$this->config->xuanxuan->turnon || $this->loadModel('im')->getXxdStatus() != 'online'): ?>
      <div class="unconfigured text-gray"><?php echo sprintf($lang->index->chat->unconfiguredFormat, $lang->index->chat->chat, (common::hasPriv('setting', 'xuanxuan') ? sprintf($lang->index->chat->goConfigureFormat, helper::createLink('setting', 'xuanxuan'), $lang->index->chat->chat) : $lang->index->chat->contactAdminForHelp)); ?></div>
    <?php endif; ?>
  </div>
  <div id="ai-chat-view">
    <?php if($this->loadModel('ai')->isModelConfigured() && commonModel::hasPriv('ai', 'chat')): ?>
      <iframe id="ai-chat-frame" src="<?php echo helper::createLink('ai', 'chat'); ?>" frameborder="no" allowtransparency="true" scrolling="auto" hidefocus></iframe>
    <?php else: ?>
      <div class="unconfigured text-gray"><?php echo sprintf($lang->index->chat->unconfiguredFormat, $lang->index->chat->ai, (common::hasPriv('ai', 'models') ? sprintf($lang->index->chat->goConfigureFormat, helper::createLink('ai', 'models'), $lang->index->chat->ai) : $lang->index->chat->contactAdminForHelp)); ?></div>
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

    /* Create another chat button */
    const $chatBtn = $('<a href="javascript:void(0)" id="chatBtn" class="btn btn-link"><i class="text-primary icon icon-chat-solid"></i><span class="badge bg-red" id="chatNoticeBadge"></span></a>');
    $chatBtn.insertBefore('#globalSearchDiv').on('click', function()
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
  });
</script>
