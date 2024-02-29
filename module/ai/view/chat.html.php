<?php
/**
 * Chat view of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class="chat">
  <div class="messages">
  <?php
  if(!empty($error))
  {
    echo "<div class='message-container-error'><div class='message-content'>$error</div></div>";
  }
  $shownMessages = array_reverse(array_filter($messages, function($message) { return $message->role != 'system'; }));
  if(!empty($shownMessages))
  {
    foreach($shownMessages as $message)
    {
      if($message->role != 'system') echo "<div class='message-container-$message->role'><div class='message-content'>" . htmlspecialchars($message->content) . '</div></div>';
    }
  }
  else
  {
    echo "<div class='message-container-assistant'><div class='message-content'>{$lang->ai->chatPlaceholderMessage}</div></div>";
  }
  ?>
  </div>
  <form method="post">
    <div class="chat-input">
      <textarea name="message" placeholder="<?php echo $lang->ai->chatPlaceholderInput; ?>" autocomplete="off"></textarea>
      <input type="submit" value class="disabled" disabled />
      <input type="button" id="reset" <?php if(empty($shownMessages)) echo 'class="disabled"'; ?> value="<?php echo $lang->ai->chatReset; ?>" />
    </div>
    <input type="hidden" name="history" value="<?php echo htmlspecialchars(json_encode($messages)); ?>" />
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
