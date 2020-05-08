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
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('status', $status);?>
<?php js::set('orderBy', $orderBy);?>
<?php if($programType == 'bygrid'):?>
<style>
#mainMenu{padding-left: 10px; padding-right: 10px;}
</style>
<?php endif;?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->program->featureBar as $key => $label):?>
    <?php $active = $status == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($status == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('index', "status=$key&orderBy=$orderBy"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('mine', array('1' => $lang->program->mine), '', $this->cookie->mine ? 'checked=checked' : '');?>
  </div>
  <div class='pull-right'>
    <div class='btn-group'>
      <?php echo html::a('javascript:setProgramType("bygrid")', "<i class='icon icon-cards-view'></i>", '', "title={$lang->program->bygrid} class='btn btn-icon " . ($programType == 'bygrid' ? 'text-primary' : '') . "'");?>
      <?php echo html::a('javascript:setProgramType("bylist")', "<i class='icon icon-bars'></i>", '', "title={$lang->program->bylist} class='btn btn-icon " . ($programType == 'bylist' ? 'text-primary' : '') . "'");?>
    </div>
    <?php common::printLink('program', 'export', "status=$status&orderBy=$orderBy", "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export'")?>
    <?php echo $lang->pageActions;?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <?php if(empty($projectList)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->program->noProgram;?></span> <?php common::printLink('program', 'createguide', '', "<i class='icon icon-plus'></i> " . $lang->program->create, '', "class='btn btn-info' data-toggle='modal' data-type='ajax'");?></p>
  </div>
  <?php else:?>
  <div class='main-col'>
    <?php if($programType == 'bygrid'):?>
    <div class='row cell' id='cards'>
      <?php foreach ($projectList as $projectID => $project):?>
      <div class='col' data-id='<?php echo $projectID?>'>
        <div class='panel' data-url='<?php echo $this->createLink('project', 'task', "projectID=$project->id");?>'>
          <div class='panel-heading'>
            <strong class='project-name' title='<?php echo $project->name;?>'><?php echo $project->name;?></strong>
            <?php if($project->type === 'cmmi'): ?>
            <span class='project-type-label label label-warning label-outline'><?php echo $lang->program->cmmi; ?></span>
            <?php else: ?>
            <span class='project-type-label label label-info label-outline'><?php echo $lang->program->scrum; ?></span>
            <?php endif; ?>
            <nav class='panel-actions nav nav-default'>
              <li class='dropdown'>
                <a href='javascript:;' data-toggle='dropdown' class='panel-action'><i class='icon icon-ellipsis-v'></i></a>
                <ul class='dropdown-menu pull-right'>
                  <li><?php common::printicon('program', 'activate', "projectid=$project->id", $project, 'button', '', '', 'iframe', true);?></li>
                  <li><?php if(common::hasPriv('program', 'edit')) echo html::a($this->createLink("program", "edit", "projectID=$project->id"), "<i class='icon-edit'></i> " . $lang->edit, '', "");?></li>
                  <li><?php common::printIcon('program', 'start',   "projectID=$project->id", $project, 'button', '', '', 'iframe', true);?></li>
                  <li><?php common::printIcon('program', 'suspend', "projectID=$project->id", $project, 'button', '', '', 'iframe', true);?></li>
                  <li><?php common::printIcon('program', 'finish',  "projectID=$project->id", $project, 'button', '', '', 'iframe', true);?></li>
                  <li><?php common::printIcon('program', 'close',   "projectID=$project->id", $project, 'button', '', '', 'iframe', true);?></li>
                  <li><?php if(common::hasPriv('program', 'delete'))  echo html::a($this->createLink("project", "delete", "projectID=$project->id"), "<i class='icon-trash'></i> " . $lang->delete, 'hiddenwin', "");?></li>
                </ul>
              </li>
            </nav>
          </div>
          <div class='panel-body'>
            <div class='project-infos'>
              <span><i class='icon icon-group'></i> <?php printf($lang->program->membersUnit, $project->teamCount); ?></span>
              <span><i class='icon icon-clock'></i> <?php printf($lang->program->hoursUnit, $project->hours->totalEstimate); ?></span>
              <span><i class='icon icon-cost'></i> <?php echo $project->budget . '' . zget($lang->program->unitList, $project->budgetUnit);?></span>
            </div>
            <?php if($project->type === 'cmmi'): ?>
            <div class='project-detail project-stages'>
              <p class='text-muted'><?php echo $lang->program->ongoingStage; ?></p>
              <div class='label label-outline'><?php echo zget($lang->project->statusList, $project->status);?></div>
            </div>
            <?php else: ?>
            <div class='project-detail project-iteration'>
              <p class='text-muted'><?php echo $lang->program->lastIteration; ?></p>
              <div class='row'>
                <div class='col-xs-5'><?php echo $project->name; ?></div>
                <div class='col-xs-7'>
                <div class="progress progress-text-left">
                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $project->hours->progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $project->hours->progress;?>%">
                  <span class="progress-text"><?php echo $project->hours->progress;?>%</span>
                  </div>
                </div>
                </div>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach;?>
      <div class='col-xs-12' id='cardsFooter'>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </div>
    <?php else:?>
    <?php $canOrder = (common::hasPriv('project', 'updateOrder') and strpos($orderBy, 'order') !== false)?>
    <form class='main-table' id='programForm' method='post' data-ride='table'>
      <table class='table has-sort-head table-fixed' id='programList'>
        <?php $vars = "status=$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <thead>
          <tr>
            <th class='c-id w-80px'>
              <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                <label></label>
              </div>
              <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
            </th>
            <th class='w-120px'><?php common::printOrderLink('code', $orderBy, $vars, $lang->program->code);?></th>
            <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->program->name);?></th>
            <th class='w-100px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->project->status);?></th>
            <th class='w-100px'><?php common::printOrderLink('category', $orderBy, $vars, $lang->program->category);?></th>
            <th class='w-100px'><?php common::printOrderLink('type', $orderBy, $vars, $lang->program->type);?></th>
            <th class='w-100px'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->program->begin);?></th>
            <th class='w-100px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->program->end);?></th>
            <th class='w-100px'><?php common::printOrderLink('budget', $orderBy, $vars, $lang->program->budget);?></th>
            <th class='w-100px'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->program->PM);?></th>
            <th class='w-200px text-left'><?php echo $lang->program->desc;?></th>
            <th class='text-center w-200px'><?php echo $lang->actions;?></th>
            <?php if($canOrder):?>
            <th class='w-60px sort-default'><?php common::printOrderLink('order', $orderBy, $vars, $lang->project->orderAB);?></th>
            <?php endif;?>
          </tr>
        </thead>
        <tbody class='sortable' id='programTableList'>
          <?php foreach($projectList as $project):?>
          <tr data-id='<?php echo $project->id ?>' data-order='<?php echo $project->order ?>'>
            <td class='c-id'>
              <div class="checkbox-primary">
                <input type='checkbox' name='projectIDList[<?php echo $project->id;?>]' value='<?php echo $project->id;?>' />
                <label></label>
              </div>
              <?php printf('%03d', $project->id);?>
            </td>
            <td class='text-left'><?php echo $project->code;?></td>
            <td class='text-left' title='<?php echo $project->name?>'>
              <?php echo html::a($this->createLink('project', 'task', 'projectID=' . $project->id), $project->name);?>
            </td>
            <td class='c-status'><span class="status-project status-<?php echo $project->status?>"><?php echo zget($lang->project->statusList, $project->status, '');?></span></td>
            <td class='text-left'><?php echo zget($lang->program->categoryList, $project->category, '');?></td>
            <td class='text-left'><?php echo zget($lang->program->typeList, $project->type, '');?></td>
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
            <?php if($canOrder):?>
            <td class='sort-handler'><i class="icon icon-move"></i></td>
            <?php endif;?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <?php endif;?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
