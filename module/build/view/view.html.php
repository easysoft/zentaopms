<?php
/**
 * The view file of build module's view method of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: view.html.php 4386 2013-02-19 07:37:45Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->build->confirmUnlinkStory)?>
<?php js::set('confirmUnlinkBug', $lang->build->confirmUnlinkBug)?>
<?php js::set('flow', $config->global->flow)?>
<?php if(isonlybody()):?>
<style>
#stories .action {display: none;}
#bugs .action {display: none;}
tbody tr td:first-child input {display: none;}
.page-title .dropdown-menu {top: 40px; left: 70px;}
</style>
<?php endif;?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $browseLink = $this->session->buildList ? $this->session->buildList : $this->createLink('execution', 'build', "executionID=$build->execution");?>
    <?php
    $dataApp = strpos($browseLink, 'release') !== false ? 'data-app=product' : '';
    if(strpos($browseLink, 'projectrelease') !== false) $dataApp = 'data-app=project';
    ?>
    <?php common::printBack($browseLink, 'btn btn-secondary', $dataApp);?>
    <div class='divider'></div>
    <div class='page-title'>
      <span title='<?php echo $build->name;?>'>
      <?php echo html::a('javascript:void(0)', "<span class='label label-id'>{$build->id}</span> " . $build->name . " <span class='caret'></span>", '', "data-toggle='dropdown' class='text btn btn-link btn-active-text'");?>
      <?php
      echo "<ul class='dropdown-menu'>";
      foreach($buildPairs as $id => $name)
      {
          $buildInfo = zget($builds, $id);
          echo '<li' . ($id == $build->id ? " class='active'" : '') . '>';
          echo html::a($this->createLink($buildInfo->execution ? 'build' : 'projectbuild', 'view', "buildID=$id") . "#app={$app->tab}", $name);
          echo '</li>';
      }
      echo '</ul>';
      ?>
      </span>
      <?php if($build->deleted):?>
      <span class='label label-danger'><?php echo $lang->build->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
  <?php if(!isonlybody()):?>
  <div class='btn-toolbar pull-right'>
    <?php echo $this->build->buildOperateMenu($build, 'view');?>
  </div>
  <?php endif;?>
</div>
<?php $module = $this->app->tab == 'project' ? 'projectbuild' : 'build';?>
<div id='mainContent' class='main-content'>
  <div class='tabs' id='tabsNav'>
  <?php $countStories = count($stories); $countBugs = count($bugs); $countGeneratedBugs = count($generatedBugs);?>
    <ul class='nav nav-tabs'>
      <li <?php if($type == 'story')        echo "class='active'"?>><a href='#stories' data-toggle='tab'><?php echo html::icon($lang->icons['story'], 'text-primary') . ' ' . $lang->build->stories;?></a></li>
      <li <?php if($type == 'bug')          echo "class='active'"?>><a href='#bugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug'], 'text-green') . ' ' . $lang->build->bugs;?></a></li>
      <li <?php if($type == 'generatedBug') echo "class='active'"?>><a href='#generatedBugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug'], 'text-red') . ' ' . $lang->build->generatedBugs;?></a></li>
      <li <?php if($type == 'buildInfo')    echo "class='active'"?>><a href='#buildInfo' data-toggle='tab'><?php echo html::icon($lang->icons['plan'], 'text-info') . ' ' . $lang->build->view;?></a></li>
    </ul>
    <div class='tab-content'>
      <div class='tab-pane <?php if($type == 'story') echo 'active'?>' id='stories'>
        <?php if($canBeChanged and common::hasPriv($module, 'linkStory') and !isonlybody()):?>
        <div class='actions'><?php echo html::a("javascript:showLink($build->id, \"story\")", '<i class="icon-link"></i> ' . $lang->build->linkStory, '', "class='btn btn-primary'");?></div>
        <div class='linkBox cell hidden'></div>
        <?php endif;?>
        <form class='main-table table-story<?php if($link === 'true' and $type == 'story') echo " hidden";?>' data-ride='table' method='post' target='hiddenwin' action='<?php echo $this->createLink($module, 'batchUnlinkStory', "buildID={$build->id}")?>' id='linkedStoriesForm'>
          <table class='table has-sort-head' id='storyList'>
            <?php $canBatchUnlink = ($canBeChanged and common::hasPriv($module, 'batchUnlinkStory'));?>
            <?php $vars = "buildID={$build->id}&type=story&link=$link&param=$param&orderBy=%s";?>
            <thead>
              <tr class='text-center'>
                <th class='c-id text-left'>
                  <?php if($canBatchUnlink):?>
                  <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                    <label></label>
                  </div>
                  <?php endif;?>
                  <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
                </th>
                <th class='text-left'><?php common::printOrderLink('title', $orderBy, $vars, $lang->story->title);?></th>
                <th class='c-pri' title=<?php echo $lang->pri;?>><?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
                <th class='c-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->statusAB);?></th>
                <?php if($childBuilds):?>
                <th class='c-build'><?php echo $lang->build->linkedBuild;?></th>
                <?php endif;?>
                <th class='c-user'>     <?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->openedByAB);?></th>
                <th class='c-estimate'> <?php common::printOrderLink('estimate', $orderBy, $vars, $lang->story->estimateAB);?></th>
                <th class='c-stage'>   <?php common::printOrderLink('stage',     $orderBy, $vars, $lang->story->stageAB);?></th>
                <th class='c-actions-1'><?php echo $lang->actions?></th>
              </tr>
            </thead>
            <tbody class='text-center'>
              <?php foreach($stories as $storyID => $story):?>
              <?php
              $unlinkClass = strpos(",$build->allStories,", ",$story->id,") !== false ? '' : "disabled";
              $buildName   = $build->name;
              if($unlinkClass == 'disabled')
              {
                  foreach($childBuilds as $childBuild)
                  {
                      if(strpos(",$childBuild->stories,", ",$story->id,") !== false)
                      {
                          $buildName = $childBuild->name;
                          break;
                      }
                  }
              }
              ?>
              <tr>
                <td class='c-id text-left'>
                  <?php if($canBatchUnlink):?>
                  <?php echo html::checkbox('unlinkStories', array($story->id => sprintf('%03d', $story->id)), '', $unlinkClass);?>
                  <?php else:?>
                  <?php printf('%03d', $story->id);?>
                  <?php endif;?>
                </td>
                <td class='text-left nobr' title='<?php echo $story->title?>'>
                  <?php
                  if($story->parent > 0) echo "<span class='label label-badge label-light'>{$lang->story->childrenAB}</span>";
                  if($this->app->tab == 'execution' and common::hasPriv('execution', 'storyView'))
                  {
                      echo html::a($this->createLink('execution', 'storyView', "storyID=$story->id", '', true), $story->title, '', isonlybody() ? "data-width='1000'" : "class='iframe' data-width='1000'");
                  }
                  elseif($this->app->tab == 'project' and common::hasPriv('projectstory', 'view'))
                  {
                      echo html::a($this->createLink('projectstory', 'view', "storyID=$story->id&version=0&param=$build->project", '', true), $story->title, '', isonlybody() ? "data-width='1000'" : "class='iframe' data-width='1000'");
                  }
                  else
                  {
                      echo $story->title;
                  }
                  ?>
                </td>
                <td>
                  <?php if($story->pri):?>
                  <span class='label-pri label-pri-<?php echo $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span>
                  <?php endif;?>
                </td>
                <td>
                  <span class='status-story status-<?php echo $story->status;?>'><?php echo $this->processStatus('story', $story);?></span>
                </td>
                <?php if($childBuilds):?>
                <td class='c-build' title='<?php echo $buildName?>'><?php echo $buildName;?></td>
                <?php endif;?>
                <td class='c-user'><?php echo zget($users, $story->openedBy);?></td>
                <td class='c-estimate' title="<?php echo $story->estimate . ' ' . $lang->hourCommon;?>"><?php echo $story->estimate . $config->hourUnit;?></td>
                <td class='c-stage'><?php echo $lang->story->stageList[$story->stage];?></td>
                <td class='c-actions'>
                  <?php
                  if($canBeChanged and common::hasPriv($module, 'unlinkStory'))
                  {
                      $unlinkURL = $this->createLink($module, 'unlinkStory', "buildID=$build->id&story=$story->id");
                      echo html::a($unlinkURL, '<i class="icon-unlink"></i>', 'hiddenwin', "class='btn' title='{$lang->build->unlinkStory}' $unlinkClass");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
          <div class='table-footer'>
            <?php if($countStories):?>
            <?php if($canBatchUnlink):?>
            <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
            <div class="table-actions btn-toolbar">
              <?php echo html::submitButton($lang->build->batchUnlink, '', 'btn');?>
            </div>
            <?php endif;?>
            <div class='table-statistic'><?php echo sprintf($lang->build->finishStories, $countStories);?></div>
            <?php endif;?>
            <?php
            $this->app->rawParams['type'] = 'story';
            $storyPager->show('right', 'pagerjs');
            $this->app->rawParams['type'] = $type;
            ?>
          </div>
        </form>
      </div>
      <div class='tab-pane <?php if($type == 'bug') echo 'active'?>' id='bugs'>
        <?php if($canBeChanged and common::hasPriv($module, 'linkBug') and !isonlybody()):?>
        <div class='actions'><?php echo html::a("javascript:showLink($build->id, \"bug\")", '<i class="icon-bug"></i> ' . $lang->build->linkBug, '', "class='btn btn-primary'");?></div>
        <div class='linkBox cell hidden'></div>
        <?php endif;?>
        <form class='main-table table-bug<?php if($link === 'true' and $type == 'bug') echo " hidden";?>' data-ride='table' method='post' target='hiddenwin' action="<?php echo $this->createLink($module, 'batchUnlinkBug', "build=$build->id");?>" id='linkedBugsForm'>
          <table class='table has-sort-head' id='bugList'>
            <?php $canBatchUnlink = $canBeChanged and common::hasPriv($module, 'batchUnlinkBug');?>
            <?php $vars = "buildID={$build->id}&type=bug&link=$link&param=$param&orderBy=%s";?>
            <thead>
              <tr class='text-center'>
                <th class='c-id text-left'>
                  <?php if($canBatchUnlink):?>
                  <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                    <label></label>
                  </div>
                  <?php endif;?>
                  <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
                </th>
                <th class='text-left'><?php common::printOrderLink('title',  $orderBy, $vars, $lang->bug->title);?></th>
                <th class='c-status'> <?php common::printOrderLink('status', $orderBy, $vars, $lang->bug->status);?></th>
                <?php if($childBuilds):?>
                <th class='c-build'><?php echo $lang->build->linkedBuild?></th>
                <?php endif;?>
                <th class='c-user'>     <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->openedByAB);?></th>
                <th class='c-date'>     <?php common::printOrderLink('openedDate',   $orderBy, $vars, $lang->bug->abbr->openedDate);?></th>
                <th class='c-user'>     <?php common::printOrderLink('resolvedBy',   $orderBy, $vars, $lang->bug->resolvedBy);?></th>
                <th class='c-date'>     <?php common::printOrderLink('resolvedDate', $orderBy, $vars, $lang->bug->abbr->resolvedDate);?></th>
                <th class='c-actions-1'><?php echo $lang->actions?></th>
              </tr>
            </thead>
            <tbody class='text-center'>
              <?php foreach($bugs as $bug):?>
              <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
              <?php
              $unlinkClass = strpos(",$build->allBugs,", ",$bug->id,") !== false ? '' : "disabled";
              $buildName   = $build->name;
              if($unlinkClass == 'disabled')
              {
                  foreach($childBuilds as $childBuild)
                  {
                      if(strpos(",$childBuild->bugs,", ",$bug->id,") !== false)
                      {
                          $buildName = $childBuild->name;
                          break;
                      }
                  }
              }
              ?>
              <tr>
                <td class='c-id text-left'>
                  <?php if($canBatchUnlink):?>
                  <?php echo html::checkbox('unlinkBugs', array($bug->id => sprintf('%03d', $bug->id)), '', $unlinkClass);?>
                  <?php else:?>
                  <?php printf('%03d', $bug->id);?>
                  <?php endif;?>
                <td class='text-left nobr' title='<?php echo $bug->title?>'>
                    <?php echo html::a($bugLink, $bug->title, '', isonlybody() ? "data-width='1000'" : "class='iframe' data-width='1000'");?>
                </td>
                <td>
                  <span class='status-bug status-<?php echo $bug->status?>'>
                    <?php echo $this->processStatus('bug', $bug);?>
                  </span>
                </td>
                <?php if($childBuilds):?>
                <td class='c-build' title='<?php echo $buildName?>'><?php echo $buildName;?></td>
                <?php endif;?>
                <td class='c-user'><?php echo zget($users, $bug->openedBy);?></td>
                <td class='c-date'><?php echo helper::isZeroDate($bug->openedDate) ? '' : substr($bug->openedDate, 5, 11);?></td>
                <td class='c-user'><?php echo zget($users, $bug->resolvedBy);?></td>
                <td class='c-date'><?php echo helper::isZeroDate($bug->resolvedDate) ? '' : substr($bug->resolvedDate, 5, 11);?></td>
                <td class='c-actions'>
                  <?php
                  if($canBeChanged and common::hasPriv($module, 'unlinkBug'))
                  {
                      $unlinkURL = $this->createLink($module, 'unlinkBug', "buildID=$build->id&bug=$bug->id");
                      echo html::a("###", '<i class="icon-unlink"></i>', '', (!$unlinkClass ? "onclick='ajaxDelete(\"$unlinkURL\", \"bugList\", confirmUnlinkBug)'" : '') . "class='btn' title='{$lang->build->unlinkBug}' $unlinkClass");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
          <div class='table-footer'>
            <?php if($countBugs):?>
            <?php if($canBatchUnlink):?>
            <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
            <div class="table-actions btn-toolbar">
              <?php echo html::submitButton($lang->build->batchUnlink, '', 'btn');?>
            </div>
            <?php endif;?>
            <div class='table-statistic'><?php echo sprintf($lang->build->resolvedBugs, $countBugs);?></div>
            <?php endif;?>
            <?php
            $this->app->rawParams['type'] = 'bug';
            $bugPager->show('right', 'pagerjs');
            $this->app->rawParams['type'] = $type;
            ?>
          </div>
        </form>
      </div>
      <div class='tab-pane <?php if($type == 'generatedBug') echo 'active'?>' id='generatedBugs'>
        <div class='main-table' data-ride='table'>
          <table class='table has-sort-head'>
            <?php $vars = "buildID={$build->id}&type=generatedBug&link=$link&param=$param&orderBy=%s";?>
            <thead>
              <tr class='text-center'>
                <th class='c-id text-left'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
                <th class='text-left'><?php common::printOrderLink('title',   $orderBy, $vars, $lang->bug->title);?></th>
                <th class='c-severity' title=<?php echo $lang->bug->severity;?>><?php common::printOrderLink('severity', $orderBy, $vars, $lang->bug->abbr->severity);?></th>
                <th class='c-status'> <?php common::printOrderLink('status', $orderBy, $vars, $lang->bug->status);?></th>
                <th class='c-build'>  <?php echo $lang->bug->openedBuild;?></th>
                <th class='c-user'>   <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->openedByAB);?></th>
                <th class='c-date'>   <?php common::printOrderLink('openedDate',   $orderBy, $vars, $lang->bug->abbr->openedDate);?></th>
                <th class='c-user'>   <?php common::printOrderLink('resolvedBy',   $orderBy, $vars, $lang->bug->resolvedBy);?></th>
                <th class='c-date'>   <?php common::printOrderLink('resolvedDate', $orderBy, $vars, $lang->bug->abbr->resolvedDate);?></th>
              </tr>
            </thead>
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
            <tbody class='text-center'>
              <?php foreach($generatedBugs as $bug):?>
              <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
              <tr>
                <td class='text-left'><?php printf('%03d', $bug->id);?></td>
                <td class='text-left nobr' title='<?php echo $bug->title?>'>
                    <?php echo html::a($bugLink, $bug->title, '', isonlybody() ? "data-width='1000'" : "class='iframe' data-width='1000'");?>
                </td>
                <td class='c-severity'>
                  <?php if($hasCustomSeverity):?>
                  <span class='label-severity-custom' data-severity='<?php echo $bug->severity;?>' title='<?php echo zget($lang->bug->severityList, $bug->severity);?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?></span>
                  <?php else:?>
                  <span class='label-severity' data-severity='<?php echo $bug->severity;?>' title='<?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?>'></span>
                  <?php endif;?>
                </td>
                <td>
                  <span class='status-bug status-<?php echo $bug->status?>'>
                    <?php echo $this->processStatus('bug', $bug);?>
                  </span>
                </td>
                <?php
                $openedBuilds = '';
                foreach(explode(',', $bug->openedBuild) as $buildID) $openedBuilds .= ($buildID == 'trunk' ? 'Trunk' : zget($buildPairs, $buildID, '')) . ' ';
                ?>
                <td class='c-build' title='<?php echo $openedBuilds;?>'><?php echo $openedBuilds;?></td>
                <td class='c-user'><?php echo zget($users, $bug->openedBy);?></td>
                <td class='c-date'><?php echo helper::isZeroDate($bug->openedDate) ? '' : substr($bug->openedDate, 5, 11);?></td>
                <td class='c-user'><?php echo zget($users, $bug->resolvedBy);?></td>
                <td class='c-date'><?php echo helper::isZeroDate($bug->resolvedDate) ? '' : substr($bug->resolvedDate, 5, 11);?></td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
          <div class='table-footer'>
            <?php if($countGeneratedBugs):?>
            <div class='table-statistic'><?php echo sprintf($lang->build->createdBugs, $countGeneratedBugs);?></div>
            <?php endif;?>
            <?php
            $this->app->rawParams['type'] = 'generatedBug';
            $generatedBugPager->show('right', 'pagerjs');
            $this->app->rawParams['type'] = $type;
            ?>
          </div>
        </div>
      </div>
      <div class='tab-pane <?php if($type == 'buildInfo') echo 'active'?>' id='buildInfo'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->build->basicInfo?></div>
            <div class='detail-content'>
              <table class='table table-data table-condensed table-borderless table-fixed'>
              <tr class="<?php echo $hidden;?>">
                  <th class='w-100px'><?php echo $lang->build->product;?></th>
                  <td><?php echo $build->productName;?></td>
                </tr>
                <?php if($build->productType != 'normal'):?>
                <tr>
                  <th class='w-100px'><?php echo $lang->product->branch;?></th>
                  <td><?php echo $branchName;?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th class='w-100px'><?php echo $lang->build->name;?></th>
                  <td><?php echo $build->name;?></td>
                </tr>
                <?php if($build->execution):?>
                <tr>
                  <th><?php echo empty($multipleProject) ? $lang->build->project :($executionType ? $lang->build->executionAB : $lang->build->execution);?></th>
                  <td>
                    <?php $executionName = zget($executions, $build->execution);?>
                    <?php echo ltrim($executionName, '/');?>
                  </td>
                </tr>
                <?php else:?>
                <tr>
                  <th><?php echo $lang->build->builds;?></th>
                  <td>
                    <?php $builds = '';?>
                    <?php foreach(explode(',', $build->builds) as $buildID):?>
                    <?php if($buildID) $builds .= html::a($this->createLink('build', 'view', "buildID=$buildID") . "#app={$app->tab}", zget($buildPairs, $buildID)) . $lang->comma;?>
                    <?php endforeach;?>
                    <?php echo rtrim($builds, $lang->comma);?>
                  </td>
                </tr>
                <?php endif;?>
                <tr>
                  <th><?php echo $lang->build->builder;?></th>
                  <td><?php echo zget($users, $build->builder);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->build->date;?></th>
                  <td><?php echo $build->date;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->build->scmPath;?></th>
                  <td style='word-break:break-all;'><?php echo html::a($build->scmPath, $build->scmPath, '_blank')?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->build->filePath;?></th>
                  <td style='word-break:break-all;'><?php echo html::a($build->filePath, $build->filePath, '_blank');?></td>
                </tr>
                <?php $this->printExtendFields($build, 'table', 'inForm=0');?>
                <tr>
                  <th style="vertical-align:top"><?php echo $lang->build->desc;?></th>
                  <td class='article-content'>
                    <?php if($build->desc):?>
                    <?php echo $build->desc;?>
                    <?php else:?>
                    <?php echo $lang->noData;?>
                    <?php endif;?>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <?php echo $this->fetch('file', 'printFiles', array('files' => $build->files, 'fieldset' => 'true'));?>
          <?php include '../../common/view/action.html.php';?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php js::set('param', helper::safe64Decode($param))?>
<?php js::set('link', $link)?>
<?php js::set('currentModule', $module)?>
<?php js::set('buildID', $build->id)?>
<?php js::set('type', $type)?>
<?php include '../../common/view/footer.html.php';?>
