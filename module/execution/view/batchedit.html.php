<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
  $maxCount = strpos($this->app->getClientLang(), 'zh-') !== false ? 5 : 3;
  $minWidth = (count($visibleFields) > $maxCount) ? 'w-150px' : '';
  $name     = $from == 'execution' ? 'execName' : 'name';
  $code     = $from == 'execution' ? 'execCode' : 'code';
  $PM       = $from == 'execution' ? 'execPM'   : 'PM';
  $type     = $from == 'execution' ? 'execType' : 'type';
  $desc     = $from == 'execution' ? 'execDesc' : 'desc';
  ?>
  <form class='main-form form-ajax' method='post' id='executionForm' action='<?php echo inLink('batchEdit');?>'>
    <div class="table-responsive">
      <table class='table table-form'>
        <thead>
          <tr>
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <?php if(isset($project) and $project->model == 'scrum'):?>
            <th class='c-project required <?php echo $minWidth?>' style="width:100%"><?php echo $lang->execution->projectName;?></th>
            <?php endif;?>
            <?php if($app->tab == 'project' and isset($project) and ($project->model == 'agileplus' or $project->model == 'waterfallplus')):?>
            <th class='c-method'><?php echo $lang->execution->method;?></th>
            <?php endif;?>
            <th class='required <?php echo $minWidth?>' style="width:100%"><?php echo $lang->execution->$name;?></th>
            <?php if(isset($config->setCode) and $config->setCode == 1):?>
            <th class='c-code required'><?php echo $lang->execution->$code;?></th>
            <?php endif;?>
            <th class='c-user<?php echo zget($visibleFields, 'PM',       ' hidden') . zget($requiredFields, 'PM',     '', ' required');?>'><?php echo $lang->execution->$PM;?></th>
            <th class='c-user<?php echo zget($visibleFields, 'PO',       ' hidden') . zget($requiredFields, 'PO',     '', ' required');?>'><?php echo $lang->execution->PO;?></th>
            <th class='c-user<?php echo zget($visibleFields, 'QD',       ' hidden') . zget($requiredFields, 'QD',     '', ' required');?>'><?php echo $lang->execution->QD;?></th>
            <th class='c-user<?php echo zget($visibleFields, 'RD',       ' hidden') . zget($requiredFields, 'RD',     '', ' required');?>'><?php echo $lang->execution->RD;?></th>
            <th class='c-type<?php echo zget($visibleFields, 'type',     ' hidden') . zget($requiredFields, 'type',   '', ' required');?>'>
              <?php echo $lang->execution->$type;?>
              <?php if($this->app->tab == 'execution' or (isset($project) and ($project->model == 'waterfall' or $project->model == 'waterfallplus'))):?>
              <?php $typeTip = $this->app->tab == 'execution' ? $lang->execution->waterfallTip . lcfirst($lang->execution->typeTip) : $lang->execution->typeTip;?>
              <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $typeTip;?>"></icon>
              <?php endif;?>
            </th>
            <th class='c-date required'><?php echo $lang->execution->begin;?></th>
            <th class='c-date required'><?php echo $lang->execution->end;?></th>
            <th class='c-desc <?php echo zget($visibleFields, 'desc', ' hidden') . zget($requiredFields, 'desc', '', ' required');?>'><?php echo $lang->execution->$desc;?></th>
            <th class='c-team-name <?php echo zget($visibleFields, 'teamName', ' hidden') . zget($requiredFields, 'teamName', '', ' required');?>'><?php echo $lang->execution->teamName;?></th>
            <th class='c-days<?php echo zget($visibleFields, 'days', ' hidden') . zget($requiredFields, 'days', '', ' required');?>'><?php echo $lang->execution->days;?></th>
            <?php
            $extendFields = $this->execution->getFlowExtendFields();
            foreach($extendFields as $extendField) echo "<th class='c-extend'>{$extendField->name}</th>";
            ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($executions as $executionID => $execution):?>
          <?php
          if(!empty($this->config->moreLinks["PM"])) $this->config->moreLinks["PMs[$executionID]"] = $this->config->moreLinks["PM"];
          if(!empty($this->config->moreLinks["PO"])) $this->config->moreLinks["POs[$executionID]"] = $this->config->moreLinks["PO"];
          if(!empty($this->config->moreLinks["QD"])) $this->config->moreLinks["QDs[$executionID]"] = $this->config->moreLinks["QD"];
          if(!empty($this->config->moreLinks["RD"])) $this->config->moreLinks["RDs[$executionID]"] = $this->config->moreLinks["RD"];
          ?>
          <tr>
            <td><?php echo sprintf('%03d', $executionID) . html::hidden("executionIDList[$executionID]", $executionID);?></td>
            <?php if(isset($project) and $project->model == 'scrum'):?>
            <td class='text-left' style='overflow:visible'><?php echo html::select("projects[$executionID]", $allProjects, $executions[$executionID]->project, "class='form-control picker-select' data-lastselected='{$executions[$executionID]->project}' onchange='changeProject(this, $executionID, {$executions[$executionID]->project})'");?></td>
            <?php endif;?>
            <?php if($app->tab == 'project' and isset($project) and ($project->model == 'agileplus' or $project->model == 'waterfallplus')):?>
            <td title='<?php echo zget($lang->execution->typeList, $executions[$executionID]->type);?>'><?php echo zget($lang->execution->typeList, $executions[$executionID]->type);?></td>
            <?php endif;?>
            <td title='<?php echo $executions[$executionID]->name?>'><?php echo html::input("names[$executionID]", $executions[$executionID]->name, "class='form-control' id='names{$executionID}'");?></td>
            <?php if(isset($config->setCode) and $config->setCode == 1):?>
            <td><?php echo html::input("codes[$executionID]", $executions[$executionID]->code, "id='codes{$executionID}' class='form-control'");?></td>
            <?php endif;?>
            <td class='text-left<?php echo zget($visibleFields, 'PM', ' hidden')?>' style='overflow:visible'><?php echo html::select("PMs[$executionID]", $pmUsers, $executions[$executionID]->PM, "class='form-control picker-select'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'PO', ' hidden')?>' style='overflow:visible'><?php echo html::select("POs[$executionID]", $poUsers, $executions[$executionID]->PO, "class='form-control picker-select'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'QD', ' hidden')?>' style='overflow:visible'><?php echo html::select("QDs[$executionID]", $qdUsers, $executions[$executionID]->QD, "class='form-control picker-select'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'RD', ' hidden')?>' style='overflow:visible'><?php echo html::select("RDs[$executionID]", $rdUsers, $executions[$executionID]->RD, "class='form-control picker-select'");?></td>
            <td class='<?php echo zget($visibleFields, 'type', 'hidden')?>'>
              <?php if(isset($projects[$execution->project]) and ($projects[$execution->project]->model == 'waterfall' or $projects[$execution->project]->model == 'waterfallplus')):?>
              <?php echo html::select("attributes[$executionID]", $lang->stage->typeList, $executions[$executionID]->attribute, 'class=form-control');?>
              <?php else:?>
              <?php echo html::select("lifetimes[$executionID]", $lang->execution->lifeTimeList, $executions[$executionID]->lifetime, 'class=form-control');?>
              <?php endif;?>
            </td>
            <td><?php echo html::input("begins[$executionID]", $executions[$executionID]->begin, "id='begins{$executionID}' class='form-control form-date' onchange='computeWorkDays(this.id)'");?></td>
            <td><?php echo html::input("ends[$executionID]", $executions[$executionID]->end, "id='ends{$executionID}' class='form-control form-date' onchange='computeWorkDays(this.id)'");?></td>
            <td class='<?php echo zget($visibleFields, 'desc', 'hidden')?>'><?php echo html::textarea("descs[$executionID]", $executions[$executionID]->desc, "rows='1' class='form-control autosize'");?></td>
            <td class='<?php echo zget($visibleFields, 'teamName', 'hidden')?>'><?php echo html::input("teams[$executionID]", $executions[$executionID]->team, "class='form-control'");?></td>
            <td class='<?php echo zget($visibleFields, 'days', 'hidden')?>'>
              <div class='input-group'>
                <?php echo html::input("dayses[$executionID]", $executions[$executionID]->days, "id='dayses{$executionID}' class='form-control'");?>
                <span class='input-group-addon'><?php echo $lang->execution->day;?></span>
              </div>
            </td>
            <?php foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->loadModel('flow')->getFieldControl($extendField, $executions[$executionID], $extendField->field . "[{$executionID}]") . "</td>";?>
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
              <?php echo html::linkButton($lang->goback, $this->session->executionList, 'self', '', 'btn btn-wide');;?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php
js::set('weekend', $config->execution->weekend);
js::set('confirmSync', $lang->execution->confirmSync);
?>
<?php include '../../common/view/footer.html.php';?>
