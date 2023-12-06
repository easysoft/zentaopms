<?php
/**
 * The task block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php if(empty($tasks)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.block-tasks .c-id {width: 55px;}
.block-tasks .c-pri {width: 45px;text-align: center;}
.block-tasks .c-pri-long {width: 80px;}
.block-tasks .c-estimate {width: 60px;text-align: right;}
.block-tasks .c-deadline {width: 95px;}
.block-tasks .c-status {width: 80px;}
.block-tasks.block-sm .c-status {text-align: center;}
</style>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter block-tasks <?php if(!$longBlock) echo 'block-sm';?>'>
    <thead>
      <tr>
        <th class='c-id'><?php echo $lang->idAB;?></th>
        <th class='c-name'> <?php echo $lang->task->name;?></th>
        <th class='c-pri <?php if($longBlock) echo "c-pri-long"?>'><?php echo $lang->priAB?></th>
        <th class='c-status'><?php echo $lang->statusAB;?></th>
        <?php if($longBlock):?>
        <th class='c-deadline'><?php echo $lang->task->deadlineAB;?></th>
        <th class='c-estimate'><?php echo $lang->task->estimateAB;?></th>
        <?php endif;?>
      </tr>
    </thead>
    <tbody>
      <?php foreach($tasks as $task):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      ?>
      <tr>
        <td class='c-id-xs'><?php echo sprintf('%03d', $task->id);?></td>
        <?php
        $onlybody = $task->executionType == 'kanban' ? true : '';
        $class    = $task->executionType == 'kanban' ? "class='iframe' data-toggle='modal'" : '';
        ?>
        <td class='c-name' style='color: <?php echo $task->color?>' title='<?php echo $task->name?>'><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id", '', $onlybody, $task->project), $task->name, null, "$class data-width='80%'")?></td>
        <td class='c-pri <?php if($longBlock) echo "c-pri-long"?>'><span class='label-pri label-pri-<?php echo $task->pri;?>' title='<?php echo zget($lang->task->priList, $task->pri)?>'><?php echo zget($lang->task->priList, $task->pri)?></span></td>
        <?php $status = $this->processStatus('task', $task);?>
        <td class='c-status' title='<?php echo $status;?>'>
          <span class="status-task status-<?php echo $task->status?>"><?php echo $status;?></span>
        </td>
        <?php if($longBlock):?>
        <td class='c-deadline'><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
        <td class='c-estimate text-center' title="<?php echo $task->estimate . ' ' . $lang->execution->workHour;?>"><?php echo $task->estimate . $lang->execution->workHourUnit;?></td>
        <?php endif;?>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
