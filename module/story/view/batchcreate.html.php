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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['story']);?></span>
    <strong>
      <small class='text-muted'><?php echo html::icon($lang->icons['batchCreate']);?></small>
      <?php echo $storyID ? $lang->story->subdivide : $lang->story->batchCreate;?>
      <?php if($product->type !== 'normal') echo '<span class="label label-info">' . $branches[$branch] . '</span>';?>
    </strong>
    <div class='actions'>
      <?php if(common::hasPriv('file', 'uploadImages')) echo html::a($this->createLink('file', 'uploadImages', 'module=story&params=' . helper::safe64Encode("productID=$productID&moduleID=$moduleID")), $lang->uploadImages, '', "data-toggle='modal' data-type='iframe' class='btn' data-width='70%'")?>
      <?php echo html::commonButton($lang->pasteText, "data-toggle='myModal'")?>
      <button type="button" class="btn btn-default" data-toggle="customModal"><i class='icon icon-cog'></i> </button>
    </div>
  </div>
</div>
<?php
$visibleFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($field) $visibleFields[$field] = '';
}
if($this->story->checkForceReview()) unset($visibleFields['review']);
?>
<form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin'>
  <table class='table table-form table-fixed with-border'> 
    <thead>
      <tr class='text-center'>
        <th class='w-30px'><?php echo $lang->idAB;?></th> 
        <th class='w-120px<?php echo zget($visibleFields, $product->type, ' hidden')?>'><?php echo $lang->product->branch;?></th>
        <th class='w-p15<?php echo zget($visibleFields, 'module', ' hidden')?>'><?php echo $lang->story->module;?></th>
        <th class='w-p15<?php echo zget($visibleFields, 'plan', ' hidden')?>'><?php echo $lang->story->plan;?></th>
        <th <?php if(count($visibleFields) >= 9) echo "class='w-150px'"?>><?php echo $lang->story->title;?> <span class='required'></span></th>
        <th class='w-p15<?php echo zget($visibleFields, 'spec', ' hidden')?>'><?php echo $lang->story->spec;?></th>
        <th class='w-80px<?php echo zget($visibleFields, 'source', ' hidden')?>'><?php echo $lang->story->source;?></th>
        <th class='w-p15<?php echo zget($visibleFields, 'verify', ' hidden')?>'><?php echo $lang->story->verify;?></th>
        <th class='w-80px<?php echo zget($visibleFields, 'pri', ' hidden')?>'><?php echo $lang->story->pri;?></th>
        <th class='w-80px<?php echo zget($visibleFields, 'estimate', ' hidden')?>'><?php echo $lang->story->estimate;?></th>
        <th class='w-70px<?php echo zget($visibleFields, 'review', ' hidden')?>'><?php echo $lang->story->review;?></th>
        <th class='w-100px<?php echo zget($visibleFields, 'keywords', ' hidden')?>'><?php echo $lang->story->keywords;?></th>
      </tr>
    </thead>
    <?php $i = 0; ?>
    <?php if(!empty($titles)):?>
    <?php foreach($titles as $storyTitle => $fileName):?>
    <?php $moduleID = $i == 0 ? $moduleID : 'ditto';?>
    <?php $planID   = $i == 0 ? '' : 'ditto';?>
    <?php $pri      = $i == 0 ? '' : 'ditto';?>
    <?php $source   = $i == 0 ? '' : 'ditto';?>
    <tr class='text-center'>
      <td><?php echo $i+1;?></td>
      <td class='text-left<?php echo zget($visibleFields, $product->type, ' hidden')?>'><?php echo html::select("branch[$i]", $branches, $branch, "class='form-control' onchange='setModuleAndPlan(this.value, $productID, $i)'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'module', ' hidden')?>' style='overflow:visible'><?php echo html::select("module[$i]", $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'plan', ' hidden')?>' style='overflow:visible'><?php echo html::select("plan[$i]", $plans, $planID, "class='form-control chosen'");?></td>
      <td style='overflow:visible'>
        <div class='input-group'>
          <?php echo html::hidden("color[$i]", '', "data-provide='colorpicker' data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='{$lang->story->colorTag}' data-update-text='#title\\[{$i}\\]'");?>
          <?php echo html::input("title[$i]", $storyTitle, "class='form-control' autocomplete='off'") . html::hidden("uploadImage[$i]", $fileName);?>
          <span class='input-group-btn'>
            <a href='javascript:copyTitle(<?php echo $i;?>)' class='btn' title='<?php echo $lang->story->copyTitle; ?>'><i class='icon-angle-right'></i></a>
          </span>
        </div>
      </td>
      <td class='<?php echo zget($visibleFields, 'spec', 'hidden')?>'><?php echo html::textarea("spec[$i]", $spec, "rows='1' class='form-control autosize'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'source', ' hidden')?>'><?php echo html::select("source[$i]", $sourceList, $source, "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'verify', 'hidden')?>'><?php echo html::textarea("verify[$i]", '', "rows='1' class='form-control autosize'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'pri', ' hidden')?>' style='overflow:visible'><?php echo html::select("pri[$i]", $priList, $pri, "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'estimate', 'hidden')?>'><?php echo html::input("estimate[$i]", $estimate, "class='form-control' autocomplete='off'");?></td>
      <td class='<?php echo zget($visibleFields, 'review', 'hidden')?>'><?php echo html::select("needReview[$i]", $lang->story->reviewList, $needReview, "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?>'><?php echo html::input("keywords[$i]", '', "class='form-control' autocomplete='off'");?></td>
    </tr>
    <?php $i++;?>
    <?php endforeach;?>
    <?php $storyTitle = '';?>
    <?php endif;?>
    <?php $nextStart = $i;?>
    <?php for($i = $nextStart; $i < $config->story->batchCreate; $i++):?>
    <?php $moduleID = $i - $nextStart == 0 ? $moduleID : 'ditto';?>
    <?php $planID   = $i - $nextStart == 0 ? '' : 'ditto';?>
    <?php $pri      = $i - $nextStart == 0 ? '' : 'ditto';?>
    <?php $source   = $i - $nextStart == 0 ? '' : 'ditto';?>
    <tr class='text-center'>
      <td><?php echo $i+1;?></td>
      <td class='text-left<?php echo zget($visibleFields, $product->type, ' hidden')?>'><?php echo html::select("branch[$i]", $branches, $branch, "class='form-control' onchange='setModuleAndPlan(this.value, $productID, $i)'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'module', ' hidden')?>' style='overflow:visible'><?php echo html::select("module[$i]", $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'plan', ' hidden')?>' style='overflow:visible'><?php echo html::select("plan[$i]", $plans, $planID, "class='form-control chosen'");?></td>
      <td style='overflow:visible'>
        <div class='input-group'>
          <?php echo html::hidden("color[$i]", '', "data-provide='colorpicker' data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='{$lang->story->colorTag}' data-update-text='#title\\[{$i}\\]'");?>
          <?php echo html::input("title[$i]", $storyTitle, "class='form-control' autocomplete='off'");?>
          <span class='input-group-btn'>
            <a href='javascript:copyTitle(<?php echo $i;?>)' class='btn' title='<?php echo $lang->story->copyTitle; ?>'><i class='icon-angle-right'></i></a>
          </span>
        </div>
      </td>
      <td class='<?php echo zget($visibleFields, 'spec', 'hidden')?>'><?php echo html::textarea("spec[$i]", $spec, "rows='1' class='form-control autosize'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'source', ' hidden')?>'><?php echo html::select("source[$i]", $sourceList, $source, "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'verify', 'hidden')?>'><?php echo html::textarea("verify[$i]", '', "rows='1' class='form-control autosize'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'pri', ' hidden')?>' style='overflow:visible'><?php echo html::select("pri[$i]", $priList, $pri, "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'estimate', 'hidden')?>'><?php echo html::input("estimate[$i]", $estimate, "class='form-control' autocomplete='off'");?></td>
      <td class='<?php echo zget($visibleFields, 'review', 'hidden')?>'><?php echo html::select("needReview[$i]", $lang->story->reviewList, $needReview, "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?>'><?php echo html::input("keywords[$i]", '', "class='form-control' autocomplete='off'");?></td>
    </tr>  
    <?php endfor;?>
    <tr><td colspan='<?php echo count($visibleFields) + 2?>' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<table class='hide' id='trTemp'>
  <tbody>
    <tr class='text-center'>
      <td>%s</td>
      <td class='text-left<?php echo zget($visibleFields, $product->type, ' hidden')?>'><?php echo html::select("branch[%s]", $branch, $branch, "class='form-control' onchange='setModuleAndPlan(this.value, $productID, \"%s\")'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'module', ' hidden')?>' style='overflow:visible'><?php echo html::select("module[%s]", $moduleOptionMenu, $moduleID, "class='form-control'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'plan', ' hidden')?>' style='overflow:visible'><?php echo html::select("plan[%s]", $plans, $planID, "class='form-control'");?></td>
      <td style='overflow:visible'>
        <div class='input-group'>
          <?php echo html::hidden("color[%s]", '', "data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='{$lang->story->colorTag}' data-update-text='#title\\[%s\\]'");?>
          <?php echo html::input("title[%s]", $storyTitle, "class='form-control' autocomplete='off'");?>
          <span class='input-group-btn'>
            <a href='javascript:copyTitle(%s)' class='btn' title='<?php echo $lang->story->copyTitle; ?>'><i class='icon-angle-right'></i></a>
          </span>
        </div>
      </td>
      <td class='<?php echo zget($visibleFields, 'spec', ' hidden')?>'><?php echo html::textarea("spec[%s]", $spec, "rows='1' class='form-control autosize'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'source', ' hidden')?>'><?php echo html::select("source[%s]", $sourceList, $source, "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'verify', ' hidden')?>'><?php echo html::textarea("verify[%s]", '', "rows='1' class='form-control autosize'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'pri', ' hidden')?>' style='overflow:visible'><?php echo html::select("pri[%s]", $priList, $pri, "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'estimate', ' hidden')?>'><?php echo html::input("estimate[%s]", $estimate, "class='form-control autocomplete='off''");?></td>
      <td class='<?php echo zget($visibleFields, 'review', ' hidden')?>'><?php echo html::select("needReview[%s]", $lang->story->reviewList, $needReview, "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'keywords', ' hidden')?>'><?php echo html::input("keywords[%s]", '', "class='form-control autocomplete='off''");?></td>
    </tr>
  </tbody>
</table>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=story&section=custom&key=batchCreateFields')?>
<?php include '../../common/view/customfield.html.php';?>
<?php include '../../common/view/pastetext.html.php';?>
<?php include '../../common/view/footer.html.php';?>
