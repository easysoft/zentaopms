<?php
/**
 * The task view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<?php include './taskheader.html.php';?>
<script language='Javascript'>
var browseType = '<?php echo $browseType;?>';
</script>
<div id='querybox' class='<?php if($browseType !='bysearch') echo 'hidden';?>'><?php echo $searchForm;?></div>
<table class='table-1 fixed colored tablesorter datatable'>
  <?php $vars = "projectID=$project->id&status=$status&parma=$param&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage"; ?>
  <thead>
  <tr class='colhead'>
    <th class='w-id'>    <?php common::printOrderLink('id',        $orderBy, $vars, $lang->idAB);?></th>
    <th class='w-pri'>   <?php common::printOrderLink('pri',       $orderBy, $vars, $lang->priAB);?></th>
    <th class='w-p30'>   <?php common::printOrderLink('name',      $orderBy, $vars, $lang->task->name);?></th>
    <th class='w-status'><?php common::printOrderLink('status',    $orderBy, $vars, $lang->statusAB);?></th>
    <th class='w-70px'>  <?php common::printOrderLink('deadline',  $orderBy, $vars, $lang->task->deadlineAB);?></th>

    <?php if($this->cookie->windowWidth > $this->config->wideSize):?>
    <th class='w-id'>    <?php common::printOrderLink('openedDate',$orderBy, $vars, $lang->task->openedDateAB);?></th>
    <?php endif;?>

    <th class='w-user'>  <?php common::printOrderLink('assignedTo',$orderBy, $vars, $lang->task->assignedToAB);?></th>
    <th class='w-user'>  <?php common::printOrderLink('finishedBy',$orderBy, $vars, $lang->task->finishedByAB);?></th>

    <?php if($this->cookie->windowWidth > $this->config->wideSize):?>
    <th class='w-40px'>  <?php common::printOrderLink('finishedDate',$orderBy, $vars, $lang->task->finishedDateAB);?></th>
    <?php endif;?>

    <th class='w-35px'>  <?php common::printOrderLink('estimate',  $orderBy, $vars, $lang->task->estimateAB);?></th>
    <th class='w-40px'>  <?php common::printOrderLink('consumed',  $orderBy, $vars, $lang->task->consumedAB);?></th>
    <th class='w-40px'>  <?php common::printOrderLink('left',      $orderBy, $vars, $lang->task->leftAB);?></th>
    <th><?php common::printOrderLink('story', $orderBy, $vars, $lang->task->story);?></th>
    <th class='w-100px {sorter:false}'><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <?php  
    $taskSum = $statusWait = $statusDone = $statusDoing = $statusClosed = $statusCancel = 0;  
    $totalEstimate = $totalConsumed = $totalLeft = 0.0;
  ?>
  <tbody>
  <?php foreach($tasks as $task):?>
  <?php $class = $task->assignedTo == $app->user->account ? 'style=color:red' : '';?>
  <?php  
  $totalEstimate  += $task->estimate;
  $totalConsumed  += $task->consumed;
  $totalLeft      += (($task->status == 'cancel' or $task->closedReason == 'cancel') ? 0 : $task->left);
  $statusVar      = 'status' . ucfirst($task->status);
  $$statusVar ++;
  ?>
  <tr class='a-center'>
    <td><?php if(!common::printLink('task', 'view', "task=$task->id", sprintf('%03d', $task->id))) printf('%03d', $task->id);?></td>
    <td><?php echo $lang->task->priList[$task->pri];?></td>
    <td class='a-left nobr'>
      <?php 
      if($task->fromBug != 0) echo "<span class='bug'>[BUG] </span>";
      else echo "<span class='task'>[TASK] </span>";
      if(!common::printLink('task', 'view', "task=$task->id", $task->name)) echo $task->name;
      ?>
    </td>
    <td class=<?php echo $task->status;?> >
      <?php
      $storyChanged = ($task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion);
      $storyChanged ? print("<span class='warning'>{$lang->story->changed}</span> ") : print($lang->task->statusList[$task->status]);
      ?>
    </td>
    <td class=<?php if(isset($task->delay)) echo 'delayed';?>><?php if(substr($task->deadline, 0, 4) > 0) echo substr($task->deadline, 5, 6);?></td>


    <?php if($this->cookie->windowWidth > $this->config->wideSize):?>
    <td><?php echo substr($task->openedDate, 5, 6);?></th>
    <?php endif;?>

    <td <?php echo $class;?>><?php echo $task->assignedToRealName;?></td>
    <td><?php echo $users[$task->finishedBy];?></td>

    <?php if($this->cookie->windowWidth > $this->config->wideSize):?>
    <td><?php echo substr($task->finishedDate, 5, 6);?></th>
    <?php endif;?>

    <td><?php echo $task->estimate;?></td>
    <td><?php echo $task->consumed;?></td>
    <td><?php echo $task->left;?></td>
    <td class='a-left nobr'>
      <?php 
      $story = '';
      if($task->storyID and common::hasPriv('story', 'view'))  $story = html::a($this->createLink('story', 'view', "storyid=$task->storyID"), $task->storyTitle);
      if($task->storyID and !common::hasPriv('story', 'view')) $story = $task->storyTitle;
      echo $story;
      ?>
    </td>
    <td>
      <?php
      if(!(($task->status == 'wait'  or $task->status == 'doing')  and common::printLink('task', 'finish', "taskID=$task->id", $lang->task->buttonDone))) echo $lang->task->buttonDone . ' ';
      if(!(($task->status == 'done'  or $task->status == 'cancel') and common::printLink('task', 'close', "taskID=$task->id", $lang->task->buttonClose))) echo $lang->task->buttonClose . ' ';
      if(!common::printLink('task', 'edit',  "taskID=$task->id", $lang->task->buttonEdit)) echo $lang->task->buttonEdit . ' ';
      if($browseType == 'needconfirm') common::printLink('task', 'confirmStoryChange', "taskid=$task->id", $lang->confirm, 'hiddenwin');
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <?php $columns = $this->cookie->windowWidth > $this->config->wideSize ? 14 : 12;?>
      <td colspan='<?php echo $columns;?>'>
        <div class='f-left'><?php printf($lang->project->taskSummary, count($tasks), $statusWait, $statusDoing, $totalEstimate, $totalConsumed, $totalLeft);?></div>
        <?php $pager->show();?>
     </td>
   </tr>
  </tfoot>
</table>
<?php include '../../common/view/footer.html.php';?>
