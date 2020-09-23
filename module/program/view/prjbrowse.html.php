<?php
/**
 * The prjbrowse view file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @version     $Id: prjbrowse.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php
js::set('orderBy', $orderBy);
js::set('programID', $programID);
js::set('browseType', $browseType);
?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <div class="title">
      <?php echo empty($program) ? $lang->program->PGMCommon : $program->name;?>
      <?php if($programID) echo html::a(inLink('PRJBrowse', 'programID=0'), "<i class='icon icon-sm icon-close'></i>", '', 'class="text-muted"');?>
    </div>
  </div>
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->program->featureBar as $key => $label):?>
    <?php $active = $browseType == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($browseType == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('PRJBrowse', "programID=$programID&browseType=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('PRJMine', array('1' => $lang->program->mine), '', $this->cookie->PRJMine ? 'checked=checked' : '');?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('program', 'createGuide', "programID=$programID", '<i class="icon icon-plus"></i>' . $lang->program->PRJCreate, '', 'class="btn btn-primary" data-toggle="modal" data-target="#guideDialog"');?>
  </div>
</div>
<div id='mainContent' class="main-row fade">
  <div id="sidebar" class="side-col">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php echo $PRJTree;?>
      <div class="text-center">
        <?php common::printLink('program', 'PRJProgramTitle', '', $lang->program->PRJModuleSetting, '', "class='btn btn-info btn-wide iframe'", true, true);?>
      </div>
    </div>
  </div>
  <div class="main-col">
    <?php $canOrder = common::hasPriv('program', 'PRJOrderUpdate');?>
    <form class='main-table table-project' id='projectsForm' method='post' data-ride='table'>
      <table class='table has-sort-head table-fixed'>
        <?php $vars = "programID=$programID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <thead>
          <tr>
            <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
            <th class='w-100px'><?php common::printOrderLink('code', $orderBy, $vars, $lang->program->PRJCode);?></th>
            <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->program->PRJName);?></th>
            <th class='w-90px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->program->PRJStatus);?></th>
            <th class='w-120px'><?php echo $lang->program->begin;?></th>
            <th class='w-120px'><?php echo $lang->program->end;?></th>
            <th class='w-80px'><?php echo $lang->program->PRJBudget;?></th>
            <th class='w-80px'><?php echo $lang->program->PRJPM;?></th>
            <th class='text-center w-210px'><?php echo $lang->actions;?></th>
            <?php if($canOrder):?>
            <th class='w-70px sort-default'><?php common::printOrderLink('order', $orderBy, $vars, $lang->program->PRJUpdateOrder);?></th>
            <?php endif;?>
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
            <td><?php echo zget($lang->program->statusList, $project->status);?></td>
            <td><?php echo $project->begin;?></td>
            <td><?php echo $project->end;?></td>
            <td><?php echo $project->budget;?></td>
            <td><?php echo zget($users, $project->PM);?></td>
            <td class='text-center c-actions'>
              <?php common::printIcon('program', 'PRJGroup', "projectID=$project->id&programID=$programID", $project, 'list', 'group');?>
              <?php common::printIcon('program', 'PRJManageMembers', "programID=$project->id", $project, 'list', 'persons');?>
              <?php common::printIcon('program', 'PRJStart', "programID=$project->id", $project, 'list', 'start', '', 'iframe', true);?>
              <?php common::printIcon('program', 'PRJActivate', "programID=$project->id", $project, 'list', 'magic', '', 'iframe', true);?>
              <?php common::printIcon('program', 'PRJSuspend', "programID=$project->id", $project, 'list', 'pause', '', 'iframe', true);?>
              <?php common::printIcon('program', 'PRJClose', "programID=$project->id", $project, 'list', 'off', '', 'iframe', true);?>
              <?php if(common::hasPriv('program', 'PRJEdit')) echo html::a($this->createLink("program", "PRJEdit", "programID=$project->id"), "<i class='icon-edit'></i>", '', "class='btn' title='{$lang->edit}'");?>
              <?php common::printIcon('program', 'PRJDelete', "projectID=$project->id&confirm=no&from=PRJ&programID=$programID", $project, 'list', 'trash', 'hiddenwin', '', true);?>
            </td>
            <?php if($canOrder):?>
              <td class='c-actions sort-handler'><i class="icon icon-move"></i></td>
            <?php endif;?>
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
<?php include '../../common/view/footer.html.php';?>
