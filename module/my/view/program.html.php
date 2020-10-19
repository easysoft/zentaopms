<?php
/**
 * The program view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: program.html.php 5095 2013-07-11 06:03:40Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('orderBy', $orderBy);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->my->myProgram;?></span></span>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <?php if(empty($programs)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->program->noPRJ;?></span>
      <?php if(common::hasPriv('program', 'createGuide')):?>
      <?php echo html::a($this->createLink('program', 'createGuide'), "<i class='icon icon-plus'></i> " . $lang->my->createProgram, '', "class='btn btn-info' data-toggle=modal");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
    <?php $canOrder = (common::hasPriv('program', 'updateOrder') and strpos($orderBy, 'order') !== false)?>
    <form class='main-table' id='programForm' method='post' data-ride='table' data-nested='true' data-expand-nest-child='false' data-checkable='false'>
      <table class='table has-sort-head table-fixed table-nested' id='programList'>
        <?php $vars = "status=$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <thead>
          <tr>
            <th class='c-id w-80px'>
              <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
            </th>
            <th class='w-100px'><?php common::printOrderLink('code', $orderBy, $vars, $lang->program->PRJCode);?></th>
            <th class='table-nest-title'><?php common::printOrderLink('name', $orderBy, $vars, $lang->program->PRJName);?></th>
            <th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->program->PRJStatus);?></th>
            <th class='w-100px'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->program->begin);?></th>
            <th class='w-100px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->program->end);?></th>
            <th class='w-100px'><?php common::printOrderLink('budget', $orderBy, $vars, $lang->program->PRJBudget);?></th>
            <th class='w-100px'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->program->PRJPM);?></th>
            <th class='text-center w-240px'><?php echo $lang->actions;?></th>
            <?php if($canOrder):?>
            <th class='w-60px sort-default'><?php common::printOrderLink('order', $orderBy, $vars, $lang->project->orderAB);?></th>
            <?php endif;?>
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
              <?php common::printIcon('program', 'create', "template=&programID=$program->id", '', 'list', 'treemap-alt', '', '', '', '', $this->lang->program->PRJChildren);?>
              <?php if(common::hasPriv('program', 'delete')) echo html::a($this->createLink("program", "delete", "programID=$program->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn' title='{$lang->delete}'");?>
            </td>
            <?php if($canOrder):?>
              <td class='sort-handler text-center'><i class="icon icon-move"></i></td>
            <?php endif;?>
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
    <script>
    $(function()
    {
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
                var orders = {};
                e.list.each(function()
                {
                    orders[$(this.item).data('id')] = this.order;
                });
                $.post(createLink('project', 'updateOrder'), {'projects' : orders, 'orderBy' : orderBy});

                var $thead = $list.closest('table').children('thead');
                $thead.find('.headerSortDown, .headerSortUp').removeClass('headerSortDown headerSortUp').addClass('header');
                $thead.find('th.sort-default .header').removeClass('header').addClass('headerSortDown');

                e.element.addClass('drop-success');
                setTimeout(function(){e.element.removeClass('drop-success');}, 800);
                $list.children('.drop-not-allowed').removeClass('drop-not-allowed');
            }
        });
    });
    </script>
  <?php endif;?>
</div>

<?php include '../../common/view/footer.html.php';?>
