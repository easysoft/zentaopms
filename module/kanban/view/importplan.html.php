<?php
/**
 * The import plan view of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Qiyu Xie<xieqiyu@cnezsoft.com>
 * @package     kanban
 * @version     $Id: importplan.html.php 5090 2022-01-19 14:19:24Z xieqiyu@cnezsoft.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('kanbanID', $kanbanID);?>
<?php js::set('regionID', $regionID);?>
<?php js::set('groupID', $groupID);?>
<?php js::set('columnID', $columnID);?>
<?php js::set('methodName', $this->app->rawMethod);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanban->importPlan;?></h2>
    </div>
  </div>
  <div class='table-row p-10px'>
    <div class='table-col w-150px text-center'><h4><?php echo $lang->kanban->selectedProduct;?></h4></div>
    <div class='table-col'><?php echo html::select('product', $products, $selectedProductID, "onchange='reloadObjectList(this.value)' class='form-control chosen'");?></div>
  </div>
  <div class='table-row p-10px'>
    <div class='table-col w-150px text-center'><h4><?php echo $lang->kanban->selectedLane;?></h4></div>
    <div class='table-col'><?php echo html::select('lane', $lanePairs, '', "onchange='setTargetLane(this.value)' class='form-control chosen'");?></div>
  </div>
  <form class='main-table' method='post' data-ride='table' target='hiddenwin' id='importPlanForm'>
    <table class='table table-fixed' id='planList'>
      <thead>
        <tr>
          <th class="c-id">
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-name'><?php echo $lang->productplan->title;?></th>
          <th class='c-date'><?php echo $lang->productplan->begin;?></th>
          <th class='c-date'><?php echo $lang->productplan->end;?></th>
          <th class='w-90px'><?php echo $lang->productplan->stories;?></th>
          <th class='w-80px'><?php echo $lang->productplan->bugs;?></th>
          <th class='c-hour'><?php echo $lang->productplan->hour;?></th>
          <th><?php echo $lang->productplan->desc;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($plans2Imported as $plan):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='productplans[]' value='<?php echo $plan->id;?>'/>
              <label></label>
            </div>
            <?php printf('%03d', $plan->id);?>
          </td>
          <?php if(common::hasPriv('productplan', 'view')):?>
          <td title='<?php echo $plan->title;?>'><?php common::printLink('productplan', 'view', "planID=$plan->id", $plan->title, '', "class='iframe'", true, true);?></td>
          <?php else:?>
          <td title='<?php echo $plan->title;?>'><?php echo $plan->title;?></td>
          <?php endif;?>
          <td title='<?php echo $plan->begin;?>'><?php echo $plan->begin;?></td>
          <td title='<?php echo $plan->end;?>'><?php echo $plan->end;?></td>
          <td title='<?php echo $plan->stories;?>'><?php echo $plan->stories;?></td>
          <td title='<?php echo $plan->bugs;?>'><?php echo $plan->bugs;?></td>
          <td title='<?php echo $plan->hour;?>'><?php echo $plan->hour;?></td>
          <td title='<?php echo $plan->desc;?>'><?php echo $plan->desc;?></td>
        </tr>
        <?php endforeach;?>
        <tr><?php echo html::hidden('targetLane', key($lanePairs));?></tr>
      </tbody>
    </table>
    <?php if($plans2Imported):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar show-always"><?php echo html::submitButton($lang->kanban->importPlan, '', 'btn btn-default');?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
