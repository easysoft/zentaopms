<?php
/**
 * The view view file of vm module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      xiawenlong <xiawenlong@cnezsoft.com>
 * @package     host
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->vm->confirmDelete)?>
<?php js::set('confirmBoot', $lang->vm->confirmBoot)?>
<?php js::set('confirmReboot', $lang->vm->confirmReboot)?>
<?php js::set('confirmShutdown', $lang->vm->confirmShutdown)?>
<?php js::set('actionSuccess', $lang->vm->actionSuccess)?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->vm->view;?></h2>
  </div>
  <div class='main'>
    <div class='detail'>
      <table class='table table-striped'>
        <tr>
          <th class='thWidth'><?php echo $lang->vm->name;?></th>
          <td><?php echo $vm->name;?></td>
          <th></th><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->vm->osType;?></th>
          <td><?php echo $config->vm->os->type[$vm->osCategory][$vm->osType];?></td>
          <th><?php echo $lang->vm->osCategory;?></th>
          <td><?php echo $config->vm->os->list[$vm->osCategory];?></td>
        </tr>
        <tr>
          <th><?php echo $lang->vm->osVersion;?></th>
          <td><?php echo $lang->vm->versionList[$vm->osType][$vm->osVersion];?></td>
          <th><?php echo $lang->vm->osLang;?></th>
          <td><?php echo $lang->vm->langList[$vm->osLang];?></td>
        </tr>
        <tr>
          <th><?php echo $lang->vm->ip;?></th>
          <td><?php echo $vm->ip;?></td>
          <th><?php echo $lang->vm->macAddress;?></th>
          <td><?php echo $vm->macAddress;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->vm->vnc;?></th>
          <td><?php echo $vm->vncPort;?></td>
          <th><?php echo $lang->vm->status;?></th>
          <td><?php echo zget($lang->vm->statusList, $vm->status);?></td>
        </tr>
        <tr>
          <th><?php echo $lang->vm->createdDate;?></th>
          <td><?php echo $vm->createdDate;?></td>
          <th><?php echo $lang->vm->destroyAt;?></th>
          <td><?php echo $vm->destroyAt;?></td>
        </tr>
      </table>
    </div>
    <?php include $app->getModuleRoot() . 'common/view/action.html.php'?>
  </div>
  <div id='mainActions' class='main-actions'>
    <nav class='container'></nav>
    <div class='btn-toolbar'>
      <?php
      if(empty($vm->destroyAt))
      {
          common::printLink('vm', 'reboot', "vmID={$vm->id}", "<i class='icon icon-restart'></i> ". $lang->vm->reboot, '', "title='{$lang->vm->reboot}' class='btn' target='hiddenwin' onclick='if(confirm(\"{$lang->vm->confirmReboot}\")==false) return false;'");
          common::printLink('vm', 'destroy', "vmID={$vm->id}", "<i class='icon icon-trash'></i> " . $lang->vm->destroy, '', "title='{$lang->vm->destroy}' class='btn' target='hiddenwin' onclick='if(confirm(\"{$lang->vm->confirmDelete}\")==false) return false;'");
      }
      if(!isonlybody()) common::printLink('vm', 'browse', "", "<i class='icon-goback icon-back'></i> " . $lang->goback, '', "class='btn'");
      ?>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
