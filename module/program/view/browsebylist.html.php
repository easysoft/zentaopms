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
        <th class='w-100px'><?php common::printOrderLink('code', $orderBy, $vars, $lang->program->code);?></th>
        <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->program->name);?></th>
        <th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->program->status);?></th>
        <th class='w-100px'><?php common::printOrderLink('category', $orderBy, $vars, $lang->program->category);?></th>
        <th class='w-80px'><?php common::printOrderLink('template', $orderBy, $vars, $lang->program->template);?></th>
        <th class='w-100px'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->program->begin);?></th>
        <th class='w-100px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->program->end);?></th>
        <th class='w-100px'><?php common::printOrderLink('budget', $orderBy, $vars, $lang->program->budget);?></th>
        <th class='w-100px'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->program->PM);?></th>
        <th class='text-center w-210px'><?php echo $lang->actions;?></th>
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
          <?php echo html::a($this->createLink('program', 'transfer', 'projectID=' . $project->id), $project->name);?>
        </td>
        <td class='c-status'><span class="status-project status-<?php echo $project->status?>"><?php echo zget($lang->project->statusList, $project->status, '');?></span></td>
        <td class='text-left'><?php echo zget($lang->program->categoryList, $project->category, '');?></td>
        <td class='text-left'><?php echo zget($lang->program->templateList, $project->template, '');?></td>
        <td class='text-center'><?php echo $project->begin;?></td>
        <td class='text-center'><?php echo $project->end;?></td>
        <td class='text-left'><?php echo $project->budget . ' ' . zget($lang->program->unitList, $project->budgetUnit);?></td>
        <td><?php echo zget($users, $project->PM);?></td>
        <td class='text-center c-actions'>
          <?php common::printIcon('program', 'group', "projectID=$project->id", $project, 'list', 'group');?>
          <?php common::printIcon('program', 'manageMembers', "projectID=$project->id", $project, 'list', 'persons');?>
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
