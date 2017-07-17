<?php
/**
 * The create view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('weekend', $config->project->weekend);?>
<?php js::import($jsRoot . 'misc/date.js');?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['plan']);?></span>
      <strong><small class='text-muted'><i class='icon icon-plus'></i></small> <?php echo $lang->productplan->create;?></strong>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <tr>
        <th class='w-80px'><?php echo $lang->productplan->product;?></th>
        <td class='w-p25-f'><?php echo $product->name;?></td><td></td>
      </tr>
      <?php if($product->type != 'normal'):?>
      <tr>
        <th><?php echo $lang->product->branch;?></th>
        <td><?php echo html::select('branch', $branches, $branch, "class='form-control'");?></td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->productplan->title;?></th>
        <td><?php echo html::input('title', '', "class='form-control' autocomplete='off'");?></td>
        <td>
          <?php if($lastPlan) echo '<span class="help-block"> &nbsp; (' . $lang->productplan->last . ': ' . $lastPlan->title . ')</span>';?>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->productplan->begin;?></th>
        <td><?php echo html::input('begin', $begin, "class='form-control form-date'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->productplan->end;?></th>
        <td>
          <?php echo html::input('end', '', "class='form-control form-date'");?>
        </td>
        <td>
          &nbsp; &nbsp; <?php echo html::radio('delta', $lang->productplan->endList , '', "onclick='computeEndDate(this.value)'");?>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->productplan->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', '', "rows='10' class='form-control'");?></td>
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
