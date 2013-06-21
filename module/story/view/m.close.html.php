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
<form method='post' target='hiddenwin'>
    <h3><?php echo $story->title;?></h3>
    <span><?php echo $lang->story->closedReason;?></span>
    <?php echo html::select('closedReason', $lang->story->reasonList, '', 'onchange="setStory(this.value)"');?>
    <div id='duplicateStoryBox' class='hidden'>
      <span><?php echo $lang->story->duplicateStory;?></span>
      <?php echo html::input('duplicateStory', '');?>
    </div>
    <div id='childStoriesBox' class='hidden'>
      <span><?php echo $lang->story->childStories;?></span>
      <?php echo html::input('childStories', '');?>
    </div>
    <span><?php echo $lang->story->comment;?></span>
    <?php echo html::textarea('comment', '');?>
    <p>
    <?php echo html::submitButton('', "data-inline='true' data-theme='b'");?>
    <?php echo html::backButton("data-inline='true'");?>
    </p>
</form>
<?php include '../../common/view/m.footer.html.php';?>
