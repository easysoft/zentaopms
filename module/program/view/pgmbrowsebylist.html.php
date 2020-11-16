<?php $canOrder = (common::hasPriv('program', 'updateOrder') and strpos($orderBy, 'order') !== false)?>
<form class='main-table' id='programForm' method='post' data-ride='table' data-nested='true' data-expand-nest-child='false' data-checkable='false'>
  <table class='table has-sort-head table-fixed table-nested' id='programList'>
    <?php $vars = "status=$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
    <thead>
      <tr>
        <th class='table-nest-title'>
          <a class='table-nest-toggle table-nest-toggle-global' data-expand-text='<?php echo $lang->expand; ?>' data-collapse-text='<?php echo $lang->collapse; ?>'></a>
          <?php echo $lang->program->PGMName;?>
        </th>
        <th class='w-90px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->program->PGMStatus);?></th>
        <th class='w-100px'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->program->begin);?></th>
        <th class='w-100px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->program->end);?></th>
        <th class='w-100px'><?php common::printOrderLink('budget', $orderBy, $vars, $lang->program->PGMBudget);?></th>
        <th class='w-100px'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->program->PGMPM);?></th>
        <th class='text-center w-250px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody id='programTableList'>
      <?php $originOrders = array(); ?>
      <?php foreach($programs as $program):?>
      <?php
      $trClass = '';
      $trAttrs = "data-id='$program->id' data-order='$program->order' data-parent='$program->parent'";
      if($program->type == 'program')
      {
          $trAttrs .= " data-nested='true'";
          if($program->parent == '0') $trClass .= ' is-top-level table-nest-child-hide';
          else $trClass .= ' table-nest-hide';
      }

      if($program->parent and isset($programs[$program->parent]))
      {
          if($program->type != 'program') $trClass .= ' is-nest-child';
          $trClass .= ' table-nest-hide';
          $trAttrs .= " data-nest-parent='$program->parent' data-nest-path='$program->path'";
      }
      elseif($program->type != 'program')
      {
          $trClass .= ' no-nest';
      }
      $trAttrs .= " class='$trClass'";
      $originOrders[] = $program->order;
      ?>

      <tr <?php echo $trAttrs;?>>
        <td class='c-name text-left <?php if($canOrder) echo 'sort-handler';?>' title='<?php echo $program->name?>'>
          <span class="table-nest-icon icon <?php if($program->type == 'program') echo ' table-nest-toggle' ?>"></span>
          <?php if($program->type == 'program'):?>
          <?php echo html::a($this->createLink('program', 'pgmproduct', "programID=$program->id"), $program->name);?>
          <?php else:?>
          <?php echo html::a($this->createLink('program', 'index', "programID=$program->id", '', '', $program->id), $program->name);?>
          <?php endif;?>
        </td>
        <td class='c-status'><span class="status-program status-<?php echo $program->status?>"><?php echo zget($lang->project->statusList, $program->status, '');?></span></td>
        <td class='text-center'><?php echo $program->begin;?></td>
        <td class='text-center'><?php echo $program->end == '2059-12-31' ? '' : $program->end;?></td>
        <td class='text-left'><?php echo $program->budget . ' ' . zget($lang->program->unitList, $program->budgetUnit);?></td>
        <td><?php echo zget($users, $program->PM);?></td>
        <td class='text-right c-actions'>
          <?php if($program->type == 'program'):?>
          <?php common::printIcon('program', 'PRJStart', "programID=$program->id", $program, 'list', 'play', '', 'iframe', true);?>
          <?php common::printIcon('program', 'PGMActivate', "programID=$program->id", $program, 'list', 'magic', '', 'iframe', true);?>
          <?php common::printIcon('program', 'PRJSuspend', "programID=$program->id", $program, 'list', 'pause', '', 'iframe', true);?>
          <?php common::printIcon('program', 'PGMClose', "programID=$program->id", $program, 'list', 'off', '', 'iframe', true);?>
          <?php if(common::hasPriv('program', 'PGMEdit')) echo html::a($this->createLink("program", "pgmedit", "programID=$program->id"), "<i class='icon-edit'></i>", '', "class='btn' title='{$lang->edit}'");?>
          <?php common::printIcon('program', 'PGMCreate', "programID=$program->id", '', 'list', 'treemap-alt', '', '', '', '', $this->lang->program->PGMChildren);?>
          <?php if(common::hasPriv('program', 'PGMDelete')) echo html::a($this->createLink("program", "pgmdelete", "programID=$program->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn' title='{$lang->delete}'");?>
          <?php else:?>
          <?php common::printIcon('program', 'PRJGroup', "programID=$program->id", $program, 'list', 'icon icon-lock');?>
          <?php common::printIcon('program', 'PRJManageMembers', "programID=$program->id", $program, 'list', 'persons');?>
          <?php common::printIcon('program', 'PRJStart', "programID=$program->id", $program, 'list', 'play', '', 'iframe', true);?>
          <?php common::printIcon('program', 'PRJActivate', "programID=$program->id", $program, 'list', 'magic', '', 'iframe', true);?>
          <?php common::printIcon('program', 'PRJSuspend', "programID=$program->id", $program, 'list', 'pause', '', 'iframe', true);?>
          <?php common::printIcon('program', 'PRJClose', "programID=$program->id", $program, 'list', 'off', '', 'iframe', true);?>
          <?php if(common::hasPriv('program','PRJEdit')) echo html::a($this->createLink("program", "prjedit", "programID=$program->id"), "<i class='icon-edit'></i>", '', "class='btn' title='{$lang->edit}'");?>
          <?php common::printIcon('program', 'PRJCreate', "programID=$program->id", '', 'list', 'treemap-alt', '', 'disabled', '', '', $this->lang->program->PGMChildren);?>
          <?php if(common::hasPriv('program','PRJDelete')) echo html::a($this->createLink("program", "prjdelete", "programID=$program->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn' title='{$lang->delete}'");?>
          <?php endif;?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
</form>
<style>
.w-240px {width:240px;}
#programTableList.sortable-sorting > tr {opacity: 0.7}
#programTableList.sortable-sorting > tr.drag-row {opacity: 1;}
#programTableList > tr.drop-not-allowed {opacity: 0.1!important}
</style>
<?php js::set('originOrders', $originOrders);?>
<script>
$(function()
{
    /* Init orders numbers list */
    var ordersList = [];
    for(var i = 0; i < originOrders.length; ++i) ordersList.push(parseInt(originOrders[i]));
    ordersList.sort(function(x, y){return x - y;});

    var $list = $('#programTableList');
    $list.addClass('sortable').sortable(
    {
        reverse: orderBy === 'order_desc',
        selector: 'tr',
        dragCssClass: 'drag-row',
        trigger: $list.find('.sort-handler').length ? '.sort-handler' : null,
        canMoveHere: function($ele, $target)
        {
            return $ele.data('parent') === $target.data('parent');
        },
        start: function(e)
        {
            e.targets.filter('[data-parent!="' + e.element.attr('data-parent') + '"]').addClass('drop-not-allowed');
        },
        finish: function(e)
        {
            var projects = '';
            e.list.each(function()
            {
                projects += $(this.item).data('id') + ',' ;
            });
            $.post(createLink('program', 'PRJUpdateOrder'), {'projects' : projects, 'orderBy' : orderBy});

            var $thead = $list.closest('table').children('thead');
            $thead.find('.headerSortDown, .headerSortUp').removeClass('headerSortDown headerSortUp').addClass('header');
            $thead.find('th.sort-default .header').removeClass('header').addClass('headerSortDown');

            e.element.addClass('drop-success');
            setTimeout(function(){e.element.removeClass('drop-success');}, 800);
            $list.children('.drop-not-allowed').removeClass('drop-not-allowed');
            $('#programForm').table('initNestedList')
        }
    });
});
</script>
