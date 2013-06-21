<?php
/**
 * The activate view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     story
 * @version     $Id: activate.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/m.header.html.php';?>
</div>
<form method='post' target='hiddenwin'>
  <h3><?php echo $lang->story->activate;?></h3>
    <span><?php echo $lang->story->assignedTo;?></span>
    <?php echo html::select('assignedTo', $users, $story->closedBy, 'class="select-3"');?>
    <span><?php echo $lang->story->comment;?></span>
    <?php echo html::textarea('comment', '', 'rows=5 class="area-1"');?>
    <p class='a-center'>
    <?php echo html::submitButton('', "data-inline='true' data-theme='b'");?>
    <?php echo html::backButton("data-inline='true'");?>
    </p>
</form>
<?php include '../../common/view/m.footer.html.php';?>
