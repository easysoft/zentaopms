<?php
/**
 * The close view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     story
 * @version     $Id: close.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/m.header.html.php';?>
</div>
<form class='form-condensed' method='post' target='hiddenwin'>
  <h3><?php echo $lang->story->close . $lang->colon . $story->title;?></h3>
  <table class='table-1'>
    <tr>
      <td class='w-70px'><?php echo $lang->story->closedReason;?></td>
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
    </tr>
  </table>
</form>
<?php include '../../common/view/m.footer.html.php';?>
