<?php
/**
 * The plan block view file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<?php if(empty($plans)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed-head tablesorter block-plan'>
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
      $viewLink = $this->createLink('productplan', 'view', "planID={$plan->id}");
      ?>
      <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
        <?php if($longBlock):?>
        <td class='text-center'><?php echo $plan->id;?></td>
        <td title='<?php echo $plan->productName?>'><?php echo $plan->productName?></td>
        <?php endif;?>
        <td title='<?php echo $plan->title?>'><?php echo $plan->title?></td>
        <td class='text-center'><?php echo $plan->begin?></td>
        <td class='text-center'><?php echo $plan->end?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
