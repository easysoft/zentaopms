<?php
/**
 * The import plan view of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
<?php if(count($plans2Imported) <= 3):?>
<style>#importPlanForm, .table-empty-tip {margin-bottom: 120px}</style>
<?php endif;?>
<div id='mainContent' class='main-content importModal'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanban->importAB . $lang->kanban->importPlan;?></h2>
    </div>
  </div>
  <div class='input-group space'>
    <span class='input-group-addon'><?php echo $lang->kanban->selectedProduct;?></span>
    <?php echo html::select('product', $products, $selectedProductID, "onchange='reloadObjectList(this.value)' class='form-control chosen' data-drop_direction='down'");?>
    <span class='input-group-addon'><?php echo $lang->kanban->selectedLane;?></span>
    <?php echo html::select('lane', $lanePairs, '', "onchange='setTargetLane(this.value)' class='form-control chosen' data-drop_direction='down'");?>
  </div>
  <?php if($plans2Imported):?>
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
          <th class='c-story'><?php echo $lang->productplan->stories;?></th>
          <th class='c-bug'><?php echo $lang->productplan->bugs;?></th>
          <th class='c-hour'><?php echo $lang->productplan->hour;?></th>
          <th class='c-name'><?php echo $lang->productplan->desc;?></th>
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
          <td title='<?php echo $plan->title;?>'>
            <a href='javascript:void(0);' onclick="locateView('productplan', <?php echo $plan->id;?>)"><?php echo $plan->title;?></a>
          </td>
          <?php else:?>
          <td title='<?php echo $plan->title;?>'><?php echo $plan->title;?></td>
          <?php endif;?>
          <td><?php echo $plan->begin == $config->productplan->future ? $lang->productplan->future : $plan->begin;?></td>
          <td><?php echo $plan->end == $config->productplan->future ? $lang->productplan->future : $plan->end;?></td>
          <td title='<?php echo $plan->stories;?>'><?php echo $plan->stories;?></td>
          <td title='<?php echo $plan->bugs;?>'><?php echo $plan->bugs;?></td>
          <td title='<?php echo $plan->hour;?>'><?php echo $plan->hour;?></td>
          <td class='c-name' title='<?php echo strip_tags(htmlspecialchars_decode($plan->desc));?>'><?php echo strip_tags(htmlspecialchars_decode($plan->desc));?></td>
        </tr>
        <?php endforeach;?>
        <tr><?php echo html::hidden('targetLane', key($lanePairs));?></tr>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar show-always"><?php echo html::submitButton($lang->kanban->importAB, '', 'btn btn-default');?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php else:?>
  <div class='table-empty-tip'><?php echo $lang->noData;?></div>
  <?php endif;?>
</div>
<style>#product_chosen {width: 45% !important}</style>
<?php include '../../common/view/footer.lite.html.php';?>
