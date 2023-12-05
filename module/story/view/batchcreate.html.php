<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<?php if($forceReview) $config->story->create->requiredFields .= ',review';?>
<?php js::set('showFields', $showFields);?>
<?php js::set('requiredFields', $config->story->create->requiredFields);?>
<?php js::set('storyType', $type);?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $storyID ? $storyTitle . ' - ' . $this->lang->story->subdivide : $this->lang->story->batchCreate;?></h2>
    <div class="pull-right btn-toolbar">
      <?php if(common::hasPriv('file', 'uploadImages')) echo html::a($this->createLink('file', 'uploadImages', 'module=story&params=' . helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID&storyID=$storyID&executionID=$executionID&plan=&type=$type")), $lang->uploadImages, '', "data-toggle='modal' data-type='iframe' class='btn btn-primary' data-width='70%'")?>
      <button type='button' data-toggle='modal' data-target="#importLinesModal" class="btn btn-primary"><?php echo $lang->pasteText;?></button>
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=story&section=custom&key=batchCreateFields')?>
      <?php include '../../common/view/customfield.html.php';?>
      <?php if(isonlybody()):?>
      <div class="divider"></div>
      <button id="closeModal" type="button" class="btn btn-link" data-dismiss="modal"><i class="icon icon-close"></i></button>
      <?php endif;?>
    </div>
  </div>
  <?php
  $visibleFields  = array();
  $requiredFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if($field) $visibleFields[$field] = '';
  }
  foreach(explode(',', $config->story->create->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->story->list->customBatchCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  unset($visibleFields['module']);
  ?>
  <form method='post' class='load-indicator main-form form-ajax' enctype='multipart/form-data' id="batchCreateForm">
    <div class="table-responsive">
      <table class="table table-form">
        <thead>
          <tr>
            <th class='c-branch<?php echo zget($visibleFields, $product->type, ' hidden')?> branchBox'><?php echo $lang->product->branch;?></th>
            <th class='c-module<?php echo zget($requiredFields, 'module', '', ' required');?>'><?php echo $lang->story->module;?></th>
            <?php if(!$hiddenPlan):?>
            <th class='c-plan<?php echo zget($visibleFields, 'plan', ' hidden') . zget($requiredFields, 'plan', '', ' required');?> planBox'><?php echo $lang->story->plan;?></th>
            <?php endif;?>
            <?php if(isset($execution) and $execution->type == 'kanban'):?>
            <th class='c-branch'><?php echo $lang->kanbancard->region;?></th>
            <th class='c-branch'><?php echo $lang->kanbancard->lane;?></th>
            <?php endif;?>
            <th class='c-name required has-btn'><?php echo $lang->story->title;?></th>
            <th class='c-spec<?php echo zget($visibleFields, 'spec', ' hidden') . zget($requiredFields, 'spec', '', ' required');?> specBox'><?php echo $lang->story->spec;?></th>
            <th class='c-source<?php echo zget($visibleFields, 'source', ' hidden') . zget($requiredFields, 'source', '', ' required');?> sourceBox'><?php echo $lang->story->source;?></th>
            <th class='c-note<?php echo zget($visibleFields, 'source', ' hidden') . zget($requiredFields, 'sourceNote', '', ' required');?> sourceBox'><?php echo $lang->story->sourceNote;?></th>
            <th class='c-verify<?php echo zget($visibleFields, 'verify', ' hidden') . zget($requiredFields, 'verify', '', ' required');?> verifyBox'><?php echo $lang->story->verify;?></th>
            <th class='c-category'><?php echo $lang->story->category;?></th>
            <th class='c-pri<?php echo zget($visibleFields, 'pri', ' hidden') . zget($requiredFields, 'pri', '', ' required');?> priBox'><?php echo $lang->story->pri;?></th>
            <th class='c-estimate<?php echo zget($visibleFields, 'estimate', ' hidden') . zget($requiredFields, 'estimate', '', ' required');?> estimateBox'><?php echo $lang->story->estimate;?></th>
            <th class='<?php echo zget($visibleFields, 'review',   ' hidden');?><?php if($forceReview) echo ' required'?> reviewBox'><?php echo $lang->story->reviewedBy;?></th>
            <th class='c-keywords<?php echo zget($visibleFields, 'keywords', ' hidden') . zget($requiredFields, 'keywords', '', ' required');?> keywordsBox'><?php echo $lang->story->keywords;?></th>
            <?php
            $extendFields = $this->story->getFlowExtendFields();
            foreach($extendFields as $extendField)
            {
                $required = strpos(",$extendField->rules,", ',1,') !== false ? 'required' : '';
                echo "<th class='c-extend $required'>{$extendField->name}</th>";
            }
            ?>
            <th class='c-actions'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <tr class="template">
            <td class='text-left<?php echo zget($visibleFields, $product->type, ' hidden')?> branchBox'><?php echo html::select('branch[$id]', $branches, $branch, "class='form-control chosen' onchange='setModuleAndPlan(this.value, $productID, \$id)'");?></td>
            <td class='text-left' style='overflow:visible'><?php echo html::select('module[$id]', $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></td>
            <?php if(!$hiddenPlan):?>
            <td class='text-left<?php echo zget($visibleFields, 'plan', ' hidden')?> planBox' style='overflow:visible'><?php echo html::select('plan[$id]', $plans, $planID, "class='form-control chosen'");?></td>
            <?php endif;?>
            <?php if(isset($execution) and $execution->type == 'kanban'):?>
            <td class='text-left'><?php echo html::select('regions[$id]', $regionPairs, $regionID, "class='form-control chosen' onchange='setLane(this.value, \$id)'");?>
            <td class='text-left'><?php echo html::select('lanes[$id]', $lanePairs, $laneID, "class='form-control chosen'");?>
            <?php endif;?>
            <td style='overflow:visible'>
              <div class="input-group">
                <div class="input-control has-icon-right">
                  <input type="text" name="title[$id]" id="title$id" value="" class="form-control title-import input-story-title" autocomplete="off">
                  <div class="colorpicker">
                    <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                    <ul class="dropdown-menu clearfix">
                      <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                    </ul>
                    <input type="hidden" class="colorpicker" id="color$id" name="color[$id]" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title$id">
                  </div>
                </div>
                <span class="input-group-btn">
                  <button type="button" class="btn btn-link btn-icon btn-copy" data-copy-from="#title$id" data-copy-to="#spec$id" title="<?php echo $lang->story->copyTitle;?>"><i class="icon icon-arrow-right"></i></button>
                </span>
              </div>
            </td>
            <td class='<?php echo zget($visibleFields, 'spec', 'hidden')?> specBox'><textarea name="spec[$id]" id="spec$id" rows="1" class="form-control autosize"></textarea></td>
            <td class='text-left<?php echo zget($visibleFields, 'source', ' hidden')?> sourceBox'><?php echo html::select('source[$id]', $sourceList, '', "class='form-control chosen' id='source_\$id'");?></td>
            <td class='<?php echo zget($visibleFields, 'source', 'hidden')?> sourceBox'><?php echo html::input('sourceNote[$id]', '', "class='form-control' id='sourceNote_\$id'");?></td>
            <td class='<?php echo zget($visibleFields, 'verify', 'hidden')?> verifyBox'><textarea name="verify[$id]" id="verify$id" rows="1" class="form-control autosize"></textarea></td>
            <td class='text-left' style='overflow:visible'><?php echo html::select('category[$id]', $lang->story->categoryList, 'feature', "class='form-control chosen'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'pri', ' hidden')?> priBox' style='overflow:visible'><?php echo html::select('pri[$id]', $priList, $pri, "class='form-control chosen'");?></td>
            <td class='<?php echo zget($visibleFields, 'estimate', 'hidden')?> estimateBox'><?php echo html::input('estimate[$id]', $estimate, "class='form-control'");?></td>
            <td class='<?php echo zget($visibleFields, 'review', 'hidden')?> reviewBox'>
              <div class='input-group'>
                <?php echo html::select('reviewer[$id][]', $reviewers, '', "class='form-control chosen' multiple");?>
                <span class='input-group-addon reviewerDitto'><input type='checkbox' name='reviewDitto[$id]' value='ditto' checked='checked' id='dittocheck$id'/> <?php echo $lang->story->ditto;?></span>
              </div>
            </td>
            <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?> keywordsBox'><?php echo html::input('keywords[$id]', '', "class='form-control'");?></td>
            <?php
            $this->loadModel('flow');
            foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->flow->getFieldControl($extendField, '', $extendField->field . '[$id]') . "</td>";
            ?>
            <td class='c-actions text-left'>
              <a href='javascript:;' onclick='addRow(this)' class='btn btn-link'><i class='icon-plus'></i></a>
              <a href='javascript:;' onclick='deleteRow(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="<?php echo count($visibleFields) + 3?>" class="text-center form-actions">
              <?php echo html::commonButton($lang->save, "id='saveButton'", 'btn btn-primary btn-wide');?>
              <?php echo html::commonButton($lang->story->saveDraft, "id='saveDraftButton'", 'btn btn-secondary btn-wide');?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<div>
  <?php $i = '%i%';?>
  <table class='hidden'>
    <tr id='addRow' class='hidden'>
      <td class='text-left<?php echo zget($visibleFields, $product->type, ' hidden')?> branchBox'><?php echo html::select("branch[$i]", $branches, $branch, "class='form-control chosen' onchange='setModuleAndPlan(this.value, $productID, $i)'");?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("module[$i]", $moduleOptionMenu, 'ditto', "class='form-control chosen'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'plan', ' hidden')?> planBox' style='overflow:visible'><?php echo html::select("plan[$i]", $plans, 'ditto', "class='form-control chosen'");?></td>
      <?php if(isset($execution) and $execution->type == 'kanban'):?>
      <td class='text-left'><?php echo html::select("regions[$i]", $regionPairs, $regionID, "class='form-control chosen' onchange='setLane(this.value, $i)'");?>
      <td class='text-left'><?php echo html::select("lanes[$i]", $lanePairs, $laneID, "class='form-control chosen'");?>
      <?php endif;?>
      <td style='overflow:visible'>
        <div class="input-group">
          <div class="input-control has-icon-right">
            <input type="text" name="title[<?php echo $i?>]" id="title<?php echo $i?>" value="" class="form-control title-import input-story-title" autocomplete="off">
            <div class="colorpicker">
              <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
              <ul class="dropdown-menu clearfix">
                <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
              </ul>
              <input type="hidden" class="colorpicker" id="color<?php echo $i?>" name="color[<?php echo $i?>]" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title<?php echo $i?>">
            </div>
          </div>
          <span class="input-group-btn">
            <button type="button" class="btn btn-link btn-icon btn-copy" data-copy-from="#title<?php echo $i?>" data-copy-to="#spec<?php echo $i?>" title="<?php echo $lang->story->copyTitle;?>"><i class="icon icon-arrow-right"></i></button>
          </span>
        </div>
      </td>
      <td class='<?php echo zget($visibleFields, 'spec', 'hidden')?> specBox'><textarea name="spec[<?php echo $i?>]" id="spec<?php echo $i;?>" rows="1" class="form-control autosize"></textarea></td>
      <td class='text-left<?php echo zget($visibleFields, 'source', ' hidden')?> sourceBox'><?php echo html::select("source[$i]", $sourceList, 'ditto', "class='form-control chosen' id='source_$i'");?></td>
      <td class='<?php echo zget($visibleFields, 'source', 'hidden')?> sourceBox'><?php echo html::input("sourceNote[$i]", '', "class='form-control' id='sourceNote_$i'");?></td>
      <td class='<?php echo zget($visibleFields, 'verify', 'hidden')?> verifyBox'><textarea name="verify[<?php echo $i?>]" id="verify<?php echo $i?>" rows="1" class="form-control autosize"></textarea></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("category[$i]", $lang->story->categoryList, 'feature', "class='form-control chosen'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'pri', ' hidden')?> priBox' style='overflow:visible'><?php echo html::select("pri[$i]", $priList, 'ditto', "class='form-control chosen'");?></td>
      <td class='<?php echo zget($visibleFields, 'estimate', 'hidden')?> estimateBox'><?php echo html::input("estimate[$i]", $estimate, "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'review', 'hidden')?> reviewBox'>
        <div class='input-group'>
          <?php echo html::select("reviewer[$i][]", $reviewers, '', "class='form-control chosen' multiple");?>
          <span class='input-group-addon reviewerDitto'><input type='checkbox' name="reviewDitto[<?php echo $i?>]" value='ditto' checked='checked' id="dittocheck<?php echo $i?>"/> <?php echo $lang->story->ditto;?></span>
        </div>
      </td>
      <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?> keywordsBox'><?php echo html::input("keywords[$i]", '', "class='form-control'");?></td>
      <?php
      $this->loadModel('flow');
      foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->flow->getFieldControl($extendField, '', $extendField->field . "[$i]") . "</td>";
      ?>
      <td class='c-actions text-left'>
        <a href='javascript:;' onclick='addRow(this)' class='btn btn-link'><i class='icon-plus'></i></a>
        <a href='javascript:;' onclick='deleteRow(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
      </td>
    </tr>
  </table>
</div>
<script>
$(function()
{
    var imageTitles = <?php echo empty($titles) ? '""' : json_encode($titles);?>;
    var storyTitles = <?php echo empty($titles) ? '""' : json_encode(array_keys($titles));?>;

    $('#batchCreateForm').batchActionForm(
    {
        idStart: 1,
        idEnd: <?php echo max((empty($titles) ? 1 : count($titles)), 10)?>,
        rowCreator: function($row, index)
        {
            rowIndex = index; // Set the index for the add element operation
            $row.find('select.chosen,select.picker-select').each(function()
            {
                var $select = $(this);
                if($select.hasClass('picker-select')) $select.parent().find('.picker').remove();
                if(index == 1) $select.find("option[value='ditto']").remove();
                if(index > 1 && $select.find('option[value="ditto"]').length > 0) $select.val('ditto');
                if($select.attr('id').indexOf('branch') >= 0) $select.val('<?php echo $branch;?>')
                $select.chosen();
                setTimeout(function()
                {
                    $select.next('.chosen-container').find('.chosen-drop').width($select.closest('td').width() + 50);
                }, 200);
            });

            var storyTitle = storyTitles && storyTitles[index - 1];
            if (storyTitle !== undefined && storyTitle !== null)
            {
                $row.find('.input-story-title').val(storyTitle).after('<input type="hidden" name="uploadImage[' + index + ']" id="uploadImage[' + index + ']" value="' + imageTitles[storyTitle] + '">');
            }

            if(index == 1) $row.find('td.c-actions > a:last').remove();

            /* Implement a custom form without feeling refresh. */
            var fieldList = ',' + showFields + ',';
            $('#formSettingForm > .checkboxes > .checkbox-primary > input').each(function()
            {
                var field     = ',' + $(this).val() + ',';
                var $field    = $row.find('[name^=' + $(this).val() + ']');
                var required  = ',' + requiredFields + ',';
                var $fieldBox = $row.find('.' + $(this).val() + 'Box' );
                if(fieldList.indexOf(field) >= 0 || required.indexOf(field) >= 0)
                {
                    $fieldBox.removeClass('hidden');
                    $field.removeAttr('disabled');
                }
                else if(!$fieldBox.hasClass('hidden'))
                {
                    $fieldBox.addClass('hidden');
                    $field.attr('disabled', true);
                }
            })
        }
    });

    $(document).on('change', "#mainContent select[name^=needReview]", function()
    {
        select = $(this).parent('td').next('td').children("select[name^=reviewer]");
        $(select).removeAttr('disabled');
        if($(this).val() == 0) $(select).attr('disabled', 'disabled');
        $(select).trigger("chosen:updated");
    })

    $('.reviewerDitto:first').remove();
});

</script>
<?php if(isset($execution)) js::set('execution', $execution);?>
<?php js::set('storyType', $type);?>
<?php if(isonlybody()):?>
<style>
.body-modal .main-header {padding-right: 0px;}
.btn-toolbar > .dropdown {margin: 0px;}
</style>
<script>
$(function()
{
    parent.$('#triggerModal .modal-content .modal-header .close').hide();
})
</script>
<?php endif;?>
<?php include '../../common/view/pastetext.html.php';?>
<?php include '../../common/view/footer.html.php';?>
