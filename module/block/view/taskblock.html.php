<?php
/**
 * The task block view file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<style>
td.delayed{background: #e84e0f!important; color: white;}
</style>
<?php $longBlock = $block->grid >= 6;?>
<table class='table table-borderless table-hover table-fixed block-task'>
  <thead>
  <tr>
    <?php if($longBlock):?>
    <th width='50'><?php echo $lang->idAB?></th>
    <?php endif;?>
    <th width='30'><?php echo $lang->priAB?></th>
    <th>           <?php echo $lang->task->name;?></th>
    <th width='50'><?php echo $lang->task->estimateAB;?></th>
    <?php if($longBlock):?>
    <th width='75'><?php echo $lang->task->deadline;?></th>
    <?php endif;?>
    <th width='70'><?php echo $lang->statusAB;?></th>
  </tr>
  </thead>
  <?php foreach($tasks as $task):?>
  <?php
  $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
  $viewLink = $this->createLink('task', 'view', "taskID={$task->id}");
  ?>
  <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
    <?php if($longBlock):?>
    <td class='text-center'><?php echo $task->id;?></td>
    <?php endif;?>
    <td class='text-center'><?php echo zget($lang->task->priList, $task->pri, $task->pri)?></td>
    <td style='color: <?php echo $task->color?>' title='<?php echo $task->name?>'><?php echo $task->name?></td>
    <td class='text-center'><?php echo $task->estimate?></td>
    <?php if($longBlock):?>
    <td class='<?php if(isset($task->delay)) echo 'delayed';?>'><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
    <?php endif;?>
    <td class='text-center' title='<?php echo zget($lang->task->statusList, $task->status)?>'>
      <span class="project-status-<?php echo $task->status?>">
        <span class="label label-dot"></span>
        <?php if($longBlock) echo zget($lang->task->statusList, $task->status);?>
      </span>
    </td>
  </tr>
  <?php endforeach;?>
</table>
