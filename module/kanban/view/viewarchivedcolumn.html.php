<?php
/**
 * The more columns view file of kanban module of ZDOO.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     kanban
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<style>
#archivedColumns {top: 50px; right: -400px; width: 400px; position: fixed; z-index: 1050; background-color: rgb(255,255,255);}
#archivedColumns .panel .panel-body {overflow: auto; padding: 0px 20px;}
#archivedColumns .parent-item,#archivedColumns .child-item {padding: 20px 0px; border-bottom: 1px solid #ddd; line-height: 26px; overflow: hidden;}
#archivedColumns .title {width: 250px; float: left;}
#archivedColumns .label-child {border-radius: 12px; background-color: #DDDDDD; color: #333; margin-right: 10px;}
#archivedColumns .btn {float: right; line-height: 26px; height: 26px; padding: 0 20px;}
</style>
<div class='panel'>
  <div class='panel-heading text-center'>
    <strong><?php echo $lang->kanban->archivedColumn;?></strong>
    <button type="button" class="close" aria-hidden="true">×</button>
  </div>
  <div class='panel-body'>
  <?php if(empty($columns)):?>
    <div class="table-empty-tip">
      <p><span class="text-muted"><?php echo $lang->kanbancolumn->empty;?></span></p>
    </div>
    <?php else:?>
    <?php foreach($columns as $column):?>
    <div class="item-body">
      <div class="item parent-item" data-archived='<?php echo $column->archived;?>' data-id="<?php echo $column->id;?>">
        <div class="title text-ellipsis"><?php echo $column->name;?></div>
        <?php if(commonModel::hasPriv('kanban', 'restoreColumn') && $column->archived == '1') echo html::a(inlink('restoreColumn', "columnID={$column->id}", '',true), $lang->kanban->restore, '', "class='btn btn-primary' target='hiddenwin'");?>
      </div>
      <?php if(!empty($column->child) && $column->archived == '0'):?>
      <?php foreach($column->child as $childColumn):?>
        <div class="item child-item" data-id="<?php echo $childColumn->id;?>" data-archived='<?php echo $childColumn->archived;?>'>
          <div class="title text-ellipsis"><label class="label label-child"><?php echo $lang->kanban->child;?></label><?php echo $childColumn->name;?></div>
          <?php if(commonModel::hasPriv('kanban', 'restoreColumn') && $childColumn->archived == '1') echo html::a(inlink('restoreColumn', "columnID={$childColumn->id}", '',true), $lang->kanban->restore, 'hiddenwin', "class='btn btn-primary");?>
      <?php endforeach;?>
      <?php endif;?>
    </div>
    <?php endforeach;?>
    <?php endif;?>
  </div>
</div>
<script>
$(function()
{
    $('#archivedColumns .panel .close').click(function()
    {
        $('#archivedColumns').animate({right: -400}, 500);
    });
})
</script>
