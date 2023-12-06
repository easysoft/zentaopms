<?php
/**
 * The pgmproject view file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @version     $Id: pgmproject.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php
js::set('programID', $programID);
js::set('browseType', $browseType);
$canBatchEdit   = common::hasPriv('project', 'batchEdit');
$waitCount      = 0;
$doingCount     = 0;
$suspendedCount = 0;
$closedCount    = 0;
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolBar pull-left">
    <?php common::sortFeatureMenu('program', 'project');?>
    <?php foreach($lang->program->featureBar['project'] as $key => $label):?>
    <?php $active = $browseType == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($browseType == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('project', "programID=$programID&browseType=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php if($canBatchEdit) echo html::checkbox('showEdit', array('1' => $lang->project->edit), $showBatchEdit);?>
    <?php echo html::checkbox('involved ', array('1' => $lang->project->mine), '', $this->cookie->involved ? 'checked=checked' : '');?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('project', 'create')) common::printLink('project', 'createGuide', "programID=$programID", '<i class="icon icon-plus"></i> ' . $lang->project->create, '', 'class="btn btn-primary" data-toggle="modal"');?>
  </div>
</div>
<div id='mainContent' class="main-row fade">
  <div class="main-col">
    <?php if(empty($projectStats)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->project->empty;?></span>
        <?php if(empty($allProjectsNum)) common::printLink('project', 'createGuide', "programID=$programID", '<i class="icon icon-plus"></i> ' . $lang->project->create, '', 'class="btn btn-info btn-wide " data-toggle="modal"');?>
      </p>
    </div>
    <?php else:?>
    <form class='main-table' id='projectsForm' method='post'>
      <?php
        $vars    = "programID=$programID&browseType=$browseType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";
        $setting = $this->datatable->getSetting('program');
      ?>
      <table class='table has-sort-head'>
        <thead>
          <tr>
            <?php
              foreach($setting as $value)
              {
                if($value->id == 'projectStatus' and $browseType !== 'all') $value->show = false;
                if($value->id == 'status' and strpos('all,unclosed', $browseType) === false) $value->show = false;
                if($value->show) $this->datatable->printHead($value, $orderBy, $vars, $canBatchEdit);
              }
            ?>
          </tr>
        </thead>
        <tbody class="sortable" id='projectTableList'>
          <?php foreach($projectStats as $project):?>
          <tr data-id="<?php echo $project->id;?>" data-status="<?php echo $project->status;?>">
            <?php $project->from = 'pgmproject';?>
            <?php if($project->status == 'wait')      $waitCount ++;?>
            <?php if($project->status == 'doing')     $doingCount ++;?>
            <?php if($project->status == 'suspended') $suspendedCount ++;?>
            <?php if($project->status == 'closed')    $closedCount ++;?>
            <?php foreach($setting as $value) $this->project->printCell($value, $project, $users, $programID);?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php if($canBatchEdit):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <?php endif;?>
        <div class="table-actions btn-toolbar">
        <?php
        if($canBatchEdit)
        {
            $actionLink = $this->createLink('project', 'batchEdit', "from=pgmproject&programID=$programID");
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
js::set('summary', sprintf($lang->project->summary, count($projectStats)));
js::set('allSummary', sprintf($lang->project->allSummary, count($projectStats), $waitCount, $doingCount, $suspendedCount, $closedCount));
js::set('checkedSummary', $lang->project->checkedSummary);
js::set('checkedAllSummary', $lang->project->checkedAllSummary);
?>
<script>
$('input[name^="involved"]').click(function()
{
    var involved = $(this).is(':checked') ? 1 : 0;
    $.cookie('involved', involved, {expires:config.cookieLife, path:config.webRoot});
    window.location.reload();
});
</script>
<?php include '../../common/view/footer.html.php';?>
