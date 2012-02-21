<?php
/**
 * The edit view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<form method='post' target='hiddenwin'>
  <table class='table-1'> 
    <caption><?php echo $lang->productplan->edit;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->productplan->product;?></th>
      <td><?php echo $product->name;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->productplan->title;?></th>
      <td><?php echo html::input('title', $plan->title, 'class="text-3"');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->productplan->begin;?></th>
      <td><?php echo html::input('begin', $plan->begin, 'class="text-3 date"');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->productplan->end;?></th>
      <td><?php echo html::input('end', $plan->end, 'class="text-3 date"');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->productplan->desc;?></th>
      <td><?php echo html::textarea('desc', htmlspecialchars($plan->desc), "rows='10' class='area-1'");?></td>
    </tr>  
    <tr>
      <td colspan='2' class='a-center'>
        <?php 
        echo html::submitButton();
        echo html::resetButton();
        echo html::hidden('product', $product->id);
        ?>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
