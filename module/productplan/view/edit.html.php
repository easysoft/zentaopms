<?php
/**
 * The edit view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['plan']);?></span>
      <strong><?php echo html::a(inlink('view', "id=$plan->id"), $plan->title);?></strong>
      <small><?php echo html::icon($lang->icons['edit']) . ' ' . $lang->productplan->edit;?></small>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <tr>
        <th class='w-80px'><?php echo $lang->productplan->product;?></th>
        <td class='w-p25-f'><?php echo $product->name;?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->productplan->title;?></th>
        <td><?php echo html::input('title', $plan->title, 'class="form-control"');?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->productplan->begin;?></th>
        <td><?php echo html::input('begin', $plan->begin, 'class="form-control form-date"');?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->productplan->end;?></th>
        <td><?php echo html::input('end', $plan->end, 'class="form-control form-date"');?></td>
      </tr>
      <tr>
        <th><?php echo $lang->productplan->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', htmlspecialchars($plan->desc), "rows='10' class='form-control'");?></td>
      </tr>  
      <tr>
        <td></td>
        <td colspan='2'>
          <?php 
          echo html::submitButton();
          echo html::backButton();
          echo html::hidden('product', $product->id);
          ?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
