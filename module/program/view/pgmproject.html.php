<?php
/**
 * The pgmproject view file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @version     $Id: pgmproject.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php
js::set('programID', $programID);
js::set('browseType', $browseType);
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->program->featureBar as $key => $label):?>
    <?php $active = $browseType == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($browseType == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('PGMProject', "programID=$programID&browseType=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('PRJMine', array('1' => $lang->program->mine), '', $this->cookie->PRJMine ? 'checked=checked' : '');?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('program', 'createGuide', "programID=$programID&from=PGM", '<i class="icon icon-plus"></i>' . $lang->program->PRJCreate, '', 'class="btn btn-primary" data-toggle="modal" data-target="#guideDialog"');?>
  </div>
</div>
<div id='mainContent' class="main-row fade">
  <div class="main-col">
    <form class='main-table table-project' id='projectsForm' method='post' data-ride='table'>
      <table class='table has-sort-head table-fixed'>
        <?php $vars = "programID=$programID&browseType=$browseType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <thead>
          <tr>
            <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
            <th class='w-80px'><?php common::printOrderLink('code', $orderBy, $vars, $lang->program->PRJCode);?></th>
            <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->program->PRJName);?></th>
            <th class='w-100px'><?php echo $lang->program->PGMCommon;?></th>
            <th class='w-70px'><?php echo $lang->program->PRJModel;?></th>
            <th class='w-90px'><?php echo $lang->program->PRJPM;?></th>
            <th class='w-100px'><?php echo $lang->program->begin;?></th>
            <th class='w-100px'><?php echo $lang->program->end;?></th>
            <th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->program->PRJStatus);?></th>
            <th class='w-80px'><?php echo $lang->program->PRJBudget;?></th>
            <th class='w-80px'><?php echo $lang->program->teamCount;?></th>
            <th class='w-60px'><?php echo $lang->program->PRJEstimate;?></th>
            <th class='w-60px'><?php echo $lang->program->PRJConsume;?></th>
            <th class='w-60px'><?php echo $lang->program->PRJSurplus;?></th>
            <th class='w-150px'><?php echo $lang->program->PRJProgress;?></th>
            <th class='text-center w-210px'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody class="sortable" id='projectTableList'>
          <?php foreach($projectStats as $project):?>
          <tr data-id='<?php echo $project->id ?>' data-order='<?php echo $project->code;?>'>
            <td><?php printf('%03d', $project->id);?></td>
            <td class='text-left' title="<?php echo $project->code;?>"><?php echo $project->code;?></td>
            <td class='c-name text-left' title='<?php echo $project->name?>'>
              <?php echo html::a($this->createLink('program', 'index', "projectID=$project->id", '', '', $project->id), $project->name);?>
            </td>
            <td><?php echo $program->name;?></td>
            <td><?php echo zget($lang->program->templateList, $project->model);?></td>
            <td><?php echo zget($users, $project->PM);?></td>
            <td><?php echo $project->begin;?></td>
            <td><?php echo $project->end;?></td>
            <td><?php echo zget($lang->program->statusList, $project->status);?></td>
            <td><?php echo $project->budget . zget($lang->program->unitList, $project->budgetUnit);?></td>
            <td><?php echo $project->teamCount;?></td>
            <td><?php echo $project->hours->totalEstimate;?></td>
            <td><?php echo $project->hours->totalConsumed;?></td>
            <td><?php echo $project->hours->totalLeft;?></td>
            <td class="c-progress">
              <span class='pie-icon' data-percent='<?php echo $project->hours->progress;?>' data-border-color='#ddd' data-back-color='#f1f1f1'></span> <?php echo $project->hours->progress;?>%
            </td>
            <td class='text-center c-actions'>
              <?php common::printIcon('program', 'PRJGroup', "projectID=$project->id&programID=$programID", $project, 'list', 'group');?>
              <?php common::printIcon('program', 'PRJManageMembers', "programID=$project->id", $project, 'list', 'persons');?>
              <?php common::printIcon('program', 'PRJStart', "programID=$project->id", $project, 'list', 'start', '', 'iframe', true);?>
              <?php common::printIcon('program', 'PRJActivate', "programID=$project->id", $project, 'list', 'magic', '', 'iframe', true);?>
              <?php common::printIcon('program', 'PRJSuspend', "programID=$project->id", $project, 'list', 'pause', '', 'iframe', true);?>
              <?php common::printIcon('program', 'PRJClose', "programID=$project->id", $project, 'list', 'off', '', 'iframe', true);?>
              <?php if(common::hasPriv('program', 'PRJEdit')) echo html::a($this->createLink("program", "PRJEdit", "programID=$project->id"), "<i class='icon-edit'></i>", '', "class='btn' title='{$lang->edit}'");?>
              <?php common::printIcon('program', 'PRJDelete', "projectID=$project->id", $project, 'list', 'trash', 'hiddenwin', '', true);?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php if($projectStats):?>
      <div class='table-footer'>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
  </div>
</div>
<script>
$('#PRJMine1').click(function()
{
    var PRJMine = $(this).is(':checked') ? 1 : 0;
    $.cookie('PRJMine', PRJMine, {expires:config.cookieLife, path:config.webRoot});
    window.location.reload();
});
</script>
<?php include '../../common/view/footer.html.php';?>
