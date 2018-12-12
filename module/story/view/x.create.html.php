<?php
/**
 * The create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: create.html.php 4902 2013-06-26 05:25:58Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<style>
body{padding-bottom: 0px}
</style>
<div id="mainContent" class="main-content fade">
  <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
    <table class="table table-form">
      <tbody>
        <tr>
          <th class='w-70px'><?php echo $lang->story->product;?></th>
          <td colspan="2">
            <?php if($product->type != 'normal'):?>
            <div class='input-group'>
            <?php endif;?>
            <?php echo html::select('product', $products, $productID, "onchange='loadProduct(this.value);' class='form-control chosen control-product'");?>
            <?php if($product->type != 'normal'):?>
            <span class='input-group-addon fix-border fix-padding'></span>
            <?php echo html::select('branch', $branches, $branch, "onchange='loadBranch();' class='form-control chosen control-branch'");?>
            </div>
            <?php endif;?>
          </td>
          <td colspan="2">
            <div class='input-group' id='moduleIdBox'>
              <div class="input-group-addon"><?php echo $lang->story->module;?></div>
              <?php echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control chosen'"); ?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->story->plan;?></th>
          <td colspan="2">
            <div class='input-group' id='planIdBox'>
              <?php echo html::select('plan', $plans, $planID, "class='form-control chosen'"); ?>
            </div>
          </td>
          <td colspan="2">
            <div class="input-group">
              <div class="input-group">
                <div class="input-group-addon"><?php echo $lang->story->source;?></div>
                <?php echo html::select('source', $lang->story->sourceList, $source, "class='form-control chosen'");?>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->story->reviewedBy;?></th>
          <td><?php echo html::select('assignedTo', $users, empty($needReview) ? $product->PO : '', "class='form-control chosen'");?></td>
          <?php if(!$this->story->checkForceReview()):?>
          <td>
            <div class='checkbox-primary'>
              <input id='needNotReview' name='needNotReview' value='1' type='checkbox' class='no-margin' <?php echo $needReview;?>/>
              <label for='needNotReview'><?php echo $lang->story->needNotReview;?></label>
            </div>
          </td>
          <?php endif;?>
          <td colspan='2'>
            <div class="input-group">
              <span class='input-group-addon'><?php echo $lang->story->sourceNote;?></span>
              <?php echo html::input('sourceNote', $sourceNote, "class='form-control' autocomplete='off'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->story->title;?></th>
          <td colspan="4">
            <div class='table-row'>
              <div class='table-col'>
                <div class="input-control has-icon-right">
                  <?php echo html::input('title', $storyTitle, "class='form-control' autocomplete='off' required");?>
                  <div class="colorpicker">
                    <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                    <ul class="dropdown-menu clearfix">
                      <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                    </ul>
                    <input type="hidden" class="colorpicker" id="color" name="color" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title"  data-provide="colorpicker">
                  </div>
                </div>
              </div>
              <div class='table-col w-150px'>
                <div class="input-group">
                  <span class="input-group-addon fix-border br-0"><?php echo $lang->story->pri;?></span>
                  <?php
                  $hasCustomPri = false;
                  foreach($lang->story->priList as $priKey => $priValue)
                  {
                      if(!empty($priKey) and (string)$priKey != (string)$priValue)
                      {
                          $hasCustomPri = true;
                          break;
                      }
                  }
                  $priList = $lang->story->priList;
                  ?>
                  <?php if($hasCustomPri):?>
                  <?php echo html::select('pri', (array)$priList, $pri, "class='form-control'");?>
                  <?php else:?>
                  <div class="input-group-btn pri-selector" data-type="pri">
                    <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
                      <span class="pri-text"><span class="label-pri label-pri-<?php echo empty($pri) ? '0' : $pri?>" title="<?php echo $pri?>"><?php echo $pri?></span></span> &nbsp;<span class="caret"></span>
                    </button>
                    <div class='dropdown-menu pull-right'>
                      <?php echo html::select('pri', (array)$priList, $pri, "class='form-control' data-provide='labelSelector' data-label-class='label-pri'");?>
                    </div>
                  </div>
                  <?php endif;?>
                </div>
              </div>
              <div class='table-col w-120px'>
                <div class="input-group">
                  <span class="input-group-addon fix-border br-0"><?php echo $lang->story->estimateAB;?></span>
                  <input type="text" name="estimate" id="estimate" value="<?php echo $estimate;?>" class="form-control" autocomplete="off" placeholder='<?php echo $lang->story->hour;?>' />
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->story->spec;?></th>
          <td colspan="4"><?php echo html::textarea('spec', $spec, "rows='9' class='form-control kindeditor disabled-ie-placeholder' hidefocus='true' placeholder='" . htmlspecialchars($lang->story->specTemplate) . "'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->story->verify;?></th>
          <td colspan="4"><?php echo html::textarea('verify', $verify, "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->story->mailto;?></th>
          <td colspan="4">
            <div class="input-group">
              <?php echo html::select('mailto[]', $users, str_replace(' ' , '', $mailto), "class='form-control chosen' data-placeholder='{$lang->chooseUsersToMail}' multiple");?>
              <?php echo $this->fetch('my', 'buildContactLists');?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->story->keywords;?></th>
          <td colspan="4">
            <?php echo html::input('keywords', $keywords, 'class="form-control" autocomplete="off"');?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->story->legendAttatch;?></th>
          <td colspan='4'><?php echo $this->fetch('file', 'buildform');?></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5" class="text-center form-actions">
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<?php js::set('storyModule', $lang->story->module);?>
