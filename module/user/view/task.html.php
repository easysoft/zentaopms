<?php
/**
 * The task view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: task.html.php 4771 2013-05-05 07:41:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include './featurebar.html.php';?>
<div id='mainContent'>
  <nav id='contentNav'>
    <ul class='nav nav-default'>
      <?php
      $active = $type == 'assignedTo' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('task', "account=$account&type=assignedTo"), $lang->user->assignedTo) . "</li>";

      $active = $type == 'openedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('task', "account=$account&type=openedBy"),   $lang->user->openedBy)   . "</li>";

      $active = $type == 'finishedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('task', "account=$account&type=finishedBy"), $lang->user->finishedBy) . "</li>";

      $active = $type == 'closedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('task', "account=$account&type=closedBy"),   $lang->user->closedBy)   . "</li>";

      $active = $type == 'canceledBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('task', "account=$account&type=canceledBy"), $lang->user->canceledBy) . "</li>";
      ?>
    </ul>
  </nav>

  <div class='main-table'>
    <table class='table has-sort-head' id='tasktable'>
      <thead>
        <tr class='colhead'>
          <th class='w-id'><?php echo $lang->idAB;?></th>
          <th class='w-pri'><?php echo $lang->priAB;?></th>
          <th><?php echo $lang->task->project;?></th>
          <th><?php echo $lang->task->name;?></th>
          <th class='w-hour'><?php echo $lang->task->estimateAB;?></th>
          <th class='w-70px'><?php echo $lang->task->consumedAB;?></th>
          <th class='w-hour'><?php echo $lang->task->leftAB;?></th>
          <th class='w-date'><?php echo $lang->task->deadlineAB;?></th>
          <th class='w-70px'><?php echo $lang->statusAB;?></th>
        </tr>
      </thead>   
      <tbody>
        <?php foreach($tasks as $task):?>
        <tr class='text-center'>
          <td><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), sprintf('%03d', $task->id));?></td>
          <td><span class='<?php echo 'pri' . zget($lang->task->priList, $task->pri, $task->pri);?>'><?php echo $task->pri == '0' ? '' : zget($lang->task->priList, $task->pri, $task->pri)?></span></td>
          <td class='nobr'><?php echo html::a($this->createLink('project', 'browse', "projectid=$task->projectID"), $task->projectName);?></td>
          <td class='text-left nobr'><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name);?></td>
          <td><?php echo $task->estimate;?></td>
          <td><?php echo $task->consumed;?></td>
          <td><?php echo $task->left;?></td>
          <td class=<?php if(isset($task->delay)) echo 'delayed';?>><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
          <td class='<?php echo $task->status;?>'><?php echo $lang->task->statusList[$task->status];?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table> 
    <?php if($tasks):?>
    <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
