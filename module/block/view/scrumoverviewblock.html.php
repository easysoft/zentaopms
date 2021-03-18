<?php
/**
 * The scrum overview block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(empty($totalData)): ?>
<div class='empty-tip'><?php common::printLink('project', 'create', '', "<i class='icon-plus'></i> " . $lang->project->create, '', "class='btn btn-primary'")?></div>
<?php else:?>
<style>
#totalInvestment {padding-bottom: 5px;}
.overview-title {font-size: 14px; color: #838a9d;}
.extra-tips {font-size: 12px; color: #a1a7b7;}
.progress {margin-bottom: 8px;}
.total-block {padding: 0px 25px;}
.hours-statistics {position: relative; padding: 0px 5px 5px; display: table-cell;text-align: center;}
.hours-statistics > small {display: block; color: #A6AAB8;}
.hours-statistics > span {display: block; color: #3c4353; font-weight: bold;}
.tile-amount {font-size: 20px;}
</style>
<div class="panel-body conatiner-fluid">
  <div class="table-row">
    <div class="col-6 tile">
      <div class="text-left" id="totalInvestment"><strong><?php echo $lang->block->totalInvestment;?></strong></div>
      <div class="table-row">
        <div class="col-4 tile">
          <div class="overview-title"><i class="icon-program-group icon-group"></i> <?php echo $lang->block->totalPeople;?></div>
          <div class="tile-amount">
          <?php echo $totalData[$projectID]->teamCount ? html::a($this->createLink('project', 'manageMembers', 'projectID=' . $projectID), $totalData[$projectID]->teamCount) : 0;?>
          </div>
        </div>
        <div class="col-4 tile">
          <div class="overview-title"><i class="icon icon-clock"></i> <?php echo $lang->block->consumedHours;?></div>
          <div class="tile-amount" title="<?php echo $totalData[$projectID]->consumed . $lang->execution->workHour;?>">
          <?php echo $totalData[$projectID]->consumed ? html::a($this->createLink('project', 'index', 'locate=no'), $totalData[$projectID]->consumed . $lang->execution->workHourUnit) : 0;?>
          </div>
          <div class="tile-info">
          <span class="extra-tips"><?php echo $lang->block->estimatedHours . ' ' . $totalData[$projectID]->estimate . $lang->execution->workHour;?></span>
          </div>
        </div>
        <div class="col-4 tile">
          <div class="overview-title"><i class="icon icon-cost"></i> <?php echo $lang->block->spent;?></div>
          <div class="tile-amount">￥0</div>
          <div class="tile-info">
            <?php $budget = $totalData[$projectID]->budget != 0 ? zget($lang->project->currencySymbol, $totalData[$projectID]->budgetUnit) . number_format($totalData[$projectID]->budget, 2) : $lang->project->future;?>
            <span class="extra-tips"><?php echo $lang->block->budget . ' ' . $budget;?></span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-3 total-block hide-in-sm">
      <div><strong><?php echo $lang->block->totalStory;?></strong></div>
      <div class="tile-amount">
      <?php echo $totalData[$projectID]->allStories;?>
      </div>
      <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $totalData[$projectID]->doneStories;?>" aria-valuemin="0" aria-valuemax="<?php echo $totalData[$projectID]->allStories;?>" style="width: <?php echo floor(($totalData[$projectID]->doneStories/$totalData[$projectID]->allStories)*100).'%';?>"></div>
      </div>
      <div class="hours-statistics">
        <small><?php echo $lang->block->done;?></small>
        <span><?php echo $totalData[$projectID]->doneStories;?></span>
      </div>
      <div class="hours-statistics">
        <small><?php echo $lang->block->left;?></small>
        <span><?php echo $totalData[$projectID]->leftStories;?></span>
      </div>
    </div>
    <div class="col-3 total-block hide-in-sm">
      <div><strong><?php echo $lang->block->totalBug;?></strong></div>
      <div class="tile-amount">
      <?php echo $totalData[$projectID]->allBugs;?>
      </div>
      <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $totalData[$projectID]->doneBugs;?>" aria-valuemin="0" aria-valuemax="<?php echo $totalData[$projectID]->allBugs;?>" style="width: <?php echo floor(($totalData[$projectID]->doneBugs/$totalData[$projectID]->allBugs)*100).'%';?>"></div>
      </div>
      <div class="hours-statistics">
        <small><?php echo $lang->block->done;?></small>
        <span><?php echo $totalData[$projectID]->doneBugs;?></span>
      </div>
      <div class="hours-statistics">
        <small><?php echo $lang->block->left;?></small>
        <span><?php echo $totalData[$projectID]->leftBugs;?></span>
      </div>
    </div>
  </div>
</div>
<?php endif;?>
