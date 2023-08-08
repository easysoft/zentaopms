<?php
/**
 * The browsebylist view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     project
 * @version     $Id: browsebylist.html.php 4769 2021-07-23 11:16:21Z $
 * @link        https://www.zentao.net
 */
?>
<style>
.export {margin-left: 0px !important;}
.project-type-label.label-outline {width: 50px; min-width: 50px;}
.project-type-label.label {overflow: unset !important; text-overflow: unset !important; white-space: unset !important;}
.project-name {display: flex; align-items: center;}
.project-name > span,
.project-name > span {flex: none;}
.project-name > a {display: inline-block; max-width: calc(100% - 50px);}
.project-name.has-prefix > a {padding-left: 5px;}
.project-name.has-suffix > a {padding-right: 5px;}
</style>
<?php $canBatchEdit = common::hasPriv('project', 'batchEdit');?>
<div id="mainMenu" class="clearfix">
  <?php if(empty($globalDisableProgram)):?>
  <div id="sidebarHeader">
    <div class="title">
      <?php echo $programID ? $program->name : $lang->project->parent;?>
      <?php if($programID) echo html::a(inLink('browse', 'programID=0'), "<i class='icon icon-sm icon-close'></i>", '', 'class="text-muted"');?>
    </div>
  </div>
  <?php endif;?>
  <div class="btn-toolBar pull-left">
    <?php common::sortFeatureMenu();?>
    <?php foreach($lang->project->featureBar['browse'] as $key => $label):?>
    <?php $active = $browseType == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($browseType == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('browse', "programID=$programID&browseType=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php if($canBatchEdit) echo html::checkbox('showEdit', array('1' => $lang->project->edit), $showBatchEdit);?>
    <?php if($browseType != 'bysearch') echo html::checkbox('involved', array('1' => $lang->project->mine), '', $this->cookie->involved ? 'checked=checked' : '');?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->search->common;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <div class="btn-group panel-actions">
      <?php echo html::a('#',"<i class='icon-list'></i> &nbsp;", '', "class='btn btn-icon text-primary' title='{$lang->project->bylist}' id='switchButton' data-type='bylist'");?>
      <?php echo html::a('#',"<i class='icon-cards-view'></i> &nbsp;", '', "class='btn btn-icon' title='{$lang->project->bycard}' id='switchButton' data-type='bycard'");?>
    </div>
    <?php common::printLink('project', 'export', "status=$browseType&orderBy=$orderBy", "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export'")?>
    <?php if(!defined('TUTORIAL')):?>
    <?php if(common::hasPriv('project', 'create')) common::printLink('project', 'createGuide', "programID=$programID", '<i class="icon icon-plus"></i> ' . $lang->project->create, '', 'class="btn btn-primary create-project-btn" data-toggle="modal"');?>
    <?php else:?>
    <?php common::printLink('project', 'create', "mode=scrum&programID=$programID", '<i class="icon icon-plus"></i> ' . $lang->project->create, '', 'class="btn btn-primary create-project-btn"');?>
    <?php endif;?>
  </div>
</div>
<?php
$waitCount      = 0;
$doingCount     = 0;
$suspendedCount = 0;
$closedCount    = 0;
?>
<div id='mainContent' class="main-row fade">
  <?php if(empty($globalDisableProgram)):?>
  <div id="sidebar" class="side-col">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php echo $programTree;?>
      <div class="text-center">
        <?php common::printLink('project', 'programTitle', '', $lang->project->moduleSetting, '', "class='btn btn-info btn-wide iframe'", true, true);?>
      </div>
    </div>
  </div>
  <?php endif;?>
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='project'></div>
    <?php if(empty($projectStats)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->project->empty;?></span>
          <?php if(!defined('TUTORIAL')):?>
            <?php if(common::hasPriv('project', 'create') and $browseType != 'bysearch') common::printLink('project', 'createGuide', "programID=$programID", '<i class="icon icon-plus"></i> ' . $lang->project->create, '', 'class="btn btn-info" data-toggle="modal"');?>
          <?php else:?>
            <?php common::printLink('execution', 'create', '', '<i class="icon icon-plus"></i> ' . $lang->execution->create, '', 'class="btn btn-info"');?>
          <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class='main-table' id='projectForm' method='post'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <?php
      $vars             = "programID=$programID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";
      $datatableId      = $this->moduleName . ucfirst($this->methodName);
      $useDatatable     = (!commonModel::isTutorialMode() and (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable'));
      $setting          = $this->datatable->getSetting('project');
      $fixedFieldsWidth = $this->datatable->setFixedFieldWidth($setting);

      if($useDatatable) include dirname(dirname(dirname(__FILE__))) . '/common/view/datatable.html.php';
      ?>
      <?php if(!$useDatatable) echo '<div class="table-responsive">';?>
      <table class='table has-sort-head <?php if($useDatatable) echo 'datatable';?>' data-fixed-left-width='<?php echo $fixedFieldsWidth['leftWidth']?>' data-fixed-right-width='<?php echo $fixedFieldsWidth['rightWidth']?>'>
        <?php $canBatchEdit = common::hasPriv('project', 'batchEdit');?>
        <thead>
          <tr>
            <?php
            foreach($setting as $value)
            {
              if($value->id == 'status' and strpos(',all,bysearch,undone,', ",$browseType,") === false) $value->show = false;
              if(commonModel::isTutorialMode() && ($value->id == 'PM' || $value->id == 'budget' || $value->id == 'teamCount')) $value->show = false;
              if($value->show) $this->datatable->printHead($value, $orderBy, $vars, $canBatchEdit);
            }
            ?>
          </tr>
        </thead>
        <tbody class="sortable">
          <?php foreach($projectStats as $project):?>
          <?php $project->from = 'project';?>
          <?php if($project->status == 'wait')      $waitCount ++;?>
          <?php if($project->status == 'doing')     $doingCount ++;?>
          <?php if($project->status == 'suspended') $suspendedCount ++;?>
          <?php if($project->status == 'closed')    $closedCount ++;?>
          <tr data-id="<?php echo $project->id;?>" data-status="<?php echo $project->status;?>">
            <?php foreach($setting as $value) $this->project->printCell($value, $project, $users, $programID);?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php if(!$useDatatable) echo '</div>';?>
      <div class='table-footer'>
        <?php if($canBatchEdit):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <?php endif;?>
        <div class="table-actions btn-toolbar">
        <?php
        if($canBatchEdit)
        {
            $actionLink = $this->createLink('project', 'batchEdit');
            $misc       = "data-form-action='$actionLink'";
            echo html::commonButton($lang->edit, $misc);
        }
        ?>
        </div>
        <div class="table-statistic"><?php echo $browseType == 'all' ? sprintf($lang->project->allSummary, count($projectStats), $waitCount, $doingCount, $suspendedCount, $closedCount) : sprintf($lang->project->summary, count($projectStats));?></div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <?php endif;?>
  </div>
</div>
<?php
js::set('useDatatable', isset($useDatatable) ? $useDatatable : false);
js::set('summary', sprintf($lang->project->summary, count($projectStats)));
js::set('allSummary', sprintf($lang->project->allSummary, count($projectStats), $waitCount, $doingCount, $suspendedCount, $closedCount));
js::set('checkedSummary', $lang->project->checkedSummary);
js::set('checkedAllSummary', $lang->project->checkedAllSummary);
?>
<script>
$(function()
{
    $('#projectForm').table(
    {
        replaceId: 'projectIdList',
        statisticCreator: function(table)
        {
            var $table            = table.getTable();
            var $checkedRows      = $table.find('tbody>tr.checked');
            var checkedTotal      = $checkedRows.length;
            var statistics        = summary;
            var checkedStatistics = checkedSummary.replace('%total%', checkedTotal);

            if(browseType == 'all')
            {
                var checkedWait      = $checkedRows.filter("[data-status=wait]").length;
                var checkedDoing     = $checkedRows.filter("[data-status=doing]").length;
                var checkedSuspended = $checkedRows.filter("[data-status=suspended]").length;
                var checkedClosed    = $checkedRows.filter("[data-status=closed]").length;

                statistics        = allSummary;
                checkedStatistics = checkedAllSummary.replace('%total%', checkedTotal)
                    .replace('%wait%', checkedWait)
                    .replace('%doing%', checkedDoing)
                    .replace('%suspended%', checkedSuspended)
                    .replace('%closed%', checkedClosed);
            }

            return checkedTotal ? checkedStatistics : statistics;
        }
    });
});
</script>
