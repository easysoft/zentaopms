<?php
/**
 * The view of mr link module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      gang zeng
 * @package     repo
 * @version     $Id: link.html.php $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->productplan->confirmUnlinkStory)?>
<?php js::set('confirmUnlinkBug', $lang->productplan->confirmUnlinkBug)?>
<?php js::set('confirmUnlinkTask', $lang->mr->confirmUnlinkTask)?>
<?php js::set('productID', $product->id);?>
<?php js::set('MRID', $MR->id);?>
<?php js::set('storyPageID', $storyPager->pageID);?>
<?php js::set('storyRecPerPage', $storyPager->recPerPage);?>
<?php js::set('storyRecTotal', $storyPager->recTotal);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php common::printBack(inlink('browse'), 'btn btn-primary');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $MR->id;?></span>
      <span title='<?php echo $MR->title;?>' class='text'><?php echo $MR->title;?></span>
      <?php if($MR->deleted):?>
      <span class='label label-danger'><?php echo $lang->product->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <div class='tabs' id='tabsNav'>
    <ul class='nav nav-tabs'>
      <li><?php echo html::a(inlink('view', "MRID={$MR->id}"), $lang->mr->view);?></li>
      <li><?php echo html::a(inlink('diff', "MRID={$MR->id}"), $lang->mr->viewDiff);?></li>
      <li class='<?php if($type == 'story') echo 'active'?>'><a href='#stories' data-toggle='tab'><?php echo  html::icon($lang->icons['story'], 'text-primary') . ' ' . $lang->productplan->linkedStories;?></a></li>
      <li class='<?php if($type == 'bug') echo 'active'?>'><a href='#bugs' data-toggle='tab'><?php echo  html::icon($lang->icons['bug'], 'text-red') . ' ' . $lang->productplan->linkedBugs;?></a></li>
      <li class='<?php if($type == 'task') echo 'active'?>'><a href='#tasks' data-toggle='tab'><?php echo  html::icon('check-sign', 'text-info') . ' ' . $lang->mr->linkedTasks;?></a></li>
    </ul>
    <div class='tab-content'>
      <div id='stories' class='tab-pane <?php if($type == 'story') echo 'active'?>'>
        <?php $canOrder = false;?>
        <div class='actions'>
          <?php echo html::a("javascript:showLink($product->id, \"story\")", '<i class="icon-link"></i> ' . $lang->productplan->linkStory, '', "class='btn btn-primary'");?>
        </div>
        <div class='linkBox cell hidden'></div>
        <form class='main-table table-story' data-ride='table' method='post' target='hiddenwin' action="<?php echo inlink('batchUnlinkStory', "planID=$MR->id&orderBy=$orderBy");?>">
          <table class='table has-sort-head' id='storyList'>
            <?php
            $canBatchClose        = common::hasPriv('story', 'batchClose');
            $canBatchEdit         = common::hasPriv('story', 'batchEdit');
            $canBatchReview       = common::hasPriv('story', 'batchReview');
            $canBatchChangeBranch = common::hasPriv('story', 'batchChangeBranch');
            $canBatchChangeModule = common::hasPriv('story', 'batchChangeModule');
            $canBatchChangePlan   = common::hasPriv('story', 'batchChangePlan');
            $canBatchChangeStage  = common::hasPriv('story', 'batchChangeStage');
            $canBatchAssignTo     = common::hasPriv('story', 'batchAssignTo');

            $vars = "MRID={$MR->id}&type=story&orderBy=%s&link=$link&param=$param";
            ?>
            <thead>
              <tr class='text-center'>
                <th class='c-id text-left'>
                  <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
                </th>
                <?php if($canOrder):?>
                <th class='w-70px'><?php common::printOrderLink('order', $orderBy, $vars, $lang->productplan->updateOrder);?></th>
                <?php endif;?>
                <th class='w-70px' title=<?php echo $lang->pri;?>><?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
                <th class='w-150px text-left'><?php common::printOrderLink('module',     $orderBy, $vars, $lang->story->module);?></th>
                <th class='text-left'><?php common::printOrderLink('title',      $orderBy, $vars, $lang->story->title);?></th>
                <th class='c-user'> <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
                <th class='c-user'> <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
                <th class='w-70px text-right'> <?php common::printOrderLink('estimate',   $orderBy, $vars, $lang->story->estimateAB);?></th>
                <th class='w-70px'> <?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
                <th class='w-80px'> <?php common::printOrderLink('stage',      $orderBy, $vars, $lang->story->stageAB);?></th>
                <th class='c-actions-1'> <?php echo $lang->actions?></th>
              </tr>
            </thead>
            <tbody class='sortable text-center'>
              <?php
              $totalEstimate = 0.0;
              ?>
              <?php foreach($stories as $story):?>
              <?php
              $viewLink = $this->createLink('story', 'view', "storyID=$story->id", '', true);
              $totalEstimate += $story->estimate;
              ?>
              <tr data-id='<?php echo $story->id;?>'>
                <td class='c-id text-left'>
                  <?php printf('%03d', $story->id);?>
                </td>
                <?php if($canOrder):?><td class='sort-handler'><i class='icon-move'></i></td><?php endif;?>
                <td><span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
                <td class='text-left nobr'><?php echo zget($modulePairs, $story->module, '');?></td>
                <td class='text-left nobr' title='<?php echo $story->title?>'>
                  <?php
                  if($story->parent > 0) echo "<span class='label label-badge label-light' title={$lang->story->children}>{$lang->story->childrenAB}</span>";
                  echo html::a($viewLink , $story->title, '', 'class="iframe"');
                  ?>
                </td>
                <td><?php echo zget($users, $story->openedBy);?></td>
                <td><?php echo zget($users, $story->assignedTo);?></td>
                <td class='text-right' title="<?php echo $story->estimate . ' ' . $lang->hourCommon;?>"><?php echo $story->estimate . $config->hourUnit;?></td>
                <td>
                  <span class='status-story status-<?php echo $story->status?>'>
                    <?php echo $this->processStatus('story', $story);?>
                  </span>
                </td>
                <td><?php echo $lang->story->stageList[$story->stage];?></td>
                <td class='c-actions'>
                  <?php
                  if($canBeChanged and common::hasPriv('mr', 'unlink'))
                  {
                      $unlinkURL = $this->createLink('mr', 'unlink', "MRID=$MR->id&productID=$product->id&type=story&linkID=$story->id&confirm=yes");
                      echo html::a("javascript:ajaxDelete(\"$unlinkURL\", \"storyList\", confirmUnlinkStory)", '<i class="icon-unlink"></i>', '', "class='btn' title='{$lang->productplan->unlinkStory}'");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
          <?php if($stories):?>
          <div class='table-footer'>
            <div class='table-statistic'><?php echo sprintf($lang->mr->storySummary, count($stories));?></div>
            <?php
            $this->app->rawParams['type'] = 'story';
            $storyPager->show('right', 'pagerjs');
            $this->app->rawParams['type'] = $type;
            ?>
          </div>
          <?php endif;?>
        </form>
      </div>
      <div id='bugs' class='tab-pane <?php if($type == 'bug') echo 'active';?>'>
        <div class='actions'>
        <?php echo html::a("javascript:showLink($product->id, \"bug\")", '<i class="icon-bug"></i> ' . $lang->productplan->linkBug, '', "class='btn btn-primary'");?>
        </div>
        <div class='linkBox cell hidden'></div>
        <form class='main-table table-bug' data-ride='table' method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkBug', "planID=$MR->id&orderBy=$orderBy");?>">
          <table class='table has-sort-head' id='bugList'>
            <?php $canBatchUnlink = $canBeChanged and common::hasPriv('mr', 'unlink');?>
            <?php $vars = "planID={$MR->id}&type=bug&orderBy=%s&link=$link&param=$param"; ?>
            <thead>
              <tr class='text-center'>
                <th class='c-id text-left'>
                  <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
                </th>
                <th class='w-70px' title=<?php echo $lang->pri;?>> <?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
                <th class='text-left'><?php common::printOrderLink('title',      $orderBy, $vars, $lang->bug->title);?></th>
                <th class='c-user'> <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
                <th class='c-user'> <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->bug->assignedToAB);?></th>
                <th class='w-100px'><?php common::printOrderLink('status',     $orderBy, $vars, $lang->bug->status);?></th>
                <th class='w-50px'> <?php echo $lang->actions?></th>
              </tr>
            </thead>
            <tbody class='text-center'>
              <?php foreach($bugs as $bug):?>
              <tr>
                <td class='c-id text-left'>
                  <?php printf('%03d', $bug->id);?>
                </td>
                <td><span class='label-pri label-pri-<?php echo $bug->pri;?>' title='<?php echo zget($lang->bug->priList, $bug->pri, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri);?></span></td>
                <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), $bug->title, '', 'class="iframe"');?></td>
                <td><?php echo zget($users, $bug->openedBy);?></td>
                <td><?php echo zget($users, $bug->assignedTo);?></td>
                <td>
                  <span class='status-bug status-<?php echo $bug->status?>'>
                    <?php echo $this->processStatus('bug', $bug);?>
                  </span>
                </td>
                <td class='c-actions'>
                  <?php
                  if($canBeChanged and common::hasPriv('mr', 'unlink'))
                  {
                      $unlinkURL = $this->createLink('mr', 'unlink', "MRID=$MR->id&productID=$product->id&type=bug&linkID=$bug->id&confirm=yes");
                      echo html::a("javascript:ajaxDelete(\"$unlinkURL\", \"bugList\", confirmUnlinkBug)", '<i class="icon-unlink"></i>', '', "class='btn' title='{$lang->productplan->unlinkBug}'");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
          <?php if($bugs):?>
          <div class='table-footer'>
            <div class='table-statistic'><?php echo sprintf($lang->productplan->bugSummary, count($bugs));?></div>
            <?php
            $this->app->rawParams['type'] = 'bug';
            $bugPager->show('right', 'pagerjs');
            $this->app->rawParams['type'] = $type;
            ?>
          </div>
          <?php endif;?>
        </form>
      </div>
      <div id='tasks' class='tab-pane <?php if($type == 'task') echo 'active';?>'>
        <div class='actions'>
        <?php echo html::a("javascript:showLink($product->id, \"task\")", '<i class="icon-todo"></i> ' . $lang->mr->linkTask, '', "class='btn btn-primary'");?>
        </div>
        <div class='linkBox cell hidden'></div>
        <form class='main-table table-task' data-ride='table' method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkTask', "planID=$MR->id&orderBy=$orderBy");?>">
          <table class='table has-sort-head' id='taskList'>
            <?php $canBatchUnlink = $canBeChanged and common::hasPriv('mr', 'unlink');?>
            <?php $vars = "MRID={$MR->id}&type=task&orderBy=%s&link=$link&param=$param"; ?>
            <thead>
              <tr class='text-center'>
                <th class='c-id text-left'>
                  <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
                </th>
                <th class='w-70px' title=<?php echo $lang->pri;?>> <?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
                <th class='text-left'><?php common::printOrderLink('name',      $orderBy, $vars, $lang->task->name);?></th>
                <th class='c-user'> <?php common::printOrderLink('finishedBy',   $orderBy, $vars, $lang->task->finishedByAB);?></th>
                <th class='c-user'> <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->task->assignedToAB);?></th>
                <th class='w-100px'><?php common::printOrderLink('status',     $orderBy, $vars, $lang->task->status);?></th>
                <th class='w-50px'> <?php echo $lang->actions?></th>
              </tr>
            </thead>
            <tbody class='text-center'>
              <?php foreach($tasks as $task):?>
              <tr>
                <td class='c-id text-left'>
                  <?php printf('%03d', $task->id);?>
                </td>
                <td><span class='label-pri label-pri-<?php echo $task->pri;?>' title='<?php echo zget($lang->task->priList, $task->pri, $task->pri);?>'><?php echo zget($lang->task->priList, $task->pri, $task->pri);?></span></td>
                <td class='text-left nobr' title='<?php echo $task->name?>'><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id", '', true), $task->name, '', 'class="iframe"');?></td>
                <td><?php echo zget($users, $task->finishedBy);?></td>
                <td><?php echo zget($users, $task->assignedTo);?></td>
                <td>
                  <span class='status-task status-<?php echo $task->status?>'>
                    <?php echo $this->processStatus('task', $task);?>
                  </span>
                </td>
                <td class='c-actions'>
                  <?php
                      $unlinkURL = $this->createLink('mr', 'unlink', "MRID=$MR->id&productID=$product->id&type=task&linkID=$task->id&confirm=yes");
                      echo html::a("javascript:ajaxDelete(\"$unlinkURL\", \"taskList\", confirmUnlinkTask)", '<i class="icon-unlink"></i>', '', "class='btn' title='{$lang->mr->unlinkTask}'");
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
          <?php if($tasks):?>
          <div class='table-footer'>
            <div class='table-statistic'><?php echo sprintf($lang->mr->taskSummary, count($tasks));?></div>
            <?php
            $this->app->rawParams['type'] = 'task';
            $taskPager->show('right', 'pagerjs');
            $this->app->rawParams['type'] = $type;
            ?>
          </div>
          <?php endif;?>
        </form>
      </div>
    </div>
  </div>
</div>
<?php js::set('param', helper::safe64Decode($param))?>
<?php js::set('link', $link)?>
<?php js::set('orderBy', $orderBy)?>
<?php js::set('type', $type)?>
<?php include '../../common/view/footer.html.php';?>
