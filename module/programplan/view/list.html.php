<?php
/**
 * The list of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: list.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/datatable.fix.html.php';
js::set('confirmDelete', $lang->programplan->confirmDelete);
?>
<style>
#tableCustomBtn{display: none;}

.table-children {border-left: 2px solid #cbd0db; border-right: 2px solid #cbd0db;}
.table tbody > tr.table-children.table-child-top {border-top: 2px solid #cbd0db;}
.table tbody > tr.table-children.table-child-bottom {border-bottom: 2px solid #cbd0db;}
.table td.has-child > a:not(.plan-toggle) {max-width: 90%; max-width: calc(100% - 30px); display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
.table td.has-child > .plan-toggle {color: #838a9d; position: relative; top: 1px;}
.table td.has-child > .plan-toggle:hover {color: #006af1; cursor: pointer;}
.table td.has-child > .plan-toggle > .icon {font-size: 16px; display: inline-block; transition: transform .2s; -ms-transform:rotate(-90deg); -moz-transform:rotate(-90deg); -o-transform:rotate(-90deg); -webkit-transform:rotate(-90deg); transform: rotate(-90deg);}
.table td.has-child > .plan-toggle > .icon:before {text-align: left;}
.table td.has-child > .plan-toggle.collapsed > .icon {-ms-transform:rotate(90deg); -moz-transform:rotate(90deg); -o-transform:rotate(90deg); -webkit-transform:rotate(90deg); transform: rotate(90deg);}
.main-table tbody > tr.table-children > td:first-child::before {width: 3px;}
@-moz-document url-prefix() {.main-table tbody > tr.table-children > td:first-child::before {width: 4px;}}
</style>
<form class='main-table table-programplan' id='programplanForm' method='post'>
  <div class="table-header fixed-right">
    <nav class="btn-toolbar pull-right"></nav>
  </div>
  <?php
  $vars    = "projectID=$projectID&productID=$productID&type=lists&orderBy=%s";
  $setting = $this->datatable->getSetting('programplan');
  $widths  = $this->datatable->setFixedFieldWidth($setting);
  $widths['leftWidth']  = 300;
  $columns = 0;
  ?>
  <table class='table has-sort-head' id='programplanList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>' data-checkbox-name='programplanList[]'>
    <thead>
      <tr>
        <th class='w-50px'><?php common::printOrderLink('id',        $orderBy, $vars, $lang->idAB);?></th>
        <th>               <?php common::printOrderLink('name',      $orderBy, $vars, $lang->programplan->name);?></th>
        <th class='w-120px'><?php common::printOrderLink('percent',   $orderBy, $vars, $lang->programplan->percent);?></th>
        <th class='w-80px'><?php common::printOrderLink('attribute', $orderBy, $vars, $lang->programplan->attribute);?></th>
        <th class='w-90px'><?php common::printOrderLink('begin',     $orderBy, $vars, $lang->programplan->begin);?></th>
        <th class='w-90px'><?php common::printOrderLink('end',       $orderBy, $vars, $lang->programplan->end);?></th>
        <th class='w-100px'><?php common::printOrderLink('realBegan', $orderBy, $vars, $lang->programplan->realBegan);?></th>
        <th class='w-90px'><?php common::printOrderLink('realEnd',   $orderBy, $vars, $lang->programplan->realEnd);?></th>
        <th class='w-150px text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($stages as $plan):?>
      <tr data-id='<?php echo $plan->id?>'>
        <?php foreach($setting as $key => $value) $this->programplan->printCell($value, $plan, $users, $projectID);?>
      </tr>
      <?php if(!empty($plan->children)):?>
      <?php $i = 0;?>
      <?php foreach($plan->children as $key => $child):?>
      <?php $class  = $i == 0 ? ' table-child-top' : '';?>
      <?php $class .= ($i + 1 == count($plan->children)) ? ' table-child-bottom' : '';?>
      <tr class='table-children<?php echo $class;?> parent-<?php echo $plan->id;?>' data-id='<?php echo $child->id?>'>
        <?php foreach($setting as $key => $value) $this->programplan->printCell($value, $child, $users, $projectID);?>
      </tr>
      <?php $i ++;?>
      <?php endforeach;?>
      <?php endif;?>
      <?php endforeach;?>
    </tbody>
  </table>
</form>
<script>
$(function(){$('#programplanForm').table();})
$(document).on('click', '.plan-toggle', function(e)
{
    var $toggle = $(this);
    var id      = $(this).data('id');
    var isCollapsed = $toggle.toggleClass('collapsed').hasClass('collapsed');
    $toggle.closest('[data-ride="table"]').find('tr.parent-' + id).toggle(!isCollapsed);

    e.stopPropagation();
    e.preventDefault();
});
</script>
