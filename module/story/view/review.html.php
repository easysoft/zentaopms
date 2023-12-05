<?php
/**
 * The view file of review method of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: review.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div class='main-content' id='mainContent'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $story->id;?></span>
        <?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?>
        <small><?php echo $lang->arrow . $lang->story->review;?></small>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='thWidth'><?php echo $lang->story->reviewedDate;?></th>
          <td class='w-p25-f'><?php echo html::input('reviewedDate', helper::now(), "class='form-control form-datetime' data-picker-position='bottom-right'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->story->reviewResult;?></th>
          <td class='required'><?php echo html::select('result', $lang->story->resultList, '', 'class="form-control chosen" onchange="switchShow(this.value)"');?></td><td></td>
        </tr>
        <tr id='assignedToBox' <?php echo $isLastOne ? '' : "class='hide'";?>>
          <th><?php echo $lang->story->assignedTo;?></th>
          <td><?php echo html::select('assignedTo', $users, $story->assignedTo, "class='form-control picker-select'");?></td><td></td>
        </tr>
        <tr id='rejectedReasonBox' style="display:none">
          <th><?php echo $lang->story->rejectedReason;?></th>
          <td class='required'><?php echo html::select('closedReason', $lang->story->reasonList, '', 'class=form-control onchange="setStory(this.value)"');?></td><td></td>
        </tr>
        <tr id='priBox' class='hide'>
          <th><?php echo $lang->story->pri;?></th>
          <td><?php echo html::select('pri', $lang->story->priList, $story->pri,"class='form-control'");?></td><td></td>
        </tr>
        <tr id='estimateBox' class='hide'>
          <th><?php echo $lang->story->estimate;?></th>
          <td><?php echo html::input('estimate', $story->estimate, "class='form-control'");?></td><td></td>
        </tr>
        <tr id='duplicateStoryBox' class='hide'>
          <th><?php echo $lang->story->duplicateStory;?></th>
          <td class='required'><?php echo html::input('duplicateStory', '', "class='form-control'");?></td><td></td>
        </tr>
        <tr id='childStoriesBox' class='hide'>
          <th><?php echo $lang->story->childStories;?></th>
          <td><?php echo html::input('childStories', '', "class='form-control'");?></td><td></td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->story->status;?></th>
          <td><?php echo html::hidden('status', $story->status);?></td>
        </tr>
        <?php $this->printExtendFields($story, 'table');?>
        <tr>
          <th><?php echo $lang->story->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='8' class='form-control'");?></td>
        </tr>
        <?php if($this->config->vision != 'or'):?>
        <tr>
          <th><?php echo $lang->story->checkAffection;?></th>
          <td colspan='2'><?php include './affected.html.php';?></td>
        </tr>
        <?php endif;?>
        <tr>
          <td colspan='3' class='text-center form-actions'>
          <?php echo html::submitButton();?>
          <?php if(!isonlybody()) echo html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include '../../common/view/action.html.php';?></div>
  </div>
</div>
<?php js::set('storyID', $story->id);?>
<?php js::set('storyType', $story->type);?>
<?php js::set('rawModule', $this->app->rawModule);?>
<?php js::set('isMultiple', count($reviewers) == 1 ? false : true);?>
<?php js::set('isLastOne', $isLastOne);?>
<?php include '../../common/view/footer.html.php';?>
