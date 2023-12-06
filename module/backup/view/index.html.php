<?php
/**
 * The view file of backup module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     backup
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if(!empty($error)):?>
<div id="notice" class='alert alert-success' style="margin-bottom:35px;">
  <div class="content"><i class='icon-exclamation-sign'></i> <?php echo $error;?></div>
</div>
<?php endif;?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'><?php // common::printAdminSubMenu('system');?></div>
</div>
<div id='mainContent' class="main-row">
  <div class="main-col main-content">
    <div class='main-header'>
      <h2>
        <?php echo $lang->backup->history?>
      </h2>
      <div class='pull-right'>
        <?php common::printLink('backup', 'setting', '', "<i class='icon icon-cog'></i> " . $lang->backup->setting, '', "data-width='500' class='iframe btn btn-primary'");?>
        <?php
        if(common::hasPriv('backup', 'backup'))
        {
            $backupLink = helper::createLink('backup', 'backup', 'reload=yes');
            echo html::commonButton("<i class='icon icon-copy'> </i>" . $lang->backup->backup, "data-link='{$backupLink}'", 'btn btn-primary backup load-indicator');
        }
        ?>
      </div>
    </div>
    <table class='table table-condensed table-bordered active-disabled table-fixed'>
      <thead class="text-center">
        <tr>
          <th class='w-150px'><?php echo $lang->backup->time?></th>
          <th><?php echo $lang->backup->files?></th>
          <th class='w-100px'><?php echo $lang->backup->allCount?></th>
          <th class='w-100px'><?php echo $lang->backup->count?></th>
          <th class='w-100px'><?php echo $lang->backup->size?></th>
          <th class='w-100px'><?php echo $lang->backup->status?></th>
          <th class='actionWidth'><?php echo $lang->actions?></th>
        </tr>
      </thead>
      <tbody class='text-center'>
      <?php foreach($backups as $backupFile):?>
        <?php $rowspan = count($backupFile->files);?>
        <?php $i = 0;?>
        <?php $isPHP = false;?>
        <?php foreach($backupFile->files as $file => $summary):?>
        <?php if(!$isPHP) $isPHP = strpos($file, '.php') !== false;?>
        <tr>
          <?php if($i == 0):?>
          <td <?php if($rowspan > 1) echo "rowspan='$rowspan'"?>><?php echo date(DT_DATETIME1, $backupFile->time);?></td>
          <?php endif;?>
          <td title=<?php echo $file;?> class='text-left' style='padding-left:5px;'><?php echo $file;?></td>
          <td><?php echo zget($summary, 'allCount', '');?></td>
          <td><?php echo zget($summary, 'count', '');?></td>
          <td>
            <?php
            $size = zget($summary, 'size', 0);
            if(!empty($size)) echo $this->backup->processFileSize($size);
            ?>
          </td>
          <td style='overflow:visible'>
            <?php
            $status = zget($summary, 'count', 0) == zget($summary, 'allCount', 0) ? 'success' : 'fail';
            if(!empty($summary['errorFiles'])) $status = 'fail';
            if(!empty($summary['errorFiles'])):
            ?>
            <div class="dropdown dropdown-hover"><?php echo $lang->backup->statusList[$status];?> <span class="caret"></span><div class="dropdown-menu pull-right errorFiles"><?php echo $lang->backup->copiedFail . '<br />' . join('<br />', $summary['errorFiles']);?></div></div>
            <?php else:?>
            <?php echo $lang->backup->statusList[$status];?>
            <?php endif;?>
          </td>
          <?php if($i == 0):?>
          <td <?php if($rowspan > 1) echo "rowspan='$rowspan'"?>>
            <?php
            if(common::hasPriv('backup', 'rmPHPHeader') and $isPHP)
            {
                echo html::a(inlink('rmPHPHeader', "file={$backupFile->name}"), $lang->backup->rmPHPHeader, 'hiddenwin', "class='rmPHPHeader'");
                echo "<br />";
            }
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
    <h2>
      <span class='label label-info'>
      <?php echo $lang->backup->restoreTip;?>
      <?php printf($lang->backup->holdDays, $config->backup->holdDays)?>
      </span>
    </h2>
  </div>
</div>
<div class="modal fade" id="waitting" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-400px">
    <div class="modal-content">
      <div class="modal-body">
        <p><?php echo $lang->backup->waitting?></p>
        <div id='message'><?php echo sprintf($lang->backup->progressSQL, 0);?></div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="spaceConfirm" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body">
        <p></p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang->backup->cancelBackup;?></button>
      </div>
    </div>
  </div>
</div>
<?php js::set('backup', $lang->backup->common);?>
<?php js::set('startBackup', $lang->backup->backup);?>
<?php js::set('getSpaceLoading', $lang->backup->getSpaceLoading);?>
<?php js::set('rmPHPHeader', $lang->backup->rmPHPHeader);?>
<?php js::set('confirmRestore', $lang->backup->confirmRestore);?>
<?php js::set('restore', $lang->backup->restore);?>
<?php js::set('backupTimeout', $lang->backup->error->timeout);?>
<?php js::set('alertTips', $lang->backup->insufficientDisk);?>
<?php js::set('backupError', empty($backupError) ? '' : $backupError);?>
<?php include '../../common/view/footer.html.php';?>
