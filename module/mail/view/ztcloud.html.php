<?php
/**
 * The ztcloud view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . 'message/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-800px'>
    <?php if($step == 'license'):?>
    <div class='main-header'>
      <h2><?php echo $lang->mail->license;?></h2>
    </div>
    <div class='content' style='padding:20px;'>
      <div class='license'><?php echo$lang->mail->ztCloudNotice;?></div>
      <p>
        <?php echo html::a("javascript:agreeLicense()", $lang->mail->agreeLicense, '', "class='btn'");?>
        <?php echo html::a(inlink('index'), $lang->mail->disagree, '', "class='btn'");?>
      </p>
    </div>
    <?php else:?>
    <div class='main-header'>
      <h2>
        <?php echo $lang->mail->common;?>
        <small class='text-muted'> <?php echo $lang->arrow . $lang->mail->edit;?></small>
      </h2>
    </div>
    <form method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->mail->turnon; ?></th>
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
          <td><?php echo html::input('domain', zget($config->mail, 'domain', common::getSysURL()), "class='form-control'")?></td>
        </tr>
        <tr>
          <th><?php echo $lang->mail->fromAddress; ?></th>
          <td><?php echo html::input('fromAddress', $mailConfig->fromAddress, "class='form-control'");?></td>
          <td><?php echo $lang->mail->addressWhiteList?></td>
        </tr>
        <tr>
          <th><?php echo $lang->mail->fromName; ?></th>
          <td>
            <div class='required'></div>
            <?php echo html::input('fromName', $mailConfig->fromName, "class='form-control'");?>
          </td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php
            if($config->mail->turnon and $mailExist) echo html::a(inlink('test'), $lang->mail->test, '', "class='btn btn-wide'");
            echo html::a(inlink('reset'), $lang->mail->reset, '', "class='btn btn-wide'");
            if(common::hasPriv('mail', 'browse') and !empty($config->mail->async) and !empty($config->global->cron)) echo html::a(inlink('browse'), $lang->mail->browse, '', "class='btn btn-wide'");
            ?>
          </td>
        </tr>
      </table>
    </form>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
