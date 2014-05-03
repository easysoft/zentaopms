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
<h3><?php echo $lang->story->review . $lang->colon . $story->title?></h3>
<form class='form-condensed' method='post' target='hiddenwin'>
  <table class='table table-form'>
    <tr>
      <td class='w-70px'><?php echo $lang->story->reviewedDate;?></td>
      <td><?php echo html::input('reviewedDate', helper::today());?></td>
    </tr>
    <tr>
      <td><?php echo $lang->story->reviewResult;?></td>
      <td><?php echo html::select('result', $lang->story->reviewResultList, '', 'onchange="switchShow(this.value)"');?></td>
    </tr>
    <tr id='rejectedReasonBox' class='hide'>
      <td><?php echo $lang->story->rejectedReason;?></td>
      <td><?php echo html::select('closedReason', $lang->story->reasonList, '', 'onchange="setStory(this.value)"');?></td>
    </tr>
    <tr id='duplicateStoryBox' class='hide'>
      <td><?php echo $lang->story->duplicateStory;?></td>
      <td><?php echo html::input('duplicateStory', '');?></td>
    </tr>
    <tr id='childStoriesBox' class='hide'>
      <td><?php echo $lang->story->childStories;?></td>
      <td><?php echo html::input('childStories', '');?></td>
    </tr>
    <?php if($story->status == 'changed' or ($story->status == 'draft' and $story->version > 1)):?>
    <tr id='preVersionBox' class='hide'>
      <td><?php echo $lang->story->preVersion;?></td>
      <td><?php echo html::radio('preVersion', array_combine(range($story->version - 1, 1), range($story->version - 1, 1)), $story->version - 1);?></td>
    </tr>
    <?php endif;?>
    <tr>
      <td><?php echo $lang->story->assignedTo;?></td>
      <td><?php echo html::select('assignedTo', $users, $story->lastEditedBy ? $story->lastEditedBy : $story->openedBy);?></td>
    </tr>
    <tr>
      <td><?php echo $lang->story->reviewedBy;?></td>
      <td><?php echo html::input('reviewedBy', $app->user->account . ', ');?></td>
    </tr>
    <tr>
      <td><?php echo $lang->story->comment;?></td>
      <td><?php echo html::textarea('comment', '');?></td>
    </tr>
    <tr>
      <td class='text-center' colspan='2'>
        <?php
        echo html::submitButton('', "data-inline='true' data-theme='b'");
        echo html::linkButton($lang->goback, $this->createLink('story', 'view', "storyID=$story->id"), 'self', "data-inline='true'");
        ?>
      </td>
    <tr>
  </table>
</form>
<?php include '../../common/view/m.footer.html.php';?>
