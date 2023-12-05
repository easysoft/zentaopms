<?php
/**
 * The import execution view of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie<xieqiyu@cnezsoft.com>
 * @package     kanban
 * @version     $Id: importexecution.html.php 5090 2022-01-19 14:19:24Z xieqiyu@cnezsoft.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('kanbanID', $kanbanID);?>
<?php js::set('regionID', $regionID);?>
<?php js::set('groupID', $groupID);?>
<?php js::set('columnID', $columnID);?>
<?php js::set('methodName', $this->app->rawMethod);?>
<?php if(count($executions2Imported) <= 3):?>
<style>#importExecutionForm, .table-empty-tip {margin-bottom: 120px}</style>
<?php endif;?>
<div id='mainContent' class='main-content importModal'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanban->importAB . $lang->kanban->importExecution;?></h2>
    </div>
  </div>
  <div class='input-group space'>
    <span class='input-group-addon'><?php echo $lang->kanban->selectedProject;?></span>
    <?php echo html::select('project', $projects, $selectedProjectID, "onchange='reloadObjectList(this.value)' class='form-control chosen' data-drop_direction='down'");?>
    <span class='input-group-addon'><?php echo $lang->kanban->selectedLane;?></span>
    <?php echo html::select('lane', $lanePairs, '', "onchange='setTargetLane(this.value)' class='form-control chosen' data-drop_direction='down'");?>
  </div>
  <?php if($executions2Imported):?>
  <form class='main-table' method='post' data-ride='table' target='hiddenwin' id='importExecutionForm'>
    <table class='table table-fixed' id='executionList'>
      <thead>
        <tr>
          <th class="c-id">
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-name'><?php echo $lang->execution->execName;?></th>
          <th class='c-status'><?php echo $lang->execution->execStatus;?></th>
          <th class='c-user'><?php echo $lang->execution->owner;?></th>
          <th class='c-date'><?php echo $lang->execution->end;?></th>
          <th class='c-hour'><?php echo $lang->execution->totalEstimate;?></th>
          <th class='c-hour'><?php echo $lang->execution->totalConsumed;?></th>
          <th class='c-hour'><?php echo $lang->execution->totalLeft;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($executions2Imported as $execution):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='executions[]' value='<?php echo $execution->id;?>'/>
              <label></label>
            </div>
            <?php printf('%03d', $execution->id);?>
          </td>
          <?php if(common::hasPriv('execution', 'view')):?>
          <?php
          $isClickable = true;
          if($execution->type == 'stage') $isClickable = $this->programplan->checkLeafStage($execution->id) ? true : false;
          ?>
          <td title='<?php echo $execution->title;?>'>
            <?php if($isClickable):?>
            <a href='javascript:void(0);' onclick="locateView('execution', <?php echo $execution->id;?>)"><?php echo $execution->name;?></a>
            <?php else:?>
            <?php echo $execution->name;?>
            <?php endif;?>
          </td>
          <?php else:?>
          <td title='<?php echo $execution->title;?>'><?php echo $execution->name;?></td>
          <?php endif;?>
          <td title='<?php echo zget($lang->execution->statusList, $execution->status);?>'><?php echo zget($lang->execution->statusList, $execution->status);?></td>
          <td title='<?php echo zget($users, $execution->PM);?>'><?php echo zget($users, $execution->PM);?></td>
          <td title='<?php echo $execution->end;?>'><?php echo $execution->end;?></td>
          <td title='<?php echo $execution->hours->totalEstimate;?>'><?php echo $execution->hours->totalEstimate;?></td>
          <td title='<?php echo $execution->hours->totalConsumed;?>'><?php echo $execution->hours->totalConsumed;?></td>
          <td title='<?php echo $execution->hours->totalLeft;?>'><?php echo $execution->hours->totalLeft;?></td>
        </tr>
        <?php endforeach;?>
        <tr><?php echo html::hidden('targetLane', key($lanePairs));?></tr>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar show-always"><?php echo html::submitButton($lang->kanban->importAB, '', 'btn btn-default');?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php else:?>
  <div class='table-empty-tip'><?php echo $lang->noData;?></div>
  <?php endif;?>
</div>
<?php if($config->systemMode == 'light'):?>
<style>.input-group {width: 45% !important}</style>
<?php else:?>
<style>#project_chosen {width: 45% !important}</style>
<?php endif;?>
<?php include '../../common/view/footer.lite.html.php';?>
