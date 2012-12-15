<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sparkline.html.php';?>
<div class='block' id='productbox'>
<?php if(empty($productStats)):?>
<table class='table-1 a-center' height='100%'>
  <caption><?php echo $lang->my->home->products;?></caption>
  <tr>
    <td valign='middle'>
    <?php 
    $productLink = $this->createLink('product', 'create');
    printf($lang->my->home->noProductsTip, $productLink);
    ?>
    </td>
  </tr>
</table>
<?php else:?>
  <table class='table-1 colored fixed'>
    <tr class='colhead'>
      <th class='w-150px'><?php echo $lang->product->name;?></th>
      <th><?php echo $lang->story->statusList['active']  . $lang->story->common;?></th>
      <th><?php echo $lang->story->statusList['changed'] . $lang->story->common;?></th>
      <th><?php echo $lang->story->statusList['draft']   . $lang->story->common;?></th>
      <th><?php echo $lang->story->statusList['closed']  . $lang->story->common;?></th>
      <th><?php echo $lang->product->plans;?></th>
      <th><?php echo $lang->product->releases;?></th>
      <th><?php echo $lang->product->bugs;?></th>
      <th><?php echo $lang->bug->unResolved;?></th>
      <th><?php echo $lang->bug->assignToNull;?></th>
    </tr>
    <?php foreach($productStats as $product):?>
    <tr class='a-center' style='height:30px'>
      <td class='a-left'><?php echo html::a($this->createLink('product', 'view', 'product=' . $product->id), $product->name);?></td>
      <td><?php echo $product->stories['active']?></td>
      <td><?php echo $product->stories['changed']?></td>
      <td><?php echo $product->stories['draft']?></td>
      <td><?php echo $product->stories['closed']?></td>
      <td><?php echo $product->plans?></td>
      <td><?php echo $product->releases?></td>
      <td><?php echo $product->bugs?></td>
      <td><?php echo $product->unResolved;?></td>
      <td><?php echo $product->assignToNull;?></td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
<?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
