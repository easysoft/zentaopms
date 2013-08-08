<?php
/**
 * The batch edit view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "projectID={$projectID}")?>">
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->task->common . $lang->colon . $lang->task->batchEdit;?></caption>
    <tr>
      <th class='w-30px'><?php echo $lang->idAB;?></th> 
      <th class='red'>   <?php echo $lang->task->name?></th>
      <?php 
      if(!isset($project) or (isset($project) and $project->type != 'sprint')) 
      {
          echo "<th class='w-80px'>" . $lang->task->module . "</th>";
      }
      ?>
      <th class='w-80px'><?php echo $lang->task->assignedTo;?></th>
      <th class='w-60px red'><?php echo $lang->typeAB;?></th>
      <th class='w-70px'><?php echo $lang->task->status;?></th>
      <th class='w-40px'><?php echo $lang->task->pri;?></th>
      <th class='w-30px red'><?php echo $lang->task->estimateAB;?></th>
      <th class='w-70px'><?php echo $lang->task->consumed;?></th>
      <th class='w-60px red'><?php echo $lang->task->consumedThisTime?></th>
      <th class='w-30px red'><?php echo $lang->task->leftAB?></th>
      <th class='w-80px'><?php echo $lang->task->finishedBy;?></th>
      <th class='w-80px'><?php echo $lang->task->closedBy;?></th>
      <th class='w-80px'><?php echo $lang->task->closedReason;?></th>
    </tr>
    <?php foreach($taskIDList as $taskID):?>
    <?php 
    if(!isset($project))
    {
        $modules = $this->tree->getOptionMenu($tasks[$taskID]->project, $viewType = 'task'); 
        $members = $this->project->getTeamMemberPairs($tasks[$taskID]->project, 'nodeleted');
    }
    ?>
    <tr class='a-center'>
      <td><?php echo $taskID . html::hidden("taskIDList[$taskID]", $taskID);?></td>
      <td><?php echo html::input("names[$taskID]",          $tasks[$taskID]->name, 'class=text-1');?></td>
      <?php 
      if(!isset($project) or (isset($project) and $project->type != 'sprint')) 
      {
          echo "<td>" . html::select("modules[$taskID]", $modules, $tasks[$taskID]->module, 'class=select-1') . "</td>";
      }
      ?>
      <td><?php echo html::select("assignedTos[$taskID]",   $members, $tasks[$taskID]->assignedTo, 'class=select-1');?></td>
      <td><?php echo html::select("types[$taskID]",         $lang->task->typeList, $tasks[$taskID]->type, 'class=select-1');?></td>
      <td><?php echo html::select("statuses[$taskID]",      $lang->task->statusList, $tasks[$taskID]->status, 'class=select-1');?></td>
      <td><?php echo html::select("pris[$taskID]",          (array)$lang->task->priList, $tasks[$taskID]->pri, 'class=select-1');?></td>
      <td><?php echo html::input("estimates[$taskID]",      $tasks[$taskID]->estimate, "class='text-1 a-center'");?></td>
      <td><?php echo html::input("", $tasks[$taskID]->consumed, "class='text-1 a-center' disabled");?></td>
      <td><?php echo html::input("consumeds[$taskID]",      '', "class='text-1 a-center'");?></td>
      <td><?php echo html::input("lefts[$taskID]",          $tasks[$taskID]->left, "class='text-1 a-center'");?></td>
      <td><?php echo html::select("finishedBys[$taskID]",   $members, $tasks[$taskID]->finishedBy, 'class=select-1');?></td>
      <td><?php echo html::select("closedBys[$taskID]",     $members, $tasks[$taskID]->closedBy, 'class=select-1');?></td>
      <td><?php echo html::select("closedReasons[$taskID]", $lang->task->reasonList, $tasks[$taskID]->closedReason, 'class=select-1');?></td>
    </tr>  
    <?php endforeach;?>

    <?php if(isset($suhosinInfo)):?>
    <tr><td colspan='<?php echo $this->config->task->batchEdit->columns + 1;?>'>
      <div class='f-left blue'><?php echo $suhosinInfo;?></div>
    </td></tr>
    <?php endif;?>

    <?php $colspan = (!isset($project) or (isset($project) and $project->type != 'sprint')) ? $this->config->task->batchEdit->columns + 1 : $this->config->task->batchEdit->columns;?>
    <tr><td colspan='<?php echo $colspan;?>' class='a-center'>
      <?php echo html::submitButton();?>
    </td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
