<?php
/**
 * The viewarchivedcolumn file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     kanban
 * @version     $Id: viewarchivedcolumn.html.php 935 2021-12-22 10:49:24Z $
 * @link        https://www.zentao.net
 */
?>
<style>
#archivedColumns {top: 50px; right: -400px; width: 400px; position: fixed; z-index: 1050; background-color: rgb(255,255,255);}
#archivedColumns .panel .panel-body {overflow: auto; padding: 0px 20px;}
.hr {border-bottom: 1px solid #ddd;}
#archivedColumns .parent-item,#archivedColumns .child-item {padding: 20px 0px; line-height: 26px; overflow: hidden;}
#archivedColumns .child-item {padding: 5px 0px;}
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
      <div class="item parent-item hr" data-archived='<?php echo $column->archived;?>' data-id="<?php echo $column->id;?>">
        <div class="title text-ellipsis"><?php echo $column->name;?></div>
        <?php if(commonModel::hasPriv('kanban', 'restoreColumn') && $column->archived == '1' && !(isset($this->config->CRKanban) and $this->config->CRKanban == '0' and $kanban->status == 'closed')) echo html::a(inlink('restoreColumn', "columnID={$column->id}"), $lang->kanban->restore, '', "class='btn btn-primary' target='hiddenwin'");?>
      </div>
      <?php if(!empty($column->child) && $column->archived == '0'):?>
      <?php $count = 1;?>
      <?php foreach($column->child as $childColumn):?>
        <?php $class = $count == count($column->child) ? 'hr' : '';?>
        <div class="item child-item <?php echo $class;?>" data-id="<?php echo $childColumn->id;?>" data-archived='<?php echo $childColumn->archived;?>'>
          <div class="title text-ellipsis"><label class="label label-child"><?php echo $lang->kanban->child;?></label><?php echo $childColumn->name;?></div>
          <?php if(commonModel::hasPriv('kanban', 'restoreColumn') && $childColumn->archived == '1' && !(isset($this->config->CRKanban) and $this->config->CRKanban == '0' and $kanban->status == 'closed')) echo html::a(inlink('restoreColumn', "columnID={$childColumn->id}"), $lang->kanban->restore, '', "class='btn btn-primary' target='hiddenwin'");?>
      </div>
      <?php $count ++;?>
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
