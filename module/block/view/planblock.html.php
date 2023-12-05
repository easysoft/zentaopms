<?php
/**
 * The plan block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(empty($plans)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter block-plan'>
    <thead>
    <tr>
      <?php if($longBlock):?>
      <th class='text-center w-50px'><?php echo $lang->idAB?></th>
      <th><?php echo $lang->productplan->product;?></th>
      <?php endif;?>
      <th><?php echo $lang->productplan->title;?></th>
      <th class='text-center w-100px'><?php echo $lang->productplan->begin;?></th>
      <th class='text-center w-100px'><?php echo $lang->productplan->end;?></th>
    </tr>
    </thead>
    <tbody>
      <?php foreach($plans as $plan):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      $planViewLink    = $this->createLink('productplan', 'view', "planID={$plan->id}");
      $productViewLink = $this->createLink('product', 'view', "productID={$plan->product}");
      ?>
      <tr <?php echo $appid?>>
        <?php if($longBlock):?>
        <td class='text-center'><?php echo $plan->id;?></td>
        <td title='<?php echo $plan->productName?>'><?php echo html::a($productViewLink, $plan->productName);?></td>
        <?php endif;?>
        <td title='<?php echo $plan->title?>'><?php echo html::a($planViewLink, $plan->title);?></td>
        <td class='text-center'><?php echo $plan->begin?></td>
        <td class='text-center'><?php echo $plan->end?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
