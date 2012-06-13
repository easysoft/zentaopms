<?php
/**
 * The view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
<?php if($task->fromBug == 0):?>
  <div id='main' class='<?php if($task->deleted) echo 'deleted';?>'>TASK #<?php echo $task->id . $lang->colon . $task->name;?></div>
<?php else:?>
  <div id='main' class='<?php if($task->deleted) echo 'deleted';?>'>TASK #<?php echo $task->id . $lang->colon . $task->name . '('. $lang->task->fromBug . $lang->colon . $task->fromBug . ')';?></div>
<?php endif;?>
  <div>
  <?php
  $browseLink = $app->session->taskList != false ? $app->session->taskList : $this->createLink('project', 'browse', "projectID=$task->project");
  if(!$task->deleted)
  {
      //if(!($task->status != 'closed' and $task->status != 'cancel' and common::printLink('task', 'logEfforts', "taskID=$task->id", $lang->task->buttonLogEfforts))) echo $lang->task->buttonLogEfforts . ' ';
      if(!(($task->status == 'wait') and common::printLink('task', 'start',  "taskID=$task->id", $lang->task->buttonStart))) echo $lang->task->buttonStart . ' ';
      if(!(($task->status == 'wait'   or $task->status == 'doing')  and common::printLink('task', 'finish', "taskID=$task->id", $lang->task->buttonDone))) echo $lang->task->buttonDone . ' ';
      if(!(($task->status == 'done'   or $task->status == 'cancel') and common::printLink('task', 'close', "taskID=$task->id", $lang->task->buttonClose))) echo $lang->task->buttonClose . ' ';
      if(!(($task->status == 'wait'   or $task->status == 'doing')  and common::printLink('task', 'cancel', "taskID=$task->id", $lang->task->buttonCancel))) echo $lang->task->buttonCancel . ' ';
      if(!(($task->status == 'closed' or $task->status == 'done' or $task->status == 'cancel') and common::printLink('task', 'activate',   "taskID=$task->id", $lang->task->buttonActivate)))   echo $lang->task->buttonActivate . ' ';
      if(!common::printLink('task', 'assignTo', "projectID=$task->project&taskID=$task->id", $lang->task->assign)) echo $lang->task->assign . ' ';
      if(!common::printLink('task', 'edit',  "taskID=$task->id", $lang->task->buttonEdit)) echo $lang->task->buttonEdit . ' ';
      if(common::hasPriv('task', 'edit')) echo html::a('#comment', $lang->comment, '', 'onclick=setComment()'). ' ';
      if(!common::printLink('task', 'delete',"projectID=$task->project&taskID=$task->id", $lang->task->buttonDelete, 'hiddenwin')) echo $lang->task->buttonDelete . ' ';
  }
  echo html::a($browseLink,  $lang->goback);
  if($preAndNext->pre) 
  {
      echo "<abbr id='pre' title='{$preAndNext->pre->id}{$lang->colon}{$preAndNext->pre->name}'>" . html::a($this->inLink('view', "taskID={$preAndNext->pre->id}"), '<') . "</abbr>&nbsp;&nbsp;";
  }
  if($preAndNext->next) 
  {
      echo "<abbr id='next' title={$preAndNext->next->id}{$lang->colon}{$preAndNext->pre->name}>" . html::a($this->inLink('view', "taskID={$preAndNext->next->id}"), '>') . "</abbr>";
  }
  ?>
  </div>
</div>

<table class='cont-rt5'>
  <tr valign='top'>
    <td>
      <fieldset>
        <legend><?php echo $lang->task->legendDesc;?></legend>
        <div class='content'><?php echo $task->desc;?></div>
      </fieldset>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $task->files, 'fieldset' => 'true'));?>
      <?php include '../../common/view/action.html.php';?>
      <div class='a-center f-16px strong'>
        <?php
        if(!$task->deleted)
        {
            if(!(($task->status == 'wait') and common::printLink('task', 'start',  "taskID=$task->id", $lang->task->buttonStart))) echo $lang->task->buttonStart . ' ';
            if(!(($task->status == 'wait'  or $task->status == 'doing')  and common::printLink('task', 'finish', "taskID=$task->id", $lang->task->buttonDone))) echo $lang->task->buttonDone . ' ';
            if(!(($task->status == 'done'   or $task->status == 'cancel') and common::printLink('task', 'close', "taskID=$task->id", $lang->task->buttonClose))) echo $lang->task->buttonClose . ' ';
            if(!(($task->status == 'wait'  or $task->status == 'doing')  and common::printLink('task', 'cancel', "taskID=$task->id", $lang->task->buttonCancel))) echo $lang->task->buttonCancel . ' ';
            if(!(($task->status == 'closed' or $task->status == 'done' or $task->status == 'cancel') and common::printLink('task', 'activate',   "taskID=$task->id", $lang->task->buttonActivate)))   echo $lang->task->buttonActivate . ' ';
            if(!common::printLink('task', 'assignTo', "projectID=$task->project&taskID=$task->id", $lang->task->assign)) echo $lang->task->assign . ' ';
            if(!common::printLink('task', 'edit',  "taskID=$task->id", $lang->task->buttonEdit)) echo $lang->task->buttonEdit . ' ';
            if(common::hasPriv('task', 'edit')) echo html::a('#comment', $lang->comment, '', 'onclick=setComment()'). ' ';
            if(!common::printLink('task', 'delete',"projectID=$task->project&taskID=$task->id", $lang->task->buttonDelete, 'hiddenwin')) echo $lang->task->buttonDelete . ' ';
        }
        echo html::a($browseLink,  $lang->goback);
        ?>
      </div>
      <div id='comment' class='hidden'>
        <fieldset>
          <legend><?php echo $lang->comment;?></legend>
          <form method='post' action='<?php echo inlink('edit', "taskID=$task->id&comment=true")?>'>
            <table align='center' class='table-1'>
            <tr><td><?php echo html::textarea('comment', '',"rows='5' class='w-p100'");?></td></tr>
            <tr><td><?php echo html::submitButton() . html::resetButton();?></td></tr>
            </table>
          </form>
        </fieldset>
      </div>
    </td>
    <td class='divider'></td>
    <td class='side'>
      <fieldset>
        <legend><?php echo $lang->task->legendBasic;?></legend>
        <table class='table-1'> 
          <tr>
            <th class='rowhead w-p20'><?php echo $lang->task->project;?></th>
            <td><?php if(!common::printLink('project', 'task', "projectID=$task->project", $project->name)) echo $project->name;?></td>
          </tr>  
          <tr class='nofixed'>
            <th class='rowhead'><?php echo $lang->task->story;?></th>
            <td>
              <?php 
              if($task->storyTitle and !common::printLink('story', 'view', "storyID=$task->story", $task->storyTitle)) echo $task->storyTitle;
              if($task->needConfirm)
              {
                  echo "(<span class='warning'>{$lang->story->changed}</span> ";
                  echo html::a($this->createLink('task', 'confirmStoryChange', "taskID=$task->id"), $lang->confirm, 'hiddenwin');
                  echo ")";
              }
              ?>
            </td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->task->assignedTo;?></th>
            <td><?php echo $task->assignedToRealName . $lang->at . $task->assignedDate;?></td> 
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->task->type;?></th>
            <td><?php echo $lang->task->typeList[$task->type];?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->task->status;?></th>
            <td><?php $lang->show($lang->task->statusList, $task->status);?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->task->pri;?></th>
            <td><?php $lang->show($lang->task->priList, $task->pri);?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->task->mailto;?></td>
            <td><?php $mailto = explode(',', str_replace(' ', '', $task->mailto)); foreach($mailto as $account) echo ' ' . $users[$account]; ?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->task->legendEffort;?></legend>
        <table class='table-1'> 
          <tr>
            <th class='rowhead'><?php echo $lang->task->deadline;?></th>
            <td>
              <?php
              echo $task->deadline;
              if(isset($task->delay)) printf($lang->task->delayWarning, $task->delay);
              ?>
            </td>
          </tr>  
          <tr>
            <th class='rowhead w-p20'><?php echo $lang->task->estimate;?></th>
            <td><?php echo $task->estimate . $lang->workingHour;?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->task->consumed;?></th>
            <td><?php echo $task->consumed . $lang->workingHour;?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->task->left;?></th>
            <td><?php echo $task->left . $lang->workingHour;?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->task->legendLife;?></legend>
        <table class='table-1'> 
          <tr>
            <th class='rowhead w-p20'><?php echo $lang->task->openedBy;?></th>
            <td><?php if($task->openedBy) echo $users[$task->openedBy] . $lang->at . $task->openedDate;?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->task->finishedBy;?></th>
            <td><?php if($task->finishedBy) echo $users[$task->finishedBy] . $lang->at . $task->finishedDate;?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->task->canceledBy;?></th>
            <td><?php if($task->canceledBy) echo $users[$task->canceledBy] . $lang->at . $task->canceledDate;?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->task->closedBy;?></th>
            <td><?php if($task->closedBy) echo $users[$task->closedBy] . $lang->at . $task->closedDate;?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->task->closedReason;?></th>
            <td><?php echo $lang->task->reasonList[$task->closedReason];?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->task->lastEdited;?></th>
            <td><?php if($task->lastEditedBy) echo $users[$task->lastEditedBy] . $lang->at . $task->lastEditedDate;?></td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
