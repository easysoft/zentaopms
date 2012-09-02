<?php
/**
 * The batch edit view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin' action="<?php echo $this->inLink('batchEdit', "projectID=$projectID&from=taskBatchEdit")?>">
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->task->common . $lang->colon . $lang->task->batchEdit;?></caption>
    <tr>
      <th class='w-20px'><?php echo $lang->idAB;?></th> 
      <th class='red'>   <?php echo $lang->task->name?></th>
      <th class='w-80px'><?php echo $lang->task->module;?></th>
      <th class='w-80px'><?php echo $lang->task->assignedTo;?></th>
      <th class='w-60px'><?php echo $lang->typeAB;?></th>
      <th class='w-70px'><?php echo $lang->task->status;?></th>
      <th class='w-40px'><?php echo $lang->task->pri;?></th>
      <th class='w-30px red'><?php echo $lang->task->estimateAB?></th>
      <th class='w-30px red'><?php echo $lang->task->consumedAB?></th>
      <th class='w-30px red'><?php echo $lang->task->leftAB?></th>
      <th class='w-80px'><?php echo $lang->task->finishedBy;?></th>
      <th class='w-80px'><?php echo $lang->task->closedBy;?></th>
      <th class='w-80px'><?php echo $lang->task->closedReason;?></th>
    </tr>
    <?php foreach($editedTasks as $task):?>
    <tr class='a-center'>
      <td><?php echo $task->id . html::hidden("taskIDList[$task->id]", $task->id);?></td>
      <td><?php echo html::input("names[$task->id]",          $task->name, 'class=text-1');?></td>
      <td><?php echo html::select("modules[$task->id]",       $modules, $task->module, 'class=select-1');?></td>
      <td><?php echo html::select("assignedTos[$task->id]",   $members, $task->assignedTo, 'class=select-1');?></td>
      <td><?php echo html::select("types[$task->id]",         $lang->task->typeList, $task->type, 'class=select-1');?></td>
      <td><?php echo html::select("statuses[$task->id]",      $lang->task->statusList, $task->status, 'class=select-1');?></td>
      <td><?php echo html::select("pris[$task->id]",          (array)$lang->task->priList, $task->pri, 'class=select-1');?></td>
      <td><?php echo html::input("estimates[$task->id]",      $task->estimate, 'class=text-1');?></td>
      <td><?php echo html::input("consumeds[$task->id]",      $task->consumed, 'class=text-1');?></td>
      <td><?php echo html::input("lefts[$task->id]",          $task->left, 'class=text-1');?></td>
      <td><?php echo html::select("finishedBys[$task->id]",   $members, $task->finishedBy, 'class=select-1');?></td>
      <td><?php echo html::select("closedBys[$task->id]",     $members, $task->closedBy, 'class=select-1');?></td>
      <td><?php echo html::select("closedReasons[$task->id]", $lang->task->reasonList, $task->closedReason, 'class=select-1');?></td>
    </tr>  
    <?php endforeach;?>
    <?php if(isset($suhosinInfo)):?>
    <tr><td colspan='12'><div class='f-left blue'><?php echo $suhosinInfo;?></div></td></tr>
    <?php endif;?>
    <tr><td colspan='12' class='a-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
