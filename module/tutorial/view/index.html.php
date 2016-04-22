<?php
/**
 * The index view file of tutorial module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     tutorial
 * @version     $Id: browse.html.php 4728 2013-05-03 06:14:34Z sunhao@cnezsoft.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<script>
$(function()
{
    var formatSetting = function()
    {
        
    };

    var tasks = $.parseJSON('<?php echo json_encode(array_values($lang->tutorial->tasks), JSON_HEX_QUOT | JSON_HEX_APOS) ?>');
    console.log(tasks);
    var current = '<?php echo $current ?>';
    var setting = '<?php echo $setting ?>';

    var $tasks = $('#tasks');

    var updateTasks = function()
    {
        $tasks.empty();
        $.each(tasks, function(idx, task)
        {
            var $li = $('<li/>');
            var $a = $('<a href="###" />');

        });
    };


});
</script>
<div id='pageContainer'>
  <div id='iframeWrapper'>
    <iframe id='iframePage' name='iframePage' src='<?php echo helper::createLink('my', 'index') ?>' frameborder='no' allowtransparency='true' scrolling='auto' hidefocus='' style='width: 100%; height: 100%; left: 0; top: 0'></iframe>
  </div>
  <div id='sidebar'>
    <header>
      <div class='start-icon'><i class='icon icon-certificate icon-back'></i><i class='icon icon-flag icon-front'></i></div>
      <h2><?php echo $lang->tutorial->common ?></h2>
      <div class='actions'>
        <a data-toggle='tooltip' data-placement='left' title='<?php echo $lang->tutorial->exit ?>' href='<?php echo helper::createLink('my', 'index') ?>' class='btn btn-sm'><i class="icon icon-signout"></i></a>
      </div>
    </header>
    <section id='finish'>
      <div class='start-icon'><i class='icon icon-certificate icon-spin icon-back'></i><i class='icon icon-check icon-front'></i></div>
      <h3><?php echo $lang->tutorial->congratulation ?></h3>
      <button type='button' class='btn btn-success'><i class="icon icon-repeat"></i>  <?php echo $lang->tutorial->restart ?></button> &nbsp; <a href='<?php echo helper::createLink('my', 'index') ?>' class='btn btn-success'><i class="icon icon-signout"></i> <?php echo $lang->tutorial->exit ?></a>
    </section>
    <section id='current'>
      <h4><?php echo $lang->tutorial->currentTask ?></h4>
      <div class='panel finish' id='task'>
        <div class='panel-heading'>
          <strong class='task-name task-name-current'>1. 创建账号</strong>
          <i class="icon icon-check pull-right"></i>
        </div>
        <div class='panel-body'></div>
      </div>
      <div class='clearfix actions'>
        <button type='button' class='btn btn-sm btn-prev-task'><i class="icon icon-angle-left"></i> <?php echo $lang->tutorial->previous ?></button>
        <button type='button' class='btn btn-primary btn-sm pull-right btn-next-task'><?php echo $lang->tutorial->nextTask ?> <i class="icon icon-angle-right"></i></button>
      </div>
    </section>
    <section id='all'>
      <h4><?php echo $lang->tutorial->allTasks ?> (<span class='task-num-current'>2</span>/<span class='tasks-count'>8</span>)</h4>
      <div class='progress' id='tasksProgress'>
        <div class='progress-bar' style='width: 40%'>
        </div>
      </div>
      <ul id='tasks' class='nav nav-primary nav-stacked'>
        <li class='finish'><a href="#"><span>1</span>. <span class='task-name'></span><i class="icon icon-check pull-right"></i></a></li>
      </ul>
    </section>
  </div>
</div>

<?php include '../../common/view/footer.lite.html.php';?>
