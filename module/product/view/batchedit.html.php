<?php
/**
 * The batch edit file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<form method='post' target='hiddenwin' action='<?php echo inLink('batchEdit');?>'>
  <table class='table-1 colored fixed'>
    <tr class='colhead'>
      <th class='w-id'>   <?php echo $lang->idAB;?></th>
      <th class='red'>    <?php echo $lang->product->name;?></th>
      <th class='w-150px red'><?php echo $lang->product->code;?></th>
      <th class='w-100px'><?php echo $lang->product->PO;?></th>
      <th class='w-100px'><?php echo $lang->product->QD;?></th>
      <th class='w-100px'><?php echo $lang->product->RD;?></th>
      <th class='w-100px'><?php echo $lang->product->status;?></th>
    </tr>
    <?php foreach($productIDList as $productID):?>
    <tr class='a-center'>
      <td><?php echo sprintf('%03d', $productID) . html::hidden("productIDList[$productID]", $productID);?></td>
      <td><?php echo html::input("names[$productID]", $products[$productID]->name, "class='text-1'");?></td>
      <td><?php echo html::input("codes[$productID]", $products[$productID]->code, "class='text-1'");?></td>
      <td><?php echo html::select("POs[$productID]",  $poUsers, $products[$productID]->PO);?></td>
      <td><?php echo html::select("QDs[$productID]",  $qdUsers, $products[$productID]->QD);?></td>
      <td><?php echo html::select("RDs[$productID]",  $rdUsers, $products[$productID]->RD);?></td>
      <td><?php echo html::select("statuses[$productID]", $lang->product->statusList, $products[$productID]->status);?></td>
    </tr>
    <?php endforeach;?>
    <tfoot><tr><td colspan='7' class='a-center'><?php echo html::submitButton();?></td></tr></tfoot>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
