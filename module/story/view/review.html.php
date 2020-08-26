<?php
/**
 * The view file of review method of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: review.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<script>
var assignedTo = '<?php $story->lastEditedBy ? print($story->lastEditedBy) : print($story->openedBy);?>';
</script>
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
          <td class='w-p25-f'><?php echo html::input('reviewedDate', helper::today(), "class='form-control form-date'");?></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->story->reviewResult;?></th>
          <td class = 'required'><?php echo html::select('result', $lang->story->reviewResultList, '', 'class=form-control onchange="switchShow(this.value)"');?></td><td></td>
        </tr>
        <tr id='rejectedReasonBox' class='hide'>
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
        <?php if($story->status == 'changed' or ($story->status == 'draft' and $story->version > 1)):?>
        <tr id='preVersionBox' class='hide'>
          <th><?php echo $lang->story->preVersion;?></th>
          <td colspan='2'><?php echo html::radio('preVersion', array_combine(range($story->version - 1, 1), range($story->version - 1, 1)), $story->version - 1);?></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->story->assignedTo;?></th>
          <td><?php echo html::select('assignedTo', $users, $story->lastEditedBy ? $story->lastEditedBy : $story->openedBy, "class='form-control chosen'");?></td><td></td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->story->status;?></th>
          <td><?php echo html::hidden('status', $story->status);?></td>
        </tr>
        <?php $this->printExtendFields($story, 'table');?>
        <tr>
          <th><?php echo $lang->story->reviewedBy;?></th>
          <td colspan='2'><?php echo html::select('reviewedBy[]', $users, $app->user->account, "class='form-control chosen' multiple data-placeholder='{$lang->story->chosen->reviewedBy}'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->story->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='8' class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->story->checkAffection;?></th>
          <td colspan='2'><?php include './affected.html.php';?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
          <?php echo html::submitButton();?>
          <?php echo html::linkButton($lang->goback, $app->session->storyList ? $app->session->storyList : inlink('view', "storyID=$story->id"), 'self', '', 'btn btn-wide');?>
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
<?php include '../../common/view/footer.html.php';?>
