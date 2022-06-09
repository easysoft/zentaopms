<?php
/**
 * The batchEdit view file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     productplan
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('oldBranch', $oldBranch);?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->productplan->batchEdit;?></h2>
  </div>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchEditForm">
    <table class="table table-form">
      <thead>
        <tr class='text-center'>
          <th class='c-id'><?php echo $lang->productplan->id?></th>
          <?php if($product->type != 'normal'):?>
          <th class='c-branch'><?php echo $lang->productplan->branch;?></th>
          <?php endif;?>
          <th class='required'><?php echo $lang->productplan->title?></th>
          <th class='c-status'><?php echo $lang->productplan->status?></th>
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
        <?php $isChecked = ($plan->begin == $config->productplan->future and $plan->end == $config->productplan->future) ? 'checked="checked"' : '';?>
        <tr>
          <td class='text-center'><?php echo $plan->id . html::hidden("id[$plan->id]", $plan->id);?></td>
          <?php if($product->type != 'normal'):?>
          <td class='text-left'>
            <?php $disabled = $plan->parent == -1 ? "disabled='disabled'" : '';?>
            <?php echo html::select("branch[$plan->id]", $plan->parent == '-1' ? '' : $branchTagOption, $plan->branch, "onchange='getConflictStories($plan->id, this.value); 'class='form-control chosen' $disabled");?>
          </td>
          <?php endif;?>
          <td title='<?php echo $plan->title?>'><?php echo html::input("title[$plan->id]", $plan->title, "class='form-control'")?></td>
          <?php if($plan->parent != -1):?>
          <td><?php echo html::select("status[$plan->id]", array_slice($lang->productplan->statusList,($plan->status == 'wait' ? 0 : 1)), $plan->status, "class='form-control chosen'");?></td>
          <?php else:?>
          <td><?php echo html::select("status[$plan->id]", array_slice($lang->productplan->statusList,($plan->status == 'wait' ? 0 : 1)), $plan->status, "class='form-control chosen' disabled ");?></td>
          <?php endif;?>
          <?php $disabled = (($plan->begin == $config->productplan->future and $plan->end == $config->productplan->future)) ? 'disabled="disabled"' : '';?>
          <?php if($plan->parent == -1 and ($plan->begin == $config->productplan->future and $plan->end == $config->productplan->future)) $disabled = 'disabled="disabled"';?>
          <?php if($plan->begin == $config->productplan->future) $plan->begin = '';?>
          <?php if($plan->end == $config->productplan->future) $plan->end = '';?>
          <td><?php echo html::input("begin[$plan->id]", $plan->begin, "class='form-control form-date' $disabled");?></td>
          <td><?php echo html::input("end[$plan->id]", $plan->end, "class='form-control form-date' $disabled");?></td>
          <td><div class='checkbox-primary'><input type='checkbox' id="future<?php echo $plan->id; ?>" name='future[<?php echo $plan->id; ?>]' <?php echo $isChecked;?> onclick="changeDate(<?php echo $plan->id;?>);"/><label for='future<?php echo $plan->id; ?>'><?php echo $lang->productplan->future;?></label></div></td>
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
