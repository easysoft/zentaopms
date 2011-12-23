<?php
/**
 * The resolve file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin'>
  <table class='table-1'>
    <caption><?php echo $bug->title;?></caption>
    <tr>
      <td class='rowhead'><?php echo $lang->bug->resolution;?></td>
      <td><?php unset($lang->bug->resolutionList['tostory']); echo html::select('resolution', $lang->bug->resolutionList, '', 'class=select-3 onchange=setDuplicate(this.value)');?></td>
    </tr>
    <tr id='duplicateBugBox' style='display:none'>
      <td class='rowhead'><?php echo $lang->bug->duplicateBug;?></td>
      <td><?php echo html::input('duplicateBug', '', 'class=text-3');?></td>
    </tr>
    <tr>
      <td class='rowhead'><?php echo $lang->bug->resolvedBuild;?></td>
      <td><?php echo html::select('resolvedBuild', $builds, '', 'class=select-3');?></td>
    </tr>
    <tr>
      <td class='rowhead'><?php echo $lang->bug->assignedTo;?></td>
      <td><?php echo html::select('assignedTo', $users, $bug->openedBy, 'class=select-3');?></td>
    </tr>
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
</form>
<?php include '../../common/view/footer.html.php';?>
