<?php
/**
 * The import view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<script language='Javascript'>
$(function(){
     $(".preview").colorbox({width:1000, height:700, iframe:true, transition:'elastic', speed:350, scrolling:true});
})
var browseType = '<?php echo $browseType;?>';
</script>
<div id='featurebar'>
<?php /* echo "<span id='bysearchTab'><a href='#'>{$lang->project->byQuery}</a></span> ";*/?>
</div>
<div id='querybox' class='<?php /*if($browseType !='bysearch')*/ echo 'hidden';?>'><?php echo $searchForm;?></div>
<div>
<form method='post' enctype='multipart/form-data' target='hiddenwin'>
  <table class='table-1 colored tablesorter'>
    <thead>
    <tr class='colhead'>
      <th class='w-id'>       <?php echo $lang->import;?></th>
      <th class='w-id'>       <?php echo $lang->idAB;?></th>
      <th class='w-severity'> <?php echo $lang->bug->severityAB;?></th>
      <th class='w-pri'>      <?php echo $lang->priAB;?></th>
      <th><?php echo $lang->bug->title;?></th>
      <th class='w-80px'><?php echo $lang->bug->statusAB;?></th>
      <th class='w-80px'><?php echo $lang->task->pri;?></th>
      <th class='w-150px'><?php echo $lang->task->assignedTo;?></th>
      <th class='w-80px nobr {sorter:false}'><?php echo $lang->task->estimate;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($bugs as $bug):?>
    <tr class='a-center'>
      <td><?php echo html::checkbox("import[$bug->id]", '');?> </td>
      <td><?php echo sprintf('%03d', $bug->id) . html::hidden("id[$bug->id]", $bug->id);?></td>
      <td><?php echo $lang->bug->severityList[$bug->severity]?></td>
      <td><?php echo $lang->bug->priList[$bug->pri]?></td>
      <td class='a-left nobr'><?php common::printLink('bug', 'view', "bugID=$bug->id", $bug->title, '', "class='preview'");?></td>
      <td><?php echo $lang->bug->statusList[$bug->status];?></td>
      <td><?php echo html::select("pri[$bug->id]", $lang->task->priList, 3);?></td>
      <td><?php echo html::select("assignedTo[$bug->id]", $users, '');?></td>
      <td><?php echo html::input("estimate[$bug->id]", '', 'size=4');?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
    <tr><td colspan='9'><div class='f-right'><?php $pager->show();?></div></td></tr>
  </table>
    <div class='a-center'><?php echo html::submitButton($lang->import) . html::resetButton();?></div>
</form>
</div>
<?php include '../../common/view/footer.html.php';?>
