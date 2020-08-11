<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $storyID ? $this->lang->story->subdivide : $this->lang->story->batchCreate;?></h2>
    <div class="pull-right btn-toolbar">
      <?php if(common::hasPriv('file', 'uploadImages')) echo html::a($this->createLink('file', 'uploadImages', 'module=story&params=' . helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID&storyID=$storyID")), $lang->uploadImages, '', "data-toggle='modal' data-type='iframe' class='btn btn-primary' data-width='70%'")?>
      <button type='button' data-toggle='modal' data-target="#importLinesModal" class="btn btn-primary"><?php echo $lang->pasteText;?></button>
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=story&section=custom&key=batchCreateFields')?>
      <?php include '../../common/view/customfield.html.php';?>
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
  if($this->story->checkForceReview()) unset($visibleFields['review']);
  unset($visibleFields['module']);
  ?>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchCreateForm">
    <div class="table-responsive">
      <table class="table table-form">
        <thead>
          <tr>
            <th class='col-id'><?php echo $lang->idAB;?></th>
            <th class='w-150px<?php echo zget($visibleFields, $product->type, ' hidden')?>'><?php echo $lang->product->branch;?></th>
            <th class='col-module<?php echo zget($requiredFields, 'module', '', ' required');?>'><?php echo $lang->story->module;?></th>
            <th class='col-plan<?php echo zget($visibleFields, 'plan', ' hidden') . zget($requiredFields, 'plan', '', ' required');?>'><?php echo $lang->story->plan;?></th>
            <th class='col-name required has-btn'><?php echo $lang->story->title;?></th>
            <th class='w-150px<?php      echo zget($visibleFields, 'spec',     ' hidden') . zget($requiredFields, 'spec',     '', ' required');?>'><?php echo $lang->story->spec;?></th>
            <th class='w-100px<?php      echo zget($visibleFields, 'source',   ' hidden') . zget($requiredFields, 'source',   '', ' required');?>'><?php echo $lang->story->source;?></th>
            <th class='w-150px<?php        echo zget($visibleFields, 'verify',   ' hidden') . zget($requiredFields, 'verify',   '', ' required');?>'><?php echo $lang->story->verify;?></th>
            <th class='col-pri<?php      echo zget($visibleFields, 'pri',      ' hidden') . zget($requiredFields, 'pri',      '', ' required');?>'><?php echo $lang->story->pri;?></th>
            <th class='col-estimate<?php echo zget($visibleFields, 'estimate', ' hidden') . zget($requiredFields, 'estimate', '', ' required');?>'><?php echo $lang->story->estimate;?></th>
            <th class='col-review<?php   echo zget($visibleFields, 'review',   ' hidden') . zget($requiredFields, 'review',   '', ' required');?>'><?php echo $lang->story->needReview;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'keywords', ' hidden') . zget($requiredFields, 'keywords', '', ' required');?>'><?php echo $lang->story->keywords;?></th>
            <?php
            $extendFields = $this->story->getFlowExtendFields();
            foreach($extendFields as $extendField) echo "<th class='w-100px'>{$extendField->name}</th>";
            ?>
          </tr>
        </thead>
        <tbody>
          <tr class="template">
            <td class="col-id">$idPlus</td>
            <td class='text-left<?php echo zget($visibleFields, $product->type, ' hidden')?>'><?php echo html::select('branch[$id]', $branches, $branch, "class='form-control chosen' onchange='setModuleAndPlan(this.value, $productID, \$id)'");?></td>
            <td class='text-left' style='overflow:visible'><?php echo html::select('module[$id]', $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'plan', ' hidden')?>' style='overflow:visible'><?php echo html::select('plan[$id]', $plans, $planID, "class='form-control chosen'");?></td>
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
            <td class='<?php echo zget($visibleFields, 'spec', 'hidden')?>'><textarea name="spec[$id]" id="spec$id" rows="1" class="form-control autosize"></textarea></td>
            <td class='text-left<?php echo zget($visibleFields, 'source', ' hidden')?>'><?php echo html::select('source[$id]', $sourceList, '', "class='form-control'");?></td>
            <td class='<?php echo zget($visibleFields, 'verify', 'hidden')?>'><textarea name="verify[$id]" id="verify$id" rows="1" class="form-control autosize"></textarea></td>
            <td class='text-left<?php echo zget($visibleFields, 'pri', ' hidden')?>' style='overflow:visible'><?php echo html::select('pri[$id]', $priList, $pri, "class='form-control chosen'");?></td>
            <td class='<?php echo zget($visibleFields, 'estimate', 'hidden')?>'><?php echo html::input('estimate[$id]', $estimate, "class='form-control'");?></td>
            <td class='<?php echo zget($visibleFields, 'review', 'hidden')?>'><?php echo html::select('needReview[$id]', $lang->story->reviewList, $needReview, "class='form-control'");?></td>
            <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?>'><?php echo html::input('keywords[$id]', '', "class='form-control'");?></td>
            <?php foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->loadModel('flow')->getFieldControl($extendField, '', $extendField->field . '[$id]') . "</td>";?>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="<?php echo count($visibleFields) + 3?>" class="text-center form-actions">
              <?php echo html::submitButton($lang->save);?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<script>
$(function()
{
    var imageTitles = <?php echo empty($titles) ? '""' : json_encode($titles);?>;
    var storyTitles = <?php echo empty($titles) ? '""' : json_encode(array_keys($titles));?>;

    $('#batchCreateForm').batchActionForm(
    {
        idEnd: <?php echo max((empty($titles) ? 0 : count($titles)), 9)?>,
        rowCreator: function($row, index)
        {
            $row.find('select.chosen').each(function()
            {
                var $select = $(this);
                if(index == 0) $select.find("option[value='ditto']").remove();
                if(index > 0) $select.val('ditto');
                if($select.attr('id').indexOf('branch') >= 0) $select.val('<?php echo $branch;?>')
                $select.chosen();
                setTimeout(function()
                {
                    $select.next('.chosen-container').find('.chosen-drop').width($select.closest('td').width() + 50);
                }, 200);
              });
              var storyTitle = storyTitles && storyTitles[index];
              if (storyTitle !== undefined && storyTitle !== null)
              {
                  $row.find('.input-story-title').val(storyTitle).after('<input type="hidden" name="uploadImage[' + index + ']" id="uploadImage[' + index + ']" value="' + imageTitles[storyTitle] + '">');
              }
        }
    });
});
</script>
<?php js::set('storyType', $type);?>
<?php include '../../common/view/pastetext.html.php';?>
<?php include '../../common/view/footer.html.php';?>
