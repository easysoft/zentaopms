<?php
/**
 * The edit view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('weekend', $config->project->weekend);?>
<?php js::import($jsRoot . 'misc/date.js');?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo html::a(inlink('view', "id=$plan->id"), $plan->title, '', "title='$plan->title'");?></strong></h2>
    </div>
    <form class='load-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tbody>
          <tr>
            <th><?php echo $lang->productplan->product;?></th>
            <td class='muted'><?php echo $product->name;?></td><td></td><td></td>
          </tr>
          <?php if($product->type != 'normal'):?>
          <tr>
            <th><?php echo $lang->product->branch;?></th>
            <td><?php echo html::select('branch', $branches, $plan->branch, 'class="form-control"');?></td><td></td><td></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->productplan->title;?></th>
            <td><?php echo html::input('title', $plan->title, "class='form-control' required");?></td><td></td><td></td>
          </tr>
          <tr>
            <th><?php echo $lang->productplan->begin;?></th>
            <td><?php echo html::input('begin', $plan->begin != '2030-01-01' ? formatTime($plan->begin) : '', "class='form-control form-date'");?></td>
            <td>
              <?php $checked = $plan->begin == '2030-01-01' || $plan->end == '2030-01-01' ? "checked='checked'" : '';?>
              <div class='checkbox-primary'>
                <input type='checkbox' id='future' name='future' value='1' <?php echo $checked;?> />
                <label for='future'><?php echo $lang->productplan->future;?></label>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->productplan->end;?></th>
            <td><?php echo html::input('end', $plan->end != '2030-01-01' ? formatTime($plan->end) : '', 'class="form-control form-date"');?></td>
            <td colspan='2'><?php echo html::radio('delta', $lang->productplan->endList , '', "onclick='computeEndDate(this.value)'");?></td>
          </tr>
          <?php $this->printExtendFields($plan, 'table');?>
          <tr>
            <th><?php echo $lang->productplan->desc;?></th>
            <td colspan='3'><?php echo html::textarea('desc', htmlspecialchars($plan->desc), "rows='10' class='form-control kindeditor' hidefocus='true'");?></td>
          </tr>
          <tr>
            <td colspan='4' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
              <?php echo html::hidden('product', $product->id);?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
