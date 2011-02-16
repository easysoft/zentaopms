<?php
/**
 * The activate view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<div class='yui-d0'><div class='u-1'>
  <form method='post' enctype='multipart/form-data' target='hiddenwin'>
  <table class='table-1'>
    <caption><?php echo $lang->story->activate;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->story->assignedTo;?></th>
      <td><?php echo html::select('assignedTo', $users, $story->closedBy, 'class="select-3"');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->story->comment;?></th>
      <td><?php echo html::textarea('comment', '', 'rows=5 class="area-1"');?></td>
    </tr>
    <tr>
      <td colspan='2' class='a-center'>
        <?php 
        echo html::submitButton();
        echo html::linkButton($lang->goback, $app->session->storyList ? $app->session->storyList : inlink('view', "storyID=$story->id"));
        ?>
      </td>
    </tr>
  </table>
  <?php include '../../common/view/action.html.php';?>
  </form>
</div></div>
<?php include '../../common/view/footer.html.php';?>
