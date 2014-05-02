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
<div id='titlebar'>
  <div class='heading'><i class='icon-flag'></i> <?php echo $lang->productplan->browse;?>  </div>
  <div class='actions'>
    <?php common::printIcon('productplan', 'create', "productID=$product->id", '', 'button', 'plus');?>
  </div>
</div>
<table class='table' id="productplan">
  <thead>
  <?php $vars = "productID=$productID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
  <tr class='colhead'>
    <th class='w-id'>    <?php common::printOrderLink('id',    $orderBy, $vars, $lang->idAB);?></th>
    <th>                 <?php common::printOrderLink('title', $orderBy, $vars, $lang->productplan->title);?></th>
    <th class='w-p50'>   <?php common::printOrderLink('desc',  $orderBy, $vars, $lang->productplan->desc);?></th>
    <th class='w-100px'> <?php common::printOrderLink('begin', $orderBy, $vars, $lang->productplan->begin);?></th>
    <th class='w-100px'> <?php common::printOrderLink('end',   $orderBy, $vars, $lang->productplan->end);?></th>
    <th class="w-100px {sorter: false}"><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($plans as $plan):?>
  <tr class='text-center'>
    <td><?php echo $plan->id;?></td>
    <td class='text-left' title="<?php echo $plan->title?>"><?php echo html::a(inlink('view', "id=$plan->id"), $plan->title);?></td>
    <td class='text-left content'><div class='article-content'><?php echo $plan->desc;?></div></td>
    <td><?php echo $plan->begin;?></td>
    <td><?php echo $plan->end;?></td>
    <td class='text-center'>
      <?php
      common::printIcon('productplan', 'linkStory', "planID=$plan->id", '', 'list', 'link');
      common::printIcon('productplan', 'linkBug', "planID=$plan->id", '', 'list', 'bug');
      common::printIcon('productplan', 'edit', "planID=$plan->id", '', 'list');

      if(common::hasPriv('productplan', 'delete'))
      {
          $deleteURL = $this->createLink('productplan', 'delete', "planID=$plan->id&confirm=yes");
          echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"productplan\",confirmDelete)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->productplan->delete}'");
      }
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='6'><?php $pager->show();?></td></tr></tfoot>
</table>
<?php include '../../common/view/footer.html.php';?>
