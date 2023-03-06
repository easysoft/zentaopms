<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $storyID ? $storyTitle . ' - ' . $this->lang->story->subdivide : $this->lang->story->batchCreate;?></h2>
    <div class="pull-right btn-toolbar">
      <?php if(common::hasPriv('file', 'uploadImages')) echo html::a($this->createLink('file', 'uploadImages', 'module=story&params=' . helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID&storyID=$storyID&executionID=&plan=&type=$type")), $lang->uploadImages, '', "data-toggle='modal' data-type='iframe' class='btn btn-primary' data-width='70%'")?>
      <button type='button' data-toggle='modal' data-target="#importLinesModal" class="btn btn-primary"><?php echo $lang->pasteText;?></button>
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
            <th class='c-module<?php echo zget($requiredFields, 'module', '', ' required');?>'><?php echo $lang->story->module;?></th>
            <th class='c-name required has-btn'><?php echo $lang->story->title;?></th>
            <th class='c-spec<?php echo zget($visibleFields, 'spec', ' hidden') . zget($requiredFields, 'spec', '', ' required');?>'><?php echo $lang->story->spec;?></th>
            <th class='c-pri<?php echo zget($requiredFields, 'pri', '', ' required');?>'><?php echo $lang->story->pri;?></th>
            <th class='c-estimate<?php echo zget($requiredFields, 'estimate', '', ' required');?>'><?php echo $lang->story->estimate;?></th>
            <th class="<?php if($forceReview) echo ' required'?>" style="width: 200px !important"><?php echo $lang->story->reviewedBy;?></th>
            <th class='c-keywords<?php echo zget($requiredFields, 'keywords', '', ' required');?>'><?php echo $lang->story->keywords;?></th>
            <?php
            $extendFields = $this->story->getFlowExtendFields();
            foreach($extendFields as $extendField) echo "<th class='c-extend'>{$extendField->name}</th>";
            ?>
          </tr>
        </thead>
        <tbody>
          <tr class="template">
            <td class='text-left' style='overflow:visible'><?php echo html::select('module[$id]', $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></td>
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
            <td class='text-left<?php echo zget($visibleFields, 'pri', ' hidden')?>' style='overflow:visible'><?php echo html::select('pri[$id]', $priList, $pri, "class='form-control chosen'");?></td>
            <td><?php echo html::input('estimate[$id]', $estimate, "class='form-control'");?></td>
            <td><?php echo html::select('reviewer[$id][]', $reviewers, '', "class='form-control chosen' multiple");?></td>
            <td><?php echo html::input('keywords[$id]', '', "class='form-control'");?></td>
            <?php foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->loadModel('flow')->getFieldControl($extendField, '', $extendField->field . '[$id]') . "</td>";?>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="7" class="text-center form-actions">
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
            $row.find('select.chosen,select.picker-select').each(function()
            {
                var $select = $(this);
                if($select.hasClass('picker-select')) $select.parent().find('.picker').remove();
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

    $(document).on('change', "#mainContent select[name^=needReview]", function()
    {
        select = $(this).parent('td').next('td').children("select[name^=reviewer]");
        $(select).removeAttr('disabled');
        if($(this).val() == 0) $(select).attr('disabled', 'disabled');
        $(select).trigger("chosen:updated");
    })
});
</script>
<?php if(isset($execution)) js::set('execution', $execution);?>
<?php js::set('storyType', $type);?>
<?php include $this->app->getModuleRoot() . 'common/view/pastetext.html.php';?>
<?php include $this->app->getModuleRoot() . 'common/view/footer.html.php';?>
