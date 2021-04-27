<?php $canOrder = (common::hasPriv('program', 'updateOrder') and strpos($orderBy, 'order') !== false)?>
<form class='main-table' id='programForm' method='post' data-ride='table' data-nested='true' data-expand-nest-child='false' data-checkable='false' data-enable-empty-nested-row='true'>
  <table class='table has-sort-head table-fixed table-nested' id='programList'>
    <?php $vars = "status=$status&orderBy=%s";?>
    <thead>
      <tr>
        <th class='table-nest-title'>
          <a class='table-nest-toggle table-nest-toggle-global' data-expand-text='<?php echo $lang->expand; ?>' data-collapse-text='<?php echo $lang->collapse;?>'></a>
          <?php echo $lang->nameAB;?>
        </th>
        <th class='w-100px'><?php common::printOrderLink('PM',     $orderBy, $vars, $lang->program->PM);?></th>
        <th class='text-right w-100px'><?php common::printOrderLink('budget', $orderBy, $vars, $lang->project->budget);?></th>
        <th class='w-90px'> <?php common::printOrderLink('status', $orderBy, $vars, $lang->program->status);?></th>
        <th class='w-100px'><?php common::printOrderLink('begin',  $orderBy, $vars, $lang->project->begin);?></th>
        <th class='w-100px'><?php common::printOrderLink('end',    $orderBy, $vars, $lang->project->end);?></th>
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
          if(empty($program->path)) $program->path = $programs[$program->parent]->path . "$program->id,";
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
          <?php $class = $program->type == 'program' ? ' table-nest-toggle' : '';?>
          <span class="table-nest-icon icon icon-<?php echo $program->type;?> <?php echo $class;?>"></span>
          <?php if($program->type == 'program'):?>
          <?php echo html::a($this->createLink('program', 'product', "programID=$program->id"), $program->name);?>
          <?php else:?>
          <?php echo html::a($this->createLink('project', 'index', "projectID=$program->id", '', '', $program->id), $program->name);?>
          <?php endif;?>
        </td>
        <td>
          <?php $userID = isset($PMList[$program->PM]) ? $PMList[$program->PM]->id : ''?>
          <?php if(!empty($program->PM)) echo html::a($this->createLink('user', 'profile', "userID=$userID", '', true), zget($users, $program->PM), '', "data-toggle='modal' data-type='iframe' data-width='600'");?>
        </td>
        <?php $programBudget = in_array($this->app->getClientLang(), ['zh-cn','zh-tw']) ? round((float)$program->budget / 10000, 2) . $lang->project->tenThousand : round((float)$program->budget, 2);?>
        <td class='text-right'><?php echo $program->budget != 0 ? zget($lang->project->currencySymbol, $program->budgetUnit) . ' ' . $programBudget : $lang->project->future;?></td>
        <td class='c-status'><span class="status-program status-<?php echo $program->status?>"><?php echo zget($lang->project->statusList, $program->status, '');?></span></td>
        <td><?php echo $program->begin;?></td>
        <td><?php echo $program->end == LONG_TIME ? $lang->program->longTime : $program->end;?></td>
        <td class='c-actions'>
          <?php if($program->type == 'program'):?>
          <?php if($program->status == 'wait' || $program->status == 'suspended') common::printIcon('program', 'start', "programID=$program->id", $program, 'list', 'play', '', 'iframe', true, '', $this->lang->program->start);?>
          <?php if($program->status == 'doing')  common::printIcon('program', 'close',    "programID=$program->id", $program, 'list', 'off',   '', 'iframe', true);?>
          <?php if($program->status == 'closed') common::printIcon('program', 'activate', "programID=$program->id", $program, 'list', 'magic', '', 'iframe', true);?>

          <?php if(common::hasPriv('program', 'suspend') || (common::hasPriv('program', 'close') && $program->status != 'doing') || (common::hasPriv('program', 'activate') && $program->status != 'closed')):?>
          <div class='btn-group'>
            <button type='button' class='btn icon-caret-down dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>" style="width: 16px; padding-left: 0px;"></button>
            <ul class='dropdown-menu pull-right text-center' role='menu' style="min-width:auto; padding: 5px 10px;">
              <?php common::printIcon('program', 'suspend', "programID=$program->id", $program, 'list', 'pause', '', 'iframe', true, '', $this->lang->program->suspend);?>
              <?php if($program->status != 'doing')  common::printIcon('program', 'close',    "programID=$program->id", $program, 'list', 'off', '',   'iframe', true);?>
              <?php if($program->status != 'closed') common::printIcon('program', 'activate', "programID=$program->id", $program, 'list', 'magic', '', 'iframe', true);?>
            </ul>
          </div>
          <?php endif;?>
          <?php common::printIcon('program', 'edit',   "programID=$program->id", '', 'list', 'edit');?>
          <?php common::printIcon('program', 'create', "programID=$program->id", '', 'list', 'split', '', '', '', $program->status == 'closed' ? 'disabled' : '', $this->lang->program->children);?>
          <?php if(common::hasPriv('program', 'delete')) echo html::a($this->createLink("program", "delete", "programID=$program->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn' title='{$this->lang->program->delete}'");?>

          <?php else:?>
          <?php if($program->status == 'wait' || $program->status == 'suspended') common::printIcon('project', 'start', "projectID=$program->id", $program, 'list', 'play', '', 'iframe', true);?>
          <?php if($program->status == 'doing')  common::printIcon('project', 'close',    "projectID=$program->id", $program, 'list', 'off', '',   'iframe', true);?>
          <?php if($program->status == 'closed') common::printIcon('project', 'activate', "projectID=$program->id", $program, 'list', 'magic', '', 'iframe', true);?>
          <?php if(common::hasPriv('project', 'suspend') || (common::hasPriv('project', 'close') && $program->status != 'doing') || (common::hasPriv('project', 'activate') && $program->status != 'closed')):?>
          <div class='btn-group'>
            <button type='button' class='btn icon-caret-down dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>" style="width: 16px; padding-left: 0px;"></button>
            <ul class='dropdown-menu pull-right text-center' role='menu' style="min-width:auto; padding: 5px 10px;">
              <?php common::printIcon('project', 'suspend', "projectID=$program->id", $program, 'list', 'pause', '', 'iframe', true);?>
              <?php if($program->status != 'doing')  common::printIcon('project', 'close',    "projectID=$program->id", $program, 'list', 'off',   '', 'iframe', true);?>
              <?php if($program->status != 'closed') common::printIcon('project', 'activate', "projectID=$program->id", $program, 'list', 'magic', '', 'iframe', true);?>
            </ul>
          </div>
          <?php endif;?>
          <?php common::printIcon('project', 'edit', "projectID=$program->id&from=browse", $program, 'list', 'edit', '', '', '', "data-app='project'", '', $program->id);?>
          <?php common::printIcon('project', 'manageMembers', "projectID=$program->id", $program, 'list', 'group', '', '', '', 'data-app="project"', '', $program->id);?>
          <?php common::printIcon('project', 'group',         "projectID=$program->id", $program, 'list', 'lock', '', '', '', 'data-app="project"', '', $program->id);?>
          <?php if(common::hasPriv('project', 'manageProducts') || common::hasPriv('project', 'whitelist') || common::hasPriv('project', 'delete')):?>
          <div class='btn-group'>
            <button type='button' class='btn dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>"><i class='icon-more-alt'></i></button>
            <ul class='dropdown-menu pull-right text-center' role='menu'>
              <?php common::printIcon('project', 'manageProducts', "projectID=$program->id&from=browse", $program, 'list', 'link', '', '', '', "data-app='project'", '', $program->id);?>
              <?php common::printIcon('project', 'whitelist',      "projectID=$program->id&module=project&from=browse", $program, 'list', 'shield-check', '', '', '', "data-app='project'", '', $program->id);?>
              <?php if(common::hasPriv('project','delete')) echo html::a($this->createLink("project", "delete", "projectID=$program->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn' title='{$this->lang->delete}' data-group='program'");?>
            </ul>
          </div>
          <?php endif;?>
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
#programList > thead > tr > th .table-nest-toggle-global {top: 6px}
#programList > thead > tr > th .table-nest-toggle-global:before {color: #a6aab8;}
#programTableList > tr:last-child .c-actions .dropdown-menu {top: auto; bottom: 100%; margin-bottom: -5px;}
#programTableList .icon-project:before {content: '\e99c'; width: 22px; height: 22px; background: none; color: #16a8f8; top: 0; line-height: 22px; margin-right: 2px; font-size: 14px}
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
            $.post(createLink('project', 'updateOrder'), {'projects' : projects, 'orderBy' : orderBy});

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
