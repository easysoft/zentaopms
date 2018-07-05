<?php
/**
 * The view file of release module's view method of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: view.html.php 4386 2013-02-19 07:37:45Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->release->confirmUnlinkStory)?>
<?php js::set('confirmUnlinkBug', $lang->release->confirmUnlinkBug)?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $browseLink = $this->session->releaseList ? $this->session->releaseList : inlink('browse', "productID=$release->product");?>
    <?php common::printBack($browseLink, 'btn btn-link');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $release->id;?></span>
      <span class='text' title='<?php echo $release->name;?>'><?php echo $release->name;?></span>
      <?php if($release->deleted):?>
      <span class='label label-danger'><?php echo $lang->release->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php
    if(!$release->deleted)
    {
        if(common::hasPriv('release', 'linkStory')) echo html::a(inlink('view', "releaseID=$release->id&type=story&link=true"), '<i class="icon-link"></i> ' . $lang->release->linkStory, '', "class='btn btn-link'");
        if(common::hasPriv('release', 'linkBug') and $config->global->flow != 'onlyStory') echo html::a(inlink('view', "releaseID=$release->id&type=bug&link=true"),   '<i class="icon-bug"></i> '  . $lang->release->linkBug,   '', "class='btn btn-link'");

        if(common::hasPriv('release', 'changeStatus', $release))
        {
            $changedStatus = $release->status == 'normal' ? 'terminate' : 'normal';
            echo html::a(inlink('changeStatus', "releaseID=$release->id&status=$changedStatus"), '<i class="icon-' . ($release->status == 'normal' ? 'pause' : 'play') . '"></i> ', 'hiddenwin', "class='btn btn-link' title='{$lang->release->changeStatusList[$changedStatus]}'");
        }
        common::printIcon('release', 'edit',   "releaseID=$release->id", $release);
        common::printIcon('release', 'delete', "releaseID=$release->id", $release, 'button', '', 'hiddenwin');
    }
    ?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <div class='main-col'>
    <div class='main'>
      <div class='tabs' id='tabsNav'>
        <?php $countStories = count($stories); $countBugs = count($bugs); $countLeftBugs = count($leftBugs);?>
        <ul class='nav nav-tabs'>
          <li <?php if($type == 'story')   echo "class='active'"?>><a href='#stories' data-toggle='tab'><?php echo html::icon($lang->icons['story'], 'text-green') . ' ' . $lang->release->stories;?></a></li>
          <?php if($config->global->flow != 'onlyStory'):?>
          <li <?php if($type == 'bug')     echo "class='active'"?>><a href='#bugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug'], 'text-green') . ' ' . $lang->release->bugs;?></a></li>
          <li <?php if($type == 'leftBug') echo "class='active'"?>><a href='#leftBugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug'], 'text-red') . ' ' . $lang->release->generatedBugs;?></a></li>
          <?php endif;?>
          <li <?php if($type == 'releaseInfo') echo "class='active'"?>><a href='#releaseInfo' data-toggle='tab'><?php echo html::icon($lang->icons['plan'], 'text-info') . ' ' . $lang->release->view;?></a></li>
          <?php if($countStories or ($config->global->flow != 'onlyStory' and ($countBugs or $countLeftBugs))):?>
          <li class='pull-right'><div><?php common::printIcon('release', 'export', '', '', 'button', '', '', "export btn-sm");?></div></li>
          <?php endif;?>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane <?php if($type == 'story') echo 'active'?>' id='stories'>
            <?php if(common::hasPriv('release', 'linkStory')):?>
            <div class='actions'><?php echo html::a("javascript:showLink({$release->id}, \"story\")", '<i class="icon-link"></i> ' . $lang->release->linkStory, '', "class='btn btn-primary'");?></div>
            <div class='linkBox cell hidden'></div>
            <?php endif;?>
            <form class='main-table table-story' method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkStory', "release=$release->id");?>" id='linkedStoriesForm' data-ride="table">
              <table class='table has-sort-head' id='storyList'>
                <?php $canBatchUnlink = common::hasPriv('release', 'batchUnlinkStory');?>
                <?php $vars = "releaseID={$release->id}&type=story&link=$link&param=$param&orderBy=%s";?>
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
                    <th class='c-pri'>    <?php common::printOrderLink('pri',      $orderBy, $vars, $lang->priAB);?></th>
                    <th class="text-left"><?php common::printOrderLink('title',    $orderBy, $vars, $lang->story->title);?></th>
                    <th class='c-user'>   <?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->openedByAB);?></th>
                    <th class='w-80px'>   <?php common::printOrderLink('estimate', $orderBy, $vars, $lang->story->estimateAB);?></th>
                    <th class='w-90px'>   <?php common::printOrderLink('status',   $orderBy, $vars, $lang->statusAB);?></th>
                    <th class='w-100px'>  <?php common::printOrderLink('stage',    $orderBy, $vars, $lang->story->stageAB);?></th>
                    <th class='c-actions-1'>   <?php echo $lang->actions?></th>
                  </tr>
                </thead>
                <tbody class='text-center'>
                  <?php foreach($stories as $storyID => $story):?>
                  <?php $storyLink = $this->createLink('story', 'view', "storyID=$story->id", '', true);?>
                  <tr>
                    <td class='c-id text-left'>
                      <?php if($canBatchUnlink):?>
                      <div class="checkbox-primary">
                        <input type='checkbox' name='unlinkStories[]'  value='<?php echo $story->id;?>'/> 
                        <label></label>
                      </div>
                      <?php endif;?>
                      <?php echo sprintf('%03d', $story->id);?>
                    </td>
                    <td><span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
                    <td class='text-left nobr' title='<?php echo $story->title?>'><?php echo html::a($storyLink,$story->title, '', "class='preview'");?></td>
                    <td><?php echo $users[$story->openedBy];?></td>
                    <td><?php echo $story->estimate;?></td>
                    <td><span class='status-<?php echo $story->status;?>'><span class="label label-dot"></span> <?php echo $lang->story->statusList[$story->status];?></span></td>
                    <td><?php echo $lang->story->stageList[$story->stage];?></td>
                    <td class='c-actions'>
                      <?php
                      if(common::hasPriv('release', 'unlinkStory'))
                      {
                          $unlinkURL = $this->createLink('release', 'unlinkStory', "releaseID=$release->id&story=$story->id");
                          echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"storyList\",confirmUnlinkStory)", '<i class="icon-unlink"></i>', '', "class='btn' title='{$lang->release->unlinkStory}'");
                      }
                      ?>
                    </td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
              <?php if($countStories and $canBatchUnlink):?>
              <div class='table-footer'>
                <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
                <div class="table-actions btn-toolbar">
                  <?php echo html::submitButton($lang->release->batchUnlink, '', 'btn');?>
                </div>
                <div class='text'><?php echo sprintf($lang->release->finishStories, $countStories);?></div>
              </div>
              <?php endif;?>
            </form>
          </div>
          <div class='tab-pane <?php if($type == 'bug') echo 'active'?>' id='bugs'>
            <?php if(common::hasPriv('release', 'linkBug')):?>
            <div class='actions'><?php echo html::a("javascript:showLink({$release->id}, \"bug\")", '<i class="icon-bug"></i> ' . $lang->release->linkBug, '', "class='btn btn-primary'");?></div>
            <div class='linkBox cell hidden'></div>
            <?php endif;?>
            <form class='main-table table-bug' method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkBug', "releaseID=$release->id");?>" id='linkedBugsForm' data-ride="table">
              <table class='table has-sort-head' id='bugList'>
                <?php $canBatchUnlink = common::hasPriv('release', 'batchUnlinkBug');?>
                <?php $vars = "releaseID={$release->id}&type=bug&link=$link&param=$param&orderBy=%s";?>
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
                    <th class='text-left'><?php common::printOrderLink('title',        $orderBy, $vars, $lang->bug->title);?></th>
                    <th class='w-100px'>  <?php common::printOrderLink('status',       $orderBy, $vars, $lang->bug->status);?></th>
                    <th class='w-user'>   <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->openedByAB);?></th>
                    <th class='w-date'>   <?php common::printOrderLink('openedDate',   $orderBy, $vars, $lang->bug->openedDateAB);?></th>
                    <th class='w-user'>   <?php common::printOrderLink('resolvedBy',   $orderBy, $vars, $lang->bug->resolvedByAB);?></th>
                    <th class='w-100px'>  <?php common::printOrderLink('resolvedDate', $orderBy, $vars, $lang->bug->resolvedDateAB);?></th>
                    <th class='w-50px'>   <?php echo $lang->actions;?></th>
                  </tr>
                </thead>
                <tbody class='text-center'>
                  <?php foreach($bugs as $bug):?>
                  <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
                  <tr>
                    <td class='c-id text-left'>
                      <?php if($canBatchUnlink):?>
                      <div class="checkbox-primary">
                        <input type='checkbox' name='unlinkBugs[]'  value='<?php echo $bug->id;?>'/> 
                        <label></label>
                      </div>
                      <?php endif;?>
                      <?php echo sprintf('%03d', $bug->id);?>
                    </td>
                    <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
                    <td><span class='status-<?php echo $bug->status?>'><span class="label label-dot"></span> <?php echo zget($lang->bug->statusList, $bug->status);?></td>
                    <td><?php echo $users[$bug->openedBy];?></td>
                    <td><?php echo substr($bug->openedDate, 5, 11)?></td>
                    <td><?php echo $users[$bug->resolvedBy];?></td>
                    <td><?php echo substr($bug->resolvedDate, 5, 11)?></td>
                    <td class='c-actions'>
                      <?php
                      if(common::hasPriv('release', 'unlinkBug'))
                      {
                          $unlinkURL = $this->createLink('release', 'unlinkBug', "releaseID=$release->id&bug=$bug->id");
                          echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"bugList\",confirmUnlinkBug)", '<i class="icon-unlink"></i>', '', "class='btn' title='{$lang->release->unlinkBug}'");
                      }
                      ?>
                    </td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
              <?php if($countBugs and $canBatchUnlink):?>
              <div class='table-footer'>
                <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
                <div class="table-actions btn-toolbar">
                  <?php echo html::submitButton($lang->release->batchUnlink, '', 'btn');?>
                </div>
                <div class='text'><?php echo sprintf($lang->release->resolvedBugs, $countBugs);?></div>
              </div>
              <?php endif;?>
            </form>
          </div>
          <div class='tab-pane <?php if($type == 'leftBug') echo 'active'?>' id='leftBugs'>
            <?php if(common::hasPriv('release', 'linkBug')):?>
            <div class='actions'><?php echo html::a("javascript:showLink({$release->id}, \"leftBug\")", '<i class="icon-bug"></i> ' . $lang->release->linkBug, '', "class='btn btn-primary'");?></div>
            <div class='linkBox cell hidden'></div>
            <?php endif;?>
            <form class='main-table table-bug' method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkBug', "releaseID=$release->id&type=leftBug");?>" id='linkedBugsForm' data-ride="table">
              <table class='table has-sort-head' id='leftBugList'>
                <?php $canBatchUnlink = common::hasPriv('release', 'batchUnlinkBug');?>
                <?php $vars = "releaseID={$release->id}&type=leftBug&link=$link&param=$param&orderBy=%s";?>
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
                    <th class='w-80px'>    <?php common::printOrderLink('severity',   $orderBy, $vars, $lang->bug->severityAB);?></th>
                    <th class='text-left'> <?php common::printOrderLink('title',      $orderBy, $vars, $lang->bug->title);?></th>
                    <th class='w-100px'>   <?php common::printOrderLink('status',     $orderBy, $vars, $lang->bug->status);?></th>
                    <th class='c-user'>    <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
                    <th class='w-150px'>   <?php common::printOrderLink('openedDate', $orderBy, $vars, $lang->bug->openedDateAB);?></th>
                    <th class='w-50px'>    <?php echo $lang->actions;?></th>
                  </tr>
                </thead>
                <tbody class='text-center'>
                  <?php foreach($leftBugs as $bug):?>
                  <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
                  <tr>
                    <td class='c-id text-left'>
                      <?php if($canBatchUnlink):?>
                      <div class="checkbox-primary">
                        <input type='checkbox' name='unlinkBugs[]'  value='<?php echo $bug->id;?>'/> 
                        <label></label>
                      </div>
                      <?php endif;?>
                      <?php echo sprintf('%03d', $bug->id);?>
                    </td>
                    <td class='c-severity'>
                      <span class='label-severity' data-severity='<?php echo $bug->severity;?>' title='<?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?>'></span>
                    </td>
                    <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
                    <td><span class='status-<?php echo $bug->status?>'><span class="label label-dot"></span> <?php echo zget($lang->bug->statusList, $bug->status);?></span></td>
                    <td><?php echo zget($users, $bug->openedBy);?></td>
                    <td><?php echo $bug->openedDate?></td>
                    <td class='c-actions'>
                      <?php
                      if(common::hasPriv('release', 'unlinkBug'))
                      {
                          $unlinkURL = $this->createLink('release', 'unlinkBug', "releaseID=$release->id&bug=$bug->id&type=leftBug");
                          echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"leftBugList\",confirmUnlinkBug)", '<i class="icon-unlink"></i>', '', "class='btn' title='{$lang->release->unlinkBug}'");
                      }
                      ?>
                    </td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
              <?php if($countLeftBugs and $canBatchUnlink):?>
              <div class='table-footer'>
                <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
                <div class="table-actions btn-toolbar">
                  <?php echo html::submitButton($lang->release->batchUnlink, '', 'btn');?>
                </div>
                <div class='text'><?php echo sprintf($lang->release->createdBugs, $countLeftBugs);?></div>
              </div>
              <?php endif;?>
            </form>
          </div>

          <div class='tab-pane <?php if($type == 'releaseInfo') echo 'active'?>' id='releaseInfo'>
            <div class='cell'>
              <div class='detail'>
                <div class='detail-title'><?php echo $lang->release->basicInfo?></div>
                <div class='detail-content'>
                  <table class='table table-data'>
                    <tr>
                      <th class='w-80px'><?php echo $lang->release->product;?></th>
                      <td><?php echo $release->productName;?></td>
                    </tr>
                      <?php if($release->productType != 'normal'):?>
                        <tr>
                          <th><?php echo $lang->product->branch;?></th>
                          <td><?php echo $branchName;?></td>
                        </tr>
                      <?php endif;?>
                    <tr>
                      <th><?php echo $lang->release->name;?></th>
                      <td><?php echo $release->name;?></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->release->build;?></th>
                      <td title='<?php echo $release->buildName?>'>
                          <?php echo ($release->project) ? html::a($this->createLink('build', 'view', "buildID=$release->buildID"), $release->buildName, '_blank') : $release->buildName;?>
                      </td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->release->status;?></th>
                      <td><?php echo $lang->release->statusList[$release->status];?></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->release->date;?></th>
                      <td><?php echo $release->date;?></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->release->desc;?></th>
                      <td><?php echo $release->desc;?></td>
                    </tr>
                  </table>
                </div>
              </div>
              <div class='detail'>
                <div class='detail-title'><?php echo $lang->files?></div>
                <div class='detail-content article-content'>
                  <?php
                  if($release->files)
                  {
                      echo $this->fetch('file', 'printFiles', array('files' => $release->files, 'fieldset' => 'false'));
                  }
                  elseif($release->filePath)
                  {
                      echo $lang->release->filePath . html::a($release->filePath, $release->filePath, '_blank');
                  }
                  elseif($release->scmPath)
                  {
                      echo $lang->release->scmPath . html::a($release->scmPath, $release->scmPath, '_blank');
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
.tabs .tab-content .tab-pane .action{position: absolute; right: <?php echo ($countStories or $countBugs or $countLeftBugs) ? '100px' : '-1px'?>; top: 0px;}
</style>
<?php js::set('param', helper::safe64Decode($param))?>
<?php js::set('link', $link)?>
<?php js::set('releaseID', $release->id)?>
<?php js::set('type', $type)?>
<?php include '../../common/view/footer.html.php';?>
