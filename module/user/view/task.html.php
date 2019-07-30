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
      $that   = zget($lang->user->thirdPerson, $user->gender);
      $active = $type == 'assignedTo' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('task', "account=$account&type=assignedTo"), sprintf($lang->user->assignedTo, $that)) . "</li>";

      $active = $type == 'openedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('task', "account=$account&type=openedBy"), sprintf($lang->user->openedBy, $that)) . "</li>";

      $active = $type == 'finishedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('task', "account=$account&type=finishedBy"), sprintf($lang->user->finishedBy, $that)) . "</li>";

      $active = $type == 'closedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('task', "account=$account&type=closedBy"), sprintf($lang->user->closedBy, $that)) . "</li>";

      $active = $type == 'canceledBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('task', "account=$account&type=canceledBy"), sprintf($lang->user->canceledBy, $that)) . "</li>";
      ?>
    </ul>
  </nav>

  <div class='main-table'>
    <table class='table has-sort-head tablesorter' id='tasktable'>
      <thead>
        <tr class='colhead'>
          <th class='w-id'><?php echo $lang->idAB;?></th>
          <th class='w-pri'><?php echo $lang->priAB;?></th>
          <th><?php echo $lang->task->project;?></th>
          <th><?php echo $lang->task->name;?></th>
          <th class='w-70px'><?php echo $lang->task->estimateAB;?></th>
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
          <td class='text-left nobr'><?php echo html::a($this->createLink('project', 'browse', "projectid=$task->projectID"), $task->projectName);?></td>
          <td class='text-left nobr'>
            <?php if(!empty($task->team))   echo '<span class="label label-badge label-light">' . $this->lang->task->multipleAB . '</span> ';?>
            <?php if($task->parent > 0) echo '<span class="label label-badge label-light">' . $this->lang->task->childrenAB . '</span> ';?>
            <?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name, null, "style='color: $task->color'");?>
          </td>
          <td><?php echo $task->estimate;?></td>
          <td><?php echo $task->consumed;?></td>
          <td><?php echo $task->left;?></td>
          <td class=<?php if(isset($task->delay)) echo 'delayed';?>><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
          <td class='<?php echo $task->status;?>'><?php echo $this->processStatus('task', $task);?></td>
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
