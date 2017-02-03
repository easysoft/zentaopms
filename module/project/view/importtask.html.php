<?php
/**
 * The importtask view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: importtask.html.php 4669 2013-04-23 02:28:08Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix pull-left'><?php echo html::icon($lang->icons['task']);?></span>
    <strong class='pull-left'><small class='text-muted'><?php echo html::icon($lang->icons['import']);?></small> <?php echo $lang->project->importTask;?></strong>
    <div class='input-group pull-left' style='font-weight:normal;'>
      <?php $projects = array(0 => $lang->project->allProjects) + $projects;?>
      <span class='input-group-addon'><?php echo $lang->project->selectProject;?></span>
      <?php  echo html::select('fromproject', $projects, $fromProject, "onchange='reload($projectID, this.value)' class='form-control chosen'");?>
    </div>
  </div>
</div>
<form class='form-condensed' method='post' target='hiddenwin' id='importTaskForm'>
  <table class='table tablesorter table-fixed table-selectable'>
    <thead>
    <tr class='colhead'>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th class='w-150px {sorter:false}'><?php echo $lang->project->name ?></th>
      <th class='w-pri'><?php echo $lang->priAB;?></th>
      <th class='w-p30'><?php echo $lang->task->name;?></th>
      <th class='w-user'><?php echo $lang->task->assignedTo;?></th>
      <th class='w-hour'><?php echo $lang->task->leftAB;?></th>
      <th class='w-date'><?php echo $lang->task->deadlineAB;?></th>
      <th class='w-status'><?php echo $lang->statusAB;?></th>
      <th><?php echo $lang->task->story;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($tasks2Imported as $task):?>
    <?php $class = $task->assignedTo == $app->user->account ? 'style=color:red' : '';?>
    <tr class='text-center'>
      <td class='cell-id'>
        <input type='checkbox' name='tasks[]' value='<?php echo $task->id;?>' />
        <?php if(!common::printLink('task', 'view', "task=$task->id", sprintf('%03d', $task->id))) printf('%03d', $task->id);?>
      </td>
      <td><?php echo substr($projects[$task->project], 2);?></td>
      <td><span class='<?php echo 'pri' . zget($lang->task->priList, $task->pri, $task->pri)?>'><?php echo $task->pri == '0' ? '' : zget($lang->task->priList, $task->pri, $task->pri);?></span></td>
      <td class='text-left nobr'><?php if(!common::printLink('task', 'view', "task=$task->id", $task->name)) echo $task->name;?></td>
      <td <?php echo $class;?>><?php echo $task->assignedToRealName;?></td>
      <td><?php echo $task->left;?></td>
      <td class=<?php if(isset($task->delay)) echo 'delayed';?>><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
      <td class=<?php echo $task->status;?> ><?php echo $lang->task->statusList[$task->status];?></td>
      <td class='text-left nobr'>
        <?php 
        if($task->storyID)
        {
            if(common::hasPriv('story', 'view'))
            {
                echo html::a($this->createLink('story', 'view', "storyid=$task->storyID"), $task->storyTitle);
            }
            else
            {
                echo $task->storyTitle;
            }
        }
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    <tfoot>
      <tr>
        <td colspan='9'>
          <div class='table-actions clearfix'>
            <?php echo html::selectButton();?>
            <?php echo html::submitButton($lang->project->importTask);?>
          </div>
        </td>
      </tr>
    </tfoot>
    </tbody>
  </table>
  <div></div>
</form>
<?php include '../../common/view/footer.html.php';?>
