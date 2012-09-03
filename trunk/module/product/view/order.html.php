<?php
/**
 * The order view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin'>
<table align='center' class='table-5'>
  <tr class='colhead'>
    <th class='w-80px'><?php echo $lang->product->id?></th>
    <th><?php echo $lang->product->name?></th>
    <th class='w-80px'><?php echo $lang->product->order?></th>
  </tr>
  <?php foreach($products as $product):?>
  <tr class='a-center'>
    <td><?php echo $product->id?></td>
    <td class='a-left'><?php echo $product->name?></td>
    <td><?php echo html::input($product->id, $product->order, "size='5'")?></td>
  </tr>
  <?php endforeach;?>
  <tr><td colspan='3' align='center'><?php echo html::submitButton() . html::resetButton()?></td></tr>
</table>
</form>
<?php include '../../common/view/footer.html.php';?>

