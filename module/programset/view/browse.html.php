<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <?php if(empty($programsets)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->programset->noProgramset;?></span> <?php common::printLink('programset', 'createguide', '', "<i class='icon icon-plus'></i> " . $lang->programset->create, '', "class='btn btn-info' data-toggle=modal");?></p>
  </div>
  <?php else:?>
  <div class='main-col'>
    <form class='main-table' id='programForm' method='post' data-ride='table' data-nested='true' data-expand-nest-child='false' data-checkable='false'>
      <table class='table has-sort-head table-fixed table-nested' id='programList'>
        <?php $vars = "status=$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <thead>
          <tr>
            <th class='c-id w-80px'>
              <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
            </th>
            <th class='w-100px'><?php common::printOrderLink('code', $orderBy, $vars, $lang->program->code);?></th>
            <th class='table-nest-title'><?php common::printOrderLink('name', $orderBy, $vars, $lang->program->name);?></th>
            <th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->program->status);?></th>
            <th class='w-100px'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->program->begin);?></th>
            <th class='w-100px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->program->end);?></th>
            <th class='w-100px'><?php common::printOrderLink('budget', $orderBy, $vars, $lang->program->budget);?></th>
            <th class='w-100px'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->program->PM);?></th>
            <th class='text-center w-240px'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody id='programTableList'>
          <?php foreach($programs as $program):?>
          <?php
          $trClass = '';
          $trAttrs = "data-id='$program->id' data-order='$program->order' data-parent='$program->parent'";
          if($program->isCat)
          {
              $trAttrs .= " data-nested='true'";
              if($program->parent == '0') $trClass .= ' is-top-level table-nest-child-hide';
              else $trClass .= ' is-top-level table-nest-hide';
          }
    
          if($program->parent)
          {
              if(!$program->isCat) $trClass .= ' is-nest-child';
              $trClass .= ' table-nest-hide';
              $trAttrs .= " data-nest-parent='$program->parent' data-nest-path='$program->path'";
          }
          else if(!$program->isCat) $trClass .= ' no-nest';
          $trAttrs .= " class='$trClass'";
          ?>
          <tr <?php echo $trAttrs;?>>
            <td class='c-id'>
              <?php printf('%03d', $program->id);?>
            </td>
            <td class='text-left'><?php echo $program->code;?></td>
            <td class='text-left pgm-title table-nest-title' title='<?php echo $program->name?>'>
              <span class="table-nest-icon icon<?php if($program->isCat) echo ' table-nest-toggle' ?>"></span>
              <?php echo $program->isCat ? $program->name : html::a($this->createLink('program', 'index', "programID=$program->id", '', '', $program->id), $program->name);?>
            </td>
            <td class='c-status'><span class="status-program status-<?php echo $program->status?>"><?php echo zget($lang->project->statusList, $program->status, '');?></span></td>
            <td class='text-center'><?php echo $program->begin;?></td>
            <td class='text-center'><?php echo $program->end == '0000-00-00' ? '' : $program->end;?></td>
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
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
  </div>
  <?php endif;?>
</div>
<style>
.w-240px {width:240px;}
#programsetTableList.sortable-sorting > tr {opacity: 0.7}
#programsetTableList.sortable-sorting > tr.drag-row {opacity: 1;}
#programsetTableList > tr.drop-not-allowed {opacity: 0.1!important}
</style>
<?php include '../../common/view/footer.html.php';?>
