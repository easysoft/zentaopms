<?php
/**
 * The view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: view.html.php 4808 2013-06-17 05:48:13Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['task']);?> <strong><?php echo $task->id;?></strong></span>
    <strong><?php echo $task->name;?></strong>
    <?php if($task->deleted):?>
    <span class='label label-danger'><?php echo $lang->task->deleted;?></span>
    <?php endif; ?>
    <?php if($task->fromBug != 0):?>
    <small> <?php echo html::icon($lang->icons['bug']) . " {$lang->task->fromBug}$lang->colon$task->fromBug"; ?></small>
    <?php endif;?>
  </div>
  <div class='actions'>
    <?php
    $browseLink  = $app->session->taskList != false ? $app->session->taskList : $this->createLink('project', 'browse', "projectID=$task->project");
    $actionLinks = '';
    if(!$task->deleted)
    {
        ob_start();
        echo "<div class='btn-group'>";
        common::printIcon('task', 'assignTo',       "projectID=$task->project&taskID=$task->id", $task, 'button', '', '', 'iframe', true);
        common::printIcon('task', 'start',          "taskID=$task->id", $task, 'button', '', '', 'iframe', true);
        common::printIcon('task', 'restart',        "taskID=$task->id", $task, 'button', '', '', 'iframe', true);
        common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task, 'button', '', '', 'iframe', true);
        common::printIcon('task', 'pause',          "taskID=$task->id", $task, 'button', '', '', 'iframe', true);
        common::printIcon('task', 'finish',         "taskID=$task->id", $task, 'button', '', '', 'iframe showinonlybody text-success', true);
        common::printIcon('task', 'close',          "taskID=$task->id", $task, 'button', '', '', 'iframe', true);
        common::printIcon('task', 'activate',       "taskID=$task->id", $task, 'button', '', '', 'iframe text-success', true);
        common::printIcon('task', 'cancel',         "taskID=$task->id", $task, 'button', '', '', 'iframe', true);
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printIcon('task', 'edit',  "taskID=$task->id");
        common::printCommentIcon('task');
        common::printIcon('task', 'create', "productID=0&storyID=0&moduleID=0&taskID=$task->id", '', 'button', 'copy');
        common::printIcon('task', 'delete', "projectID=$task->project&taskID=$task->id", '', 'button', '', 'hiddenwin');
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printRPN($browseLink, $preAndNext);
        echo '</div>';

        $actionLinks = ob_get_contents();
        ob_clean();
        echo $actionLinks;
    }
    else
    {
        common::printRPN($browseLink);
    }
    ?>
  </div>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <fieldset>
        <legend><?php echo $lang->task->legendDesc;?></legend>
        <div class='article-content'><?php echo $task->desc;?></div>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->task->storySpec;?></legend>
        <div class='article-content'><?php echo $task->storySpec;?></div>
      </fieldset>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $task->files, 'fieldset' => 'true'));?>
      <?php include '../../common/view/action.html.php';?>
      <div class='actions'> <?php if(!$task->deleted) echo $actionLinks;?></div>
      <fieldset id='commentBox' class='hide'>
        <legend><?php echo $lang->comment;?></legend>
        <form method='post' action='<?php echo inlink('edit', "taskID=$task->id&comment=true")?>'>
          <div class="form-group"><?php echo html::textarea('comment', '',"rows='5' class='w-p100'");?></div>
          <?php echo html::submitButton() . html::backButton();?>
        </form>
      </fieldset>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->task->legendBasic;?></legend>
        <table class='table table-data table-condensed table-borderless'> 
          <tr>
            <th class='w-80px'><?php echo $lang->task->project;?></th>
            <td><?php if(!common::printLink('project', 'task', "projectID=$task->project", $project->name)) echo $project->name;?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->module;?></th>
            <td>
              <?php
              if(empty($modulePath))
              {
                  echo "/";
              }
              else
              {
                 if($product) echo $product->name . $lang->arrow;
                 foreach($modulePath as $key => $module)
                 {
                   if(!common::printLink('project', 'task', "projectID=$task->project&browseType=byModule&param=$module->id", $module->name)) echo $module->name;
                   if(isset($modulePath[$key + 1])) echo $lang->arrow;
                 }
              }
              ?>
            </td>
          </tr>  
          <tr class='nofixed'>
            <th><?php echo $lang->task->story;?></th>
            <td>
            <?php 
            if($task->storyTitle and !common::printLink('story', 'view', "storyID=$task->story", $task->storyTitle, '', "class='iframe' data-width='80%'", true, true)) echo $task->storyTitle;
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
            <th><?php echo $lang->task->assignedTo;?></th>
            <td><?php echo $task->assignedTo ? $task->assignedToRealName . $lang->at . $task->assignedDate : '';?></td> 
          </tr>
          <tr>
            <th><?php echo $lang->task->type;?></th>
            <td><?php echo $lang->task->typeList[$task->type];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->status;?></th>
            <td><?php $lang->show($lang->task->statusList, $task->status);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->pri;?></th>
            <td><?php $lang->show($lang->task->priList, $task->pri);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->mailto;?></th>
            <td><?php $mailto = explode(',', str_replace(' ', '', $task->mailto)); foreach($mailto as $account) echo ' ' . zget($users, $account, $account); ?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->task->legendEffort;?></legend>
        <table class='table table-data table-condensed table-borderless'> 
          <tr>
            <th class='w-80px'><?php echo $lang->task->estStarted;?></th>
            <td><?php echo $task->estStarted;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->realStarted;?></th>
            <td><?php echo $task->realStarted; ?> </td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->deadline;?></th>
            <td>
            <?php
            echo $task->deadline;
            if(isset($task->delay)) printf($lang->task->delayWarning, $task->delay);
            ?>
            </td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->estimate;?></th>
            <td><?php echo $task->estimate . $lang->workingHour;?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->consumed;?></th>
            <td><?php echo round($task->consumed, 2) . $lang->workingHour;?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->left;?></th>
            <td><?php echo $task->left . $lang->workingHour;?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->task->legendLife;?></legend>
        <table class='table table-data table-condensed table-borderless'> 
          <tr>
            <th class='w-80px'><?php echo $lang->task->openedBy;?></th>
            <td><?php if($task->openedBy) echo zget($users, $task->openedBy, $task->openedBy) . $lang->at . $task->openedDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->finishedBy;?></th>
            <td><?php if($task->finishedBy) echo zget($users, $task->finishedBy, $task->finishedBy) . $lang->at . $task->finishedDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->canceledBy;?></th>
            <td><?php if($task->canceledBy) echo zget($users, $task->canceledBy, $task->canceledBy) . $lang->at . $task->canceledDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->closedBy;?></th>
            <td><?php if($task->closedBy) echo zget($users, $task->closedBy, $task->closedBy) . $lang->at . $task->closedDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->closedReason;?></th>
            <td><?php echo $lang->task->reasonList[$task->closedReason];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->lastEdited;?></th>
            <td><?php if($task->lastEditedBy) echo zget($users, $task->lastEditedBy, $task->lastEditedBy) . $lang->at . $task->lastEditedDate;?></td>
          </tr>
        </table>
      </fieldset>
    </div>
  </div>
</div>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>
