<?php
/**
 * The custom seting fields view of bug module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<style>
body{background:white}
.button-c {width:60px}
</style>
<script language='Javascript'>
function restoreDefault()
{
    $('#customFields option').remove();
    $('#defaultFields option').clone().appendTo('#customFields');
}
</script>
<div id='yui-d0' style='margin-top:20px'>
  <form method='post'>
    <table class='table-1'> 
      <caption class='caption-tl'><?php echo $lang->bug->customFields;?></caption>
      <tr class='colhead'>
        <th><?php echo $lang->bug->lblAllFields;?></th>
        <th></th>
        <th><?php echo $lang->bug->lblCustomFields;?></th>
        <th></th>
      </tr>  
      <tr>
        <td>
          <?php 
          echo html::select('allFields[]', $allFields, '', 'class=select-2 size=10 multiple');
          echo html::select('defaultFields[]', $defaultFields, '', 'class=hidden');
          ?>
        </td>
        <td>
          <?php
          echo html::commonButton('>', "onclick=\"addItem('allFields', 'customFields')\"") . '<br />';
          echo html::commonButton('<', "onclick=delItem('customFields')")  . '<br />';
          ?>
        </td>
        <td><?php echo html::select('customFields[]', $customFields, '', 'class=select-2 size=10 multiple');?></td>
        <td>
          <?php
          echo html::commonButton('+', "onclick=upItem('customFields')")  . '<br />';
          echo html::commonButton('-', "onclick=downItem('customFields')")  . '<br />';
          echo html::commonButton($lang->bug->restoreDefault, "onclick=restoreDefault()")  . '<br />';
          ?>
        </td>
      </tr>  
      <tr><td colspan='4' class='a-center'><?php echo html::submitButton('', 'onclick=selectItem("customFields")');?></td></tr>
    </table>
  </form>
</div>  
