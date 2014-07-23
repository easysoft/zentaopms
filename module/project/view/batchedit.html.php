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
<?php include '../../common/view/datepicker.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['project']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchEdit']);?></small> <?php echo $lang->project->common . $lang->colon . $lang->project->batchEdit;?></strong>
  </div>
</div>
<form class='form-condensed' method='post' action='<?php echo inLink('batchEdit');?>'>
<table class='table table-form table-fixed'>
  <thead>
    <tr class='text-center'>
      <th class='w-50px'><?php echo $lang->idAB;?></th>
      <th>        <?php echo $lang->project->name;?> <span class='required'></span></th>
      <th class='w-150px'><?php echo $lang->project->code;?> <span class='required'></span></th>
      <th class='w-150px'><?php echo $lang->project->PM;?></th>
      <th class='w-100px'><?php echo $lang->project->status;?></th>
      <th class='w-110px'><?php echo $lang->project->begin;?> <span class='required'></span></th>
      <th class='w-110px'><?php echo $lang->project->end;?> <span class='required'></span></th>
      <th class='w-150px'>    <?php echo $lang->project->days;?></th>
    </tr>
  </thead>
  <?php foreach($projectIDList as $projectID):?>
  <tr class='text-center'>
    <td><?php echo sprintf('%03d', $projectID) . html::hidden("projectIDList[$projectID]", $projectID);?></td>
    <td><?php echo html::input("names[$projectID]",     $projects[$projectID]->name, "class='form-control'");?></td>
    <td><?php echo html::input("codes[$projectID]",     $projects[$projectID]->code, "class='form-control'");?></td>
    <td class='text-left' style='overflow:visible'><?php echo html::select("PMs[$projectID]",      $pmUsers, $projects[$projectID]->PM, "class='form-control chosen'");?></td>
    <td><?php echo html::select("statuses[$projectID]", $lang->project->statusList, $projects[$projectID]->status, 'class=form-control');?></td>
    <td><?php echo html::input("begins[$projectID]",    $projects[$projectID]->begin, "class='form-control form-date' onchange='computeWorkDays(this.id)'");?></td>
    <td><?php echo html::input("ends[$projectID]",      $projects[$projectID]->end, "class='form-control form-date' onchange='computeWorkDays(this.id)'");?></td>
    <td>
      <div class='input-group'>
        <?php echo html::input("dayses[$projectID]",    $projects[$projectID]->days, "class='form-control'");?>
        <span class='input-group-addon'><?php echo $lang->project->day;?></span>
      </div>
    </td>
  </tr>
  <?php endforeach;?>
  <tr><td colspan='8' class='text-center'><?php echo html::submitButton();?></td></tr>
</table>
</form>
<?php include '../../common/view/footer.html.php';?>
