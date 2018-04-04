<?php
/**
 * The action->dynamic view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: action->dynamic.html.php 1477 2011-03-01 15:25:50Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<main id="main">
  <div class="container">
    <div id="mainMenu" class="clearfix">
      <div class="btn-toolbar pull-left">
        <?php
        echo html::a(inlink('dynamic', "type=all"),       "<span class='text'>{$lang->action->dynamic->all}</span> <span class='label label-light label-badge'>{$allCount}</span>", '', "class='btn btn-link " . ($type == 'all' ? 'btn-active-text' : '') . "'");
        echo html::a(inlink('dynamic', "type=today"),     "<span class='text'>{$lang->action->dynamic->today}</span>",     '', "class='btn btn-link " . ($type == 'today'     ? 'btn-active-text' : '') . "'");
        echo html::a(inlink('dynamic', "type=yesterday"), "<span class='text'>{$lang->action->dynamic->yesterday}</span>", '', "class='btn btn-link " . ($type == 'yesterday' ? 'btn-active-text' : '') . "'");
        echo html::a(inlink('dynamic', "type=thisweek"),  "<span class='text'>{$lang->action->dynamic->thisWeek}</span>",  '', "class='btn btn-link " . ($type == 'thisweek'  ? 'btn-active-text' : '') . "'");
        echo html::a(inlink('dynamic', "type=lastweek"),  "<span class='text'>{$lang->action->dynamic->lastWeek}</span>",  '', "class='btn btn-link " . ($type == 'lastweek'  ? 'btn-active-text' : '') . "'");
        echo html::a(inlink('dynamic', "type=thismonth"), "<span class='text'>{$lang->action->dynamic->thisMonth}</span>", '', "class='btn btn-link " . ($type == 'thismonth' ? 'btn-active-text' : '') . "'");
        echo html::a(inlink('dynamic', "type=lastmonth"), "<span class='text'>{$lang->action->dynamic->lastMonth}</span>", '', "class='btn btn-link " . ($type == 'lastmonth' ? 'btn-active-text' : '') . "'");
        ?>
      </div>
    </div>
    <div id="mainContent" class="main-content">
      <div id="dynamics">
        <?php foreach($dateGroups as $date => $actions):?>
        <div class="dynamic">
          <div class="dynamic-date">
            <?php if(date(DT_DATE4) == $date):?>
            <span class="date-label"><?php echo $lang->action->dynamic->today;?></span>
            <?php endif;?>
            <span class="date-text"><?php echo $date;?></span>
            <button type="button" class="btn btn-info btn-icon btn-sm dynamic-btn"><i class="icon icon-caret-up"></i></button>
          </div>
          <ul class="timeline timeline-tag-left">
            <?php foreach($actions as $i => $action):?>
            <li <?php if($i % 3 == 0) echo "class='active'";?>><div><span class="timeline-tag"><?php echo $action->time?></span><span class="timeline-text"><?php echo $app->user->realname . ' ' . $action->actionLabel;?> <span class="text-muted"><?php echo $action->objectLabel;?></span> <span class="label label-id"><?php echo $action->objectID;?></span> <?php echo html::a($action->objectLink, $action->objectName);?></span></div></li>
            <?php endforeach;?>
          </ul>
        </div>
        <?php endforeach;?>
      </div>
      <?php if($pager->recTotal > $pager->recPerPage):?>
      <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
      <?php endif;?>
    </div>
  </div>
</main>
<script>
$(function()
{
    $('#dynamics .dynamic:first').addClass('active');
    $('#dynamics').on('click', '.dynamic-btn', function()
    {
        $(this).closest('.dynamic').toggleClass('collapsed');
    });
})
</script>
<?php include '../../common/view/footer.html.php';?>
