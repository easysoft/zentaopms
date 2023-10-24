<?php
/**
 * The edit view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: edit.html.php 4645 2013-04-11 08:32:09Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $this->app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('page', 'edit')?>
<?php js::set('oldProductID', $story->product);?>
<?php js::set('storyID', $story->id);?>
<?php js::set('parentStory', !empty($story->children));?>
<?php js::set('moveChildrenTips', $lang->story->moveChildrenTips);?>
<?php js::set('rawModule', $this->app->rawModule);?>
<?php js::set('reviewedReviewer', $reviewedReviewer);?>
<?php js::set('storyModule', $lang->story->module);?>
<?php js::set('reviewers', $reviewers);?>
<?php js::set('reviewerNotEmpty', $lang->story->notice->reviewerNotEmpty);?>
<?php js::set('feedbackSource', $config->story->feedbackSource); ?>
<?php js::set('storyStatus', $story->status);?>
<div class='main-content' id='mainContent'>
  <form method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $story->id;?></span>
        <?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title, '', 'class="story-title"');?>
        <small><?php echo $lang->arrow . ' ' . $lang->story->edit;?></small>
      </h2>
    </div>
    <div class='main-row'>
      <div class='main-col col-8'>
        <div class='cell'>
          <div class='form-group titleBox'>
            <div class="input-control has-icon-right">
              <div class="colorpicker">
                <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" title="<?php echo $lang->task->colorTag ?>"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                <ul class="dropdown-menu clearfix">
                  <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                </ul>
                <input type="hidden" class="colorpicker" id="color" name="color" value="<?php echo $story->color ?>" data-icon="color" data-wrapper="input-control-icon-right" data-update-color=".story-title"  data-provide="colorpicker">
              </div>
              <?php echo html::input('title', $story->title, 'class="form-control disabled story-title"' . (strpos('draft,changing', $story->status) !== false ? '' : ' disabled="disabled"'));?>
            </div>
          </div>
          <?php if(strpos('draft,changing', $story->status) !== false):?>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->reviewers;?></div>
            <div class='detail-content'>
              <div class="table-row">
                <?php if(!$this->story->checkForceReview()):?>
                <div class="table-col">
                  <?php echo html::select('reviewer[]', $productReviewers, $reviewers, 'class="form-control picker-select" multiple')?>
                </div>
                <div class="table-col needNotReviewBox">
                  <span class="input-group-addon" style="border: 1px solid #dcdcdc; border-left-width: 0px;">
                    <div class='checkbox-primary'>
                      <input id='needNotReview' name='needNotReview' value='1' type='checkbox' class='no-margin' <?php echo empty($reviewers) ? 'checked' : '';?>/>
                      <label for='needNotReview'><?php echo $lang->story->needNotReview;?></label>
                    </div>
                  </span>
                </div>
                <?php else:?>
                <div class="table-col">
                  <?php echo html::select('reviewer[]', $productReviewers, $reviewers, 'class="form-control picker-select" multiple required')?>
                </div>
                <?php endif;?>
              </div>
            </div>
          </div>
          <?php endif;?>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->legendSpec;?></div>
            <div class='detail-content article-content'>
              <?php echo strpos('draft,changing', $story->status) !== false ? html::textarea('spec', htmlSpecialString($story->spec), "rows='5' class='form-control'") : $story->spec;?>
            </div>
          </div>
          <?php $showFile = (strpos('draft,changing', $story->status) === false and empty($files)) ? false : true;?>
          <?php if($showFile):?>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->attatch;?></div>
            <div class='form-group'>
              <?php $canChangeFile = strpos('draft,changing', $story->status) !== false ? true : false;?>
              <?php echo $this->fetch('file', 'printFiles', array('files' => $story->files, 'fieldset' => 'false', 'object' => $story, 'method' => 'edit', 'showDelete' => $canChangeFile));?>
              <?php echo $canChangeFile ? $this->fetch('file', 'buildform') : '';?>
            </div>
          </div>
          <?php endif;?>
          <?php $this->printExtendFields($story, 'div', 'position=left');?>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->comment;?></div>
            <div class='form-group'>
              <?php echo html::textarea('comment', '', "rows='5' class='form-control'");?>
            </div>
          </div>
          <div class='actions form-actions text-center'>
            <?php
            echo html::hidden('lastEditedDate', $story->lastEditedDate);
            echo html::hidden('product', $story->product);
            if(strpos('draft,changing', $story->status) !== false)
            {
                echo html::commonButton($lang->save, "id='saveButton'", 'btn btn-primary btn-wide');
                echo html::commonButton($story->status == 'changing' ? $lang->story->doNotSubmit : $lang->story->saveDraft, "id='saveDraftButton'", 'btn btn-secondary btn-wide');
            }
            else
            {
                echo html::submitButton($lang->save);
            }
            if(!isonlybody()) echo html::backButton();
            ?>
          </div>
          <hr class='small' />
          <?php include $this->app->getModuleRoot() . 'common/view/action.html.php';?>
        </div>
      </div>
      <div class='side-col col-4'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->legendBasicInfo;?></div>
            <table class='table table-form'>
              <tr>
                <th class='thWidth'><?php echo $lang->story->module;?></th>
                <td>
                  <div class='input-group' id='moduleIdBox'>
                  <?php
                  echo html::select('module', $moduleOptionMenu, $story->module, "class='form-control chosen'");
                  if(count($moduleOptionMenu) == 1)
                  {
                      echo "<span class='input-group-addon'>";
                      echo html::a($this->createLink('tree', 'browse', "rootID=$story->product&view=story&currentModuleID=0&branch=$story->branch", '', true), $lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
                      echo '&nbsp; ';
                      echo html::a("javascript:void(0)", $lang->refreshIcon, '', "class='refresh' title='$lang->refresh' onclick='loadProductModules($story->product)'");
                      echo '</span>';
                  }
                  ?>
                  </div>
                </td>
              </tr>
              <?php if($story->parent >= 0 and $story->type == 'story'):?>
              <tr>
                <th><?php echo $lang->story->parent;?></th>
                <td><?php echo html::select('parent', $stories, $story->parent, "class='form-control chosen'");?></td>
              </tr>
              <?php endif;?>
              <tr>
                <th><?php echo $lang->story->status;?></th>
                <td>
                  <span class='story-<?php echo $story->status;?>'><?php echo $this->processStatus('story', $story);?></span>
                  <?php echo html::hidden('status', $story->status);?>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->story->pri;?></th>
                <td><?php echo html::select('pri', $lang->story->priList, $story->pri, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->estimate;?></th>
                <td><?php echo $story->parent >= 0 ? html::input('estimate', $story->estimate, "class='form-control'") : $story->estimate;?></td>
              </tr>
              <tr class='feedbackBox <?php echo in_array($story->source, $config->story->feedbackSource) ? '' : 'hidden';?>'>
                <th><?php echo $lang->story->feedbackBy;?></th>
                <td><?php echo html::input('feedbackBy', $story->feedbackBy, "class='form-control'");?></td>
              </tr>
              <tr class='feedbackBox <?php echo in_array($story->source, $config->story->feedbackSource) ? '' : 'hidden';?>'>
                <th><?php echo $lang->story->notifyEmail;?></th>
                <td><?php echo html::input('notifyEmail', $story->notifyEmail, "class='form-control'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->keywords;?></th>
                <td><?php echo html::input('keywords', $story->keywords, "class='form-control'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->mailto;?></th>
                <td>
                  <div class='input-group'>
                    <?php echo html::select('mailto[]', $users, str_replace(' ' , '', $story->mailto), "class='form-control picker-select' multiple");?>
                    <?php echo $this->fetch('my', 'buildContactLists');?>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->legendLifeTime;?></div>
            <table class='table table-form'>
              <tr>
                <th class='thWidth'><?php echo $lang->story->openedBy;?></th>
                <td><?php echo zget($users, $story->openedBy);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->assignedTo;?></th>
                <?php $assignedToList = $story->status == 'closed' ? $users + array('closed' => 'Closed') : $users;?>
                <td><?php echo html::select('assignedTo', $assignedToList, $story->assignedTo, 'class="form-control chosen"');?></td>
              </tr>
              <?php if($story->status == 'reviewing'):?>
              <tr>
                <th><?php echo $lang->story->reviewers;?></th>
                <td><?php echo html::select('reviewer[]', $productReviewers, $reviewers, 'class="form-control picker-select" multiple')?></td>
              </tr>
              <?php endif;?>
              <?php if($story->status == 'closed'):?>
              <tr>
                <th><?php echo $lang->story->closedBy;?></th>
                <td><?php echo html::select('closedBy', $users, $story->closedBy, 'class="form-control chosen"');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->closedReason;?></th>
                <td><?php echo html::select('closedReason', $lang->story->reasonList, $story->closedReason, "class='form-control'  onchange='setStory(this.value)'");?></td>
              </tr>
              <?php endif;?>
            </table>
          </div>

          <?php $this->printExtendFields($story, 'div', 'position=right');?>

        </div>
      </div>
    </div>
  </form>
</div>
<?php js::set('storyType', $story->type);?>
<?php js::set('executionID', isset($objectID) ? $objectID : 0);?>
<script>
function loadProductModules(productID)
{
    var branch        = 0;
    var currentModule = $('#module').val();
    var moduleLink    = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true&extra=&currentModuleID=' + currentModule);
    var $moduleIDBox  = $('#moduleIdBox');
    $moduleIDBox.load(moduleLink, function()
    {
        $moduleIDBox.find('#module').chosen();
    });
}
</script>
<?php include $this->app->getModuleRoot() . 'common/view/footer.html.php';?>
