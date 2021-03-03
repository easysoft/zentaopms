<?php $canOrder = (common::hasPriv('project', 'updateOrder') and strpos($orderBy, 'order') !== false)?>
<form class='main-table' id='projectForm' method='post' data-ride='table' data-nested='true' data-expand-nest-child='false' data-checkable='false'>
  <table class='table has-sort-head table-fixed table-nested' id='projectList'>
    <?php $vars = "status=$status&orderBy=%s";?>
    <thead>
      <tr>
        <th class='table-nest-title'>
          <a class='table-nest-toggle table-nest-toggle-global' data-expand-text='<?php echo $lang->expand; ?>' data-collapse-text='<?php echo $lang->collapse; ?>'></a>
          <?php echo $lang->project->PGMName;?>
        </th>
        <th class='w-100px'><?php common::printOrderLink('PM',     $orderBy, $vars, $lang->project->PGMPM);?></th>
        <th class='text-right w-100px'><?php common::printOrderLink('budget', $orderBy, $vars, $lang->project->PGMBudget);?></th>
        <th class='w-90px'> <?php common::printOrderLink('status', $orderBy, $vars, $lang->project->PGMStatus);?></th>
        <th class='w-100px'><?php common::printOrderLink('begin',  $orderBy, $vars, $lang->project->begin);?></th>
        <th class='w-100px'><?php common::printOrderLink('end',    $orderBy, $vars, $lang->project->end);?></th>
        <th class='text-center w-180px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody id='projectTableList'>
      <?php $originOrders = array(); ?>
      <?php foreach($projects as $project):?>
      <?php
      $trClass = '';
      $trAttrs = "data-id='$project->id' data-order='$project->order' data-parent='$project->parent'";
      if($project->type == 'project')
      {
          $trAttrs .= " data-nested='true'";
          $trClass .= $project->parent == '0' ? ' is-top-level table-nest-child-hide' : ' table-nest-hide';
      }

      if($project->parent and isset($projects[$project->parent]))
      {
          if($project->type != 'project') $trClass .= ' is-nest-child';
          if(empty($project->path)) $project->path = $projects[$project->parent]->path . "$project->id,";
          $trClass .= ' table-nest-hide';
          $trAttrs .= " data-nest-parent='$project->parent' data-nest-path='$project->path'";
      }
      elseif($project->type != 'project')
      {
          $trClass .= ' no-nest';
      }
      $trAttrs .= " class='$trClass'";
      $originOrders[] = $project->order;
      ?>

      <tr <?php echo $trAttrs;?>>
        <td class='c-name text-left <?php if($canOrder) echo 'sort-handler';?>' title='<?php echo $project->name?>'>
          <span class="table-nest-icon icon icon-<?php echo $project->type; ?>"></span>
          <?php if($project->type == 'project'):?>
          <?php echo html::a($this->createLink('project', 'pgmproduct', "projectID=$project->id"), $project->name);?>
          <?php else:?>
          <?php echo html::a($this->createLink('project', 'index', "projectID=$project->id", '', '', $project->id), $project->name);?>
          <?php endif;?>
        </td>
        <td>
          <?php $userID = isset($PMList[$project->PM]) ? $PMList[$project->PM]->id : ''?>
          <?php if(!empty($project->PM)) echo html::a($this->createLink('user', 'profile', "userID=$userID", '', true), zget($users, $project->PM), '', "data-toggle='modal' data-type='iframe' data-width='600'");?>
        </td>
        <?php $projectBudget = in_array($this->app->getClientLang(), ['zh-cn','zh-tw']) && $project->budget >= 10000 ? number_format($project->budget / 10000, 1) . $lang->project->tenThousand : number_format((float)$project->budget, 1);?>
        <td class='text-right'><?php echo $project->budget != 0 ? zget($lang->project->currencySymbol, $project->budgetUnit) . ' ' . $projectBudget : $lang->project->future;?></td>
        <td class='c-status'><span class="status-project status-<?php echo $project->status?>"><?php echo zget($lang->project->statusList, $project->status, '');?></span></td>
        <td><?php echo $project->begin;?></td>
        <td><?php echo $project->end == LONG_TIME ? $lang->project->PRJLongTime : $project->end;?></td>
        <td class='c-actions'>
          <?php if($project->type == 'project'):?>
          <?php if($project->status == 'wait' || $project->status == 'suspended') common::printIcon('project', 'PGMStart', "projectID=$project->id", $project, 'list', 'play', '', 'iframe', true, '', $this->lang->project->PGMStart);?>
          <?php if($project->status == 'doing')  common::printIcon('project', 'PGMClose',    "projectID=$project->id", $project, 'list', 'off',   '', 'iframe', true);?>
          <?php if($project->status == 'closed') common::printIcon('project', 'PGMActivate', "projectID=$project->id", $project, 'list', 'magic', '', 'iframe', true);?>

          <?php if(common::hasPriv('project', 'PGMSuspend') || (common::hasPriv('project', 'PGMClose') && $project->status != 'doing') || (common::hasPriv('project', 'PGMActivate') && $project->status != 'closed')):?>
          <div class='btn-group'>
            <button type='button' class='btn icon-caret-down dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>" style="width: 16px; padding-left: 0px;"></button>
            <ul class='dropdown-menu pull-right text-center' role='menu' style="min-width:auto; padding: 5px 10px;">
              <?php common::printIcon('project', 'PGMSuspend', "projectID=$project->id", $project, 'list', 'pause', '', 'iframe', true, '', $this->lang->project->PGMSuspend);?>
              <?php if($project->status != 'doing')  common::printIcon('project', 'PGMClose',    "projectID=$project->id", $project, 'list', 'off', '',   'iframe', true);?>
              <?php if($project->status != 'closed') common::printIcon('project', 'PGMActivate', "projectID=$project->id", $project, 'list', 'magic', '', 'iframe', true);?>
            </ul>
          </div>
          <?php endif;?>
          <?php common::printIcon('project', 'PGMEdit',   "projectID=$project->id", '', 'list', 'edit');?>
          <?php common::printIcon('project', 'PGMCreate', "projectID=$project->id", '', 'list', 'split', '', '', '', '', $this->lang->project->PGMChildren);?>
          <?php if(common::hasPriv('project', 'PGMDelete')) echo html::a($this->createLink("project", "PGMDelete", "projectID=$project->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn' title='{$this->lang->project->PGMDelete}'");?>

          <?php else:?>
          <?php if($project->status == 'wait' || $project->status == 'suspended') common::printIcon('project', 'PRJStart', "projectID=$project->id", $project, 'list', 'play', '', 'iframe', true);?>
          <?php if($project->status == 'doing')  common::printIcon('project', 'PRJClose',    "projectID=$project->id", $project, 'list', 'off', '',   'iframe', true);?>
          <?php if($project->status == 'closed') common::printIcon('project', 'PRJActivate', "projectID=$project->id", $project, 'list', 'magic', '', 'iframe', true);?>
          <?php if(common::hasPriv('project', 'PRJSuspend') || (common::hasPriv('project', 'PRJClose') && $project->status != 'doing') || (common::hasPriv('project', 'PRJActivate') && $project->status != 'closed')):?>
          <div class='btn-group'>
            <button type='button' class='btn icon-caret-down dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>" style="width: 16px; padding-left: 0px;"></button>
            <ul class='dropdown-menu pull-right text-center' role='menu' style="min-width:auto; padding: 5px 10px;">
              <?php common::printIcon('project', 'PRJSuspend', "projectID=$project->id", $project, 'list', 'pause', '', 'iframe', true);?>
              <?php if($project->status != 'doing')  common::printIcon('project', 'PRJClose',    "projectID=$project->id", $project, 'list', 'off',   '', 'iframe', true);?>
              <?php if($project->status != 'closed') common::printIcon('project', 'PRJActivate', "projectID=$project->id", $project, 'list', 'magic', '', 'iframe', true);?>
            </ul>
          </div>
          <?php endif;?>
          <?php common::printIcon('project', 'PRJEdit', "projectID=$project->id&from=pgmbrowse", $project, 'list', 'edit', '', '', '', "data-group='project'", '', $project->id);?>
          <?php common::printIcon('project', 'PRJManageMembers', "projectID=$project->id", $project, 'list', 'group', '', '', '', 'data-group="project"', '', $project->id);?>
          <?php common::printIcon('project', 'PRJGroup',         "projectID=$project->id", $project, 'list', 'lock', '', '', '', 'data-group="project"', '', $project->id);?>
          <?php if(common::hasPriv('project', 'PRJManageProducts') || common::hasPriv('project', 'PRJWhitelist') || common::hasPriv('project', 'PRJDelete')):?>
          <div class='btn-group'>
            <button type='button' class='btn dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>"><i class='icon-more-alt'></i></button>
            <ul class='dropdown-menu pull-right text-center' role='menu'>
              <?php common::printIcon('project', 'PRJManageProducts', "projectID=$project->id&projectID=0&from=pgmbrowse", $project, 'list', 'link', '', '', '', "data-group='project'", '', $project->id);?>
              <?php common::printIcon('project', 'PRJWhitelist',      "projectID=$project->id&projectID=0&module=project&from=pgmbrowse", $project, 'list', 'shield-check', '', '', '', "data-group='project'", '', $project->id);?>
              <?php if(common::hasPriv('project','PRJDelete')) echo html::a($this->createLink("project", "PRJDelete", "projectID=$project->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn' title='{$this->lang->project->PRJDelete}' data-group='project'");?>
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
#projectTableList.sortable-sorting > tr {opacity: 0.7}
#projectTableList.sortable-sorting > tr.drag-row {opacity: 1;}
#projectTableList > tr.drop-not-allowed {opacity: 0.1!important}
#projectList .c-actions {overflow: visible;}
#projectTableList > tr:last-child .c-actions .dropdown-menu {top: auto; bottom: 100%; margin-bottom: -5px;}
#projectTableList .icon-project:before, #projectTableList .no-nest .icon-project:before, #projectTableList .is-nest-child .icon-project:before {content: '\e99c'; width: 22px; height: 22px; background: none; color: #16a8f8; top: 0; line-height: 22px; margin-right: 2px; font-size: 14px}
#projectTableList .no-nest .icon-project:before, #projectTableList .is-nest-child .icon-project:before {content: '\e944'; color: #ffe066; font-size: 16px;}
</style>
<?php js::set('originOrders', $originOrders);?>
<script>
$(function()
{
    /* Init orders numbers list */
    var ordersList = [];
    for(var i = 0; i < originOrders.length; ++i) ordersList.push(parseInt(originOrders[i]));
    ordersList.sort(function(x, y){return x - y;});

    var $list = $('#projectTableList');
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
            $.post(createLink('project', 'PRJUpdateOrder'), {'projects' : projects, 'orderBy' : orderBy});

            var $thead = $list.closest('table').children('thead');
            $thead.find('.headerSortDown, .headerSortUp').removeClass('headerSortDown headerSortUp').addClass('header');
            $thead.find('th.sort-default .header').removeClass('header').addClass('headerSortDown');

            e.element.addClass('drop-success');
            setTimeout(function(){e.element.removeClass('drop-success');}, 800);
            $list.children('.drop-not-allowed').removeClass('drop-not-allowed');
            $('#projectForm').table('initNestedList')
        }
    });
});
</script>
