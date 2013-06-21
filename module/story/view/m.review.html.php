<?php
/**
 * The view file of review method of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: review.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/m.header.html.php';?>
</div>
<h3><?php echo $story->title?></h3>
<form method='post' target='hiddenwin'>
  <span><?php echo $lang->story->reviewedDate;?></span>
  <?php echo html::input('reviewedDate', helper::today(), 'class=text-3');?>
  <span><?php echo $lang->story->reviewResult;?></span>
  <?php echo html::select('result', $lang->story->reviewResultList, '', 'class=select-3 onchange="switchShow(this.value)"');?>
  <div id='rejectedReasonBox' class='hidden'>
    <span><?php echo $lang->story->rejectedReason;?></span>
    <?php echo html::select('closedReason', $lang->story->reasonList, '', 'class=select-3 onchange="setStory(this.value)"');?>
  </div>
  <div id='duplicateStoryBox' class='hidden'>
    <span><?php echo $lang->story->duplicateStory;?></span>
    <?php echo html::input('duplicateStory', '', 'class=text-3');?>
  </div>
  <div id='childStoriesBox' class='hidden'>
    <span><?php echo $lang->story->childStories;?></span>
    <?php echo html::input('childStories', '', 'class=text-3');?>
  </div>
  <?php if($story->status == 'changed' or ($story->status == 'draft' and $story->version > 1)):?>
  <div id='preVersionBox' class='hidden'>
    <span><?php echo $lang->story->preVersion;?></span>
    <?php echo html::radio('preVersion', array_combine(range($story->version - 1, 1), range($story->version - 1, 1)), $story->version - 1);?>
  </div>
  <?php endif;?>
  <span><?php echo $lang->story->assignedTo;?></span>
  <?php echo html::select('assignedTo', $users, $story->lastEditedBy ? $story->lastEditedBy : $story->openedBy, 'class=select-3');?>
  <span><?php echo $lang->story->reviewedBy;?></span>
  <?php echo html::input('reviewedBy', $app->user->account . ', ', 'class=text-1');?>
  <span><?php echo $lang->story->comment;?></span>
  <?php echo html::textarea('comment', '', "rows='8' class='area-1'");?>
  <p>
  <?php echo html::submitButton('', "data-inline='true' data-theme='b'");?>
  <?php echo html::backButton("data-inline='true'");?>
  </p>
</form>
<?php include '../../common/view/m.footer.html.php';?>
