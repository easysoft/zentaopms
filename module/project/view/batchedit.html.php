<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
    <div class='actions'>
      <button type="button" class="btn btn-default" data-toggle="customModal"><i class='icon icon-cog'></i> </button>
    </div>
  </div>
</div>
<?php
$visibleFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($field)$visibleFields[$field] = '';
}
$minWidth = (count($visibleFields) > 5) ? 'w-150px' : '';
?>
<form class='form-condensed' method='post' target='hiddenwin' action='<?php echo inLink('batchEdit');?>'>
<table class='table table-form table-fixed'>
  <thead>
    <tr class='text-center'>
      <th class='w-50px'><?php echo $lang->idAB;?></th>
      <th class='<?php echo $minWidth?>'><?php echo $lang->project->name;?> <span class='required'></span></th>
      <th class='w-150px'><?php echo $lang->project->code;?> <span class='required'></span></th>
      <th class='w-150px<?php echo zget($visibleFields, 'PM',     ' hidden')?>'><?php echo $lang->project->PM;?></th>
      <th class='w-150px<?php echo zget($visibleFields, 'PO',     ' hidden')?>'><?php echo $lang->project->PO;?></th>
      <th class='w-150px<?php echo zget($visibleFields, 'QD',     ' hidden')?>'><?php echo $lang->project->QD;?></th>
      <th class='w-150px<?php echo zget($visibleFields, 'RD',     ' hidden')?>'><?php echo $lang->project->RD;?></th>
      <th class='w-100px<?php echo zget($visibleFields, 'type',   ' hidden')?>'><?php echo $lang->project->type;?></th>
      <th class='w-100px<?php echo zget($visibleFields, 'status', ' hidden')?>'><?php echo $lang->project->status;?></th>
      <th class='w-110px'><?php echo $lang->project->begin;?> <span class='required'></span></th>
      <th class='w-110px'><?php echo $lang->project->end;?> <span class='required'></span></th>
      <th class='w-150px<?php echo zget($visibleFields, 'desc', ' hidden')?>'>    <?php echo $lang->project->desc;?></th>
      <th class='w-150px<?php echo zget($visibleFields, 'teamname', ' hidden')?>'><?php echo $lang->project->teamname;?></th>
      <th class='w-150px<?php echo zget($visibleFields, 'days',     ' hidden')?>'><?php echo $lang->project->days;?></th>
      <th class='w-80px'><?php echo $lang->project->order;?></th>
    </tr>
  </thead>
  <?php foreach($projectIDList as $projectID):?>
  <tr class='text-center'>
    <td><?php echo sprintf('%03d', $projectID) . html::hidden("projectIDList[$projectID]", $projectID);?></td>
    <td title='<?php echo $projects[$projectID]->name?>'><?php echo html::input("names[$projectID]", $projects[$projectID]->name, "class='form-control' autocomplete='off'");?></td>
    <td><?php echo html::input("codes[$projectID]",     $projects[$projectID]->code, "class='form-control' autocomplete='off'");?></td>
    <td class='text-left<?php echo zget($visibleFields, 'PM', ' hidden')?>' style='overflow:visible'><?php echo html::select("PMs[$projectID]", $pmUsers, $projects[$projectID]->PM, "class='form-control chosen'");?></td>
    <td class='text-left<?php echo zget($visibleFields, 'PO', ' hidden')?>' style='overflow:visible'><?php echo html::select("POs[$projectID]", $poUsers, $projects[$projectID]->PO, "class='form-control chosen'");?></td>
    <td class='text-left<?php echo zget($visibleFields, 'QD', ' hidden')?>' style='overflow:visible'><?php echo html::select("QDs[$projectID]", $qdUsers, $projects[$projectID]->QD, "class='form-control chosen'");?></td>
    <td class='text-left<?php echo zget($visibleFields, 'RD', ' hidden')?>' style='overflow:visible'><?php echo html::select("RDs[$projectID]", $rdUsers, $projects[$projectID]->RD, "class='form-control chosen'");?></td>
    <td class='<?php echo zget($visibleFields, 'type',   'hidden')?>'><?php echo html::select("types[$projectID]",    $lang->project->typeList,   $projects[$projectID]->type,   'class=form-control');?></td>
    <td class='<?php echo zget($visibleFields, 'status', 'hidden')?>'><?php echo html::select("statuses[$projectID]", $lang->project->statusList, $projects[$projectID]->status, 'class=form-control');?></td>
    <td><?php echo html::input("begins[$projectID]", $projects[$projectID]->begin, "class='form-control form-date' onchange='computeWorkDays(this.id)'");?></td>
    <td><?php echo html::input("ends[$projectID]",   $projects[$projectID]->end,   "class='form-control form-date' onchange='computeWorkDays(this.id)'");?></td>
    <td class='<?php echo zget($visibleFields, 'desc', 'hidden')?>'>    <?php echo html::textarea("descs[$projectID]",  htmlspecialchars($projects[$projectID]->desc),  "rows='1' class='form-control autosize'");?></td>
    <td class='<?php echo zget($visibleFields, 'teamname', 'hidden')?>'><?php echo html::input("teams[$projectID]",  $projects[$projectID]->team,  "class='form-control' autocomplete='off'");?></td>
    <td class='<?php echo zget($visibleFields, 'days',     'hidden')?>'>
      <div class='input-group'>
        <?php echo html::input("dayses[$projectID]",    $projects[$projectID]->days, "class='form-control' autocomplete='off'");?>
        <span class='input-group-addon'><?php echo $lang->project->day;?></span>
      </div>
    </td>
    <td><?php echo html::input("orders[$projectID]", $projects[$projectID]->order, "class='form-control' autocomplete='off'")?></td>
  </tr>
  <?php endforeach;?>
  <tr><td colspan='<?php echo count($visibleFields) + 6?>' class='text-center'><?php echo html::submitButton();?></td></tr>
</table>
</form>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=project&section=custom&key=batchEditFields')?>
<?php include '../../common/view/customfield.html.php';?>
<?php js::set('weekend', $config->project->weekend);?>
<?php include '../../common/view/footer.html.php';?>
