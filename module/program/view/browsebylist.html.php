<?php $canOrder = (common::hasPriv('program', 'updateOrder') and strpos($orderBy, 'order') !== false)?>
<form class='main-table' id='programForm' method='post' data-ride='table'>
  <table class='table has-sort-head table-fixed' id='programList'>
    <?php $vars = "status=$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
    <thead>
      <tr>
        <th class='c-id w-80px'>
          <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
        </th>
        <th class='w-100px'><?php common::printOrderLink('code', $orderBy, $vars, $lang->program->code);?></th>
        <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->program->name);?></th>
        <th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->program->status);?></th>
        <th class='w-100px'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->program->begin);?></th>
        <th class='w-100px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->program->end);?></th>
        <th class='w-100px'><?php common::printOrderLink('budget', $orderBy, $vars, $lang->program->budget);?></th>
        <th class='w-100px'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->program->PM);?></th>
        <th class='text-center w-240px'><?php echo $lang->actions;?></th>
        <?php if($canOrder):?>
        <th class='w-60px sort-default'><?php common::printOrderLink('order', $orderBy, $vars, $lang->project->orderAB);?></th>
        <?php endif;?>
      </tr>
    </thead>
    <tbody class='sortable' id='programTableList'>
      <?php foreach($programs as $program):?>
      <tr data-id='<?php echo $program->id ?>' data-order='<?php echo $program->order ?>'>
        <td class='c-id'>
          <?php printf('%03d', $program->id);?>
        </td>
        <td class='text-left'><?php echo $program->code;?></td>
        <td class='text-left pgm-title' title='<?php echo $program->name?>'>
          <?php echo html::a(inlink('index', "programID=$program->id"), $program->name);?>
        </td>
        <td class='c-status'><span class="status-program status-<?php echo $program->status?>"><?php echo zget($lang->project->statusList, $program->status, '');?></span></td>
        <td class='text-center'><?php echo $program->begin;?></td>
        <td class='text-center'><?php echo $program->end;?></td>
        <td class='text-left'><?php echo $program->budget . ' ' . zget($lang->program->unitList, $program->budgetUnit);?></td>
        <td><?php echo zget($users, $program->PM);?></td>
        <td class='text-center c-actions'>
          <?php common::printIcon('program', 'group', "programID=$program->id", $program, 'list', 'group');?>
          <?php common::printIcon('program', 'manageMembers', "programID=$program->id", $program, 'list', 'persons');?>
          <?php common::printIcon('program', 'start', "programID=$program->id", $program, 'list', '', '', 'iframe', true);?>
          <?php common::printIcon('program', 'activate', "programID=$program->id", $program, 'list', '', '', 'iframe', true);?>
          <?php common::printIcon('program', 'suspend', "programID=$program->id", $program, 'list', '', '', 'iframe', true);?>
          <?php common::printIcon('program', 'close', "programID=$program->id", $program, 'list', '', '', 'iframe', true);?>
          <?php if(common::hasPriv('program', 'edit')) echo html::a($this->createLink("program", "edit", "programID=$program->id"), "<i class='icon-edit'></i>", '', "class='btn' title='{$lang->edit}'");?>
          <?php common::printIcon('program', 'create', "template=&programID=$program->id", '', 'list', 'treemap-alt', '', '', '', '', $this->lang->program->children);?>
          <?php if(common::hasPriv('program', 'delete')) echo html::a($this->createLink("program", "delete", "programID=$program->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn' title='{$lang->delete}'");?>
        </td>
        <?php if($canOrder):?>
        <td class='sort-handler'><i class="icon icon-move"></i></td>
        <?php endif;?>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
</form>
