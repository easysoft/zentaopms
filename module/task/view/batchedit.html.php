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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['task']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchEdit']);?></small> <?php echo $lang->task->batchEdit . ' ' . $lang->task->common;?></strong>
  </div>
</div>

<form class='form-condensed' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "projectID={$projectID}")?>">
  <table class='table table-form table-fixed'>
    <thead>
      <tr>
        <th class='w-30px'><?php echo $lang->idAB;?></th> 
        <th>   <?php echo $lang->task->name?> <span class='required'></span></th>
        <th class='w-150px'><?php echo $lang->task->module?></th>
        <th class='w-150px'><?php echo $lang->task->assignedTo;?></th>
        <th class='w-80px'><?php echo $lang->typeAB;?> <span class='required'></span></th>
        <th class='w-100px'><?php echo $lang->task->status;?></th>
        <th class='w-70px'><?php echo $lang->task->pri;?></th>
        <th class='w-40px'><?php echo $lang->task->estimateAB;?> <span class='required'></span></th>
        <th class='w-60px'><?php echo $lang->task->consumedThisTime?> <span class='required'></span></th>
        <th class='w-40px'><?php echo $lang->task->leftAB?> <span class='required'></span></th>
        <th class='w-150px'><?php echo $lang->task->finishedBy;?></th>
        <th class='w-100px'><?php echo $lang->task->closedBy;?></th>
        <th class='w-100px'><?php echo $lang->task->closedReason;?></th>
      </tr>
    </thead>
    <?php foreach($taskIDList as $taskID):?>
    <?php 
    if(!isset($project))
    {
        $modules = $this->tree->getOptionMenu($tasks[$taskID]->project, $viewType = 'task'); 
        $members = $this->project->getTeamMemberPairs($tasks[$taskID]->project, 'nodeleted');
    }
    ?>
    <tr class='text-center'>
      <td><?php echo $taskID . html::hidden("taskIDList[$taskID]", $taskID);?></td>
      <td><?php echo html::input("names[$taskID]",          $tasks[$taskID]->name, 'class=form-control');?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("modules[$taskID]",       $modules, $tasks[$taskID]->module, "class='form-control chosen'")?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("assignedTos[$taskID]",   $members, $tasks[$taskID]->assignedTo, "class='form-control chosen'");?></td>
      <td><?php echo html::select("types[$taskID]",         $lang->task->typeList, $tasks[$taskID]->type, 'class=form-control');?></td>
      <td><?php echo html::select("statuses[$taskID]",      $lang->task->statusList, $tasks[$taskID]->status, 'class=form-control');?></td>
      <td><?php echo html::select("pris[$taskID]",          (array)$lang->task->priList, $tasks[$taskID]->pri, 'class=form-control');?></td>
      <td><?php echo html::input("estimates[$taskID]",      $tasks[$taskID]->estimate, "class='form-control text-center'");?></td>
      <td><?php echo html::input("consumeds[$taskID]",      '', "class='form-control text-center'");?></td>
      <td><?php echo html::input("lefts[$taskID]",          $tasks[$taskID]->left, "class='form-control text-center'");?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("finishedBys[$taskID]",   $members, $tasks[$taskID]->finishedBy, "class='form-control chosen'");?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("closedBys[$taskID]",     $members, $tasks[$taskID]->closedBy, "class='form-control chosen'");?></td>
      <td><?php echo html::select("closedReasons[$taskID]", $lang->task->reasonList, $tasks[$taskID]->closedReason, 'class=form-control');?></td>
    </tr>  
    <?php endforeach;?>

    <?php if(isset($suhosinInfo)):?>
    <tr><td colspan='<?php echo $this->config->task->batchEdit->columns;?>'>
      <div class='f-left blue'><?php echo $suhosinInfo;?></div>
    </td></tr>
    <?php endif;?>
    <tfoot>
      <tr><td colspan='<?php echo $this->config->task->batchEdit->columns;?>'>
        <?php echo html::submitButton();?>
      </td></tr>
    </tfoot>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
