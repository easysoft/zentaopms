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
<?php include './header.html.php';?>
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
<?php js::set('lastReviewer', explode(',', $lastReviewer))?>
<?php js::set('twins', $story->twins)?>
<?php js::Set('relievedTwinsTip', $lang->story->relievedTwinsTip)?>
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
                  <?php echo html::select('reviewer[]', $hiddenProduct ? $teamUsers : $productReviewers, $reviewers, 'class="form-control picker-select" multiple')?>
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
                  <?php echo html::select('reviewer[]', $hiddenProduct ? $teamUsers : $productReviewers, $reviewers, 'class="form-control picker-select" multiple required')?>
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
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->verify;?></div>
            <div class='detail-content article-content'>
              <?php echo strpos('draft,changing', $story->status) !== false ? html::textarea('verify', htmlSpecialString($story->verify), "rows='5' class='form-control'") : $story->verify;?>
            </div>
          </div>
          <?php $showFile = (strpos('draft,changing', $story->status) === false and empty($story->files)) ? false : true;?>
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
          <?php if(!empty($twins)):?>
          <div class='detail' id='legendTwins'>
            <div class='detail-title'>
              <?php echo $lang->story->changeSyncTip;?>
              <span data-toggle='tooltip' data-placement='right' title='<?php echo $lang->story->syncTip;?>'><i class='icon-help'></i></span>
            </div>
            <div class='form-group'>
              <div>
                <ul class='list-unstyled'>
                  <?php include './blocktwins.html.php';?>
                </ul>
              </div>
            </div>
          </div>
          <?php endif;?>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->comment;?></div>
            <div class='form-group'>
              <?php echo html::textarea('comment', '', "rows='5' class='form-control'");?>
            </div>
          </div>
          <div class='actions form-actions text-center'>
            <?php
            echo html::hidden('lastEditedDate', $story->lastEditedDate);
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
          <?php include '../../common/view/action.html.php';?>
        </div>
      </div>
      <div class='side-col col-4'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->legendBasicInfo;?></div>
            <table class='table table-form'>
              <?php if($story->parent <= 0 && !$hiddenProduct):?>
              <tr class="<?php if($hiddenProduct) echo 'hidden';?>">
                <th class='thWidth'><?php echo $lang->story->product;?></th>
                <td id='productBox'>
                  <div class='input-group'>
                    <?php $class = $hiddenProduct ? 'disabled' : ''?>
                    <?php echo html::select('product', $products, $story->product, "onchange='loadProduct(this.value);' class='form-control chosen control-product $class' data-max_drop_width=100%");?>
                    <span class='input-group-addon fix-border fix-padding'></span>
                    <?php if($product->type != 'normal') echo html::select('branch', $branchTagOption, $story->branch, "onchange='loadBranch();' class='form-control chosen control-branch' data-max_drop_width=100%");?>
                  </div>
                </td>
              </tr>
              <?php elseif($product->type != 'normal'):?>
              <tr>
                <th class='thWidth'><?php echo $lang->product->branch = sprintf($lang->product->branch, $lang->product->branchName['branch']);?></th>
                <td>
                  <div class='input-group'><?php if($product->type != 'normal') echo html::select('branch', $branchTagOption, $story->branch, "onchange='loadBranch();' class='form-control chosen control-branch' data-max_drop_width=100%");?></div>
                </td>
              </tr>
              <?php endif;?>
              <tr>
                <th class='thWidth'><?php echo $lang->story->module;?></th>
                <td>
                  <div class='input-group' id='moduleIdBox'>
                  <?php
                  echo html::select('module', $moduleOptionMenu, $story->module, "class='form-control chosen' data-max_drop_width=100%");
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
              <?php if($app->tab == 'product'):?>
              <tr class="<?php if($hiddenParent) echo 'hidden';?>">
                <th><?php echo $lang->story->parent;?></th>
                <td><?php echo html::select('parent', $stories, $story->parent, "class='form-control chosen' data-max_drop_width=100%");?></td>
              </tr>
              <?php endif;?>
              <tr class="<?php if($hiddenPlan) echo 'hidden';?>">
                <th><?php echo $lang->story->plan;?></th>
                <td>
                  <div class='input-group' id='planIdBox'>
                  <?php $planCount = !empty($story->planTitle) ? count($story->planTitle) : 0?>
                  <?php $multiple  = ($this->session->currentProductType != 'normal' and empty($story->branch) and $planCount > 1) ? 'multiple' : '';?>
                  <?php echo html::select(!empty($multiple) ? 'plan[]' : 'plan', $plans, $story->plan, "class='form-control chosen' data-max_drop_width=100% " . $multiple);
                  if(count($plans) == 1)
                  {
                      echo "<span class='input-group-addon'>";
                      echo html::a($this->createLink('productplan', 'create', "productID=$story->product&branch=$story->branch", '', true), $lang->productplan->create, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
                      echo html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadProductPlans($story->product)'");
                      echo '</span>';
                  }
                  ?>
                  </div>
                </td>
              </tr>
              <?php endif;?>
              <tr>
                <th><?php echo $lang->story->source;?></th>
                <td><?php echo html::select('source', $lang->story->sourceList, $story->source, "class='form-control chosen' data-max_drop_width=100%");?></td>
              </tr>
              <tr>
                <th id='sourceNoteBox'><?php echo $lang->story->sourceNote;?></th>
                <td><?php echo html::input('sourceNote', $story->sourceNote, "class='form-control'");?>
              </td>
              </tr>
              <tr>
                <th><?php echo $lang->story->status;?></th>
                <td>
                  <span class='story-<?php echo $story->status;?>'><?php echo $this->processStatus('story', $story);?></span>
                  <?php echo html::hidden('status', $story->status);?>
                </td>
              </tr>
              <?php if($story->type == 'story'):?>
              <tr>
                <th><?php echo $lang->story->stage;?></th>
                <td>
                <?php
                $maxStage    = $story->stage;
                $stageList   = join(',', array_keys($this->lang->story->stageList));
                $maxStagePos = strpos($stageList, $maxStage);
                if($story->stages and $branchTagOption)
                {
                    foreach($story->stages as $branch => $stage)
                    {
                        if(strpos($stageList, $stage) !== false and strpos($stageList, $stage) > $maxStagePos)
                        {
                            $maxStage    = $stage;
                            $maxStagePos = strpos($stageList, $stage);
                        }
                    }
                }
                echo html::select('stage', $lang->story->stageList, $maxStage, "class='form-control chosen' data-max_drop_width=100%");
                ?>
                </td>
              </tr>
              <?php endif;?>
              <tr>
                <th><?php echo $lang->story->category;?></th>
                <?php if(empty($lang->story->categoryList[$story->category]) && !empty($lang->story->ipdCategoryList[$story->category])) $lang->story->categoryList[$story->category] = $lang->story->ipdCategoryList[$story->category];?>
                <td><?php echo html::select('category', $lang->story->categoryList, $story->category, "class='form-control chosen' data-max_drop_width=100%");?></td>
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
                    <?php echo html::select('mailto[]', $users, $story->mailto, "class='form-control picker-select' multiple");?>
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
                <td><?php echo html::select('assignedTo', $hiddenProduct ? $teamUsers : $assignedToList, $story->assignedTo, 'class="form-control chosen"');?></td>
              </tr>
              <?php if($story->status == 'reviewing'):?>
              <tr>
                <th><?php echo $lang->story->reviewers;?></th>
                <td><?php echo html::select('reviewer[]', $hiddenProduct ? $teamUsers : $productReviewers, $reviewers, 'class="form-control picker-select" multiple')?></td>
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

          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->legendMisc;?></div>
            <table class='table table-form'>
              <?php if($story->status == 'closed'):?>
              <tr id='duplicateStoryBox'>
                <th class='w-100px'><?php echo $lang->story->duplicateStory;?></th>
                <td><?php echo html::select('duplicateStory', array('' => '') + $productStories, $story->duplicateStory ? $story->duplicateStory : '', "class='form-control' placeholder='{$lang->bug->duplicateTip}'"); ?></td>
              </tr>
              <?php endif;?>
              <tr class='text-top'>
                <th class='thWidth'><?php echo $story->type == 'story' ? $lang->requirement->linkStory : $lang->story->linkStory;?></th>
                <td>
                  <?php if(common::hasPriv('story', 'linkStories') and $story->type == 'story') echo html::a("#", $lang->story->linkStoriesAB, '', "class='btn btn-info' id='linkStoriesLink'");?>
                  <?php if(common::hasPriv('requirement', 'linkRequirements') and $story->type == 'requirement') echo html::a("#", $lang->story->linkRequirementsAB, '', "class='btn btn-info' id='linkStoriesLink'");?>
                </td>
              </tr>
              <tr>
                <th></th>
                <td class='linkStoryTd'>
                  <ul class='list-unstyled'>
                    <?php
                    $linkStoryField = $story->type == 'story' ? 'linkStories' : 'linkRequirements';
                    if(isset($story->linkStoryTitles))
                    {
                        foreach($story->linkStoryTitles as $linkStoryID => $linkStoryTitle)
                        {
                            echo "<li><div class='checkbox-primary' title='$linkStoryTitle'>";
                            echo "<input type='checkbox' checked='checked' name='" . $linkStoryField . "[]' value=$linkStoryID />";
                            echo "<label class='linkStoryTitle'>#{$linkStoryID} {$linkStoryTitle}</label>";
                            echo '</div></li>';
                        }
                    }
                    ?>
                    <span id='linkStoriesBox'></span>
                  </ul>
                </td>
              </tr>
           </table>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<?php js::set('storyType', $story->type);?>
<?php js::set('executionID', isset($objectID) ? $objectID : 0);?>
<?php include '../../common/view/footer.html.php';?>
