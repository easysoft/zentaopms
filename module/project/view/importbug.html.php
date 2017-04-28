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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['task']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['import']);?></small> <?php echo $lang->project->importBug;?></strong>
  </div>
  <div id='querybox' class='show'></div>
</div>
<form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin' id='importBugForm'>
  <table class='table tablesorter table-fixed table-selectable'>
    <thead>
      <tr class='colhead'>
        <th class='w-id'>       <?php echo $lang->idAB;?></th>
        <th class='w-severity'> <?php echo $lang->bug->severityAB;?></th>
        <th class='w-pri'>      <?php echo $lang->priAB;?></th>
        <th><?php echo $lang->bug->title;?></th>
        <th class='w-80px'><?php echo $lang->bug->statusAB;?></th>
        <th class='w-80px'><?php echo $lang->task->pri;?></th>
        <th class='w-150px'><?php echo $lang->task->assignedTo;?></th>
        <th class='w-80px nobr {sorter:false}'><?php echo $lang->task->estimate;?></th>
        <th class='w-120px {sorter:false}'><?php echo $lang->task->deadline;?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($bugs as $bug):?>
    <tr class='text-center'>
      <td class='cell-id'>
        <?php echo html::checkbox("import[$bug->id]", '');?> 
        <?php echo sprintf('%03d', $bug->id) . html::hidden("id[$bug->id]", $bug->id);?>
      </td>
      <td><span class='<?php echo 'severity' . zget($lang->bug->severityList, $bug->severity, $bug->severity)?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity)?></span></td>
      <td><span class='<?php echo 'pri' . zget($lang->bug->priList, $bug->pri, $bug->pri)?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?></span></td>
      <td class='text-left nobr'><?php common::printLink('bug', 'view', "bugID=$bug->id", $bug->title, '', "class='preview'", true, true);?></td>
      <td class='bug-<?php echo $bug->status?>'><?php echo $lang->bug->statusList[$bug->status];?></td>
      <td class='td-has-control'><?php echo html::select("pri[$bug->id]", $lang->task->priList, 3, "class='input-sm form-control'");?></td>
      <td class='td-has-control text-left' style='overflow:visible'><?php echo html::select("assignedTo[$bug->id]", $users, zget($users, $bug->assignedTo, '', $bug->assignedTo), "class='input-sm form-control chosen'");?></td>
      <td class='td-has-control'><?php echo html::input("estimate[$bug->id]", '', 'size=4 class="input-sm form-control" autocomplete="off"');?></td>
      <?php $deadline = ($bug->deadline > helper::today() and $bug->deadline > $project->begin) ? $bug->deadline : '0000-00-00';?>
      <td class='td-has-control'><?php echo html::input("deadline[$bug->id]", $deadline, 'size=4 class="input-sm form-control form-date" autocomplete="off"');?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan='9'>
          <div class='table-actions clearfix'><?php echo html::selectButton() . html::submitButton($lang->import) . html::backButton();?>
          </div>
          <?php $pager->show();?>
        </td>
      </tr>
    </tfoot>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
