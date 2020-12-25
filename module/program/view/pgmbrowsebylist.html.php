<?php $canOrder = (common::hasPriv('program', 'updateOrder') and strpos($orderBy, 'order') !== false)?>
<form class='main-table' id='programForm' method='post' data-ride='table' data-nested='true' data-expand-nest-child='false' data-checkable='false'>
  <table class='table has-sort-head table-fixed table-nested' id='programList'>
    <?php $vars = "status=$status&orderBy=%s";?>
    <thead>
      <tr>
        <th class='table-nest-title'>
          <a class='table-nest-toggle table-nest-toggle-global' data-expand-text='<?php echo $lang->expand; ?>' data-collapse-text='<?php echo $lang->collapse; ?>'></a>
          <?php echo $lang->program->PGMName;?>
        </th>
        <th class='w-100px'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->program->PGMPM);?></th>
        <th class='w-100px'><?php common::printOrderLink('budget', $orderBy, $vars, $lang->program->PGMBudget);?></th>
        <th class='w-90px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->program->PGMStatus);?></th>
        <th class='w-100px'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->program->begin);?></th>
        <th class='w-100px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->program->end);?></th>
        <th class='text-center w-180px'><?php echo $lang->actions;?></th>
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
          $trClass .= $program->parent == '0' ? ' is-top-level table-nest-child-hide' : ' table-nest-hide';
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
        <td><?php echo zget($users, $program->PM);?></td>
        <td class='text-left'><?php echo $program->budget != 0 ? $program->budget . ' ' . zget($lang->program->unitList, $program->budgetUnit) : '';?></td>
        <td class='c-status'><span class="status-program status-<?php echo $program->status?>"><?php echo zget($lang->project->statusList, $program->status, '');?></span></td>
        <td><?php echo $program->begin;?></td>
        <td><?php echo $program->end == LONG_TIME ? $lang->program->PRJLongTime : $program->end;?></td>
        <td class='c-actions'>
          <?php if($program->type == 'program'):?>
          <?php if($program->status == 'wait' || $program->status == 'suspended') common::printIcon('program', 'PGMStart', "programID=$program->id", $program, 'list', 'play', '', 'iframe', true, '', $this->lang->program->PGMStart);?>
          <?php if($program->status == 'doing') common::printIcon('program', 'PGMClose', "programID=$program->id", $program, 'list', 'off', '', 'iframe', true);?>
          <?php if($program->status == 'closed') common::printIcon('program', 'PGMActivate', "programID=$program->id", $program, 'list', 'magic', '', 'iframe', true);?>
          <div class='btn-group'>
            <button type='button' class='btn icon-caret-down dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>"></button>
            <ul class='dropdown-menu pull-right text-center' role='menu'>
              <?php common::printIcon('program', 'PGMSuspend', "programID=$program->id", $program, 'list', 'pause', '', 'iframe', true, '', $this->lang->program->PGMSuspend);?>
              <?php if($program->status != 'doing') common::printIcon('program', 'PGMClose', "programID=$program->id", $program, 'list', 'off', '', 'iframe', true);?>
              <?php if($program->status != 'closed') common::printIcon('program', 'PGMActivate', "programID=$program->id", $program, 'list', 'magic', '', 'iframe', true);?>
            </ul>
          </div>
          <?php if(common::hasPriv('program', 'PGMEdit')) echo html::a($this->createLink("program", "pgmedit", "programID=$program->id"), "<i class='icon-edit'></i>", '', "class='btn' title='{$this->lang->program->PGMEdit}'");?>
          <?php common::printIcon('program', 'PGMCreate', "programID=$program->id", '', 'list', 'treemap-alt', '', '', '', '', $this->lang->program->PGMChildren);?>
          <?php if(common::hasPriv('program', 'PGMDelete')) echo html::a($this->createLink("program", "pgmdelete", "programID=$program->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn' title='{$this->lang->program->PGMDelete}'");?>

          <?php else:?>
          <?php if($program->status == 'wait' || $program->status == 'suspended') common::printIcon('program', 'PRJStart', "projectID=$program->id", $program, 'list', 'play', '', 'iframe', true);?>
          <?php if($program->status == 'doing') common::printIcon('program', 'PRJClose', "projectID=$program->id", $program, 'list', 'off', '', 'iframe', true);?>
          <?php if($program->status == 'closed') common::printIcon('program', 'PRJActivate', "projectID=$program->id", $program, 'list', 'magic', '', 'iframe', true);?>
          <div class='btn-group'>
            <button type='button' class='btn icon-caret-down dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>"></button>
            <ul class='dropdown-menu pull-right text-center' role='menu'>
            <?php common::printIcon('program', 'PRJSuspend', "projectID=$program->id", $program, 'list', 'pause', '', 'iframe', true);?>
            <?php if($program->status != 'doing') common::printIcon('program', 'PRJClose', "projectID=$program->id", $program, 'list', 'off', '', 'iframe', true);?>
            <?php if($program->status != 'closed') common::printIcon('program', 'PRJActivate', "projectID=$program->id", $program, 'list', 'magic', '', 'iframe', true);?>
            </ul>
          </div>
          <?php if(common::hasPriv('program','PRJEdit')) echo html::a($this->createLink("program", "prjedit", "projectID=$program->id"), "<i class='icon-edit'></i>", '', "class='btn' title='{$this->lang->program->PRJEdit}'");?>
          <?php common::printIcon('program', 'PRJManageMembers', "projectID=$program->id", $program, 'list', 'persons');?>
          <?php common::printIcon('program', 'PRJGroup', "projectID=$program->id", $program, 'list', 'icon icon-lock');?>
          <div class='btn-group'>
            <button type='button' class='btn icon-more-circle dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>"></button>
            <ul class='dropdown-menu pull-right text-center' role='menu'>
              <?php common::printIcon('program', 'PRJManageProducts', "projectID=$program->id", $program, 'list', 'icon icon-menu-project');?>
              <?php common::printIcon('program', 'PRJWhitelist', "projectID=$program->id", $program, 'list', 'group');?>
              <?php if(common::hasPriv('program','PRJDelete')) echo html::a($this->createLink("program", "prjdelete", "projectID=$program->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn' title='{$this->lang->program->PRJDelete}'");?>
            </ul>
          </div>
          <?php endif;?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</form>
<style>
#programTableList.sortable-sorting > tr {opacity: 0.7}
#programTableList.sortable-sorting > tr.drag-row {opacity: 1;}
#programTableList > tr.drop-not-allowed {opacity: 0.1!important}
#programList .c-actions {overflow: visible;}
#programTableList > tr:last-child .c-actions .dropdown-menu {top: auto; bottom: 100%; margin-bottom: -5px;}
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
