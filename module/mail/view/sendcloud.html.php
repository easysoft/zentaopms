<?php
/**
 * The sendcloud view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <wwccss@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
include '../../common/view/header.html.php';
?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['mail']);?></span>
      <strong><?php echo $lang->mail->common;?></strong>
      <small class='text-muted'> <?php echo $lang->mail->edit;?> <?php echo html::icon('pencil');?></small>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'>
      <tr>
        <th class='rowhead w-120px'><?php echo $lang->mail->turnon; ?></th>
        <td><?php echo html::radio('turnon', $lang->mail->turnonList, isset($mailConfig->turnon) ? $mailConfig->turnon : 1);?></td>
      </tr>
      <?php if(!empty($config->global->cron)):?>
      <tr>
        <th class='text-top'><?php echo $lang->mail->async?></th>
        <td><?php echo html::radio('async', $lang->mail->asyncList, zget($config->mail, 'async', 0))?></td>
      </tr>
      <?php endif;?>
      <tr>
      <tr>
        <th><?php echo $lang->mail->domain?></th>
        <td><?php echo html::input('domain', zget($config->mail, 'domain', common::getSysURL()), "class='form-control' autocomplete='off'")?></td>
      </tr>
        <th><?php echo $lang->mail->accessKey; ?></th>
        <td>
          <div class='required required-wrapper'></div>
          <?php echo html::input('accessKey', isset($mailConfig->accessKey) ? $mailConfig->accessKey : '', "class='form-control' autocomplete='off'");?>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->mail->secretKey; ?></th>
        <td>
          <div class='required required-wrapper'></div>
          <?php echo html::input('secretKey', isset($mailConfig->secretKey) ? $mailConfig->secretKey : '', "class='form-control' autocomplete='off'");?>
        </td>
      </tr>
      <tr>
         <td colspan='2' class='text-center'>
           <?php 
           echo html::submitButton();
           if($this->config->mail->turnon and $mailExist) echo html::linkButton($lang->mail->test, inlink('test'));
           echo html::linkButton($lang->mail->closeSendCloud, inlink('reset'));
           if($this->config->mail->turnon and common::hasPriv('mail', 'sendcloudUser')) echo html::linkButton($lang->mail->sendcloudUser, inlink('sendcloudUser'));
           if(common::hasPriv('mail', 'browse') and !empty($config->mail->async) and !empty($config->global->cron)) echo html::linkButton($lang->mail->browse, inlink('browse'));
           ?>
         </td>
       </tr>
    </table>
  </form>
  <div class='alert alert-info alert-block' style='border-top:1px solid #ddd'><?php printf($lang->mail->sendCloudHelp, common::hasPriv('mail', 'sendcloudUser') ? inlink('sendcloudUser') : '#')?></div>
</div>
<?php include '../../common/view/footer.html.php';?>
