<?php
/**
 * The welcome view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<style>
.block-welcome .col-left {width: 25%;}
.block-welcome .col-left > h4 {margin: 5px 0; line-height: 30px;}
.block-welcome .col-left .timeline {margin: 10px 0 0; font-size: 12px;}
.block-welcome .col-right > h4 small {display: inline-block;margin-right: 8px; font-size: 14px;}
.block-welcome .col-right .tiles {padding: 10px 0 0 16px;}
.block-welcome .col-right .tile {width: 33%;}
.block-welcome .panel-body {padding-top: 15px;}
.block-welcome .user-notification-icon {position: relative; display: inline-block; margin-left: 5px;}
.block-welcome .user-notification-icon .label-badge {position: absolute;top: 1px; right: -8px; min-width: 16px; padding: 2px; font-size: 12px; font-weight: normal;}
.block-welcome.block-sm .col-right {padding: 0;}
.block-welcome.block-sm .col-right .tiles {border-left: none; padding-left: 0}
.block-welcome.block-sm .tile-title {font-size: 12px; margin: 0 -10px;}
.block-welcome .progress-group{margin-top: 25px;}
.block-welcome .progress{margin-top: 10px; width: 85%;}
.block-welcome .user-welcome{margin-top: 10px !important;}
.block-welcome .col-right{border-left: 1px solid #e5e8ec;}
.block-welcome.block-sm .col-right {border: none;}
.block-welcome .left-today{margin-left: 36px;}
.block-welcome .done-progress{display: inline-block; color: #5B606E; font-weight: 500;}
.block-welcome .welcome-label{background: #ffebee; border: none;}
.block-welcome.block-sm .left-today {margin-left: 0px;}
.block-welcome .progress-num{display: flex; justify-content: space-between; width: 85%;}
</style>
<?php $progress = $tasks == 0 ? 0 : round($doneTasks / $tasks, 3) * 100;?>
<div class='panel-move-handler'></div>
<div class="panel-body conatiner-fluid">
  <div class="table-row">
    <div class="col col-left hide-in-sm">
      <h4><small class="text-muted"><?php echo date(DT_DATE3)?></small></h4>
      <h4 class="user-welcome"><?php printf($lang->block->welcomeList[$welcomeType], $app->user->realname)?></h4>
      <div class="progress-group">
        <div class="progress-num">
          <div>
            <strong><?php echo $lang->block->assignToMe;?></strong>
          </div>
          <div class="done-progress"><?php echo $lang->block->done . " $progress" . '%';?></div>
        </div>
        <div class="progress">
          <div class="progress-bar" role="progressbar" aria-valuenow="82" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress;?>%">
          </div>
        </div>
      </div>
    </div>
    <div class="col col-right">
      <h4 class="left-today"><?php echo $lang->block->leftToday?></h4>
      <div class="row tiles">
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myTask?></div>
          <div class="tile-amount text-primary"><?php echo empty($tasks) ? 0 : html::a($this->createLink('my', 'work', 'mode=task'), (int)$tasks, '', "class='text-primary'");?></div>
          <?php if(!empty($delay['task'])):?>
          <div class="tile-info">
          <span class="label label-danger label-outline welcome-label"><?php echo $lang->block->delayed . ' ' . $delay['task']?></span>
          </div>
          <?php endif;?>
        </div>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myBug?></div>
          <div class="tile-amount text-primary"><?php echo empty($bugs) ? 0 : html::a($this->createLink('my', 'work', 'mode=bug'), (int)$bugs, '', "class='text-primary'");?></div>
          <?php if(!empty($delay['bug'])):?>
          <div class="tile-info">
          <span class="label label-danger label-outline welcome-label"><?php echo $lang->block->delayed . ' ' . $delay['bug']?></span>
          </div>
          <?php endif;?>
        </div>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myStory?></div>
          <div class="tile-amount text-primary"><?php echo empty($stories) ? 0 : html::a($this->createLink('my', 'work', 'mode=story'), (int)$stories, '', "class='text-primary'");?></div>
        </div>
      </div>
    </div>
  </div>
</div>
