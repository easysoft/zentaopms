<?php
/**
 * The import view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<script>
$(function(){$(".preview").modalTrigger({width:1000, type:'iframe'});});
var browseType = '<?php echo $browseType;?>';
</script>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a(inlink('importBug', "projectID=$projectID"), "<span class='text'>{$lang->project->importBug}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
</div>
<div id='mainContent'>
  <div class='cell space-sm'>
    <div id='queryBox' class='show'></div>
  </div>
  <form class='main-table' method='post' target='hiddenwin' id='importBugForm' data-ride='table'>
    <table class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php echo $lang->idAB;?>
          </th>
          <th class='w-severity'> <?php echo $lang->bug->severityAB;?></th>
          <th class='w-pri'>      <?php echo $lang->priAB;?></th>
          <th><?php echo $lang->bug->title;?></th>
          <th class='w-80px'><?php echo $lang->bug->statusAB;?></th>
          <th class='w-100px'><?php echo $lang->task->pri;?></th>
          <th class='w-150px'><?php echo $lang->task->assignedTo;?></th>
          <th class='w-80px'><?php echo $lang->task->estimate;?></th>
          <th class='w-120px'><?php echo $lang->task->deadline;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($bugs as $bug):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='import[<?php echo $bug->id;?>]' value='<?php echo $bug->id;?>' /> 
              <label></label>
            </div>
            <?php echo sprintf('%03d', $bug->id) . html::hidden("id[$bug->id]", $bug->id);?>
          </td>
          <td><span class='<?php echo 'severity' . zget($lang->bug->severityList, $bug->severity, $bug->severity)?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity)?></span></td>
          <td><span class='label-pri <?php echo 'label-pri-' . $bug->pri;?>' title='<?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?></span></td>
          <td class='nobr'><?php common::printLink('bug', 'view', "bugID=$bug->id", $bug->title, '', "class='preview'", true, true);?></td>
          <td><span class='status-<?php echo $bug->status?>'><span class='label label-dot'></span> <?php echo $lang->bug->statusList[$bug->status];?></span></td>
          <td style='overflow:visible'><?php echo html::select("pri[$bug->id]", $lang->task->priList, 3, "class='form-control chosen'");?></td>
          <td style='overflow:visible'><?php echo html::select("assignedTo[$bug->id]", $users, zget($users, $bug->assignedTo, '', $bug->assignedTo), "class='form-control chosen'");?></td>
          <td><?php echo html::input("estimate[$bug->id]", '', 'size=4 class="form-control" autocomplete="off"');?></td>
          <?php $deadline = ($bug->deadline > helper::today() and $bug->deadline > $project->begin) ? $bug->deadline : '0000-00-00';?>
          <td><?php echo html::input("deadline[$bug->id]", $deadline, 'size=4 class="form-control form-date" autocomplete="off"');?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($bugs):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar show-always">
        <?php echo html::submitButton('<i class="icon icon-import icon-sm"></i> ' . $lang->import, '', 'btn btn-secondary');?>
      </div>
      <div class='btn-toolbar'>
        <?php echo html::backButton();?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
