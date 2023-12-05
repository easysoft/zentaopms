<?php
/**
 * The install SMTP view file of system module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php js::set('instanceNotices', $lang->instance->notices);?>
<?php js::set('instanceIdList', array($smtpInstance->id));?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->createLink('system', 'index'), "<i class='icon icon-back icon-sm'></i>" . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='main-header'>
      <h2><?php echo $lang->system->SMTP->common;?></h2>
      <div class='smtp-button-group btn-toolbar pull-right'>
        <?php $this->system->printSMTPButtons($smtpInstance);?>
      </div>
    </div>
    <table class='table table-form instance-status' instance-id='<?php echo $smtpInstance->id;?>' data-status='<?php echo $smtpInstance->status;?>'>
      <tbody>
        <tr>
          <th><?php echo $lang->system->SMTP->account;?></th>
          <td><?php echo zget($smtpSettings, 'SMTP_USER', '');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->SMTP->password;?></th>
          <td>
            <div class='w-250px input-group'>
              <?php echo html::password('smtp_password', zget($smtpSettings, 'SMTP_PASS', ''), "readonly class='form-control'");?>
              <span class='input-group-addon'><button id='smtpPassBtn'><i class='icon icon-eye-off'></i></button></span>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->system->SMTP->host;?></th>
          <td><?php echo zget($smtpSettings, 'SMTP_HOST', '');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->SMTP->port;?></th>
          <td><?php echo zget($smtpSettings, 'SMTP_PORT', '');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->instance->status;?></th>
          <td><span><?php echo zget($lang->instance->statusList, $smtpInstance->status, '');?></span></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
