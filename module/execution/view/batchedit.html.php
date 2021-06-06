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
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->execution->batchEdit;?></h2>
    <div class='btn-toolbar pull-right'>
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=execution&section=custom&key=batchEditFields')?>
      <?php include '../../common/view/customfield.html.php';?>
    </div>
  </div>
  <?php
  $visibleFields  = array();
  $requiredFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if($field)$visibleFields[$field] = '';
  }
  foreach(explode(',', $config->execution->edit->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->execution->customBatchEditFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  $minWidth = (count($visibleFields) > 5) ? 'w-150px' : '';

  $name = $from == 'execution' ? 'execName' : 'name';
  $code = $from == 'execution' ? 'execCode' : 'code';
  $PM   = $from == 'execution' ? 'execPM'   : 'PM';
  $type = $from == 'execution' ? 'execType' : 'type';
  $desc = $from == 'execution' ? 'execDesc' : 'desc';
  $status = $from == 'execution' ? 'execStatus' : 'status';

  ?>
  <form class='main-form' method='post' target='hiddenwin' action='<?php echo inLink('batchEdit');?>'>
    <div class="table-responsive">
      <table class='table table-form'>
        <thead>
          <tr>
            <th class='w-50px'><?php echo $lang->idAB;?></th>
            <th class='required <?php echo $minWidth?>' style="width:100%"><?php echo $lang->execution->$name;?></th>
            <th class='w-150px required'><?php echo $lang->execution->$code;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'PM',     ' hidden') . zget($requiredFields, 'PM',     '', ' required');?>'><?php echo $lang->execution->$PM;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'PO',     ' hidden') . zget($requiredFields, 'PO',     '', ' required');?>'><?php echo $lang->execution->PO;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'QD',     ' hidden') . zget($requiredFields, 'QD',     '', ' required');?>'><?php echo $lang->execution->QD;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'RD',     ' hidden') . zget($requiredFields, 'RD',     '', ' required');?>'><?php echo $lang->execution->RD;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'type',   ' hidden') . zget($requiredFields, 'type',   '', ' required');?>'><?php echo $lang->execution->$type;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'status', ' hidden') . zget($requiredFields, 'status', '', ' required');?>'><?php echo $lang->execution->$status;?></th>
            <th class='w-110px required'><?php echo $lang->execution->begin;?></th>
            <th class='w-110px required'><?php echo $lang->execution->end;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'desc', ' hidden') . zget($requiredFields, 'desc', '', ' required');?>'><?php echo $lang->execution->$desc;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'teamname', ' hidden') . zget($requiredFields, 'teamname', '', ' required');?>'><?php echo $lang->execution->teamname;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'days',     ' hidden') . zget($requiredFields, 'days',     '', ' required');?>'><?php echo $lang->execution->days;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($executionIDList as $executionID):?>
          <?php
          if(!empty($this->config->moreLinks["PM"])) $this->config->moreLinks["PMs[$executionID]"] = $this->config->moreLinks["PM"];
          if(!empty($this->config->moreLinks["PO"])) $this->config->moreLinks["POs[$executionID]"] = $this->config->moreLinks["PO"];
          if(!empty($this->config->moreLinks["QD"])) $this->config->moreLinks["QDs[$executionID]"] = $this->config->moreLinks["QD"];
          if(!empty($this->config->moreLinks["RD"])) $this->config->moreLinks["RDs[$executionID]"] = $this->config->moreLinks["RD"];
          ?>
          <tr>
            <td><?php echo sprintf('%03d', $executionID) . html::hidden("executionIDList[$executionID]", $executionID);?></td>
            <td title='<?php echo $executions[$executionID]->name?>'><?php echo html::input("names[$executionID]", $executions[$executionID]->name, "class='form-control'");?></td>
            <td><?php echo html::input("codes[$executionID]",     $executions[$executionID]->code, "class='form-control'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'PM',  ' hidden')?>' style='overflow:visible'><?php echo html::select("PMs[$executionID]", $pmUsers, $executions[$executionID]->PM, "class='form-control chosen'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'PO', ' hidden')?>' style='overflow:visible'><?php echo html::select("POs[$executionID]", $poUsers, $executions[$executionID]->PO, "class='form-control chosen'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'QD', ' hidden')?>' style='overflow:visible'><?php echo html::select("QDs[$executionID]", $qdUsers, $executions[$executionID]->QD, "class='form-control chosen'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'RD', ' hidden')?>' style='overflow:visible'><?php echo html::select("RDs[$executionID]", $rdUsers, $executions[$executionID]->RD, "class='form-control chosen'");?></td>
            <td class='<?php echo zget($visibleFields, 'type',   'hidden')?>'>
              <?php
              if($executions[$executionID]->type == 'stage')
              {
                  echo html::select("attributes[$executionID]",    $lang->stage->typeList,   $executions[$executionID]->attribute,   'class=form-control');
              }
              else
              {
                  echo html::select("lifetimes[$executionID]",    $lang->execution->lifeTimeList,   $executions[$executionID]->lifetime,   'class=form-control');
              }
              ?>
            </td>
            <td class='<?php echo zget($visibleFields, 'status', 'hidden')?>'><?php echo html::select("statuses[$executionID]", $lang->execution->statusList, $executions[$executionID]->status, 'class=form-control');?></td>
            <td><?php echo html::input("begins[$executionID]", $executions[$executionID]->begin, "class='form-control form-date' onchange='computeWorkDays(this.id)'");?></td>
            <td><?php echo html::input("ends[$executionID]",   $executions[$executionID]->end,   "class='form-control form-date' onchange='computeWorkDays(this.id)'");?></td>
            <td class='<?php echo zget($visibleFields, 'desc', 'hidden')?>'>    <?php echo html::textarea("descs[$executionID]",  $executions[$executionID]->desc,  "rows='1' class='form-control autosize'");?></td>
            <td class='<?php echo zget($visibleFields, 'teamname', 'hidden')?>'><?php echo html::input("teams[$executionID]",  $executions[$executionID]->team,  "class='form-control'");?></td>
            <td class='<?php echo zget($visibleFields, 'days',     'hidden')?>'>
              <div class='input-group'>
                <?php echo html::input("dayses[$executionID]",    $executions[$executionID]->days, "class='form-control'");?>
                <span class='input-group-addon'><?php echo $lang->execution->day;?></span>
              </div>
            </td>
          </tr>
          <?php
          if(isset($this->config->moreLinks["PMs[$executionID]"])) unset($this->config->moreLinks["PMs[$executionID]"]);
          if(isset($this->config->moreLinks["POs[$executionID]"])) unset($this->config->moreLinks["POs[$executionID]"]);
          if(isset($this->config->moreLinks["QDs[$executionID]"])) unset($this->config->moreLinks["QDs[$executionID]"]);
          if(isset($this->config->moreLinks["RDs[$executionID]"])) unset($this->config->moreLinks["RDs[$executionID]"]);
          ?>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='<?php echo count($visibleFields) + 6?>' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php js::set('weekend', $config->execution->weekend);?>
<?php include '../../common/view/footer.html.php';?>
