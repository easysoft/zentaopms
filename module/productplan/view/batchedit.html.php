<?php
/**
 * The batchEdit view file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     productplan
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('oldBranch', $oldBranch)?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->productplan->batchEdit;?></h2>
  </div>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchEditForm">
    <table class="table table-form">
      <thead>
        <tr class='text-center'>
          <th class='c-id'><?php echo $lang->productplan->id?></th>
          <?php if($branchProduct):?>
          <th class='c-branch'><?php echo $lang->productplan->branch;?></th>
          <?php endif;?>
          <th><?php echo $lang->productplan->title?></th>
          <th><?php echo $lang->productplan->desc?></th>
          <th class='c-full-date'><?php echo $lang->productplan->begin?></th>
          <th class='c-full-date'><?php echo $lang->productplan->end?></th>
          <th class='c-future'><?php echo $lang->productplan->future?></th>
          <?php
          $extendFields = $this->productplan->getFlowExtendFields();
          foreach($extendFields as $extendField) echo "<th class='c-extend'>{$extendField->name}</th>";
          ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach($plans as $plan):?>
        <?php $hiddenInput = ($plan->begin == '2030-01-01' || $plan->end == '2030-01-01') ? 'form-input-hidden' : 'form-input-show';?>
        <?php $showInput   = ($plan->begin == '2030-01-01' || $plan->end == '2030-01-01') ? 'form-input-show' : 'form-input-hidden';?>
        <?php $isChecked   = ($plan->begin == '2030-01-01' || $plan->end == '2030-01-01') ? 'checked="checked"' : '';?>
        <tr>
          <td class='text-center'><?php echo $plan->id . html::hidden("id[$plan->id]", $plan->id);?></td>
          <?php if($branchProduct):?>
          <td class='text-left'>
            <?php echo html::select("branch[$plan->id]", $branches, $plan->branch, "onchange='loadWarning($plan->id, this.value); 'class='form-control chosen' ");?>
          </td>
          <?php endif;?>
          <td title='<?php echo $plan->title?>'><?php echo html::input("title[$plan->id]", $plan->title, "class='form-control'")?></td>
          <td><?php echo html::textarea("desc[$plan->id]", $plan->desc, "class='form-control' rows='1'")?></td>
          <td><?php echo html::input("begin[$plan->id]", $plan->begin, "class='form-control form-date $hiddenInput'");echo html::input("begin$plan->id", '', "class='form-control $showInput' disabled='disabled'");?></td>
          <td><?php echo html::input("end[$plan->id]", $plan->end, "class='form-control form-date $hiddenInput'");echo html::input("end$plan->id", '', "class='form-control $showInput' disabled='disabled'");?></td>
          <td><div class='checkbox-primary'><input type='checkbox' id="future<?php echo $plan->id; ?>" name='future<?php echo $plan->id; ?>' <?php echo $isChecked;?> onclick="changeDate(<?php echo $plan->id;?>);"/><label for='future<?php echo $plan->id; ?>'><?php echo $lang->productplan->future;?></label></div></td>
          <?php foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->loadModel('flow')->getFieldControl($extendField, $plan, $extendField->field . "[{$plan->id}]") . "</td>";?>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='5' class="text-center form-actions">
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
