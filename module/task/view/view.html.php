<?php
/**
 * The view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: view.html.php 4808 2013-06-17 05:48:13Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php $browseLink = $app->session->taskList != false ? $app->session->taskList : $this->createLink('execution', 'browse', "executionID=$task->execution");?>
<?php js::set('sysurl', common::getSysUrl());?>
<?php if(strpos($_SERVER["QUERY_STRING"], 'isNotice=1') === false):?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $task->id?></span>
      <span class="text" title='<?php echo $task->name;?>' style='color: <?php echo $task->color; ?>'>
        <?php if(!empty($task->team)) echo '<span class="label label-badge label-primary no-margin">' . (common::checkNotCN() ? ' ' : '') . $lang->task->modeList[$task->mode] . '</span>';?>
        <?php if($task->parent > 0) echo '<span class="label label-badge label-primary no-margin">' . $lang->task->childrenAB . '</span>';?>
        <?php if($task->parent > 0) echo isset($task->parentName) ? html::a(inlink('view', "taskID={$task->parent}"), $task->parentName) . ' / ' : '';?><?php echo $task->name;?>
      </span>
      <?php if($task->deleted):?>
      <span class='label label-danger'><?php echo $lang->task->deleted;?></span>
      <?php endif;?>
      <?php if($task->fromBug != 0):?>
      <small><?php echo html::a(helper::createLink('bug', 'view', "bugID=$task->fromBug", '', true), "<i class='icon icon-bug'></i> {$lang->task->fromBug}$lang->colon$task->fromBug", '', "class='iframe' data-width='80%'");?></small>
      <?php endif;?>
    </div>
  </div>
  <?php if(!isonlybody()):?>
  <div class="btn-toolbar pull-right">
    <?php
    $checkObject = new stdclass();
    $checkObject->execution = $task->execution;
    $link = $this->createLink('task', 'create', "execution={$task->execution}&storyID={$task->story}&moduleID={$task->module}");
    if(common::hasPriv('task', 'create', $checkObject)) echo html::a($link, "<i class='icon icon-plus'></i> {$lang->task->create}", '', "class='btn btn-primary'");
    ?>
  </div>
  <?php endif;?>
</div>
<?php if($this->app->getViewType() == 'xhtml'):?>
<div id="scrollContent">
<?php endif;?>
<?php endif;?>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->task->legendDesc;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($task->desc) ? $task->desc : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </div>
      <?php if($execution->lifetime != 'ops'):?>
      <?php if($task->fromBug != 0):?>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->bug->steps;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($task->bugSteps) ? $task->bugSteps : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </div>
      <?php elseif($task->story):?>
      <div class="detail">
        <div class='detail-title'><?php echo $lang->task->storySpec;?></div>
        <div class='detail-content article-content'>
          <?php echo (!empty($task->storySpec) || !empty($task->storyFiles)) ? $task->storySpec : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
        <?php echo $this->fetch('file', 'printFiles', array('files' => $task->storyFiles, 'fieldset' => 'false', 'object' => $task, 'method' => 'view', 'showDelete' => false));?>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->task->storyVerify;?></div>
        <div class='detail-content article-content'>
          <?php echo !empty($task->storyVerify) ? $task->storyVerify : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </div>
      <?php endif;?>
      <?php if(isset($task->cases) and $task->cases):?>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->task->case;?></div>
        <div class='detail-content article-content'>
          <ul class='list-unstyled'>
            <?php foreach($task->cases as $caseID => $case) echo '<li>' . html::a($this->createLink('testcase', 'view', "caseID=$caseID", '', true), "#$caseID " . $case, '', isonlybody() ? '' : "data-toggle='modal' data-type='iframe' data-width='90%'") . '</li>';?>
          </ul>
        </div>
      </div>
      <?php endif;?>
      <?php endif;?>
      <?php if(!empty($task->children)):?>
      <div class='detail'>
        <div class='detail-title'><?php echo $this->lang->task->children;?></div>
        <div class='detail-content article-content'>
          <table class='table table-hover table-fixed' id='childrenTable'>
            <thead>
              <tr class='text-center'>
                <th class='c-id'> <?php echo $lang->task->id;?></th>
                <th class='c-lblPri'> <?php echo $lang->task->lblPri;?></th>
                <th>                <?php echo $lang->task->name;?></th>
                <th class='c-deadline'><?php echo $lang->task->deadline;?></th>
                <th class='c-assignedTo'> <?php echo $lang->task->assignedTo;?></th>
                <th class='c-status'> <?php echo $lang->task->status;?></th>
                <th class='visible-lg c-consumedAB'><?php echo $lang->task->consumedAB . $lang->task->lblHour;?></th>
                <th class='visible-lg c-leftAB'><?php echo $lang->task->leftAB . $lang->task->lblHour;?></th>
                <th class='c-actions'><?php echo $lang->actions;?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($task->children as $child):?>
              <tr class='text-center'>
                <td><?php echo $child->id;?></td>
                <td><?php if($child->pri) echo "<span class='label-pri label-pri-" . $child->pri . "'>" . zget($this->lang->task->priList, $child->pri, $child->pri) . "</span>";?></td>
                <td class='text-left' title='<?php echo $child->name;?>'><a class="iframe" data-width="90%" href="<?php echo $this->createLink('task', 'view', "taskID=$child->id", '', true); ?>"><?php echo $child->name;?></a></td>
                <td><?php echo $child->deadline;?></td>
                <td id='assignedTo'><?php $this->task->printAssignedHtml($child, $users);?></td>
                <td><?php echo $this->processStatus('task', $child);?></td>
                <td class='visible-lg'><?php echo $child->consumed;?></td>
                <td class='visible-lg'><?php echo $child->left;?></td>
                <td class='c-actions'>
                  <?php
                  common::printIcon('task', 'start', "taskID=$child->id", $child, 'list', '', '', 'iframe showinonlybody', true);
                  common::printIcon('task', 'finish', "taskID=$child->id", $child, 'list', '', '', 'iframe showinonlybody', true);
                  common::printIcon('task', 'close', "taskID=$child->id", $child, 'list', '', '', 'iframe showinonlybody', true);
                  common::printIcon('task', 'recordWorkhour', "taskID=$child->id", $child, 'list', 'time', '', 'iframe showinonlybody', true);
                  common::printIcon('task', 'edit', "taskID=$child->id", $child, 'list');
                  common::printIcon('task', 'activate', "taskID=$child->id", $child, 'list', '', '', 'iframe showinonlybody', true);
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
      <?php endif;?>
      <?php
      echo $this->fetch('file', 'printFiles', array('files' => $task->files, 'fieldset' => 'true', 'object' => $task, 'method' => 'view', 'showDelete' => false));

      $canBeChanged = common::canBeChanged('task', $task);
      if($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=task&objectID=$task->id");
      ?>
    </div>
    <?php $this->printExtendFields($task, 'div', "position=left&inForm=0&inCell=1");?>
    <?php if($this->app->getViewType() != 'xhtml'):?>
    <div class="cell"><?php include '../../common/view/action.html.php';?></div>
    <?php endif;?>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php if(!isonlybody() and $this->app->getViewType() != 'xhtml'):?>
        <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn'");?>
        <?php echo "<div class='divider'></div>";?>
        <?php endif;?>
        <?php $task->executionList = $execution;?>
        <?php echo $this->task->buildOperateMenu($task, 'view');?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendBasic' data-toggle='tab'><?php echo $lang->task->legendBasic;?></a></li>
          <li><a href='#legendLife' data-toggle='tab'><?php echo $lang->task->legendLife;?></a></li>
          <?php if(!empty($task->team)) :?>
          <li><a href='#legendTeam' data-toggle='tab'><?php echo $lang->task->team;?></a></li>
          <?php endif;?>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendBasic'>
            <table class="table table-data">
              <tbody>
                <?php if($execution->multiple):?>
                <tr>
                  <th class='w-90px'><?php echo $lang->task->execution;?></th>
                  <td>
                    <?php
                    $method = $this->config->vision == 'lite' ? 'kanban' : 'view';
                    if($execution->type != 'kanban')
                    {
                        common::printLink('execution', $method, "executionID={$task->execution}", $execution->name);
                    }
                    else
                    {
                        echo $execution->name;
                    }
                    ?>
                  </td>
                </tr>
                <?php endif;?>
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
                         if(!common::printLink('execution', 'task', "executionID=$task->execution&browseType=byModule&param=$module->id", $module->name)) echo $module->name;
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
                <?php if($execution->lifetime != 'ops'):?>
                <tr class='nofixed'>
                  <th><?php echo $lang->task->story;?></th>
                  <td>
                    <?php
                    if(!$task->storyTitle) echo $lang->noData;
                    $class = isonlybody() ? 'showinonlybody' : 'iframe';
                    if($task->storyTitle and !common::printLink('execution', 'storyView', "storyID=$task->story", $task->storyTitle, '', "class=$class data-width='80%'", true, true)) echo $task->storyTitle;
                    if($task->needConfirm)
                    {
                        echo "(<span class='warning'>{$lang->story->changed}</span> ";
                        echo html::a($this->createLink('task', 'confirmStoryChange', "taskID=$task->id"), $lang->confirm, 'hiddenwin', "class='btn btn-mini btn-info'");
                        echo ")";
                    }
                    ?>
                  </td>
                </tr>
                <?php endif;?>
                <?php if($task->fromBug):?>
                <tr>
                  <th><?php echo $lang->task->fromBug;?></th>
                  <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$task->fromBug", '', true), "#$task->fromBug " . $fromBug->title, '', "class='iframe' data-width='80%'");?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th><?php echo $lang->task->assignedTo;?></th>
                  <td>
                    <?php
                    if(!empty($task->team) and $task->mode == 'multi' and strpos('done,cancel,closed', $task->status) === false)
                    {
                        foreach($task->team as $member) echo ' ' . zget($users, $member->account);
                    }
                    else
                    {
                        echo $task->assignedTo ? $task->assignedToRealName . $lang->at . substr($task->assignedDate, 0, 19) : $lang->noData;
                    }
                    ?>
                  </td>
                </tr>
                <?php if($task->mode):?>
                <tr>
                  <th><?php echo $lang->task->mode;?></th>
                  <td><?php echo zget($lang->task->modeList, $task->mode);?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th><?php echo $lang->task->type;?></th>
                  <td><?php echo zget($this->lang->task->typeList, $task->type, $task->type);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->task->status;?></th>
                  <td><span class='status-task status-<?php echo $task->status;?>'><span class="label label-dot"></span> <?php echo $this->processStatus('task', $task);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->task->progress;?></th>
                  <td><?php echo $task->progress . '%';?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->task->pri;?></th>
                  <td><span class='label-pri <?php echo 'label-pri-' . $task->pri;?>' title='<?php echo zget($lang->task->priList, $task->pri);?>'><?php echo $task->pri == '0' ? $lang->noData : zget($lang->task->priList, $task->pri)?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->task->mailto;?></th>
                  <td>
                    <?php
                    if(empty($task->mailto))
                    {
                        echo $lang->noData;
                    }
                    else
                    {
                        foreach(explode(',', str_replace(' ', '', $task->mailto)) as $account) echo ' ' . zget($users, $account, $account);
                    }
                    ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='tab-pane' id='legendLife'>
            <table class='table table-data'>
              <tr>
                <th class='thWidth'><?php echo $lang->task->openedBy;?></th>
                <td><?php echo $task->openedBy ? zget($users, $task->openedBy, $task->openedBy) . $lang->at . $task->openedDate : $lang->noData;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->finishedBy;?></th>
                <td><?php echo ($task->finishedBy) ? zget($users, $task->finishedBy, $task->finishedBy) . $lang->at . $task->finishedDate : $lang->noData;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->canceledBy;?></th>
                <td><?php echo $task->canceledBy ? zget($users, $task->canceledBy, $task->canceledBy) . $lang->at . $task->canceledDate : $lang->noData;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->closedBy;?></th>
                <td><?php echo $task->closedBy ? zget($users, $task->closedBy, $task->closedBy) . $lang->at . $task->closedDate : $lang->noData;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->closedReason;?></th>
                <td><?php echo $task->closedReason ? $lang->task->reasonList[$task->closedReason] : $lang->noData;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->lastEdited;?></th>
                <td><?php echo $task->lastEditedBy ? zget($users, $task->lastEditedBy, $task->lastEditedBy) . $lang->at . $task->lastEditedDate : $lang->noData;?></td>
              </tr>
            </table>
          </div>
          <div class='tab-pane' id='legendTeam'>
            <table class='table table-data'>
              <thead>
              <tr>
                <th><?php echo $lang->task->team?></th>
                <th class='text-center c-hours'><?php echo $lang->task->estimateAB?></th>
                <th class='text-center c-hours'><?php echo $lang->task->consumedAB?></th>
                <th class='text-center c-hours'><?php echo $lang->task->leftAB?></th>
                <th class='text-center'><?php echo $lang->statusAB;?></th>
              </tr>
              </thead>
                <?php foreach($task->team as $member):?>
                <tr class='text-center'>
                  <td class='text-left'><?php echo zget($users, $member->account);?></td>
                  <td><?php echo (float)$member->estimate?></td>
                  <td><?php echo (float)$member->consumed?></td>
                  <td><?php echo (float)$member->left?></td>
                  <td class="status-<?php echo $member->status;?>"><?php echo zget($lang->task->statusList, $member->status);?></td>
                </tr>
                <?php endforeach;?>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendEffort' data-toggle='tab'><?php echo $lang->task->legendEffort;?></a></li>
          <li><a href='#legendMisc' data-toggle='tab'><?php echo $lang->task->legendMisc;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendEffort'>
            <table class="table table-data">
              <tr>
                <th class='effortThWidth'><?php echo $lang->task->estimate;?></th>
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
              <tr>
                <th><?php echo $lang->task->estStarted;?></th>
                <td><?php echo $task->estStarted;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->realStarted;?></th>
                <td><?php echo helper::isZeroDate($task->realStarted) ? '' : substr($task->realStarted, 0, 19); ?> </td>
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
            </table>
          </div>
          <div class='tab-pane' id='legendMisc'>
            <table class="table table-data">
              <tr>
                <th class='MRThWidth'><?php echo $lang->task->linkMR;?></th>
                <td>
                  <?php
                  $canViewMR = common::hasPriv('mr', 'view');
                  foreach($linkMRTitles as $MRID => $linkMRTitle)
                  {
                      echo ($canViewMR ? html::a($this->createLink('mr', 'view', "MRID=$MRID"), "#$MRID $linkMRTitle") : "#$MRID $linkMRTitle") . '<br />';
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <th class='MRThWidth'><?php echo $lang->task->linkCommit;?></th>
                <td>
                  <?php
                  $canViewRevision = common::hasPriv('repo', 'revision');
                  foreach($linkCommits as $commit)
                  {
                      $revision    = substr($commit->revision, 0, 10);
                      $commitTitle = $revision . ' ' . $commit->comment;
                      echo "<div class='link-commit' title='$commitTitle'>" . ($canViewRevision ? html::a($this->createLink('repo', 'revision', "repoID={$commit->repo}&objectID=0&revision={$commit->revision}"), "$revision") . ' ' . $commit->comment : $commitTitle) . '<br />';
                  }
                  ?>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php $this->printExtendFields($task, 'div', "position=right&inForm=0&inCell=1");?>
  </div>
</div>
<?php if($this->app->getViewType() == 'xhtml'):?>
</div>
<?php endif;?>

<div id="mainActions" class='main-actions'>
  <?php common::printPreAndNext($preAndNext);?>
</div>
<script>
</script>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>
