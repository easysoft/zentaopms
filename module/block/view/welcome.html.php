<?php
/**
 * The welcome view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<style>
.block-welcome .col-left {width: 25%;}
.block-welcome .col-left > h4 {margin: 5px 0;line-height: 30px;}
.block-welcome .col-left .timeline {margin: 10px 0 0;font-size: 12px;}
.block-welcome .col-right > h4 {font-weight: normal;}
.block-welcome .col-right > h4 small {display: inline-block;margin-right: 8px;font-size: 14px;}
.block-welcome .col-right .tiles {padding: 10px 0 0 16px;border-left: 1px solid #e5e8ec;}
.block-welcome .col-right .tile {width: 33%;}
.block-welcome .panel-body {padding-top: 15px;}
.block-welcome .user-notification-icon {position: relative;display: inline-block;margin-left: 5px;}
.block-welcome .user-notification-icon .label-badge {position: absolute;top: 1px;right: -8px;min-width: 16px;padding: 2px;font-size: 12px;font-weight: normal;}
.block-welcome.block-sm .col-right {padding: 0;}
.block-welcome.block-sm .col-right .tiles {border-left: none; padding-left: 0}
.block-welcome.block-sm .tile-title {font-size: 12px; margin: 0 -10px;}
.block-welcome .progress-group{margin-top: 20px;}
.block-welcome .progress{margin-top: 10px; width: 85%}
</style>
<?php $progress = $tasks == 0 ? 0 : round($doneTasks / $tasks, 3) * 100;?>
<div class='panel-move-handler'></div>
<div class="panel-body conatiner-fluid">
  <div class="table-row">
    <div class="col col-left hide-in-sm">
      <h4><?php printf($lang->block->welcomeList[$welcomeType], $app->user->realname)?></h4>
      <div class='progress-group'>
        <span class="progress-num"><strong><?php echo $lang->block->assignToMe . ' ' . $lang->block->done . $progress . '%';?></strong></span>
        <div class="progress">
          <div class="progress-bar" role="progressbar" aria-valuenow="82" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress;?>%">
          </div>
        </div>
      </div>
    </div>
    <div class="col col-right">
    <h4><small class="text-muted"><?php echo date(DT_DATE3)?></small> <?php echo $lang->block->leftToday?></h4>
      <div class="row tiles">
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myTask?></div>
          <div class="tile-amount"><?php echo empty($tasks) ? 0 : html::a($this->createLink('my', 'work', 'mode=task'), (int)$tasks);?></div>
          <?php if(!empty($delay['task'])):?>
          <div class="tile-info">
          <span class="label label-danger label-outline"><?php echo $lang->block->delayed . ' ' . $delay['task']?></span>
          </div>
          <?php endif;?>
        </div>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myBug?></div>
          <div class="tile-amount"><?php echo empty($bugs) ? 0 : html::a($this->createLink('my', 'work', 'mode=bug'), (int)$bugs);?></div>
          <?php if(!empty($delay['bug'])):?>
          <div class="tile-info">
          <span class="label label-danger label-outline"><?php echo $lang->block->delayed . ' ' . $delay['bug']?></span>
          </div>
          <?php endif;?>
        </div>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myStory?></div>
          <div class="tile-amount"><?php echo empty($stories) ? 0 : html::a($this->createLink('my', 'work', 'mode=story'), (int)$stories);?></div>
        </div>
      </div>
    </div>
  </div>
</div>
