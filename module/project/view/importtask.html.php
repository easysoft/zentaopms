<?php
/**
 * The importtask view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'><div class='u-1'>
  <form method='post' target='hiddenwin'>
  <table class='table-1 fixed tablesorter'>
    <thead>
    <tr class='colhead'>
      <th class='w-150px'><?php echo $lang->task->project;?></th>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th class='w-pri'><?php echo $lang->priAB;?></th>
      <th class='w-p30'><?php echo $lang->task->name;?></th>
      <th class='w-user'><?php echo $lang->task->assignedTo;?></th>
      <th class='w-hour'><?php echo $lang->task->leftAB;?></th>
      <th class='w-date'><?php echo $lang->task->deadlineAB;?></th>
      <th class='w-status'><?php echo $lang->statusAB;?></th>
      <th><?php echo $lang->task->story;?></th>
      <th class='w-30px {sorter:false}'><?php echo $lang->import;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($tasks2Imported as $task):?>
    <?php $class = $task->assignedTo == $app->user->account ? 'style=color:red' : '';?>
    <tr class='a-center'>
      <td><?php echo $projects[$task->project];?></td>
      <td><?php if(!common::printLink('task', 'view', "task=$task->id", sprintf('%03d', $task->id))) printf('%03d', $task->id);?></td>
      <td><?php echo $task->pri;?></td>
      <td class='a-left nobr'><?php if(!common::printLink('task', 'view', "task=$task->id", $task->name)) echo $task->name;?></td>
      <td <?php echo $class;?>><?php echo $task->assignedToRealName;?></td>
      <td><?php echo $task->left;?></td>
      <td class=<?php if(isset($task->delay)) echo 'delayed';?>><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
      <td class=<?php echo $task->status;?> ><?php echo $lang->task->statusList[$task->status];?></td>
      <td class='a-left nobr'>
        <?php 
        if($task->storyID)
        {
            if(common::hasPriv('story', 'view')) echo html::a($this->createLink('story', 'view', "storyid=$task->storyID"), $task->storyTitle);
            else echo $task->storyTitle;
        }
        ?>
      </td>
      <td><input type='checkbox' name='tasks[]' value='<?php echo $task->id;?>' /></td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <div class='a-right'><?php echo html::submitButton($lang->project->importTask);?></div>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>
