<?php
/**
 * The create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: create.html.php 4902 2013-06-26 05:25:58Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $this->app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('page', 'create');?>
<?php js::set('holders', $lang->story->placeholder); ?>
<?php js::set('blockID', $blockID); ?>
<?php js::set('feedbackSource', $config->story->feedbackSource); ?>
<?php js::set('storyType', $type);?>
<?php if(common::checkNotCN()):?>
<style> .sourceTd > .input-group > .input-group > .input-group-addon:first-child{padding: 5px 18px} </style>
<?php endif;?>
<style>
.close-modal-btn {margin-right: -48px;}
</style>
<div id="mainContent" class="main-content">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->story->create;?></h2>
      <?php if(!$this->story->checkForceReview()):?>
      <div class="needNotReviewBox">
        <div class='checkbox-primary'>
          <input id='needNotReview' name='needNotReview' value='1' type='checkbox' class='no-margin' <?php echo $needReview;?>/>
          <label for='needNotReview'><?php echo $lang->story->needNotReview;?></label>
        </div>
      </div>
      <?php endif;?>
      <?php if(isonlybody()):?>
      <div class='btn-toolbar pull-right close-modal-btn'>
        <button id="closeModal" type="button" class="btn btn-link" data-dismiss="modal"><i class="icon icon-close"></i></button>
      </div>
      <?php endif;?>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->story->module;?></th>
            <td colspan='2'>
              <div class='input-group' id='moduleIdBox'>
                <?php
                echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control chosen'");
                if(count($moduleOptionMenu) == 1)
                {
                    echo "<div class='input-group-addon'>";
                    echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=story&currentModuleID=0&branch=$branch", '', true), $lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='90%'");
                    echo '&nbsp; ';
                    echo html::a("javascript:void(0)", $lang->refreshIcon, '', "class='refresh' title='refresh' onclick='loadProductModules($productID)'");
                    echo '</div>';
                }
                ?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->story->reviewedBy;?></th>
            <td colspan='2' id='reviewerBox'>
              <div class="table-row">
                <?php $required = $this->story->checkForceReview() ? 'required' : '';?>
                <?php echo $this->story->checkForceReview() ? '' : html::hidden('needNotReview', 1);?>
                <div class="table-col">
                  <?php echo html::select('reviewer[]', $reviewers, empty($needReview) ? $product->PO : '', "class='form-control picker-select' multiple $required");?>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->story->title;?></th>
            <td colspan="4">
              <div class='table-row'>
                <div class='table-col'>
                  <div class="input-control has-icon-right">
                    <?php echo html::input('title', $storyTitle, "class='form-control' required");?>
                    <div class="colorpicker">
                      <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                      <ul class="dropdown-menu clearfix">
                        <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                      </ul>
                      <input type="hidden" class="colorpicker" id="color" name="color" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title"  data-provide="colorpicker">
                    </div>
                  </div>
                </div>
                <div class='table-col w-120px'>
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
                    if(end($priList)) unset($priList[0]);
                    if(!isset($priList[$pri]))
                    {
                        reset($priList);
                        $pri = key($priList);
                    }
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
            <td colspan="4">
              <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=story&link=spec');?>
              <?php echo html::textarea('spec', $spec, "rows='9' class='form-control kindeditor disabled-ie-placeholder' hidefocus='true' placeholder='" . htmlSpecialString($lang->noticePasteImg) . "'");?>
            </td>
          </tr>
          <?php $this->printExtendFields('', 'table', 'columns=4');?>
          <tr>
            <th><?php echo $lang->story->legendAttatch;?></th>
            <td colspan='4'><?php echo $this->fetch('file', 'buildform');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->mailto;?></th>
            <td colspan="4">
              <div class="input-group">
                <?php echo html::select('mailto[]', $users, str_replace(' ' , '', $mailto), "class='form-control picker-select' data-placeholder='{$lang->chooseUsersToMail}' multiple");?>
                <?php echo $this->fetch('my', 'buildContactLists');?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->story->keywords;?></th>
            <td colspan="4">
              <?php echo html::input('keywords', $keywords, 'class="form-control"');?>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5" class="text-center form-actions">
              <?php echo html::hidden('type', $type);?>
              <?php echo html::hidden('product', $productID);?>
              <?php echo html::hidden('plan', $planID);?>
              <?php echo html::hidden('vision', 'lite');?>
              <?php echo html::commonButton($lang->save, "id='saveButton'", 'btn btn-primary btn-wide');?>
              <?php echo html::commonButton($lang->story->saveDraft, "id='saveDraftButton'", 'btn btn-secondary btn-wide');?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<?php js::set('executionID', $objectID);?>
<?php js::set('storyModule', $lang->story->module);?>
<?php js::set('storyType', $type);?>
<script>
$(function(){parent.$('body.hide-modal-close').removeClass('hide-modal-close');})

function loadProductModules(productID)
{
    var branch        = 0;
    var currentModule = 0;
    var moduleLink    = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true&extra=&currentModuleID=' + currentModule);
    var $moduleIDBox  = $('#moduleIdBox');
    $moduleIDBox.load(moduleLink, function()
    {
        $moduleIDBox.find('#module').chosen();
    });
}
</script>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
