<?php
/**
 * The view file of release module's view method of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: view.html.php 4386 2013-02-19 07:37:45Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->release->confirmUnlinkStory)?>
<?php js::set('confirmUnlinkBug', $lang->release->confirmUnlinkBug)?>
<?php js::set('storySummary', $summary);?>
<?php js::set('storyCommon', $lang->SRCommon);?>
<?php js::set('checkedSummary', $lang->product->checkedSRSummary);?>
<?php $canBeChanged = common::canBeChanged('release', $release);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $browseLink = $this->session->releaseList ? $this->session->releaseList : inlink('browse', "productID=$release->product");?>
    <?php common::printBack($browseLink, 'btn btn-primary');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $release->id;?></span>
      <span class='text' title='<?php echo $release->name;?>'><?php echo $release->name;?></span>
      <?php $flagIcon = $release->marker ? "<icon class='icon icon-flag red' title='{$lang->release->marker}'></icon> " : '';?>
      <?php echo $flagIcon;?>
      <?php if($release->deleted):?>
      <span class='label label-danger'><?php echo $lang->release->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php echo $this->release->buildOperateMenu($release, 'view');?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <div class='main-col'>
    <div class='main'>
      <div class='tabs' id='tabsNav'>
        <?php $countBugs = count($bugs); $countLeftBugs = count($leftBugs);?>
        <ul class='nav nav-tabs'>
          <li <?php if($type == 'story')   echo "class='active'"?>><a href='#stories' data-toggle='tab'><?php echo html::icon($lang->icons['story'], 'text-green') . ' ' . $lang->release->stories;?></a></li>
          <li <?php if($type == 'bug')     echo "class='active'"?>><a href='#bugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug'], 'text-green') . ' ' . $lang->release->bugs;?></a></li>
          <li <?php if($type == 'leftBug') echo "class='active'"?>><a href='#leftBugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug'], 'text-red') . ' ' . $lang->release->generatedBugs;?></a></li>
          <li <?php if($type == 'releaseInfo') echo "class='active'"?>><a href='#releaseInfo' data-toggle='tab'><?php echo html::icon($lang->icons['plan'], 'text-info') . ' ' . $lang->release->view;?></a></li>
          <?php if($summary or $countBugs or $countLeftBugs):?>
          <li class='pull-right'><div><?php common::printIcon('release', 'export', '', '', 'button', '', '', "export btn-sm");?></div></li>
          <?php endif;?>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane <?php if($type == 'story') echo 'active'?>' id='stories'>
            <?php if(common::hasPriv('release', 'linkStory') and $canBeChanged and !isonlybody()):?>
            <div class='actions'><?php echo html::a("javascript:showLink({$release->id}, \"story\")", '<i class="icon-link"></i> ' . $lang->release->linkStory, '', "class='btn btn-primary'");?></div>
            <div class='linkBox cell hidden'></div>
            <?php endif;?>
            <form class='main-table table-story no-stash<?php if($link === 'true' and $type == 'story') echo " hidden";?>' method='post' id='linkedStoriesForm' data-ride="">
              <table class='table has-sort-head' id='storyList'>
                <?php
                $canBatchUnlink = common::hasPriv('release', 'batchUnlinkStory');
                $canBatchClose  = common::hasPriv('story', 'batchClose');
                ?>
                <?php $vars = "releaseID={$release->id}&type=story&link=$link&param=$param&orderBy=%s";?>
                <thead>
                  <tr class='text-center'>
                    <th class='c-id text-left'>
                      <?php if(($canBatchUnlink or $canBatchClose) and $canBeChanged):?>
                      <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                        <label></label>
                      </div>
                      <?php endif;?>
                      <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
                    </th>
                    <th class="text-left"><?php common::printOrderLink('title', $orderBy, $vars, $lang->story->title);?></th>
                    <th class='c-pri' title=<?php echo $lang->pri;?>><?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
                    <th class='c-status'>   <?php common::printOrderLink('status', $orderBy, $vars, $lang->statusAB);?></th>
                    <th class='c-build'>    <?php echo $lang->build->linkedBuild;?></th>
                    <th class='c-user'>     <?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->openedByAB);?></th>
                    <th class='c-estimate'> <?php common::printOrderLink('estimate', $orderBy, $vars, $lang->story->estimateAB);?></th>
                    <th class='c-stage'>    <?php common::printOrderLink('stage',    $orderBy, $vars, $lang->story->stageAB);?></th>
                    <th class='c-actions-1'><?php echo $lang->actions?></th>
                  </tr>
                </thead>
                <tbody class='text-center'>
                  <?php foreach($stories as $storyID => $story):?>
                  <?php $storyLink = $this->createLink('story', 'view', "storyID=$story->id", '', true);?>
                  <tr data-id='<?php echo $story->id;?>' data-estimate='<?php echo $story->estimate?>' <?php if(!empty($story->children)) echo "data-children=" . count($story->children);?> data-cases='<?php echo zget($storyCases, $story->id, 0);?>'>
                    <td class='c-id text-left'>
                      <?php if(($canBatchUnlink or $canBatchClose) and $canBeChanged):?>
                      <div class="checkbox-primary">
                        <input type='checkbox' name='storyIdList[]'  value='<?php echo $story->id;?>'/>
                        <label></label>
                      </div>
                      <?php endif;?>
                      <?php echo sprintf('%03d', $story->id);?>
                    </td>
                    <td class='text-left nobr' title='<?php echo $story->title?>'>
                      <?php
                      if($story->parent > 0) echo "<span class='label label-badge label-light' title='{$lang->story->childrenAB}'>{$lang->story->childrenAB}</span>";
                      echo html::a($storyLink,$story->title, '', "class='preview'");
                      ?>
                    </td>
                    <td><span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
                    <td class='c-status'>
                      <span class='status-story status-<?php echo $story->status;?>'><?php echo $this->processStatus('story', $story);?></span>
                    </td>
                    <td class='c-build' title='<?php echo $story->buildName?>'><?php echo $story->buildName?></td>
                    <td class='c-user'><?php echo zget($users, $story->openedBy);?></td>
                    <td class='c-estimate' title="<?php echo $story->estimate . ' ' . $lang->hourCommon;?>"><?php echo $story->estimate . $config->hourUnit;?></td>
                    <td class='c-stage'><?php echo $lang->story->stageList[$story->stage];?></td>
                    <td class='c-actions'>
                      <?php
                      if(common::hasPriv('release', 'unlinkStory') and $canBeChanged)
                      {
                          $unlinkURL = $this->createLink('release', 'unlinkStory', "releaseID=$release->id&story=$story->id");
                          echo html::a($unlinkURL, '<i class="icon-unlink"></i>', 'hiddenwin', "class='btn' title='{$lang->release->unlinkStory}'");
                      }
                      ?>
                    </td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
              <div class='table-footer'>
                <?php if($summary and ($canBatchUnlink or $canBatchClose) and $canBeChanged):?>
                <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
                <div class="table-actions btn-toolbar">
                  <?php
                  if(common::hasPriv('release', 'batchUnlinkStory'))
                  {
                      $unlinkURL = inlink('batchUnlinkStory', "release=$release->id");
                      echo html::a('###', $lang->release->batchUnlink, '', "onclick='setFormAction(\"$unlinkURL\", \"hiddenwin\", this)' class='btn'");
                  }

                  if(common::hasPriv('story', 'batchClose'))
                  {
                      $closeURL = $this->createLink('story', 'batchClose', "productID=$release->product");
                      echo html::a("###", $lang->story->batchClose, '', "onclick='setFormAction(\"$closeURL\", \"\", this)' class='btn'");
                  }
                  ?>
                </div>
                <?php endif;?>
                <?php if($this->app->getViewType() != 'xhtml'):?>
                <div class='table-statistic'><?php echo $summary;?></div>
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
            <?php if(common::hasPriv('release', 'linkBug') and $canBeChanged and !isonlybody()):?>
            <div class='actions'><?php echo html::a("javascript:showLink({$release->id}, \"bug\")", '<i class="icon-bug"></i> ' . $lang->release->linkBug, '', "class='btn btn-primary'");?></div>
            <div class='linkBox cell hidden'></div>
            <?php endif;?>
            <form class='main-table table-bug<?php if($link === 'true' and $type == 'bug') echo " hidden";?>' method='post' target='hiddenwin' id='linkedBugsForm' data-ride="table">
              <table class='table has-sort-head' id='bugList'>
                <?php $canBatchUnlink = common::hasPriv('release', 'batchUnlinkBug');?>
                <?php $canBatchClose  = common::hasPriv('bug', 'batchClose');?>
                <?php $vars = "releaseID={$release->id}&type=bug&link=$link&param=$param&orderBy=%s";?>
                <thead>
                  <tr class='text-center'>
                    <th class='c-id text-left'>
                      <?php if($canBatchUnlink and $canBeChanged):?>
                      <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                        <label></label>
                      </div>
                      <?php endif;?>
                      <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
                    </th>
                    <th class='text-left'><?php common::printOrderLink('title',  $orderBy, $vars, $lang->bug->title);?></th>
                    <th class='w-100px'>  <?php common::printOrderLink('status', $orderBy, $vars, $lang->bug->status);?></th>
                    <th class='c-build'>  <?php echo $lang->bug->resolvedBuild;?></th>
                    <th class='c-user'>   <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->openedByAB);?></th>
                    <th class='c-date'>   <?php common::printOrderLink('openedDate',   $orderBy, $vars, $lang->bug->abbr->openedDate);?></th>
                    <th class='c-user'>   <?php common::printOrderLink('resolvedBy',   $orderBy, $vars, $lang->bug->resolvedBy);?></th>
                    <th class='c-date'>   <?php common::printOrderLink('resolvedDate', $orderBy, $vars, $lang->bug->abbr->resolvedDate);?></th>
                    <th class='w-60px'>   <?php echo $lang->actions;?></th>
                  </tr>
                </thead>
                <tbody class='text-center'>
                  <?php foreach($bugs as $bug):?>
                  <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
                  <tr>
                    <td class='c-id text-left'>
                      <?php if($canBatchUnlink or $canBatchClose and $canBeChanged):?>
                      <div class="checkbox-primary">
                        <input type='checkbox' name='unlinkBugs[]'  value='<?php echo $bug->id;?>'/>
                        <label></label>
                      </div>
                      <?php endif;?>
                      <?php echo sprintf('%03d', $bug->id);?>
                    </td>
                    <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
                    <td>
                      <span class='status-bug status-<?php echo $bug->status?>'><?php echo $this->processStatus('bug', $bug);?></span>
                    </td>
                    <?php $resolvedBuildName = zget($builds, $bug->resolvedBuild, '');?>
                    <td class='c-build text-left' title='<?php echo $resolvedBuildName?>'><?php echo $resolvedBuildName;?></td>
                    <td class='c-user'><?php echo zget($users, $bug->openedBy);?></td>
                    <td class='c-date'><?php echo substr($bug->openedDate, 5, 11)?></td>
                    <td class='c-user'><?php echo zget($users, $bug->resolvedBy);?></td>
                    <td class='c-date'><?php echo helper::isZeroDate($bug->resolvedDate) ? '' : substr($bug->resolvedDate, 5, 11);?></td>
                    <td class='c-actions'>
                      <?php
                      if(common::hasPriv('release', 'unlinkBug') and $canBeChanged)
                      {
                          $unlinkURL = $this->createLink('release', 'unlinkBug', "releaseID=$release->id&bug=$bug->id");
                          echo html::a("javascript:ajaxDelete(\"$unlinkURL\", \"bugList\", confirmUnlinkBug)", '<i class="icon-unlink"></i>', '', "class='btn' title='{$lang->release->unlinkBug}'");
                      }
                      ?>
                    </td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
              <div class='table-footer'>
                <?php if($countBugs and ($canBatchUnlink or $canBatchClose) and $canBeChanged):?>
                <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
                <div class="table-actions btn-toolbar">
                <?php
                if($canBatchUnlink)
                {
                    $unlinkURL = inlink('batchUnlinkBug', "release=$release->id");
                    echo html::a('###', $lang->release->batchUnlink, '', "onclick='setFormAction(\"$unlinkURL\", \"hiddenwin\", this)' class='btn'");
                }
                if($canBatchClose)
                {
                    $closeURL = $this->createLink('bug', 'batchClose', "release=$release->id&viewType=release");
                    echo html::a("###", $lang->story->batchClose, '', "onclick='setFormAction(\"$closeURL\", \"hiddenwin\", this)' class='btn'");
                }
                ?>
                </div>
                <div class='table-statistic'><?php echo sprintf($lang->release->resolvedBugs, $countBugs);?></div>
                <?php endif;?>
                <?php
                $this->app->rawParams['type'] = 'bug';
                $bugPager->show('right', 'pagerjs');
                $this->app->rawParams['type'] = $type;
                ?>
              </div>
            </form>
          </div>
          <div class='tab-pane <?php if($type == 'leftBug') echo 'active'?>' id='leftBugs'>
            <?php if(common::hasPriv('release', 'linkBug') and $canBeChanged and !isonlybody()):?>
            <div class='actions'><?php echo html::a("javascript:showLink({$release->id}, \"leftBug\")", '<i class="icon-bug"></i> ' . $lang->release->linkBug, '', "class='btn btn-primary'");?></div>
            <div class='linkBox cell hidden'></div>
            <?php endif;?>
            <form class='main-table table-bug<?php if($link === 'true' and $type == 'leftBug') echo " hidden";?>' method='post' target='hiddenwin' action="<?php echo inlink('batchUnlinkBug', "releaseID=$release->id&type=leftBug");?>" id='linkedBugsForm' data-ride="table">
              <table class='table has-sort-head' id='leftBugList'>
                <?php $canBatchUnlink = common::hasPriv('release', 'batchUnlinkBug');?>
                <?php $vars = "releaseID={$release->id}&type=leftBug&link=$link&param=$param&orderBy=%s";?>
                <thead>
                  <tr class='text-center'>
                    <th class='c-id text-left'>
                      <?php if($canBatchUnlink and $canBeChanged):?>
                      <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                        <label></label>
                      </div>
                      <?php endif;?>
                      <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
                    </th>
                    <th class='text-left'><?php common::printOrderLink('title', $orderBy, $vars, $lang->bug->title);?></th>
                    <th class='w-80px' title=<?php echo $lang->bug->severity;?>><?php common::printOrderLink('severity', $orderBy, $vars, $lang->bug->abbr->severity);?></th>
                    <th class='w-100px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->bug->status);?></th>
                    <th class='c-build'><?php echo $lang->bug->openedBuild;?></th>
                    <th class='c-user'> <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
                    <th class='w-160px'><?php common::printOrderLink('openedDate', $orderBy, $vars, $lang->bug->abbr->openedDate);?></th>
                    <th class='w-60px'> <?php echo $lang->actions;?></th>
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
                  <?php foreach($leftBugs as $bug):?>
                  <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
                  <tr>
                    <td class='c-id text-left'>
                      <?php if($canBatchUnlink and $canBeChanged):?>
                      <div class="checkbox-primary">
                        <input type='checkbox' name='unlinkBugs[]'  value='<?php echo $bug->id;?>'/>
                        <label></label>
                      </div>
                      <?php endif;?>
                      <?php echo sprintf('%03d', $bug->id);?>
                    </td>
                    <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
                    <td class='c-severity'>
                      <?php if($hasCustomSeverity):?>
                      <span class='<?php echo 'label-severity-custom';?>' title='<?php echo zget($lang->bug->severityList, $bug->severity);?>' data-severity='<?php echo $bug->severity;?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?></span>
                      <?php else:?>
                      <span class='label-severity' data-severity='<?php echo $bug->severity;?>' title='<?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?>'></span>
                      <?php endif;?>
                    </td>
                    <td><span class='status-<?php echo $bug->status?>'> <?php echo $this->processStatus('bug', $bug);?></span></td>
                    <?php
                    $openedBuildName = '';
                    foreach(explode(',', $bug->openedBuild) as $buildID) $openedBuildName .= zget($builds, $buildID, '') . ' ';
                    ?>
                    <td class='c-build text-left' title='<?php echo $openedBuildName?>'><?php echo $openedBuildName;?></td>
                    <td class='c-user'><?php echo zget($users, $bug->openedBy);?></td>
                    <td class='c-date'><?php echo $bug->openedDate?></td>
                    <td class='c-actions'>
                      <?php
                      if(common::hasPriv('release', 'unlinkBug') and $canBeChanged)
                      {
                          $unlinkURL = $this->createLink('release', 'unlinkBug', "releaseID=$release->id&bug=$bug->id&type=leftBug");
                          echo html::a("javascript:ajaxDelete(\"$unlinkURL\", \"leftBugList\", confirmUnlinkBug)", '<i class="icon-unlink"></i>', '', "class='btn' title='{$lang->release->unlinkBug}'");
                      }
                      ?>
                    </td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
              <div class='table-footer'>
                <?php if($countLeftBugs and $canBatchUnlink and $canBeChanged):?>
                <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
                <div class="table-actions btn-toolbar">
                  <?php echo html::submitButton($lang->release->batchUnlink, '', 'btn');?>
                </div>
                <div class='table-statistic'><?php echo sprintf($lang->release->createdBugs, $countLeftBugs);?></div>
                <?php endif;?>
                <?php
                $this->app->rawParams['type'] = 'leftBug';
                $leftBugPager->show('right', 'pagerjs');
                $this->app->rawParams['type'] = $type;
                ?>
              </div>
            </form>
          </div>

          <div class='tab-pane <?php if($type == 'releaseInfo') echo 'active'?>' id='releaseInfo'>
            <div class='cell'>
              <div class='detail'>
                <div class='detail-title'><?php echo $lang->release->basicInfo?></div>
                <div class='detail-content'>
                  <table class='table table-data'>
                    <tr>
                      <th class='w-100px'><?php echo $lang->release->product;?></th>
                      <td><?php echo $release->productName;?></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->release->name;?></th>
                      <td><?php echo $release->name;?></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->release->includedBuild;?></th>
                      <td>
                        <?php
                        $buildHtml = array();
                        foreach($release->builds as $build)
                        {
                            $moduleName   = $build->execution ? 'build' : 'projectbuild';
                            $canClickable = false;
                            if($moduleName == 'projectbuild' and $this->loadModel('project')->checkPriv($build->project)) $canClickable = true;
                            if($moduleName == 'build' and $this->loadModel('execution')->checkPriv($build->execution))    $canClickable = true;

                            $buildHtml[] = $canClickable ? html::a($this->createLink($moduleName, 'view', "buildID=$build->id"), $build->name, '', "data-app='project'") : $build->name;
                        }
                        echo join($lang->comma, $buildHtml);
                        ?>
                      </td>
                    </tr>
                    <?php if($product->type != 'normal'):?>
                    <tr>
                      <th><?php echo $lang->release->branch;?></th>
                      <td>
                        <?php
                        foreach($release->branches as $branchID)
                        {
                            echo zget($branches, $branchID, '');
                            if($branchID != end($release->branches)) echo ', ';
                        }
                        ?>
                      </td>
                    </tr>
                    <?php endif;?>
                    <tr>
                      <th><?php echo $lang->release->status;?></th>
                      <td><?php echo $this->processStatus('release', $release);?></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->release->date;?></th>
                      <td><?php echo $release->date;?></td>
                    </tr>
                    <?php $this->printExtendFields($release, 'table', 'inForm=0');?>
                    <tr>
                      <th><?php echo $lang->release->desc;?></th>
                      <td><?php echo $release->desc;?></td>
                    </tr>
                  </table>
                </div>
              </div>
              <div class='detail'>
                <div class='detail-title'><?php echo $lang->files?></div>
                <div class='detail-content'>
                  <?php
                  if($release->files)
                  {
                      echo $this->fetch('file', 'printFiles', array('files' => $release->files, 'fieldset' => 'false', 'object' => $release, 'method' => 'view', 'showDelete' => false));
                  }
                  if($release->builds)
                  {
                      foreach($release->builds as $build)
                      {
                          if($build->filePath)
                          {
                              echo $lang->release->filePath . html::a($release->filePath, $release->filePath, '_blank');
                          }
                          elseif($build->scmPath)
                          {
                              echo $lang->release->scmPath . html::a($release->scmPath, $release->scmPath, '_blank');
                          }
                      }
                  }
                  ?>
                </div>
              </div>
              <?php include '../../common/view/action.html.php';?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
.tabs .tab-content .tab-pane .action{position: absolute; right: <?php echo ($summary or $countBugs or $countLeftBugs) ? '100px' : '-1px'?>; top: 0px;}
</style>
<?php js::set('param', helper::safe64Decode($param))?>
<?php js::set('link', $link)?>
<?php js::set('releaseID', $release->id)?>
<?php js::set('type', $type)?>
<?php include '../../common/view/footer.html.php';?>
