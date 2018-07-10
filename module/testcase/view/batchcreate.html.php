<?php
/**
 * The batch create view of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('testcaseBatchCreateNum', $config->testcase->batchCreate);?>
<?php js::set('productID', $productID);?>
<?php js::set('branch', $branch);?>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2>
      <?php echo $lang->testcase->batchCreate;?>
      <?php if($this->session->currentProductType !== 'normal') echo '<span class="label label-info">' . $branches[$branch] . '</span>';?>
      <?php if($story):?>
      <small class='text' title='<?php echo $story->title ?>'><?php echo $lang->arrow . $story->title ?></small>
      <?php endif;?>
    </h2>
    <div class="pull-right btn-toolbar">
      <button type='button' data-toggle='modal' data-target="#importLinesModal" class="btn btn-info"><?php echo $lang->pasteText;?></button>
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=testcase&section=custom&key=batchCreateFields')?>
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
  foreach(explode(',', $config->testcase->create->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->testcase->customBatchCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  $colspan     = count($visibleFields) + 3;
  $hiddenStory = (isonlybody() and $story) ? ' hidden' : '';
  if($hiddenStory and isset($visibleFields['story'])) $colspan -= 1;
  ?>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchCreateForm">
    <div class="table-responsive">
      <table class="table table-form" id="tableBody">
        <thead>
          <tr class='text-center'>
            <th class='w-50px'><?php echo $lang->idAB;?></th>
            <th class='w-120px<?php echo zget($visibleFields, $product->type, ' hidden')?>'><?php echo $lang->product->branch;?></th>
            <th class='w-180px<?php echo zget($visibleFields, 'module', ' hidden') . zget($requiredFields, 'module', '', ' required');?>'><?php echo $lang->testcase->module;?></th>
            <th class='w-180px<?php echo zget($visibleFields, 'story',  ' hidden') . zget($requiredFields, 'story',  '', ' required'); echo $hiddenStory;?>'> <?php echo $lang->testcase->story;?></th>
            <th class='text-left required has-btn c-title'><?php echo $lang->testcase->title;?></th>
            <th class='w-120px text-left required'><?php echo $lang->testcase->type;?></th>
            <th class='w-80px<?php  echo zget($visibleFields, 'pri',          ' hidden') . zget($requiredFields, 'pri',          '', ' required')?>'><?php echo $lang->testcase->pri;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'precondition', ' hidden') . zget($requiredFields, 'precondition', '', ' required')?>'><?php echo $lang->testcase->precondition;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'keywords',     ' hidden') . zget($requiredFields, 'keywords',     '', ' required')?>'><?php echo $lang->testcase->keywords;?></th>
            <th class='w-140px<?php echo zget($visibleFields, 'stage',        ' hidden') . zget($requiredFields, 'stage',        '', ' required')?>'><?php echo $lang->testcase->stage;?></th>
            <th class='w-70px<?php  echo zget($visibleFields, 'review',       ' hidden') . zget($requiredFields, 'review',       '', ' required')?>'><?php echo $lang->testcase->review;?></th>
          </tr>
        </thead>
        <tbody>
          <?php unset($lang->testcase->typeList['']);?>
          <?php for($i = 0; $i < $config->testcase->batchCreate; $i++):?>
          <?php
          if($i != 0) $currentModuleID = 'ditto';
          if($i != 0) $lang->testcase->typeList['ditto'] = $lang->testcase->ditto;
          if($i != 0) $lang->testcase->priList['ditto']  = $lang->testcase->ditto;
          $type = $i == 0 ? 'feature' : 'ditto';
          $pri  = $i == 0 ? 3 : 'ditto';
          ?>
          <tr>
            <td class="text-center"><?php echo $i+1;?></td>
            <td class='text-left<?php echo zget($visibleFields, $product->type, ' hidden')?>'><?php echo html::select("branch[$i]", $branches, $branch, "class='form-control' onchange='setModules(this.value, $productID, $i)'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'module', ' hidden')?>' style='overflow:visible'><?php echo html::select("module[$i]", $moduleOptionMenu, $currentModuleID, "class='form-control chosen'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'story', ' hidden'); echo $hiddenStory;?>' style='overflow:visible'> <?php echo html::select("story[$i]", $storyList, $story ? $story->id : '', 'class="form-control chosen"');?></td>
            <td style='overflow:visible'>
              <div class="input-control has-icon-right">
                <?php echo html::input("title[$i]", '', "class='form-control title-import' autocomplete='off'");?>
                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix">
                    <li class="heading"><?php echo $lang->testcase->colorTag;?><i class="icon icon-close"></i></li>
                  </ul>
                  <?php echo html::hidden("color[$i]", '', "data-provide='colorpicker' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#title\\[$i\\]'");?>
                </div>
              </div>
            </td>
            <td><?php echo html::select("type[$i]", $lang->testcase->typeList, $type, "class='form-control chosen'");?></td>
            <td class='<?php echo zget($visibleFields, 'pri', 'hidden')?>'>         <?php echo html::select("pri[$i]", $lang->testcase->priList, $pri, "class='form-control chosen'");?></td>
            <td class='<?php echo zget($visibleFields, 'precondition', 'hidden')?>'><?php echo html::textarea("precondition[$i]", '', "rows='1' class='form-control autosize'")?></td>
            <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?>'>    <?php echo html::input("keywords[$i]", '', "class='form-control' autocomplete='off'");?></td>
            <td class='text-left<?php echo zget($visibleFields, 'stage', ' hidden')?>' style='overflow:visible'><?php echo html::select("stage[$i][]", $lang->testcase->stageList, '', "class='form-control chosen' multiple");?></td>
            <td class='<?php echo zget($visibleFields, 'review', 'hidden')?>'><?php echo html::select("needReview[$i]", $lang->testcase->reviewList, $needReview, "class='form-control'");?></td>
          </tr>
          <?php endfor;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='<?php echo $colspan?>' class='text-center form-actions'>
              <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
              <?php echo html::backButton('', '', 'btn btn-wide');?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<table class='template' id='trTemp'>
  <tbody>
    <tr>
      <td class="text-center">%s</td>
      <td class='text-left<?php echo zget($visibleFields, $product->type, ' hidden')?>'><?php echo html::select("branch[%s]", $branches, $branch, "class='form-control chosen' onchange='setModules(this.value, $productID, \"%s\")'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'module', ' hidden')?>' style='overflow:visible'><?php echo html::select("module[%s]", $moduleOptionMenu, $currentModuleID, "class='form-control chosen'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'story', ' hidden'); echo $hiddenStory;?>' style='overflow:visible'> <?php echo html::select("story[%s]", '', '', 'class="form-control"');?></td>
      <td style='overflow:visible'>
        <div class="input-control has-icon-right">
          <?php echo html::input("title[%s]", '', "class='form-control title-import' autocomplete='off'");?>
          <div class="colorpicker">
            <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
            <ul class="dropdown-menu clearfix">
              <li class="heading"><?php echo $lang->testcase->colorTag;?><i class="icon icon-close"></i></li>
            </ul>
            <?php echo html::hidden("color[%s]", '', "data-provide='colorpicker-later' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#title\\[%s\\]'");?>
          </div>
        </div>
      </td>
      <td><?php echo html::select("type[%s]", $lang->testcase->typeList, $type, "class=form-control chosen");?></td>
      <td class='<?php echo zget($visibleFields, 'pri', 'hidden')?>'>         <?php echo html::select("pri[%s]", $lang->testcase->priList, $pri, "class=form-control chosen");?></td>
      <td class='<?php echo zget($visibleFields, 'precondition', 'hidden')?>'><?php echo html::textarea("precondition[%s]", '', "rows='1' class='form-control'")?></td>
      <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?>'>    <?php echo html::input("keywords[%s]", '', "class='form-control' autocomplete='off'");?></td>
      <td class='text-left<?php echo zget($visibleFields, 'stage', ' hidden')?>' style='overflow:visible'><?php echo html::select("stage[%s][]", $lang->testcase->stageList, '', "class='form-control chosen' multiple");?></td>
      <td class='<?php echo zget($visibleFields, 'review', 'hidden')?>'><?php echo html::select("needReview[%s]", $lang->testcase->reviewList, $needReview, "class='form-control'");?></td>
    </tr>
  </tbody>
</table>
<?php include '../../common/view/pastetext.html.php';?>
<?php include '../../common/view/footer.html.php';?>
