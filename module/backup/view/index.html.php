<?php
/**
 * The view file of backup module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2014 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     backup
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if(!empty($error)):?>
<div id="notice" class='alert alert-success'>
  <div class="content"><i class='icon-info-sign'></i> <?php echo $error;?></div>
</div>
<?php endif;?>

<div id='titlebar'>
  <div class='heading'><?php echo $lang->backup->common;?></div>
  <div class='actions'><?php common::printIcon('backup', 'backup', '', '', 'button', 'cloud', 'hiddenwin', 'backup');?></div>
</div>

<div class='panel'>
  <div class='panel-heading'><strong><?php echo $lang->backup->history?></strong></div>
  <table class='table table-condensed table-bordered active-disabled table-fixed'>
    <thead>
      <tr>
        <th class='w-150px'><?php echo $lang->backup->time?></th>
        <th><?php echo $lang->backup->files?></th>
        <th class='w-150px'><?php echo $lang->backup->size?></th>
        <th class='w-80px'><?php echo $lang->actions?></th>
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
          common::printIcon('backup', 'restore', "file=$backupFile->name", '', 'list', 'repeat', 'hiddenwin', 'restore');
          common::printIcon('backup', 'delete', "file=$backupFile->name", '', 'list', '', 'hiddenwin');
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
<?php js::set('restore', $lang->backup->restore);?>
<?php include '../../common/view/footer.html.php';?>
