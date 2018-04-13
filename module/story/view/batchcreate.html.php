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
    <h2><?php echo $lang->story->batchCreate;?></h2>
    <div class="pull-right btn-toolbar">
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=story&section=custom&key=batchCreateFields')?>
      <?php include '../../common/view/dropdowncustomfield.html.php';?>
    </div>
  </div>
  <?php
  $visibleFields  = array();
  $requiredFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if($field) $visibleFields[$field] = '';
  }
  foreach(explode(',', $this->config->story->create->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->story->list->customBatchCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  if($this->story->checkForceReview()) unset($visibleFields['review']);
  ?>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchCreateForm">
    <table class="table table-form">
      <thead>
        <tr>
          <th class='col-id'><?php echo $lang->idAB;?></th> 
          <th class='col-branch<?php echo zget($visibleFields, $product->type, ' hidden')?>'><?php echo $lang->product->branch;?></th>
          <th class='col-module required'><?php echo $lang->story->module;?></th>
          <th class='col-plan<?php echo zget($visibleFields, 'plan', ' hidden'); echo isset($requiredFields['plan']) ? ' required' : ''?>'><?php echo $lang->story->plan;?></th>
          <th class='col-name required has-btn'>
            <?php echo $lang->story->title;?>
            <button type='button' data-toggle="importLinesModal" class="btn btn-info"><?php echo $lang->pasteText;?></button>
          </th>
          <th class='col-desc<?php echo zget($visibleFields, 'spec', ' hidden'); echo isset($requiredFields['spec']) ? ' required' : ''?>'><?php echo $lang->story->spec;?></th>
          <th class='w-80px<?php echo zget($visibleFields, 'source', ' hidden'); echo isset($requiredFields['source']) ? ' required' : ''?>'><?php echo $lang->story->source;?></th>
          <th class='w-p15<?php echo zget($visibleFields, 'verify', ' hidden'); echo isset($requiredFields['verify']) ? ' required' : ''?>'><?php echo $lang->story->verify;?></th>
          <th class='col-pri<?php echo zget($visibleFields, 'pri', ' hidden'); echo isset($requiredFields['pri']) ? ' required' : ''?>'><?php echo $lang->story->pri;?></th>
          <th class='col-estimate<?php echo zget($visibleFields, 'estimate', ' hidden'); echo isset($requiredFields['estimate']) ? ' required' : ''?>'><?php echo $lang->story->estimate;?></th>
          <th class='col-review<?php echo zget($visibleFields, 'review', ' hidden'); echo isset($requiredFields['review']) ? ' required' : ''?>'><?php echo $lang->story->review;?></th>
          <th class='col-keywords<?php echo zget($visibleFields, 'keywords', ' hidden'); echo isset($requiredFields['keywords']) ? ' required' : ''?>'><?php echo $lang->story->keywords;?></th>
        </tr>
      </thead>
      <tbody>
        <tr class="template">
          <td class="col-id">$idPlus</td>
	  	  <td class='text-left<?php echo zget($visibleFields, $product->type, ' hidden')?>'><?php echo html::select('branch[$id]', $branches, $branch, "class='form-control' onchange='setModuleAndPlan(this.value, $productID, \$id)'");?></td>
      	  <td class='text-left' style='overflow:visible'><?php echo html::select('module[$id]', $moduleOptionMenu, $moduleID, "class='form-control'");?></td>
      	  <td class='text-left<?php echo zget($visibleFields, 'plan', ' hidden')?>' style='overflow:visible'><?php echo html::select('plan[$id]', $plans, $planID, "class='form-control'");?></td>
          <td style='overflow:visible'>
            <div class="input-group">
              <div class="input-control has-icon-right">
                <input type="text" name="title[$id]" id="title$id" value="" class="form-control input-story-title" autocomplete="off">
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
          <td class='text-left<?php echo zget($visibleFields, 'pri', ' hidden')?>' style='overflow:visible'><?php echo html::select('pri[$id]', $priList, $pri, "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'estimate', 'hidden')?>'><?php echo html::input('estimate[$id]', $estimate, "class='form-control' autocomplete='off'");?></td>
          <td class='<?php echo zget($visibleFields, 'review', 'hidden')?>'><?php echo html::select('needReview[$id]', $lang->story->reviewList, $needReview, "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?>'><?php echo html::input('keywords[$id]', '', "class='form-control' autocomplete='off'");?></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="<?php echo count($visibleFields) + 3?>" class="text-center">
            <?php echo html::submitButton($lang->save, '', 'btn btn-wide btn-primary') . '&nbsp;' . html::backButton('', '', "btn btn-wide");?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(function()
{
    $('#batchCreateForm').batchActionForm(
    {
        rowCreator: function($row, index)
        {
            $row.find('select').each(function()
            {
                var $select = $(this);
                if(index == 0) $select.find("option[value='ditto']").remove();
                if(index > 0) $select.val('ditto');
                $select.chosen(defaultChosenOptions);
                setTimeout(function()
                {
                    $select.next('.chosen-container').find('.chosen-drop').width($select.closest('td').width() + 50);
                }, 200);
            });
        }
    });
});
</script>
<?php include '../../common/view/pastetext.html.php';?>
<?php include '../../common/view/footer.html.php';?>
