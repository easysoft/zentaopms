<?php
/**
 * The confirm file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: resolve.html.php 1914 2011-06-24 10:11:25Z yidong@cnezsoft.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/kindeditor.html.php';
include '../../common/view/chosen.html.php';
js::set('holders', $lang->bug->placeholder);
js::set('page', 'confirmbug');
?>
<form method='post' target='hiddenwin'>
  <table class='table-1'>
    <caption><?php echo $bug->title;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->bug->assignedTo;?></th>
      <td><?php echo html::select('assignedTo', $users, $bug->assignedTo, "class='select-2'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->bug->pri;?></th>
      <td><?php echo html::select('pri', $lang->bug->priList, $bug->pri, 'class=select-2');?></td>
    </tr>  
    <tr>
      <td class='rowhead'><?php echo $lang->bug->mailto;?></td>
      <td><?php echo html::select('mailto[]', $users, str_replace(' ' , '', $bug->mailto), 'class="w-p98" multiple');?></td>
    </tr>
    <tr>
      <td class='rowhead'><?php echo $lang->comment;?></td>
      <td><?php echo html::textarea('comment', '', "rows='6' class='w-p98'");?></td>
    </tr>
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->server->http_referer);?></td>
    </tr>
  </table>
  <?php include '../../common/view/action.html.php';?>
</form>
<?php include '../../common/view/footer.html.php';?>
