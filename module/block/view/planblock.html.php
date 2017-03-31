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
<table class='table tablesorter table-data table-hover block-plan table-fixed'>
  <thead>
  <tr>
    <th width='50'><?php echo $lang->idAB?></th>
    <th>           <?php echo $lang->productplan->product;?></th>
    <th>           <?php echo $lang->productplan->title;?></th>
    <th width='80'><?php echo $lang->productplan->begin;?></th>
    <th width='80'><?php echo $lang->productplan->end;?></th>
  </tr>
  </thead>
  <?php foreach($plans as $plan):?>
  <?php
  $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
  $viewLink = $this->createLink('productplan', 'view', "planID={$plan->id}");
  ?>
  <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
    <td class='text-center'><?php echo $plan->id;?></td>
    <td title='<?php echo $plan->productName?>'><?php echo $plan->productName?></td>
    <td title='<?php echo $plan->title?>'><?php echo $plan->title?></td>
    <td><?php echo $plan->begin?></td>
    <td><?php echo $plan->end?></td>
  </tr>
  <?php endforeach;?>
</table>
<script>
if(typeof(dataTable) == 'function')$('.block-plan').dataTable();
</script>
