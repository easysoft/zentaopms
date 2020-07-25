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
.block-welcome .col-right .tile {width: 20%;}
.block-welcome .panel-body {padding-top: 15px;}
.block-welcome .user-notification-icon {position: relative;display: inline-block;margin-left: 5px;}
.block-welcome .user-notification-icon .label-badge {position: absolute;top: 1px;right: -8px;min-width: 16px;padding: 2px;font-size: 12px;font-weight: normal;}
.block-welcome.block-sm .col-right {padding: 0;}
.block-welcome.block-sm .col-right .tiles {border-left: none; padding-left: 0}
.block-welcome.block-sm .tile-title {font-size: 12px; margin: 0 -10px;}
</style>
<div class='panel-move-handler'></div>
<div class="panel-body conatiner-fluid">
  <div class="table-row">
    <div class="col col-left hide-in-sm">
      <h4><?php printf($lang->block->welcomeList[$welcomeType], $app->user->realname)?></h4>
      <ul class="timeline timeline-sm">
        <?php
        $i = 1;
        foreach($lang->tutorial->tasks as $taskKey => $task)
        {
            if($i > 3) break;
            $class = strpos(",$tutorialed,", ",$taskKey,") !== false ? "class='active'" : '';
            echo "<li $class>" . html::a($this->createLink('tutorial', 'index', "referer=&task=$taskKey"), $i . '. ' . $task['title']) . "</li>";
            $i++;
        }
        ?>
      </ul>
    </div>
    <div class="col col-right">
    <h4><small class="text-muted"><?php echo date(DT_DATE3)?></small> <?php echo $lang->block->leftToday?></h4>
      <div class="row tiles">
        <?php if($this->config->global->flow == 'full' or $this->config->global->flow == 'onlyTask'):?>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myTask?></div>
          <div class="tile-amount"><?php echo empty($tasks) ? 0 : html::a($this->createLink('my', 'task', 'type=assignedTo'), (int)$tasks);?></div>
          <?php if(!empty($delay['task'])):?>
          <div class="tile-info">
          <span class="label label-danger label-outline"><?php echo $lang->block->delayed . ' ' . $delay['task']?></span>
          </div>
          <?php endif;?>
        </div>
        <?php endif;?>
        <?php if($this->config->global->flow == 'full' or $this->config->global->flow == 'onlyTest'):?>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myBug?></div>
          <div class="tile-amount"><?php echo empty($bugs) ? 0 : html::a($this->createLink('my', 'bug', 'type=assignedTo'), (int)$bugs);?></div>
          <?php if(!empty($delay['bug'])):?>
          <div class="tile-info">
          <span class="label label-danger label-outline"><?php echo $lang->block->delayed . ' ' . $delay['bug']?></span>
          </div>
          <?php endif;?>
        </div>
        <?php endif;?>
        <?php if($this->config->global->flow == 'full' or $this->config->global->flow == 'onlyStory'):?>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myStory?></div>
          <div class="tile-amount"><?php echo empty($stories) ? 0 : html::a($this->createLink('my', 'story', 'type=assignedTo'), (int)$stories);?></div>
        </div>
        <?php endif;?>
        <?php if($this->config->global->flow == 'full' or $this->config->global->flow == 'onlyTask'):?>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myProject?></div>
          <div class="tile-amount"><?php echo empty($projects) ? 0 : html::a($this->createLink('project', 'all', 'type=undone'), (int)$projects);?></div>
          <?php if(!empty($delay['project'])):?>
          <div class="tile-info">
          <span class="label label-danger label-outline"><?php echo $lang->block->delayed . ' ' . $delay['project']?></span>
          </div>
          <?php endif;?>
        </div>
        <?php endif;?>
        <?php if($this->config->global->flow == 'full' or $this->config->global->flow == 'onlyStory' or $this->config->global->flow == 'onlyTest'):?>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myProduct?></div>
          <div class="tile-amount"><?php echo empty($products) ? 0 : html::a($this->createLink('product', 'all', 'product=0&line=0&status=noclosed'), (int)$products);?></div>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>
