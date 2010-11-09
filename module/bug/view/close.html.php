<?php
/**
 * The close file of bug module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin'>
<div class='yui-d0'>
  <table class='table-1'>
    <caption><?php echo $bug->title;?></caption>
    <tr>
      <td class='rowhead'><?php echo $lang->comment;?></td>
      <td><?php echo html::textarea('comment', '', "rows='6' class='area-1'");?></td>
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
</div>
<?php include '../../common/view/footer.html.php';?>
