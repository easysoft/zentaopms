<?php
/**
 * The show view file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php js::set('groupID', isset($group->id) ? $group->id : 0);?>
<?php js::set('pivotID', isset($pivot->id) ? $pivot->id : 0);?>
<?php js::set('pivot'  , $pivot);?>
<?php js::set('WIDTH_INPUT',  $config->pivot->widthInput);?>
<?php js::set('WIDTH_DATE',   $config->pivot->widthDate);?>
<?php js::set('pickerHeight', $config->bi->pickerHeight);?>
<?php js::set('datepickerText', $this->lang->datepicker->dpText);?>
<?php js::set('confirmDesign', $lang->pivot->confirm->design);?>
<?php $queryDom = "<div class='queryBtn query-inside hidden'> <button type='submit' id='submit' class='btn btn-primary btn-query' data-loading='Loading...'>{$lang->pivot->query}</button></div>";?>
<?php js::set('queryDom', $queryDom);?>

<div class='cell'>
  <?php if(!$pivotTree):?>
  <hr class="space">
  <div class="text-center text-muted">
    <?php echo $lang->pivot->noPivot;?>
  </div>
  <hr class="space">
  <?php else:?>
  <div class='panel-heading heading-padding content-border flex-between'>
    <div class='panel-title'>
      <?php echo $pivot->name;?>
      <?php if(!empty($pivot->desc)):?>
      <a data-toggle='tooltip' data-placement='auto' title='<?php echo $pivot->desc;?>'><i class='icon-help'></i></a>
      <?php endif;?>
    </div>
    <div class='flex-start'>
      <?php $clickable = $this->pivot->isClickable($pivot, 'design');?>
      <?php $showLine  = ($clickable and (common::hasPriv('pivot', 'design') or common::hasPriv('pivot', 'edit') or common::hasPriv('pivot', 'delete'))) ? 'query-pseudo' : 'query-after';?>
      <?php echo html::a('#', "{$lang->pivot->showOrigin}", '', "id='origin-query' class='$showLine btn-query-origin' title='{$lang->pivot->showOrigin}'");?>
      <?php echo html::a('#', "{$lang->pivot->showPivot}",  '', "id='pivot-query'  class='$showLine btn-query hidden' title='{$lang->pivot->showPivot}'");?>

      <?php
        $params    = helper::safe64Encode("dimensionID=$pivot->dimension&groupID=$pivot->group&pivotID=$pivot->id");
        $class     = !empty($pivot->used) ? "btn-design" : '';

        if(common::hasPriv('pivot', 'design') and $clickable) echo html::a($this->createLink('pivot', 'design', "id=$pivot->id&from=preview&param=$params"), "<i class='icon icon-design'></i> {$lang->pivot->designAB}", '', "class='pivot-design $class' title='{$lang->pivot->designAB}'");
        if(common::hasPriv('pivot', 'edit') and $clickable) echo html::a($this->createLink('pivot', 'edit', "id=$pivot->id&from=preview&params=$params", '', true), "<i class='icon icon-edit'></i> {$lang->edit}", '', "class='iframe pivot-edit' title='{$lang->edit}' data-width='600'");
        if(common::hasPriv('pivot', 'delete') and $clickable) echo html::a($this->createLink('pivot', 'delete', "id=$pivot->id&confirm=no&from=preview"), "<i class='icon icon-trash'></i> {$lang->delete}", 'hiddenwin', "class='pivot-delete' title='{$lang->delete}'");
      ?>
    </div>
  </div>
  <div class='panel-body' style='padding:0px;'>
    <div id="filterItems" class='filterBox'>
      <div class='filter-items'></div>
      <?php if(!empty($pivot->filters)):?>
      <div class='queryBtn query-outside visibility-hidden'><?php echo html::submitButton($lang->pivot->query, "", 'btn btn-primary btn-query');?></div>
      <?php endif;?>
    </div>
    <div id="filterMargin" class='hidden'></div>
    <div id='datagirdInfo' class='datagrid datagrid-padding'>
      <?php $sql = $this->loadModel('chart')->parseSqlVars($pivot->sql, $pivot->filters);?>
      <?php $this->pivot->buildPivotTable($data, $configs, json_decode(json_encode($pivot->fieldSettings), true), $sql);?>
    </div>
  </div>
  <?php endif;?>
</div>

<template id='queryFilterItemTpl'>
  <div class='filter-item filter-item-{index} input-group' data-index='{index}'>
    <span class='field-name input-group-addon'>{name}</span>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-input '")?></div>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-date '")?></div>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-datetime '")?></div>
    <div class="default-block hidden"><?php echo html::select('default', '', '', "class='form-control form-select multiple'");?></div>
  </div>
</template>

<template id='resultFilterItemTpl'>
  <div class='filter-item filter-item-{index} input-group' data-index='{index}'>
    <span class='field-name input-group-addon'>{name}</span>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-input '")?></div>
    <div class="default-block hidden">
      <div class="input-group">
        <?php echo html::input('default[begin]', '', "class='form-control form-date begin' placeholder='{$this->lang->pivot->unlimited}'");?>
        <span class='input-group-addon fix-border borderBox' style='border-radius: 0px;'><?php echo $lang->pivot->colon;?></span>
        <?php echo html::input('default[end]', '', "class='form-control form-date end' placeholder='{$this->lang->pivot->unlimited}'");?>
      </div>
    </div>
    <div class="default-block hidden">
      <div class="input-group">
        <?php echo html::input('default[begin]', '', "class='form-control form-datetime begin' placeholder='{$this->lang->pivot->unlimited}'");?>
        <span class='input-group-addon fix-border borderBox' style='border-radius: 0px;'><?php echo $lang->pivot->colon;?></span>
        <?php echo html::input('default[end]', '', "class='form-control form-datetime end' placeholder='{$this->lang->pivot->unlimited}'");?>
      </div>
    </div>
    <div class="default-block hidden"><?php echo html::select('default', '', '', "class='form-control form-select multiple'");?></div>
  </div>
</template>
<script>
$(function()
{
    $('.btn-design').click(function()
    {
        var result = confirm(confirmDesign);
        if(!result) return false;
    });
});
</script>
