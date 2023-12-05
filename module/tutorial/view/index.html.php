<?php
/**
 * The index view file of tutorial module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     tutorial
 * @version     $Id: browse.html.php 4728 2013-05-03 06:14:34Z sunhao@cnezsoft.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::import($jsRoot . 'misc/base64.js');?>
<div id='pageContainer'>
  <div id='iframeWrapper'>
    <iframe id='iframePage' name='iframePage' src='<?php echo helper::createLink('index', 'index', '', 'tutorial'); ?>' frameborder='no' allowtransparency='true' scrolling='auto' hidefocus='' style='width: 100%; height: 100%; left: 0; top: 0'></iframe>
    <div id='taskModalBack'></div>
    <div id='taskModal'>
      <button class='close'><i class="icon icon-close"></i></button>
      <div class='finish-all'>
        <div class='start-icon'><i class='icon icon-check-circle icon-front'></i></div>
        <h3><?php echo $lang->tutorial->congratulation;?></h3>
        <button type='button' class='btn btn-outline btn-reset-tasks'><i class='icon icon-restart'></i>  <?php echo $lang->tutorial->restart;?></button> &nbsp; <a href='<?php echo helper::createLink('tutorial', 'quit');?>' class='btn btn-outline'><i class='icon icon-signout'></i> <?php echo $lang->tutorial->exit;?></a>
      </div>
      <div class='finish'>
        <div class='start-icon'><i class='icon icon-check-circle icon-front'></i></div>
        <h3><?php echo $lang->tutorial->congratulateTask;?></h3>
        <button type='button' class='btn btn-outline btn-next-task btn-task'><?php echo $lang->tutorial->nextTask;?> <i class='icon icon-angle-right'></i></button>
      </div>
    </div>
  </div>
  <div id='sidebar'>
    <header class='bg-primary'>
      <div class='start-icon'><i class='icon icon-certificate icon-back'></i><i class='icon icon-flag icon-front text-secondary'></i></div>
      <h2><?php echo $lang->tutorial->common;?></h2>
      <div class='actions'>
        <a href='<?php echo helper::createLink('tutorial', 'quit');?>' class='btn btn-danger btn-sm'><i class="icon icon-signout"></i> <?php echo $lang->tutorial->exit;?></a>
      </div>
    </header>
    <section id='current'>
      <h4><?php echo $lang->tutorial->currentTask;?></h4>
      <div class='panel' id='task'>
        <div class='panel-heading bg-secondary'>
          <strong><span class='task-id-current'>1</span>. <span class='task-name task-name-current'></span></strong>
          <i class="icon icon-check pull-right"></i>
        </div>
        <div class='panel-body'>
          <div class='task-desc'></div>
          <a href='javascript:;' id='openTaskPage' class='btn-open-target-page hl-primary'>
            <div class='normal'><i class="icon icon-magic"></i> <?php echo $lang->tutorial->openTargetPage;?></div>
            <div class='opened'><i class="icon icon-flag"></i> <?php echo $lang->tutorial->atTargetPage;?></div>
            <div class='reload'><i class="icon icon-restart"></i> <?php echo $lang->tutorial->reloadTargetPage;?></div>
          </a>
          <div class='alert-warning' style='padding:5px 10px;margin-bottom:0px'><?php echo $lang->tutorial->dataNotSave?></div>
        </div>
      </div>
      <div class='clearfix actions'>
        <button type='button' class='btn btn-sm btn-circle btn-prev-task btn-task btn-icon-left'><span class="label label-badge label-icon"><i class="icon icon-arrow-left"></i></span><?php echo $lang->tutorial->previous;?></button>
        <button type='button' class='btn btn-sm btn-circle btn-primary pull-right btn-task btn-next-task btn-icon-right'><?php echo $lang->tutorial->nextTask;?> <span class="label label-badge label-icon"><i class="icon icon-arrow-right"></i></span></button>
      </div>
    </section>
    <section id='all'>
      <h4><?php echo $lang->tutorial->allTasks;?> (<span class='task-num-finish'></span>/<span class='tasks-count'></span>)</h4>
      <div class='progress' id='tasksProgress'>
        <div class='progress-text'></div>
        <div class='progress-bar' style='width: 0%'>
        </div>
      </div>
      <ul id='tasks' class='nav nav-primary nav-stacked'>
        <?php
        $idx = 0;
        $tasks = array();
        ?>
        <?php foreach ($lang->tutorial->tasks as $name => $task):?>
        <?php
        if(isset($task['mode']) && $task['mode'] != $mode) continue;
        $nav = $task['nav'];
        $task['name'] = $name;
        $task['id']   = ++ $idx;
        $task['url']  = helper::createLink($nav['module'], $nav['method'], isset($nav['vars']) ? $nav['vars'] : '', 'tutorial');
        $tasks[$name] = $task;
        ?>
        <li data-name='<?php echo $name;?>'><a class='btn-task' href='javascript:;' data-name='<?php echo $name;?>'><span><?php echo $idx;?></span>. <span class='task-name'><?php echo $task['title'];?></span><i class='icon icon-check pull-right'></i></a></li>
        <?php endforeach;?>
      </ul>
    </section>
  </div>
</div>
<?php js::set('tutorialReferer', $referer);?>
<?php js::set('ajaxSetTasksUrl', inlink('ajaxSetTasks'));?>
<?php js::set('serverErrorTip', $lang->tutorial->serverErrorTip);?>
<?php js::set('defaultTask', $current);?>
<?php js::set('settingString', $setting);?>
<?php js::set('langTargetPageTip', $lang->tutorial->targetPageTip);?>
<?php js::set('langTarget', $lang->tutorial->target);?>
<?php js::set('langTargetAppTip', $lang->tutorial->targetAppTip);?>
<?php js::set('langRequiredTip', $lang->tutorial->requiredTip);?>
<script>
var tutorialTasks = $.parseJSON('<?php echo helper::jsonEncode4Parse($tasks, JSON_HEX_QUOT | JSON_HEX_APOS);?>');
</script>
<?php include '../../common/view/footer.lite.html.php';?>
