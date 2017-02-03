<?php
/**
 * The close view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: close.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<div class='container'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['story']);?> <strong><?php echo $story->id;?></strong></span>
      <strong><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?></strong>
      <small><?php echo $lang->story->close;?></small>
    </div>
  </div>
  <form method='post' target='hiddenwin' class='form-condensed'>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->story->closedReason;?></th>
        <td class='w-p25-f'><?php echo html::select('closedReason', $lang->story->reasonList, '', 'class=form-control onchange="setStory(this.value)"');?></td><td></td>
      </tr>
      <tr id='duplicateStoryBox' style='display:none'>
        <th><?php echo $lang->story->duplicateStory;?></th>
        <td><?php echo html::input('duplicateStory', '', "class='form-control' autocomplete='off'");?></td><td></td>
      </tr>
      <tr id='childStoriesBox' style='display:none'>
        <th><?php echo $lang->story->childStories;?></th>
        <td><?php echo html::input('childStories', '', "class='form-control' autocomplete='off'");?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->comment;?></th>
        <td colspan='2'><?php echo html::textarea('comment', '', "rows='8' class='form-control'");?></td>
      </tr>
      <tr>
        <td></td>
        <td colspan='2'>
        <?php echo html::submitButton();?>
        <?php echo html::linkButton($lang->goback, $app->session->storyList ? $app->session->storyList : inlink('view', "storyID=$story->id"));?>
        </td>
      </tr>
    </table>
  </form>
  <hr class='small'>
  <div class='main'>
    <?php include '../../common/view/action.html.php';?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
