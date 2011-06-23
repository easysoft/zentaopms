<?php
/**
 * The activate file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' enctype='multipart/form-data' target='hiddenwin'>
  <table class='table-1'>
    <caption><?php echo $bug->title;?></caption>
    <tr>
      <td class='rowhead'><?php echo $lang->bug->assignedTo;?></td>
      <td><?php echo html::select('assignedTo', $users, $bug->resolvedBy, 'class=select-3');?></td>
    </tr>
    <tr>
      <td class='rowhead'><?php echo $lang->bug->openedBuild;?></td>
      <td><?php echo html::select('openedBuild[]', $builds, $bug->openedBuild, 'size=4 multiple=multiple class=select-3');?></td>
    </tr>
    <tr>
      <td class='rowhead'><?php echo $lang->comment;?></td>
      <td><?php echo html::textarea('comment', '', "rows='6' class='area-1'");?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->bug->files;?></th>
      <td class='a-left'><?php echo $this->fetch('file', 'buildform');?></td>
    </tr>  
    <tr>
      <td colspan='2' class='a-center'>
        <?php echo html::submitButton();?>
        <input type='button' value='<?php echo $lang->bug->buttonToList;?>' class='button-s' 
         onclick='location.href="<?php echo $this->session->bugList;?>"' />
      </td>
    </tr>
  </table>
  <?php include '../../common/view/action.html.php';?>
</form>
<?php include '../../common/view/footer.html.php';?>
