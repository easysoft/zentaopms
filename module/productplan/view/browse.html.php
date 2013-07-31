<?php
/**
 * The browse view file of plan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     plan
 * @version     $Id: browse.html.php 4707 2013-05-02 06:57:41Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->productplan->confirmDelete)?>
<table class='table-1 tablesorter fixed' id="productplan">
  <caption class='caption-tr'>
    <div class='f-left'><?php echo $lang->productplan->browse;?></div>
    <div class='f-right'><?php common::printIcon('productplan', 'create', "productID=$product->id");?></div>
  </caption>
  <thead>
  <?php $vars = "productID=$productID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
  <tr class='colhead'>
    <th class='w-id'>    <?php common::printOrderLink('id',    $orderBy, $vars, $lang->idAB);?></th>
    <th>                 <?php common::printOrderLink('title', $orderBy, $vars, $lang->productplan->title);?></th>
    <th class='w-p50'>   <?php common::printOrderLink('desc',  $orderBy, $vars, $lang->productplan->desc);?></th>
    <th class='w-100px'> <?php common::printOrderLink('begin', $orderBy, $vars, $lang->productplan->begin);?></th>
    <th class='w-100px'> <?php common::printOrderLink('end',   $orderBy, $vars, $lang->productplan->end);?></th>
    <th class="w-80px {sorter: false}"><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($plans as $plan):?>
  <tr class='a-center'>
    <td><?php echo $plan->id;?></td>
    <td class='a-left' title="<?php echo $plan->title?>"><?php echo html::a(inlink('view', "id=$plan->id"), $plan->title);?></td>
    <td class='a-left content' title="<?php echo $plan->desc?>"><?php echo $plan->desc;?></td>
    <td><?php echo $plan->begin;?></td>
    <td><?php echo $plan->end;?></td>
    <td class='a-center'>
      <?php
      common::printIcon('productplan', 'linkStory', "planID=$plan->id", '', 'list');
      common::printIcon('productplan', 'linkBug', "planID=$plan->id", '', 'list');
      common::printIcon('productplan', 'edit', "planID=$plan->id", '', 'list');

      $deleteURL = $this->createLink('productplan', 'delete', "planID=$plan->id&confirm=yes");
      echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"productplan\",confirmDelete)", '&nbsp;', '', "class='icon-green-common-delete' title='{$lang->productplan->delete}'");
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='6'><?php $pager->show();?></td></tr></tfoot>
</table>
<?php include '../../common/view/footer.html.php';?>
