<?php
/**
 * The task view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<script language="Javascript">
function checkall(checker)
{
    $('input').each(function() 
    {
        $(this).attr("checked", checker.checked)
    });
}
</script>

<div class='yui-d0'><div class='u-1'>
  <div id='featurebar'>
    <div class='f-left'>
      <?php
      echo "<span id='assignedtoTab'>" . html::a(inlink('task', "type=assignedto"),  $lang->my->taskMenu->assignedToMe) . "</span>";
      echo "<span id='openedbyTab'>"   . html::a(inlink('task', "type=openedby"),    $lang->my->taskMenu->openedByMe)   . "</span>";
      echo "<span id='finishedbyTab'>" . html::a(inlink('task', "type=finishedby"),  $lang->my->taskMenu->finishedByMe) . "</span>";
      echo "<span id='closedbyTab'>"   . html::a(inlink('task', "type=closedby"),    $lang->my->taskMenu->closedByMe)   . "</span>";
      echo "<span id='canceledbyTab'>" . html::a(inlink('task', "type=canceledby"),  $lang->my->taskMenu->canceledByMe) . "</span>";
      ?>
    </div>
  </div>
</div>
<div class='yui-d0'><div class='u-1'>
  <form method='post' target='hiddenwin' action='<?php echo $this->createLink('task', 'batchClose');?>'>
  <table class='table-1 tablesorter fixed' id='tasktable'>
    <thead>
    <tr class='colhead'>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th class='w-pri'><?php echo $lang->priAB;?></th>
      <th class='w-150px'><?php echo $lang->task->project;?></th>
      <th><?php echo $lang->task->name;?></th>
      <th class='w-hour'><?php echo $lang->task->estimateAB;?></th>
      <th class='w-hour'><?php echo $lang->task->consumedAB;?></th>
      <th class='w-hour'><?php echo $lang->task->leftAB;?></th>
      <th class='w-date'><?php echo $lang->task->deadlineAB;?></th>
      <th class='w-status'><?php echo $lang->statusAB;?></th>
      <th class='w-user'><?php echo $lang->openedByAB;?></th>
      <th class='w-120px {sorter:false}'><?php echo $lang->actions;?></th>
    </tr>
    </thead>   
    <tbody>
    <?php foreach($tasks as $task):?>
    <tr class='a-center'>
      <td class='a-left'>
        <input type='checkbox' name='tasks[]' value='<?php echo $task->id;?>' />
        <?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), sprintf('%03d', $task->id));?>
      </td>
      <td><?php echo $lang->task->priList[$task->pri];?></td>
      <td class='nobr'><?php echo html::a($this->createLink('project', 'browse', "projectid=$task->projectID"), $task->projectName);?></th>
      <td class='a-left nobr'><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name);?></td>
      <td><?php echo $task->estimate;?></td>
      <td><?php echo $task->consumed;?></td>
      <td><?php echo $task->left;?></td>
      <td class=<?php if(isset($task->delay)) echo 'delayed';?>><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
      <td class='<?php echo $task->status;?>'><?php echo $lang->task->statusList[$task->status];?></td>
      <td><?php echo $users[$task->openedBy];?></td>
      <td>
        <?php 
        if(!(($task->status == 'wait') and common::printLink('task', 'start',  "taskID=$task->id", $lang->task->buttonStart))) echo $lang->task->buttonStart . ' ';
        if(!(($task->status == 'wait'   or $task->status == 'doing')  and common::printLink('task', 'finish', "taskID=$task->id", $lang->task->buttonDone))) echo $lang->task->buttonDone . ' ';
        if(!(($task->status == 'done'   or $task->status == 'cancel') and common::printLink('task', 'close', "taskID=$task->id", $lang->task->buttonClose))) echo $lang->task->buttonClose . ' ';
        if(!(($task->status == 'closed' or $task->status == 'done' or $task->status == 'cancel') and common::printLink('task', 'activate',   "taskID=$task->id", $lang->task->buttonActivate)))   echo $lang->task->buttonActivate . ' ';
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
    <tfoot>
    <tr><td colspan='11'>
      <input type='checkbox' onclick='checkall(this);'><?php echo $lang->selectAll;?>
      <?php 
      echo html::submitButton($lang->close)?>
    </td></tr>
    </tfoot>
  </table> 
  </form>
</div>
<script language='javascript'>$("#<?php echo $type;?>Tab").addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
