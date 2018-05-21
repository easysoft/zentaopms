<?php
/**
 * The view file of backup module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     backup
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if(!empty($error)):?>
<div id="notice" class='alert alert-success' style="margin-bottom:35px;">
  <div class="content"><i class='icon-exclamation-sign'></i> <?php echo $error;?></div>
</div>
<?php endif;?>

<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->backup->common;?></span></span>
  </div>
  <div class='btn-toolbar pull-right'><?php common::printIcon('backup', 'backup', 'reload=yes', '', 'button', 'cloud', 'hiddenwin', 'backup');?></div>
</div>

<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <?php echo $lang->backup->history?>
      <span class='label label-info'><?php echo $lang->backup->restoreTip;?></span>
    </h2>
    <div class='pull-right'>
      <?php printf($lang->backup->holdDays, $config->backup->holdDays)?>
      <?php if(common::hasPriv('backup', 'change')) echo html::a(inlink('change'), $lang->backup->changeAB, '', "class='iframe btn btn-sm btn-info' data-width='300'");?>
    </div>
  </div>
  <table class='table table-condensed table-bordered active-disabled table-fixed'>
    <thead>
      <tr>
        <th class='w-150px'><?php echo $lang->backup->time?></th>
        <th><?php echo $lang->backup->files?></th>
        <th class='w-150px'><?php echo $lang->backup->size?></th>
        <th class='w-110px'><?php echo $lang->actions?></th>
      </tr>
    </thead>
    <tbody class='text-center'>
    <?php foreach($backups as $backupFile):?>
      <?php $rowspan = count($backupFile->files);?>
      <?php $i = 0?>
      <?php foreach($backupFile->files as $file => $size):?>
      <tr>
        <?php if($i == 0):?>
        <td <?php if($rowspan > 1) echo "rowspan='$rowspan'"?>><?php echo date(DT_DATETIME1, $backupFile->time);?></td>
        <?php endif;?>
        <td class='text-left' style='padding-left:5px;'><?php echo $file;?></td>
        <td><?php echo $size / 1024 >= 1024 ? round($size / 1024 / 1024, 2) . 'MB' : round($size / 1024, 2) . 'KB';?></td>
        <?php if($i == 0):?>
        <td <?php if($rowspan > 1) echo "rowspan='$rowspan'"?>>
          <?php
          if(common::hasPriv('backup', 'restore')) echo html::a(inlink('restore', "file={$backupFile->name}&confirm=yes"), $lang->backup->restore, 'hiddenwin', "class='restore'");
          if(common::hasPriv('backup', 'delete')) echo html::a(inlink('delete', "file=$backupFile->name"), $lang->delete, 'hiddenwin');
          ?>
        </td>
        <?php endif;?>
      </tr>
      <?php $i++;?>
      <?php endforeach;?>
    <?php endforeach;?>
    </tbody>
  </table>
</div>
<div class="modal fade" id="waitting" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-300px">
    <div class="modal-content">
      <div class="modal-body"><?php echo $lang->backup->waitting?></div>
    </div>
  </div>
</div>
<?php js::set('backup', $lang->backup->backup);?>
<?php js::set('confirmRestore', $lang->backup->confirmRestore);?>
<?php js::set('restore', $lang->backup->restore);?>
<?php include '../../common/view/footer.html.php';?>
