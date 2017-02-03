<?php
/**
 * The ztcloud view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-800px'>
<?php if($step == 'license'):?>
  <div id='titlebar'>
    <div class='heading'><strong><?php echo $lang->mail->license;?></strong></div>
  </div>
  <div class='content' style='padding:20px;'>
    <div class='license'><?php echo$lang->mail->ztCloudNotice;?></div>
    <p>
      <?php echo html::a("javascript:agreeLicense()", $lang->mail->agreeLicense, '', "class='btn'");?>
      <?php echo html::a(inlink('index'), $lang->mail->disagree, '', "class='btn'");?>
    </p>
  </div>
<?php else:?>
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
        <td class='w-250px'><?php echo html::radio('turnon', $lang->mail->turnonList, isset($mailConfig->turnon) ? $mailConfig->turnon : 1);?></td>
      </tr>
      <?php if(!empty($config->global->cron)):?>
      <tr>
        <th class='text-top'><?php echo $lang->mail->async?></th>
        <td><?php echo html::radio('async', $lang->mail->asyncList, zget($config->mail, 'async', 0))?></td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->mail->domain?></th>
        <td><?php echo html::input('domain', zget($config->mail, 'domain', common::getSysURL()), "class='form-control' autocomplete='off'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->mail->fromAddress; ?></th>
        <td><?php echo html::input('fromAddress', $mailConfig->fromAddress, "class='form-control' autocomplete='off'");?></td>
        <td colspan='2'><?php echo $lang->mail->addressWhiteList?></td>
      </tr>
      <tr>
        <th><?php echo $lang->mail->fromName; ?></th>
        <td colspan='3'>
          <div class='required required-wrapper'></div>
          <?php echo html::input('fromName', $mailConfig->fromName, "class='form-control' autocomplete='off'");?>
        </td>
      </tr>
      <tr>
         <td colspan='2' class='text-center'>
           <?php 
           echo html::submitButton();
           if($this->config->mail->turnon and $mailExist) echo html::linkButton($lang->mail->test, inlink('test'));
           echo html::linkButton($lang->mail->reset, inlink('reset'));
           if(common::hasPriv('mail', 'browse') and !empty($config->mail->async) and !empty($config->global->cron)) echo html::linkButton($lang->mail->browse, inlink('browse'));
           ?>
         </td>
       </tr>
    </table>
  </form>
<?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>

