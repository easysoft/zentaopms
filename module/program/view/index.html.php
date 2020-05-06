<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('status', $status);?>
<style>
.footerbar .right-info{position: absolute; bottom: 10px; right: 10px;}
.footerbar .left-info{position: absolute; bottom: 15px; left: 20px;}
.flow-block {height: 230px;}
.panel-body .card-content{overflow-y: scroll; height: 100px;}
</style>
<?php if($programType == 'bygrid'):?>
<style>
#mainMenu{padding-left: 10px; padding-right: 10px;}
</style>
<?php endif;?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-group pull-left">
    <?php echo html::a('javascript:setProgramType("bygrid")', "<i class='icon icon-cards-view'></i>", '', "title='看板' class='btn btn-icon " . ($programType == 'bygrid' ? 'text-primary' : '') . "'");?>
    <?php echo html::a('javascript:setProgramType("bylist")', "<i class='icon icon-bars'></i>", '', "title='列表' class='btn btn-icon " . ($programType == 'bylist' ? 'text-primary' : '') . "'");?>
  </div>
  <div class='pull-right'><?php echo $lang->pageActions;?></div>
</div>
<div id='mainContent' class='main-row'>
  <?php if(empty($projectList)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->program->noProgram;?></span> <?php common::printLink('program', 'create', '', "<i class='icon icon-plus'></i> " . $lang->program->create, '', "class='btn btn-info'");?></p>
  </div>
  <?php else:?>
  <div class='main-col'>
    <?php if($programType == 'bygrid'):?>
    <?php foreach ($projectList as $projectID => $project):?>
    <div class='col-sm-6 col-md-4' data-id='<?php echo $projectID?>'>
      <div class='main-table panel flow-block'>
        <div class='main-table panel-heading'>
          <strong title='<?php echo $project->name;?>'><?php echo $project->name;?></strong>
          <span class="label label-primary label-outline"><?php echo zget($templates, $project->parent);?></span>
          <span class="label label-success label-outline"><?php echo zget($lang->project->statusList, $project->status);?></span>
          <span class="label label-danger  label-outline"><?php echo zget($users,$project->PM);?></span>
          <span class="label label-warning label-outline"><?php echo $project->budget . ' ' . zget($lang->program->unitList, $project->budgetUnit);?></span>
          <nav class='panel-actions nav nav-default'>
            <li class='dropdown'>
              <a href='javascript:;' data-toggle='dropdown' class='panel-action'><i class='icon icon-ellipsis-v'></i></a>
              <ul class='dropdown-menu pull-right'>
                <li><?php common::printicon('program', 'activate', "projectid=$project->id", $project, 'button', '', '', 'iframe', true);?></li>
                <li><?php if(common::hasPriv('program', 'edit')) echo html::a($this->createLink("program", "edit", "projectID=$project->id"), "<i class='icon-edit'></i> " . $lang->edit, '', "class='btn btn-link'");?></li>
                <li><?php common::printIcon('program', 'start',   "projectID=$project->id", $project, 'button', '', '', 'iframe', true);?></li>
                <li><?php common::printIcon('program', 'suspend', "projectID=$project->id", $project, 'button', '', '', 'iframe', true);?></li>
                <li><?php common::printIcon('program', 'finish',  "projectID=$project->id", $project, 'button', '', '', 'iframe', true);?></li>
                <li><?php common::printIcon('program', 'close',   "projectID=$project->id", $project, 'button', '', '', 'iframe', true);?></li>
                <li><?php if(common::hasPriv('program', 'delete'))  echo html::a($this->createLink("project", "delete", "projectID=$project->id"), "<i class='icon-trash'></i> " . $lang->delete, 'hiddenwin', "class='btn btn-link'");?></li>
              </ul>
            </li>
          </nav>
        </div>
        <div class='main-table panel-body'>
          <div class='card-content text-muted scrollbar-hover'><?php echo $project->desc;?></div>
          <div class='footerbar'>
            <div class='right-info'>
            <?php common::printLink('programplan', 'browse', "programplan=$project->id", $lang->project->enter, '', "class='btn btn-primary'");?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
    <?php else:?>
    <div class='main-table'>
      <table class='table has-sort-head table-fixed' id='projectList'>
        <?php $vars = "status=$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <thead>
          <tr>
            <th class='c-id w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
            <th class='w-120px'><?php common::printOrderLink('code', $orderBy, $vars, $lang->program->code);?></th>
            <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->program->name);?></th>
            <th class='w-100px'><?php common::printOrderLink('type', $orderBy, $vars, $lang->program->type);?></th>
            <th class='w-100px'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->program->begin);?></th>
            <th class='w-100px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->program->end);?></th>
            <th class='w-100px'><?php common::printOrderLink('budget', $orderBy, $vars, $lang->program->budget);?></th>
            <th class='w-100px'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->program->PM);?></th>
            <th class='w-200px text-left'><?php echo $lang->program->desc;?></th>
            <th class='text-center w-200px'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody class='sortable' id='projectTableList'>
          <?php foreach($projectList as $project):?>
          <tr data-id='<?php echo $project->id ?>' data-order='<?php echo $project->order ?>'>
            <td class='c-id'><?php printf('%03d', $project->id);?></td>
            <td class='text-left'><?php echo $project->code;?></td>
            <td class='text-left' title='<?php echo $project->name?>'>
              <?php echo html::a($this->createLink('project', 'task', 'projectID=' . $project->id), $project->name);?>
            </td>
            <td class='text-center'><?php echo zget($lang->program->typeList, $project->type, '');?></td>
            <td class='text-center'><?php echo $project->begin;?></td>
            <td class='text-center'><?php echo $project->end;?></td>
            <td class='text-left'><?php echo $project->budget . ' ' . zget($lang->program->unitList, $project->budgetUnit);?></td>
            <td><?php echo zget($users, $project->PM);?></td>
            <td title='<?php echo strip_tags($project->desc);?>'><?php echo strip_tags($project->desc);?></td>
            <td class='text-center c-actions'>
              <?php common::printIcon('program', 'start', "projectID=$project->id", $project, 'list', '', '', 'iframe', true);?>
              <?php common::printIcon('program', 'activate', "projectID=$project->id", $project, 'list', '', '', 'iframe', true);?>
              <?php common::printIcon('program', 'suspend', "projectID=$project->id", $project, 'list', '', '', 'iframe', true);?>
              <?php common::printIcon('program', 'finish', "projectID=$project->id", $project, 'list', '', '', 'iframe', true);?>
              <?php common::printIcon('program', 'close', "projectID=$project->id", $project, 'list', '', '', 'iframe', true);?>
              <?php if(common::hasPriv('program', 'edit')) echo html::a($this->createLink("program", "edit", "projectID=$project->id"), "<i class='icon-edit'></i>", '', "class='btn' title='{$lang->edit}'");?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </div>
    <?php endif;?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
