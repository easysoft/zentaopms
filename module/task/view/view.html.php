<?php
/**
 * The view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
    <strong style='color: <?php echo $task->color;?>'>
      <?php if(!empty($task->parent)) echo '<span class="label">' . $this->lang->task->childrenAB . '</span> ';?>
      <?php if(!empty($task->team)) echo '<span class="label">' . $this->lang->task->multipleAB . '</span> ';?>
      <?php echo isset($task->parentName) ? $task->parentName . '/' : '';?><?php echo $task->name;?>
    </strong>
    <?php if($task->deleted):?>
    <span class='label label-danger'><?php echo $lang->task->deleted;?></span>
    <?php endif;?>
    <?php if($task->fromBug != 0):?>
    <small><?php echo html::icon($lang->icons['bug']) . " {$lang->task->fromBug}$lang->colon$task->fromBug";?></small>
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
        common::printIcon('task', 'assignTo',       "projectID=$task->project&taskID=$task->id", $task, 'button', '', '', 'iframe', true, '', empty($task->team) ? $lang->task->assignTo : $lang->task->transfer);
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
        if(empty($task->team) or empty($task->children)) common::printIcon('task', 'batchCreate',    "project=$task->project&storyID=$task->story&moduleID=$task->module&taskID=$task->id", $task, 'button','plus','','','','',' ');
        common::printIcon('task', 'edit', "taskID=$task->id", $task);
        common::printCommentIcon('task', $task);
        common::printIcon('task', 'create', "productID=0&storyID=0&moduleID=0&taskID=$task->id", $task, 'button', 'copy');
        common::printIcon('task', 'delete', "projectID=$task->project&taskID=$task->id", $task);
        echo '</div>';

        echo "<div class='btn-group'>";
        if(!empty($task->parent)) echo html::a(helper::createLink('task', 'view', "taskID=$task->parent"), "<i class='icon-pre icon-double-angle-left'></i>", '', "class='btn' title='{$lang->task->parent}'");
        common::printRPN($browseLink, $preAndNext);
        echo '</div>';

        $actionLinks = ob_get_contents();
        ob_end_clean();
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
      <?php if($project->type != 'ops'):?>
      <?php if($task->fromBug != 0):?>
      <fieldset>
        <legend><?php echo $lang->bug->steps;?></legend>
        <div class='article-content'><?php echo $task->bugSteps;?></div>
      </fieldset>
      <?php else:?>
      <fieldset>
        <legend><?php echo $lang->task->storySpec;?></legend>
        <div class='article-content'><?php echo $task->storySpec;?></div>
        <?php echo $this->fetch('file', 'printFiles', array('files' => $task->storyFiles, 'fieldset' => 'false'));?>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->task->storyVerify;?></legend>
        <div class='article-content'><?php echo $task->storyVerify;?></div>
      </fieldset>
      <?php endif;?>
      <?php if(isset($task->cases) and $task->cases):?>
      <fieldset>
        <legend><?php echo $lang->task->case;?></legend>
        <div class='article-content'>
          <ul class='list-unstyled'>
          <?php foreach($task->cases as $caseID => $case) echo '<li>' . html::a($this->createLink('testcase', 'view', "caseID=$caseID", '', true), "#$caseID " . $case, '', "data-toggle='modal' data-type='iframe' data-width='90%'") . '</li>';?>
          </ul>
        </div>
      </fieldset>
      <?php endif;?>
      <?php endif;?>
      <?php if(!empty($task->children)):?>
      <fieldset>
        <legend><?php echo $this->lang->task->children;?></legend>
        <table class='table table-hover table-data table-fixed'>
          <tr class='text-center'>
            <th class='w-60px'> <?php echo $lang->task->id;?></th>
            <th class='w-40px'> <?php echo $lang->task->lblPri;?></th>
            <th>                <?php echo $lang->task->name;?></th>
            <th class='w-100px'><?php echo $lang->task->deadline;?></th>
            <th class='w-80px'> <?php echo $lang->task->assignedTo;?></th>
            <th class='w-90px'> <?php echo $lang->task->status;?></th>
            <th class='w-50px visible-lg'><?php echo $lang->task->consumedAB . $lang->task->lblHour;?></th>
            <th class='w-50px visible-lg'><?php echo $lang->task->leftAB . $lang->task->lblHour;?></th>
            <th class='w-150px'><?php echo $lang->actions;?></th>
          </tr>
            <?php foreach($task->children as $child):?>
              <tr class='text-center'>
                <td><?php echo $child->id;?></td>
                <td>
                  <?php
                  echo "<span class='pri" . zget($this->lang->task->priList, $child->pri, $child->pri) . "'>";
                  echo $child->pri == '0' ? '' : zget($this->lang->task->priList, $child->pri, $child->pri);
                  echo "</span>";
                  ?>
                </td>
                <td class='text-left'><a href="<?php echo $this->createLink('task', 'view', "taskID=$child->id"); ?>"><?php echo $child->name;?></a></td>
                <td><?php echo $child->deadline;?></td>
                <td><?php if(isset($users[$child->assignedTo])) echo $users[$child->assignedTo];?></td>
                <td><?php echo zget($lang->task->statusList, $child->status);?></td>
                <td class='visible-lg'><?php echo $child->consumed;?></td>
                <td class='visible-lg'><?php echo $child->left;?></td>
                <td>
                    <?php
                    common::printIcon('task', 'assignTo', "projectID=$child->project&taskID=$child->id", $child, 'list', '', '', 'iframe', true);
                    common::printIcon('task', 'start',    "taskID=$child->id", $child, 'list', '', '', 'iframe', true);
                    common::printIcon('task', 'recordEstimate', "taskID=$child->id", $child, 'list', 'time', '', 'iframe', true);
                    common::printIcon('task', 'finish', "taskID=$child->id", $child, 'list', '', '', 'iframe', true);
                    common::printIcon('task', 'close',    "taskID=$child->id", $child, 'list', '', '', 'iframe', true);
                    common::printIcon('task', 'edit',"taskID=$child->id", $child, 'list');
                    ?>
                </td>
              </tr>
            <?php endforeach;?>
        </table>
      </fieldset>
      <?php endif;?>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $task->files, 'fieldset' => 'true'));?>
      <?php include '../../common/view/action.html.php';?>
      <div class='actions'> <?php if(!$task->deleted) echo $actionLinks;?></div>
      <fieldset id='commentBox' class='hide'>
        <legend><?php echo $lang->comment;?></legend>
        <form method='post' action='<?php echo $this->createLink('action', 'comment', "objectType=task&objectID=$task->id")?>' target='hiddenwin'>
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
            <td><?php if(!common::printLink('project', 'view', "projectID=$task->project", $project->name)) echo $project->name;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->module;?></th>
            <?php
            $moduleTitle = '';
            ob_start();
            if(empty($modulePath))
            {
                $moduleTitle .= '/';
                echo "/";
            }
            else
            {
                if($product)
                {
                    $moduleTitle .= $product->name . '/';
                    echo $product->name . $lang->arrow;
                }
               foreach($modulePath as $key => $module)
               {
                   $moduleTitle .= $module->name;
                   if(!common::printLink('project', 'task', "projectID=$task->project&browseType=byModule&param=$module->id", $module->name)) echo $module->name;
                   if(isset($modulePath[$key + 1]))
                   {
                       $moduleTitle .= '/';
                       echo $lang->arrow;
                   }
               }
            }
            $printModule = ob_get_contents();
            ob_end_clean();
            ?>
            <td title='<?php echo $moduleTitle?>'><?php echo $printModule?></td>
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
          <?php if($task->fromBug):?>
          <tr>
            <th><?php echo $lang->task->fromBug;?></th>
            <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$task->fromBug"), "#$task->fromBug " . $fromBug->title, '_blank');?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo empty($task->team) ? $lang->task->assignTo : $lang->task->transferTo;?></th>
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
            <td><span class='<?php echo 'pri' . zget($lang->task->priList, $task->pri);?>'><?php echo $task->pri == '0' ? '' : zget($lang->task->priList, $task->pri)?></span></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->mailto;?></th>
            <td><?php $mailto = explode(',', str_replace(' ', '', $task->mailto)); foreach($mailto as $account) echo ' ' . zget($users, $account, $account); ?></td>
          </tr>
        </table>
      </fieldset>
      <?php if(!empty($task->team)) :?>
      <fieldset>
        <legend><?php echo $lang->task->team;?></legend>
        <table class='table table-data table-condensed table-borderless'>
          <thead>
          <tr>
            <th><?php echo $lang->task->team?></th>
            <th class='text-center'><?php echo $lang->task->estimate?></th>
            <th class='text-center'><?php echo $lang->task->consumed?></th>
            <th class='text-center'><?php echo $lang->task->left?></th>
          </tr>
          </thead>
            <?php foreach($task->team as $member):?>
            <tr class='text-center'>
              <td class='text-left'><?php echo zget($users, $member->account)?></td>
              <td><?php echo $member->estimate?></td>
              <td><?php echo $member->consumed?></td>
              <td><?php echo $member->left?></td>
            </tr>
            <?php endforeach;?>
        </table>
      </fieldset>
      <?php endif;?>
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
