<?php
/**
 * The resolve file of bug module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<script language='Javascript'>
function setDuplicate(resolution)
{
    if(resolution == 'duplicate')
    {
        $('#duplicateBugBox').show();
    }
    else
    {
        $('#duplicateBugBox').hide();
    }
}
</script>
<form method='post' target='hiddenwin'>
<div class='yui-d0'>
  <table class='table-1'>
    <caption><?php echo $bug->title;?></caption>
    <tr>
      <td class='rowhead'><?php echo $lang->bug->resolution;?></td>
      <td><?php echo html::select('resolution', $lang->bug->resolutionList, '', 'class=select-3 onchange=setDuplicate(this.value)');?></td>
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
      <td><textarea name='comment' rows='6' class='area-1'></textarea></td>
    </tr>
    <tr>
      <td colspan='2' class='a-center'>
        <?php echo html::submitButton();?>
        <input type='button' value='<?php echo $lang->bug->buttonToList;?>' class='button-s' 
         onclick='location.href="<?php echo $this->session->bugList;?>"' />
      </td>
    </tr>
  </table>
  <?php include '../../common/action.html.php';?>
</div>
<?php include '../../common/footer.html.php';?>
