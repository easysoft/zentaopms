<?php
/**
 * The view file of build module's view method of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: view.html.php 4386 2013-02-19 07:37:45Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->build->confirmUnlinkStory)?>
<?php js::set('confirmUnlinkBug', $lang->build->confirmUnlinkBug)?>
<?php js::set('flow', $config->global->flow)?>
<?php if(isonlybody()):?>
<style>
#stories .action{display:none;}
#bugs .action{display:none;}
tbody tr td:first-child input{display:none;}
</style>
<?php endif;?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $browseLink = $this->session->buildList ? $this->session->buildList : $this->createLink('project', 'build', "projectID=$build->project");?>
    <?php common::printBack($browseLink, 'btn btn-link');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $build->id;?></span>
      <span class='text' title='<?php echo $build->name;?>'><?php echo $build->name;?></span>
      <?php if($build->deleted):?>
      <span class='label label-danger'><?php echo $lang->build->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php
    if(!$build->deleted)
    {
        if($config->global->flow != 'onlyTest')
        {
            if(common::hasPriv('build', 'linkStory')) echo html::a(inlink('view', "buildID=$build->id&type=story&link=true"), '<i class="icon-link"></i> ' . $lang->build->linkStory, '', "class='btn btn-link'");
            if(common::hasPriv('build', 'linkBug'))   echo html::a(inlink('view', "buildID=$build->id&type=bug&link=true"), '<i class="icon-bug"></i> ' . $lang->build->linkBug, '', "class='btn btn-link'");
        }
        common::printIcon('build', 'edit',   "buildID=$build->id", $build);
        common::printIcon('build', 'delete', "buildID=$build->id", $build, 'button', '', 'hiddenwin');
    }
    ?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <?php if($config->global->flow == 'onlyTest'):?>
  <div class='detail'>
    <div class='detail-title'><?php echo $lang->build->desc;?></div>
    <div class='detail-content article-content'><?php echo $build->desc;?></div>
  </div>
  <?php echo $this->fetch('file', 'printFiles', array('files' => $build->files, 'fieldset' => 'true'));?>
  <?php include '../../common/view/action.html.php';?>
  <div id="mainActions">
    <nav class="container"></nav>
    <div class="btn-toolbar">
      <?php
      common::printBack($browseLink);
      if(!$build->deleted)
      {
          echo "<div class='divider'></div>";
          common::printIcon('build', 'edit',   "buildID=$build->id", $build);
          common::printIcon('build', 'delete', "buildID=$build->id", $build, 'button', '', 'hiddenwin');
      }
      ?>
    </div>
  </div>
  <?php else:?>
  <div class='tabs' id='tabsNav'>
  <?php $countStories = count($stories); $countBugs = count($bugs); $countNewBugs = count($generatedBugs);?>
    <ul class='nav nav-tabs'>
      <li <?php if($type == 'story')     echo "class='active'"?>><a href='#stories' data-toggle='tab'><?php echo html::icon($lang->icons['story'], 'text-primary') . ' ' . $lang->build->stories;?></a></li>
      <li <?php if($type == 'bug')       echo "class='active'"?>><a href='#bugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug'], 'text-green') . ' ' . $lang->build->bugs;?></a></li>
      <li <?php if($type == 'newbug')    echo "class='active'"?>><a href='#newBugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug'], 'text-red') . ' ' . $lang->build->generatedBugs;?></a></li>
      <li <?php if($type == 'buildInfo') echo "class='active'"?>><a href='#buildInfo' data-toggle='tab'><?php echo html::icon($lang->icons['plan'], 'text-info') . ' ' . $lang->build->view;?></a></li>
    </ul>
    <div class='tab-content'>
      <div class='tab-pane <?php if($type == 'story') echo 'active'?>' id='stories'>
        <?php if(common::hasPriv('build', 'linkStory')):?>
        <div class='actions'><?php echo html::a("javascript:showLink($build->id, \"story\")", '<i class="icon-link"></i> ' . $lang->build->linkStory, '', "class='btn btn-primary'");?></div>
        <div class='linkBox cell hidden'></div>
        <?php endif;?>
        <form class='main-table table-story' data-ride='table' method='post' target='hiddenwin' action='<?php echo inlink('batchUnlinkStory', "buildID={$build->id}")?>' id='linkedStoriesForm'>
          <table class='table has-sort-head' id='storyList'>
            <?php $canBatchUnlink = common::hasPriv('build', 'batchUnlinkStory');?>
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
                <th class='w-70px'>   <?php common::printOrderLink('pri',      $orderBy, $vars, $lang->priAB);?></th>
                <th class='text-left'><?php common::printOrderLink('title',    $orderBy, $vars, $lang->story->title);?></th>
                <th class='c-user'>   <?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->openedByAB);?></th>
                <th class='w-70px'>   <?php common::printOrderLink('estimate', $orderBy, $vars, $lang->story->estimateAB);?></th>
                <th class='w-70px'>   <?php common::printOrderLink('status',   $orderBy, $vars, $lang->statusAB);?></th>
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
                  <?php echo html::checkbox('unlinkStories', array($story->id => sprintf('%03d', $story->id)));?>
                  <?php else:?>
                  <?php printf('%03d', $story->id);?>
                  <?php endif;?>
                </td>
                <td><span class='label-pri label-pri-<?php echo $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
                <td class='text-left nobr' title='<?php echo $story->title?>'><?php echo html::a($storyLink,$story->title, '', "class='iframe' data-width='1000'");?></td>
                <td><?php echo $users[$story->openedBy];?></td>
                <td><?php echo $story->estimate;?></td>
                <td>
                  <span class='status-<?php echo $story->status;?>'>
                    <span class='label label-dot'></span>
                    <?php echo $lang->story->statusList[$story->status];?>
                  </span>
                </td>
                <td><?php echo $lang->story->stageList[$story->stage];?></td>
                <td class='c-actions'>
                  <?php
                  if(common::hasPriv('build', 'unlinkStory'))
                  {
                      $unlinkURL = inlink('unlinkStory', "buildID=$build->id&story=$story->id");
                      echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"storyList\",confirmUnlinkStory)", '<i class="icon-unlink"></i>', '', "class='btn btn-icon' title='{$lang->build->unlinkStory}'");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
          <?php if($countStories):?>
          <div class='table-footer'>
            <?php if($canBatchUnlink):?>
            <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
            <div class="table-actions btn-toolbar">
              <?php echo html::submitButton($lang->build->batchUnlink, '', 'btn');?>
            </div>
            <?php endif;?>
            <div class='table-statistic'><?php echo sprintf($lang->build->finishStories, $countStories);?></div>
          </div>
          <?php endif;?>
        </form>
      </div>
      <div class='tab-pane <?php if($type == 'bug') echo 'active'?>' id='bugs'>
        <?php if(common::hasPriv('build', 'linkBug')):?>
        <div class='actions'><?php echo html::a("javascript:showLink($build->id, \"bug\")", '<i class="icon-bug"></i> ' . $lang->build->linkBug, '', "class='btn btn-primary'");?></div>
        <div class='linkBox cell hidden'></div>
        <?php endif;?>
        <form class='main-table table-bug' data-ride='table' method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkBug', "build=$build->id");?>" id='linkedBugsForm'>
          <table class='table has-sort-head' id='bugList'>
            <?php $canBatchUnlink = common::hasPriv('build', 'batchUnlinkBug');?>
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
                <th class='text-left'><?php common::printOrderLink('title',        $orderBy, $vars, $lang->bug->title);?></th>
                <th class='w-100px'>  <?php common::printOrderLink('status',       $orderBy, $vars, $lang->bug->status);?></th>
                <th class='c-user'>   <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->openedByAB);?></th>
                <th class='c-date'>   <?php common::printOrderLink('openedDate',   $orderBy, $vars, $lang->bug->openedDateAB);?></th>
                <th class='c-user'>   <?php common::printOrderLink('resolvedBy',   $orderBy, $vars, $lang->bug->resolvedByAB);?></th>
                <th class='w-100px'>  <?php common::printOrderLink('resolvedDate', $orderBy, $vars, $lang->bug->resolvedDateAB);?></th>
                <th class='w-60px'>   <?php echo $lang->actions?></th>
              </tr>
            </thead>
            <tbody class='text-center'>
              <?php foreach($bugs as $bug):?>
              <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
              <tr>
                <td class='c-id text-left'>
                  <?php if($canBatchUnlink):?>
                  <?php echo html::checkbox('unlinkBugs', array($bug->id => sprintf('%03d', $bug->id)));?>
                  <?php else:?>
                  <?php printf('%03d', $bug->id);?>
                  <?php endif;?>
                <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($bugLink, $bug->title, '', "class='iframe' data-width='1000'");?></td>
                <td>
                  <span class='status-<?php echo $bug->status?>'>
                    <span class='label label-dot'></span>
                    <?php echo $lang->bug->statusList[$bug->status];?>
                  </span>
                </td>
                <td><?php echo $users[$bug->openedBy];?></td>
                <td><?php echo substr($bug->openedDate, 5, 11)?></td>
                <td><?php echo $users[$bug->resolvedBy];?></td>
                <td><?php echo substr($bug->resolvedDate, 5, 11)?></td>
                <td class='c-actions'>
                  <?php
                  if(common::hasPriv('build', 'unlinkBug'))
                  {
                      $unlinkURL = inlink('unlinkBug', "buildID=$build->id&bug=$bug->id");
                      echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"bugList\",confirmUnlinkBug)", '<i class="icon-unlink"></i>', '', "class='btn btn-icon' title='{$lang->build->unlinkBug}'");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
          <?php if($countBugs):?>
          <div class='table-footer'>
            <?php if($canBatchUnlink):?>
            <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
            <div class="table-actions btn-toolbar">
              <?php echo html::submitButton($lang->build->batchUnlink, '', 'btn');?>
            </div>
            <?php endif;?>
            <div class='text'><?php echo sprintf($lang->build->resolvedBugs, $countBugs);?></div>
          </div>
          <?php endif;?>
        </form>
      </div>
      <div class='tab-pane <?php if($type == 'newbug') echo 'active'?>' id='newBugs'>
        <div class='main-table'>
          <table class='table has-sort-head'>
            <?php $vars = "buildID={$build->id}&type=newbug&link=$link&param=$param&orderBy=%s";?>
            <thead>
              <tr class='text-center'>
                <th class='c-id text-left'><?php common::printOrderLink('id',       $orderBy, $vars, $lang->idAB);?></th>
                <th class='w-severity'><?php common::printOrderLink('severity',     $orderBy, $vars, $lang->bug->severityAB);?></th>
                <th class='text-left'> <?php common::printOrderLink('title',        $orderBy, $vars, $lang->bug->title);?></th>
                <th class='w-100px'>   <?php common::printOrderLink('status',       $orderBy, $vars, $lang->bug->status);?></th>
                <th class='c-user'>    <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->openedByAB);?></th>
                <th class='c-date'>    <?php common::printOrderLink('openedDate',   $orderBy, $vars, $lang->bug->openedDateAB);?></th>
                <th class='c-user'>    <?php common::printOrderLink('resolvedBy',   $orderBy, $vars, $lang->bug->resolvedByAB);?></th>
                <th class='w-100px'>   <?php common::printOrderLink('resolvedDate', $orderBy, $vars, $lang->bug->resolvedDateAB);?></th>
              </tr>
            </thead>
            <tbody class='text-center'>
              <?php foreach($generatedBugs as $bug):?>
              <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
              <tr>
                <td class='text-left'><?php printf('%03d', $bug->id);?></td>
                <td>
                  <span class='label-severity' data-severity='<?php echo $bug->severity;?>' title='<?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?>'></span>
                </td>
                <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($bugLink, $bug->title, '', "class='iframe' data-width='1000'");?></td>
                <td>
                  <span class='status-<?php echo $bug->status?>'>
                    <span class='label label-dot'></span>
                    <?php echo $lang->bug->statusList[$bug->status];?>
                  </span>
                </td>
                <td><?php echo $users[$bug->openedBy];?></td>
                <td><?php echo substr($bug->openedDate, 5, 11)?></td>
                <td><?php echo $users[$bug->resolvedBy];?></td>
                <td><?php echo substr($bug->resolvedDate, 5, 11)?></td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
          <?php if($countNewBugs):?>
          <div class='table-footer'>
            <div class='text'><?php echo sprintf($lang->build->createdBugs, $countNewBugs);?></div>
          </div>
          <?php endif;?>
        </div>
      </div>
      <div class='tab-pane <?php if($type == 'buildInfo') echo 'active'?>' id='buildInfo'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->build->basicInfo?></div>
            <div class='detail-content'>
              <table class='table table-data table-condensed table-borderless table-fixed'>
                <tr>
                  <th class='w-80px'><?php echo $lang->build->product;?></th>
                  <td><?php echo $build->productName;?></td>
                </tr>
                <?php if($build->productType != 'normal'):?>
                <tr>
                  <th><?php echo $lang->product->branch;?></th>
                  <td><?php echo $branchName;?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th><?php echo $lang->build->name;?></th>
                  <td><?php echo $build->name;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->build->builder;?></th>
                  <td><?php echo $users[$build->builder];?></td>
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
                <?php if($config->global->flow != 'onlyTest'):?>
                <tr>
                  <th style="vertical-align:top"><?php echo $lang->build->desc;?></th>
                  <td>
                    <?php if($build->desc):?>
                    <?php echo $build->desc;?>
                    <?php else:?>
                    <?php echo $lang->noData;?>
                    <?php endif;?>
                  </td>
                </tr>
                <?php endif;?>
              </table>
            </div>
          </div>
          <?php if($config->global->flow != 'onlyTest'):?>
          <?php echo $this->fetch('file', 'printFiles', array('files' => $build->files, 'fieldset' => 'true'));?>
          <?php endif;?>
          <?php include '../../common/view/action.html.php';?>
        </div>
      </div>
    </div>
  </div>
  <?php endif;?>
</div>
<?php if($config->global->flow != 'onlyTest'):?>
<?php js::set('param', helper::safe64Decode($param))?>
<?php js::set('link', $link)?>
<?php js::set('buildID', $build->id)?>
<?php js::set('type', $type)?>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
