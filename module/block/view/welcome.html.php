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
<div class="panel-body conatiner-fluid">
  <div class="row">
    <div class="col col-left">
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
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myTask?></div>
          <div class="tile-amount"><?php echo (int)$tasks;?></div>
          <?php if(!empty($delay['task'])):?>
          <div class="tile-info">
          <span class="label label-danger label-outline"><?php echo $lang->block->delayed . ' ' . $delay['task']?></span>
          </div>
          <?php endif;?>
        </div>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myBug?></div>
          <div class="tile-amount"><?php echo (int)$bugs;?></div>
          <?php if(!empty($delay['bug'])):?>
          <div class="tile-info">
          <span class="label label-danger label-outline"><?php echo $lang->block->delayed . ' ' . $delay['bug']?></span>
          </div>
          <?php endif;?>
        </div>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myStory?></div>
          <div class="tile-amount"><?php echo (int)$stories;?></div>
        </div>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myProject?></div>
          <div class="tile-amount"><?php echo (int)$projects;?></div>
          <?php if(!empty($delay['project'])):?>
          <div class="tile-info">
          <span class="label label-danger label-outline"><?php echo $lang->block->delayed . ' ' . $delay['project']?></span>
          </div>
          <?php endif;?>
        </div>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->myProduct?></div>
          <div class="tile-amount"><?php echo (int)$products;?></div>
        </div>
      </div>
    </div>
  </div>
</div>
