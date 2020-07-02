<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: view.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('sysurl', common::getSysUrl());?>
<?php $browseLink = $app->session->bugList != false ? $app->session->bugList : inlink('browse', "productID=$bug->product");?>
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
	</div>
  </div>
  <?php if(!isonlybody()):?>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('bug', 'create', "productID={$bug->product}&branch={$bug->branch}&extra=moduleID={$bug->module}", "<i class='icon icon-plus'></i>" . $lang->bug->create, '', "class='btn btn-primary'"); ?>
  </div>
  <?php endif;?>
</div>
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
      <?php echo $this->fetch('file', 'printFiles', array('files' => $bug->files, 'fieldset' => 'true'));?>
      <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=bug&objectID=$bug->id");?>
    </div>
    <?php $this->printExtendFields($bug, 'div', "position=left&inForm=0&inCell=1");?>
    <div class='cell'><?php include '../../common/view/action.html.php';?></div>
    <?php
    $params        = "bugID=$bug->id";
    $copyParams    = "productID=$productID&branch=$bug->branch&extras=bugID=$bug->id";
    $convertParams = "productID=$productID&branch=$bug->branch&moduleID=0&from=bug&bugID=$bug->id";
    ?>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!$bug->deleted):?>
        <div class='divider'></div>
        <?php
        common::printIcon('bug', 'confirmBug', $params, $bug, 'button', 'search', '', 'iframe', true);
        common::printIcon('bug', 'assignTo',   $params, $bug, 'button', '', '', 'iframe', true);
        common::printIcon('bug', 'resolve',    $params, $bug, 'button', 'checked', '', 'iframe showinonlybody', true);
        common::printIcon('bug', 'close',      $params, $bug, 'button', '', '', 'text-danger iframe showinonlybody', true);
        common::printIcon('bug', 'activate',   $params, $bug, 'button', '', '', 'text-success iframe showinonlybody', true);

        if($config->global->flow != 'onlyTest') common::printIcon('bug', 'toStory', "product=$bug->product&branch=$bug->branch&module=0&story=0&project=0&bugID=$bug->id", $bug, 'button', $lang->icons['story']);
        common::printIcon('bug', 'createCase', $convertParams, $bug, 'button', 'sitemap');

        echo $this->buildOperateMenu($bug, 'view');

        echo "<div class='divider'></div>";
        common::printIcon('bug', 'edit', $params, $bug);
        common::printIcon('bug', 'create', $copyParams, $bug, 'button', 'copy');
        common::printIcon('bug', 'delete', $params, $bug, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendBasicInfo' data-toggle='tab'><?php echo $lang->bug->legendBasicInfo;?></a></li>
          <?php if($config->global->flow != 'onlyTest'):?>
          <li><a href='#legendPrjStoryTask' data-toggle='tab'><?php echo $lang->bug->legendPrjStoryTask;?></a></li>
          <?php endif;?>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendBasicInfo'>
            <table class="table table-data">
              <tbody>
                <tr valign='middle'>
                  <th class='thWidth'><?php echo $lang->bug->product;?></th>
                  <td><?php if(!common::printLink('bug', 'browse', "productID=$bug->product", $productName)) echo $productName;?></td>
                </tr>
                <?php if($this->session->currentProductType != 'normal'):?>
                <tr>
                  <th><?php echo $lang->product->branch;?></th>
                  <td><?php if(!common::printLink('bug', 'browse', "productID=$bug->product&branch=$bug->branch", $branchName)) echo $branchName;?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th><?php echo $lang->bug->module;?></th>
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
                          if(!common::printLink('bug', 'browse', "productID=$bug->product&branch=$module->branch&browseType=byModule&param=$module->id", $module->name)) echo $module->name;
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
                <tr valign='middle'>
                  <th><?php echo $lang->bug->productplan;?></th>
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
                  <td><span class='label-pri <?php echo 'label-pri-' . $bug->pri;?>' title='<?php echo zget($lang->bug->priList, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri)?></span></td>
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
                  <td><?php echo $lang->bug->confirmedList[$bug->confirmed];?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->lblAssignedTo;?></th>
                  <td><?php if($bug->assignedTo) echo zget($users, $bug->assignedTo) . $lang->at . $bug->assignedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->deadline;?></th>
                  <td>
                    <?php 
                    if($bug->deadline) echo  $bug->deadline;
                    if(isset($bug->delay)) printf($lang->bug->delayWarning, $bug->delay);
                    ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->os;?></th>
                  <td><?php echo $lang->bug->osList[$bug->os];?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->browser;?></th>
                  <td><?php echo $lang->bug->browserList[$bug->browser];?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->keywords;?></th>
                  <td><?php echo $bug->keywords;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->bug->mailto;?></th>
                  <td><?php $mailto = explode(',', str_replace(' ', '', $bug->mailto)); foreach($mailto as $account) echo ' ' . zget($users, $account); ?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <?php if($config->global->flow != 'onlyTest'):?>
          <div class='tab-pane' id='legendPrjStoryTask'>
            <table class='table table-data'>
              <tbody>
                <tr>
                  <th class='w-60px'><?php echo $lang->bug->project;?></th>
                  <td><?php if($bug->project) echo html::a($this->createLink('project', 'browse', "projectid=$bug->project"), $bug->projectName);?></td>
                </tr>
                <tr class='nofixed'>
                  <th><?php echo $lang->bug->story;?></th>
                  <td>
                    <?php
                    if($bug->story) echo html::a($this->createLink('story', 'view', "storyID=$bug->story", '', true), "#$bug->story $bug->storyTitle", '', "class='iframe' data-width='80%'");
                    if($bug->storyStatus == 'active' and $bug->latestStoryVersion > $bug->storyVersion)
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
          <?php endif;?>
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
                        foreach($openedBuilds as $openedBuild) isset($builds[$openedBuild]) ? print($builds[$openedBuild] . '<br />') : print($openedBuild . '<br />');
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
                  <td>
                    <?php
                    echo isset($lang->bug->resolutionList[$bug->resolution]) ? $lang->bug->resolutionList[$bug->resolution] : $bug->resolution;
                    if(isset($bug->duplicateBugTitle)) echo " #$bug->duplicateBug:" . html::a($this->createLink('bug', 'view', "bugID=$bug->duplicateBug", '', true), $bug->duplicateBugTitle, '', "class='iframe' data-width='80%'");
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
                  <th class='w-80px'><?php echo $lang->bug->linkBug;?></th>
                  <td>
                    <?php
                    if(isset($bug->linkBugTitles))
                    {
                        foreach($bug->linkBugTitles as $linkBugID => $linkBugTitle)
                        {
                            echo html::a($this->createLink('bug', 'view', "bugID=$linkBugID", '', true), "#$linkBugID $linkBugTitle", '', "class='iframe' data-width='80%'") . '<br />';
                        }
                    }
                    ?>
                  </td>
                </tr>
                <?php if($bug->case):?>
                <tr>
                  <th class='w-60px'><?php echo $lang->bug->fromCase;?></th>
                  <td><?php echo html::a($this->createLink('testcase', 'view', "caseID=$bug->case&caseVersion=" . ($bug->testtask ? "&from=testtask&taskID={$bug->testtask}" : ''), '', true), "#$bug->case $bug->caseTitle", '', "class='iframe' data-width='80%'");?></td>
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
                <?php if($config->global->flow != 'onlyTest'):?>
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

<div id="mainActions" class='main-actions'>
  <?php common::printPreAndNext($preAndNext);?>
</div>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>
