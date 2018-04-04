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
<main id="main">
  <div class="container">
    <?php $browseLink = $app->session->taskList != false ? $app->session->taskList : $this->createLink('project', 'browse', "projectID=$task->project");?>
    <div id="mainMenu" class="clearfix">
      <div class="btn-toolbar pull-left">
        <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-link'");?>
        <div class="divider"></div>
        <div class="page-title">
          <span class="label label-id"><?php echo $task->id?></span>
          <span class="text" style='color: <?php echo $task->color; ?>'>
            <?php if(!empty($task->parent)) echo '<span class="label">' . $this->lang->task->childrenAB . '</span> ';?>
            <?php if(!empty($task->team)) echo '<span class="label">' . $this->lang->task->multipleAB . '</span> ';?>
            <?php echo isset($task->parentName) ? $task->parentName . '/' : '';?><?php echo $task->name;?>
          </span>
          <?php if($task->deleted):?>
          <span class='label label-danger'><?php echo $lang->task->deleted;?></span>
          <?php endif;?>
          <?php if($task->fromBug != 0):?>
          <small><?php echo html::icon($lang->icons['bug']) . " {$lang->task->fromBug}$lang->colon$task->fromBug";?></small>
          <?php endif;?>
		</div>
      </div>
    </div>
    <div id="mainContent" class="main-row">
      <div class="main-col col-8">
        <div class="cell">
          <div class="detail">
            <div class="detail-title"><?php echo $lang->task->legendDesc;?></div>
            <div class="detail-content article-content"><?php echo $task->desc;?></div>
          </div>
          <?php if($project->type != 'ops'):?>
          <?php if($task->fromBug != 0):?>
          <div class="detail">
            <div class="detail-title"><?php echo $lang->task->steps;?></div>
            <div class="detail-content article-content"><?php echo $task->bugSteps;?></div>
          </div>
          <?php elseif($task->story):?>
          <div class="detail">
            <div class='detail-title'><?php echo $lang->task->storySpec;?></div>
            <div class='detail-content article-content'><?php echo $task->storySpec;?></div>
            <?php echo $this->fetch('file', 'printFiles', array('files' => $task->storyFiles, 'fieldset' => 'false'));?>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->task->storyVerify;?></div>
            <div class='detail-content article-content'><?php echo $task->storyVerify;?></div>
          </div>
          <?php endif;?>
          <?php if(isset($task->cases) and $task->cases):?>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->task->case;?></div>
            <div class='detail-content article-content'>
              <ul class='list-unstyled'>
              <?php foreach($task->cases as $caseID => $case) echo '<li>' . html::a($this->createLink('testcase', 'view', "caseID=$caseID", '', true), "#$caseID " . $case, '', "data-toggle='modal' data-type='iframe' data-width='90%'") . '</li>';?>
              </ul>
            </div>
          </div>
          <?php endif;?>
          <?php endif;?>
          <?php if(!empty($task->children)):?>
          <div class='detail'>
            <div class='detail-title'><?php echo $this->lang->task->children;?></div>
              <table class='table table-hover table-fixed'>
                <tr class='text-center'>
                  <th class='w-60px'> <?php echo $lang->task->id;?></th>
                  <th class='w-40px'> <?php echo $lang->task->lblPri;?></th>
                  <th>                <?php echo $lang->task->name;?></th>
                  <th class='w-100px'><?php echo $lang->task->deadline;?></th>
                  <th class='w-80px'> <?php echo $lang->task->assignedTo;?></th>
                  <th class='w-90px'> <?php echo $lang->task->status;?></th>
                  <th class='w-50px visible-lg'><?php echo $lang->task->consumedAB . $lang->task->lblHour;?></th>
                  <th class='w-50px visible-lg'><?php echo $lang->task->leftAB . $lang->task->lblHour;?></th>
                  <th class='w-200px'><?php echo $lang->actions;?></th>
                </tr>
                <?php foreach($task->children as $child):?>
                <tr class='text-center'>
                  <td><?php echo $child->id;?></td>
                  <td>
                    <?php
                    echo "<span class='pri-" . $child->pri . "'>";
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
                  <td class='c-actions'>
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
          </div>
          <?php endif;?>
          <?php echo $this->fetch('file', 'printFiles', array('files' => $task->files, 'fieldset' => 'true'));?>
          <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=task&objectID=$task->id");?>
          <?php include '../../common/view/action.html.php';?>
        </div>
      </div>
      <div class="side-col col-4">
        <div class="cell">
          <details class="detail" open>
            <summary class="detail-title"><?php echo $lang->task->legendBasic;?></summary>
            <div class="detail-content">
              <table class="table table-data">
                <tbody>
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
                </tbody>
              </table>
            </div>
          </details>
        </div>
        <?php if(!empty($task->team)) :?>
        <div class='cell'>
          <details class="detail" open>
            <summary class="detail-title"><?php echo $lang->task->team;?></summary>
            <div class="detail-content">
              <table class='table table-data'>
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
            </div>
          </details>
        </div>
        <?php endif;?>
        <div class='cell'>
          <details class="detail" open>
            <summary class="detail-title"><?php echo $lang->task->legendEffort;?></summary>
            <div class="detail-content">
              <table class='table table-data'>
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
            </div>
          </details>
        </div>
        <div class='cell'>
          <details class="detail" open>
            <summary class="detail-title"><?php echo $lang->task->legendLife;?></summary>
            <div class="detail-content">
              <table class='table table-data'>
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
            </div>
          </details>
        </div>
      </div>
    </div>
  </div>

  <div id="mainActions">
    <?php common::printPreAndNext($preAndNext);?>
    <div class="btn-toolbar">
      <?php if(!$task->deleted):?>
      <?php
      common::printIcon('task', 'assignTo',       "projectID=$task->project&taskID=$task->id", $task, 'button', '', '', 'iframe', true, '', empty($task->team) ? $lang->task->assignTo : $lang->task->transfer);
      common::printIcon('task', 'start',          "taskID=$task->id", $task, 'button', '', '', 'iframe', true);
      common::printIcon('task', 'restart',        "taskID=$task->id", $task, 'button', '', '', 'iframe', true);
      common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task, 'button', '', '', 'iframe', true);
      common::printIcon('task', 'pause',          "taskID=$task->id", $task, 'button', '', '', 'iframe', true);
      common::printIcon('task', 'finish',         "taskID=$task->id", $task, 'button', '', '', 'iframe showinonlybody text-success', true);
      common::printIcon('task', 'close',          "taskID=$task->id", $task, 'button', '', '', 'iframe', true);
      common::printIcon('task', 'activate',       "taskID=$task->id", $task, 'button', '', '', 'iframe text-success', true);
      common::printIcon('task', 'cancel',         "taskID=$task->id", $task, 'button', '', '', 'iframe', true);

      echo "<div class='divider'></div>";
      if(empty($task->team) or empty($task->children)) common::printIcon('task', 'batchCreate',    "project=$task->project&storyID=$task->story&moduleID=$task->module&taskID=$task->id", $task, 'button','plus','','','','',' ');
      common::printIcon('task', 'edit', "taskID=$task->id", $task);
      common::printIcon('task', 'create', "productID=0&storyID=0&moduleID=0&taskID=$task->id", $task, 'button', 'copy');
      common::printIcon('task', 'delete', "projectID=$task->project&taskID=$task->id", $task);

      echo "<div class='divider'></div>";
      if(!empty($task->parent)) echo html::a(helper::createLink('task', 'view', "taskID=$task->parent"), "<i class='iconicon-double-angle-up'></i>", '', "class='btn' title='{$lang->task->parent}'");
      common::printBack($browseLink);
      ?>
      <?php else:?>
      <?php common::printBack($browseLink);?>
      <?php endif;?>
    </div>
  </div>
</main>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>
