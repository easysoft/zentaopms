<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<form method='post' action='<?php echo inLink('batchEdit');?>'>
<table class='table-1 fixed colored'>
  <tr class='colhead'>
    <th class='w-30px'>     <?php echo $lang->idAB;?></th>
    <th class='red'>        <?php echo $lang->project->name;?></th>
    <th class='w-150px red'><?php echo $lang->project->code;?></th>
    <th class='w-100px'>    <?php echo $lang->project->PM;?></th>
    <th class='w-100px'>    <?php echo $lang->project->status;?></th>
    <th class='w-150px red'><?php echo $lang->project->begin;?></th>
    <th class='w-150px red'><?php echo $lang->project->end;?></th>
    <th class='w-150px'>    <?php echo $lang->project->days;?></th>
  </tr>
  <?php foreach($projectIDList as $projectID):?>
  <tr class='a-center'>
    <td><?php echo sprintf('%03d', $projectID) . html::hidden("projectIDList[$projectID]", $projectID);?></td>
    <td><?php echo html::input("names[$projectID]",     $projects[$projectID]->name, "class='text-1'");?></td>
    <td><?php echo html::input("codes[$projectID]",     $projects[$projectID]->code, "class='text-1'");?></td>
    <td><?php echo html::select("PMs[$projectID]",      $pmUsers, $projects[$projectID]->PM, 'class=text-1');?></td>
    <td><?php echo html::select("statuses[$projectID]", $lang->project->statusList, $projects[$projectID]->status, 'class=text-1');?></td>
    <td><?php echo html::input("begins[$projectID]",    $projects[$projectID]->begin, "class='text-1 date' onchange='computeWorkDays(this.id)'");?></td>
    <td><?php echo html::input("ends[$projectID]",      $projects[$projectID]->end, "class='text-1 date' onchange='computeWorkDays(this.id)'");?></td>
    <td><?php echo html::input("dayses[$projectID]",    $projects[$projectID]->days, "class='w-100px'") . $lang->project->day;?></td>
  </tr>
  <?php endforeach;?>
  <tfoot><tr><td colspan='8' class='a-center'><?php echo html::submitButton();?></td></tr></tfoot>
</table>
</form>
<?php include '../../common/view/footer.html.php';?>
