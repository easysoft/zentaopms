<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: view.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('sysurl', common::getSysUrl());?>
<?php js::set('confrimToStory', $lang->bug->notice->confirmToStory);?>
<?php js::set('tab', $app->tab);?>
<?php js::set('bugID', $bug->id);?>
<?php js::set('branchID', $bug->branch);?>
<?php js::set('errorNoExecution', $lang->bug->noExecution);?>
<?php js::set('errorNoProject', $lang->bug->noProject);?>
<?php $browseLink = $app->session->bugList ? $app->session->bugList : inlink('browse', "productID=$bug->product");?>
<?php if(strpos($_SERVER["QUERY_STRING"], 'isNotice=1') === false):?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $bug->id?></span>
      <span class="text" title="<?php echo $bug->title;?>" style='color: <?php echo $bug->color; ?>'><?php echo $bug->title;?></span>
      <?php if($bug->deleted):?>
      <span class='label label-danger'><?php echo $lang->bug->deleted;?></span>
      <?php endif; ?>
      <?php if($bug->case):?>
      <small><?php echo html::a(helper::createLink('testcase', 'view', "caseID=$bug->case&version=$bug->caseVersion", '', true), "<i class='icon icon-sitemap'></i> {$lang->bug->fromCase}$lang->colon$bug->case", '', isonlybody() ? '' : "data-toggle='modal' data-type='iframe' data-width='80%'");?></small>
      <?php endif;?>
    </div>
  </div>
  <?php if(!isonlybody()):?>
  <div class="btn-toolbar pull-right">
    <?php if(common::canModify('product', $product)):?>
    <?php $tab = strpos('|execution|project|qa|', $this->app->tab) !== false ? $this->app->tab : 'qa';?>
    <?php if($this->app->tab != 'product') common::printLink('bug', 'create', "productID={$bug->product}&branch={$bug->branch}&extra=moduleID={$bug->module},projectID={$bug->project},executionID={$bug->execution}", "<i class='icon icon-plus'></i> " . $lang->bug->create, '', "class='btn btn-primary' data-app='$tab'"); ?>
    <?php endif;?>
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
        <div class="detail-title"><?php echo $lang->bug->legendSteps;?></div>
        <div class="detail-content article-content">
          <?php
          $tplStep = strip_tags(trim($lang->bug->tplStep));
          $steps   = str_replace('<p>' . $tplStep, '<p class="stepTitle">' . $tplStep . '</p><p>', $bug->steps);

          $tplResult = strip_tags(trim($lang->bug->tplResult));
          $steps     = str_replace('<p>' . $tplResult, '<p class="stepTitle">' . $tplResult . '</p><p>', $steps);

          $tplExpect = strip_tags(trim($lang->bug->tplExpect));
          $steps     = str_replace('<p>' . $tplExpect, '<p class="stepTitle">' . $tplExpect . '</p><p>', $steps);

          $steps = str_replace('<p></p>', '', $steps);
          echo $steps;
          ?>
        </div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $bug->files, 'fieldset' => 'true', 'object' => $bug, 'method' => 'view', 'showDelete' => false));?>
      <?php
      $canBeChanged = common::canBeChanged('bug', $bug);
      if($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=bug&objectID=$bug->id");
      ?>
    </div>
    <?php $this->printExtendFields($bug, 'div', "position=left&inForm=0&inCell=1");?>
    <?php if($this->app->getViewType() != 'xhtml'):?>
    <div class="cell"><?php include '../../common/view/action.html.php';?></div>
    <?php endif;?>
    <?php
    $params        = "bugID=$bug->id";
    $extraParams   = "extras=bugID=$bug->id";
    if($this->app->tab == 'project')   $extraParams .= ",projectID={$bug->project}";
    if($this->app->tab == 'execution') $extraParams .= ",executionID={$bug->execution}";
    $copyParams    = "productID=$productID&branch=$bug->branch&$extraParams";
    $convertParams = "productID=$productID&branch=$bug->branch&moduleID=0&from=bug&bugID=$bug->id";
    ?>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!$bug->deleted):?>
        <div class='divider'></div>
        <?php echo $this->bug->buildOperateMenu($bug, 'view');?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendBasicInfo' data-toggle='tab'><?php echo $lang->bug->legendBasicInfo;?></a></li>
          <li><a href='#legendExecStoryTask' data-toggle='tab'><?php echo !empty($project->multiple) ? $lang->bug->legendPRJExecStoryTask : $lang->bug->legendExecStoryTask;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendBasicInfo'>
            <table class="table table-data">
              <tbody>
                <?php if(empty($product->shadow)):?>
                <tr valign='middle'>
                  <th class='thWidth'><?php echo $lang->bug->product;?></th>
                  <td><?php if(!common::printLink('product', 'view', "productID=$bug->product", $product->name, '', "data-app='product'")) echo $product->name;?></td>
                </tr>
                <?php endif;?>
                <?php if($product->type != 'normal'):?>
                <tr>
                  <th class='thWidth'><?php echo sprintf($lang->product->branch, $lang->product->branchName[$product->type]);?></th>
                  <td><?php if(!common::printLink('bug', 'browse', "productID=$bug->product&branch=$bug->branch", $branchName)) echo $branchName;?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th class='thWidth'><?php echo $lang->bug->module;?></th>
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
                      if($bugModule->branch and isset($branches[$bugModule->branch]))
                      {
                          $moduleTitle .= $branches[$bugModule->branch] . '/';
                          echo $branches[$bugModule->branch] . $lang->arrow;
                      }

                      foreach($modulePath as $key => $module)
                      {
                          $moduleTitle .= $module->name;
                          if(!common::printLink('bug', 'browse', "productID=$bug->product&branch=$module->branch&browseType=byModule&param=$module->id", $module->name, '', "data-app='qa'")) echo $module->name;
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
                <tr>
                  <th><?php echo $lang->bug->fromCase;?></th>
                  <td><?php if($bug->case) echo html::a(helper::createLink('testcase', 'view', "caseID=$bug->case&version=$bug->caseVersion", '', true), "<i class='icon icon-sitemap'></i> {$lang->bug->fromCase}$lang->colon$bug->case", '', isonlybody() ? '' : "data-toggle='modal' data-type='iframe' data-width='80%'");?></td>
                </tr>
                <tr valign='middle' class='<?php if($product->shadow and isset($project) and empty($project->multiple)) echo 'hide'?>'>
                  <th><?php echo $lang->bug->plan;?></th>
                  <td><?php if(!$bug->plan or !common::printLink('productplan', 'view', "planID=$bug->plan&type=bug", $bug->planName)) echo $bug->planName;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->type;?></th>
                  <td><?php if(isset($lang->bug->typeList[$bug->type])) echo $lang->bug->typeList[$bug->type]; else echo $bug->type;?></td>
                </tr>
                <tr>
                  <?php
                  $hasCustomSeverity = false;
                  foreach($lang->bug->severityList as $severityKey => $severityValue)
                  {
                      if(!empty($severityKey) and (string)$severityKey != (string)$severityValue)
                      {
                          $hasCustomSeverity = true;
                          break;
                      }
                  }
                  ?>
                  <th><?php echo $lang->bug->severity;?></th>
                  <td>
                    <?php if($hasCustomSeverity):?>
                    <span class='label-severity-custom' data-severity='<?php echo $bug->severity;?>' title='<?php echo zget($lang->bug->severityList, $bug->severity);?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?></span>
                    <?php else:?>
                    <span class='label-severity' data-severity='<?php echo $bug->severity;?>' title='<?php echo zget($lang->bug->severityList, $bug->severity)?>'></span>
                    <?php endif;?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->pri;?></th>
                  <td>
                    <?php if($bug->pri):?>
                    <span class='label-pri <?php echo 'label-pri-' . $bug->pri;?>' title='<?php echo zget($lang->bug->priList, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri)?></span>
                    <?php endif;?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->status;?></th>
                  <td><span class='status-bug status-<?php echo $bug->status?>'><?php echo $this->processStatus('bug', $bug);?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->activatedCount;?></th>
                  <td><?php echo $bug->activatedCount;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->activatedDate;?></th>
                  <td><?php echo $bug->activatedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->confirmed;?></th>
                  <td class='confirm<?php echo $bug->confirmed;?>'><?php echo $lang->bug->confirmedList[$bug->confirmed];?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->lblAssignedTo;?></th>
                  <td><?php if($bug->assignedTo) echo zget($users, $bug->assignedTo) . $lang->at . $bug->assignedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->deadline;?></th>
                  <td>
                    <?php
                    if($bug->deadline) echo helper::isZeroDate($bug->deadline) ? '' : $bug->deadline;
                    if(isset($bug->delay)) printf($lang->bug->notice->delayWarning, $bug->delay);
                    ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->feedbackBy;?></th>
                  <td><?php echo $bug->feedbackBy;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->notifyEmail;?></th>
                  <td><?php echo $bug->notifyEmail;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->os;?></th>
                  <td>
                  <?php $osList = explode(',', $bug->os);?>
                  <?php if($osList):?>
                  <p class='osContent'>
                    <?php foreach($osList as $os):?>
                    <?php if($os) echo "<span class='label label-outline'>" .  zget($lang->bug->osList, $os) . "</span>";?>
                    <?php endforeach;?>
                  </p>
                  <?php endif;?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->browser;?></th>
                  <td>
                  <?php $browserList = explode(',', $bug->browser);?>
                  <?php if($browserList):?>
                  <p class='browserContent'>
                    <?php foreach($browserList as $browser):?>
                    <?php if($browser) echo "<span class='label label-outline'>" .  zget($lang->bug->browserList, $browser) . "</span>";?>
                    <?php endforeach;?>
                  </p>
                  <?php endif;?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->keywords;?></th>
                  <td><?php echo $bug->keywords;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->mailto;?></th>
                  <td>
                  <?php
                  if(!empty($bug->mailto))
                  {
                      foreach(explode(',', str_replace(' ', '', $bug->mailto)) as $account) echo ' ' . zget($users, $account);
                  }
                  ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='tab-pane' id='legendExecStoryTask'>
            <table class='table table-data'>
              <tbody>
                <tr>
                  <th class='w-70px'><?php echo $lang->bug->project;?></th>
                  <td><?php if($bug->project) echo html::a($this->createLink('project', 'view', "projectID=$bug->project"), $bug->projectName);?></td>
                </tr>
                <?php if(!empty($project->multiple)):?>
                <tr>
                  <th class='w-70px'><?php echo (isset($project->model) and $project->model == 'kanban') ? $lang->bug->kanban : $lang->bug->execution;?></th>
                  <td><?php if($bug->execution) echo html::a($this->createLink('execution', 'browse', "executionID=$bug->execution"), $bug->executionName);?></td>
                </tr>
                <?php endif;?>
                <tr class='nofixed'>
                  <th><?php echo $lang->bug->story;?></th>
                  <td>
                    <?php
                    if($bug->story) echo html::a($this->createLink('story', 'view', "storyID=$bug->story", '', true), "#$bug->story $bug->storyTitle", '', "class='iframe' data-width='80%'");
                    if($bug->storyStatus == 'active' and $bug->latestStoryVersion > $bug->storyVersion and common::hasPriv('bug', 'confirmStoryChange'))
                    {
                        echo "(<span class='warning'>{$lang->story->changed}</span> ";
                        echo html::a($this->createLink('bug', 'confirmStoryChange', "bugID=$bug->id"), $lang->confirm, 'hiddenwin');
                        echo ")";
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->task;?></th>
                  <td><?php if($bug->task) echo html::a($this->createLink('task', 'view', "taskID=$bug->task", '', true), $bug->taskName, '', "class='iframe' data-width='80%'");?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendLife' data-toggle='tab'><?php echo $lang->bug->legendLife;?></a></li>
          <li><a href='#legendMisc' data-toggle='tab'><?php echo $lang->bug->legendMisc;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendLife'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='thWidth'><?php echo $lang->bug->openedBy;?></th>
                  <td> <?php echo zget($users, $bug->openedBy) . $lang->at . $bug->openedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->openedBuild;?></th>
                  <td>
                    <?php
                    if($bug->openedBuild)
                    {
                        $openedBuilds = explode(',', $bug->openedBuild);
                        foreach($openedBuilds as $openedBuild)
                        {
                            if(!$openedBuild) continue;
                            isset($builds[$openedBuild]) ? print($builds[$openedBuild] . '<br />') : print($openedBuild . '<br />');
                        }
                    }
                    else
                    {
                        echo $bug->openedBuild;
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->lblResolved;?></th>
                  <td><?php if($bug->resolvedBy) echo zget($users, $bug->resolvedBy) . $lang->at . $bug->resolvedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->resolvedBuild;?></th>
                  <td><?php if(isset($builds[$bug->resolvedBuild])) echo $builds[$bug->resolvedBuild]; else echo $bug->resolvedBuild;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->resolution;?></th>
                  <td class='resolution'>
                    <?php
                    echo isset($lang->bug->resolutionList[$bug->resolution]) ? $lang->bug->resolutionList[$bug->resolution] : $bug->resolution;
                    if(isset($bug->duplicateBugTitle)) echo " #$bug->duplicateBug:" . html::a($this->createLink('bug', 'view', "bugID=$bug->duplicateBug", '', true), $bug->duplicateBugTitle, '', "title='{$bug->duplicateBugTitle}' class='iframe' data-width='80%'");
                    ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->closedBy;?></th>
                  <td><?php if($bug->closedBy) echo zget($users, $bug->closedBy) . $lang->at . $bug->closedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->lblLastEdited;?></th>
                  <td><?php if($bug->lastEditedBy) echo zget($users, $bug->lastEditedBy, $bug->lastEditedBy) . $lang->at . $bug->lastEditedDate?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='tab-pane' id='legendMisc'>
            <table class="table table-data">
              <tbody>
                <tr class='text-top'>
                  <th class='w-90px'><?php echo $lang->bug->relatedBug;?></th>
                  <td>
                    <?php
                    if(isset($bug->relatedBugTitles))
                    {
                        foreach($bug->relatedBugTitles as $relatedBugID => $relatedBugTitle)
                        {
                            echo html::a($this->createLink('bug', 'view', "bugID=$relatedBugID", '', true), "#$relatedBugID $relatedBugTitle", '', "class='iframe' data-width='80%'") . '<br />';
                        }
                    }
                    ?>
                  </td>
                </tr>
                <?php if($bug->case):?>
                <tr>
                  <th><?php echo $lang->bug->fromCase;?></th>
                  <td><?php echo html::a($this->createLink('testcase', 'view', "caseID=$bug->case&caseVersion=$bug->caseVersion", '', true), "#$bug->case $bug->caseTitle", '', "class='iframe' data-width='80%'");?></td>
                </tr>
                <?php endif;?>
                <?php if($bug->toCases):?>
                <tr>
                  <th><?php echo $lang->bug->toCase;?></th>
                  <td>
                  <?php
                  foreach($bug->toCases as $caseID => $case)
                  {
                      echo '<p style="margin-bottom:0;">' . html::a($this->createLink('testcase', 'view', "caseID=$caseID", '', true), $case, '', "class='iframe' data-width='80%'") . '</p>';
                  }
                  ?>
                  </td>
                </tr>
                <?php endif;?>
                <?php if($bug->toStory != 0):?>
                <tr>
                  <th><?php echo $lang->bug->toStory;?></th>
                  <td><?php echo html::a($this->createLink('story', 'view', "storyID=$bug->toStory", '', true), "#$bug->toStory $bug->toStoryTitle", '', "class='iframe' data-width='80%'");?></td>
                </tr>
                <?php endif;?>
                <?php if($bug->toTask != 0):?>
                <tr>
                  <th><?php echo $lang->bug->toTask;?></th>
                  <td><?php echo html::a($this->createLink('task', 'view', "taskID=$bug->toTask", '', true), "#$bug->toTask $bug->toTaskTitle", '', "class='iframe' data-width='80%'");?></td>
                </tr>
                <?php endif;?>
                <?php if(helper::hasFeature('devops')):?>
                <tr>
                  <th><?php echo $lang->bug->linkMR;?></th>
                  <td>
                    <?php
                    $canViewMR = common::hasPriv('mr', 'view');
                    foreach($bug->linkMRTitles as $MRID => $linkMRTitle)
                    {
                        echo ($canViewMR ? html::a($this->createLink('mr', 'view', "MRID=$MRID"), "#$MRID $linkMRTitle") : "#$MRID $linkMRTitle") . '<br />';
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->linkCommit;?></th>
                  <td>
                    <?php
                    $canViewRevision = common::hasPriv('repo', 'revision');
                    foreach($linkCommits as $commit)
                    {
                        $revision    = substr($commit->revision, 0, 10);
                        $commitTitle = $revision . ' ' . $commit->comment;
                        echo "<div class='link-commit' title='$commitTitle'>" . ($canViewRevision ? html::a($this->createLink('repo', 'revision', "repoID={$commit->repo}&objectID=0&revision={$commit->revision}"), $revision) . " $commit->comment" : $commitTitle) . '</div>';
                    }
                    ?>
                  </td>
                </tr>
                <?php endif;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php $this->printExtendFields($bug, 'div', "position=right&inForm=0&inCell=1");?>
  </div>
</div>
<?php if($this->app->getViewType() == 'xhtml'):?>
</div>
<?php endif;?>

<div id="mainActions" class='main-actions'>
  <?php common::printPreAndNext($preAndNext);?>
</div>
<div class="modal fade" id="toTask">
  <div class="modal-dialog mw-500px select-project-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo $lang->bug->selectProjects;?></h4>
      </div>
      <div class="modal-body">
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->bug->project;?></th>
            <td class='required'><?php echo html::select('taskProjects', $projects, '', "class='form-control chosen' onchange='loadProductExecutions({$productID}, this.value)'");?></td>
          </tr>
          <tr>
            <th id='executionHead'><?php echo $lang->bug->execution;?></th>
            <td id='executionBox' class='required'><?php echo html::select('execution', '', '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <td colspan='2' class='text-center'>
              <?php echo html::commonButton($lang->bug->nextStep, "id='toTaskButton'", 'btn btn-primary btn-wide');?>
              <?php echo html::commonButton($lang->cancel, "id='cancelButton' data-dismiss='modal'", 'btn btn-default btn-wide');?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
</script>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>
