<?php
/**
 * The create view of todo module of ZenTaoMS.
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
 * @package     todo
 * @version     $Id: edit.html.php 1423 2009-10-17 05:38:40Z wwccss $
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>

<div id='doc3'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-1 a-left'> 
      <caption><?php echo $lang->todo->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->todo->date;?></th>
        <td><?php echo html::select('date', $dates, $todo->date, 'class=select-3');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->todo->type;?></th>
        <td><input type='hidden' name='type' value='<?php echo $todo->type;?>' /><?php echo $lang->todo->typeList->{$todo->type};?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->todo->name;?></th>
        <td><div id='nameBox'><input type='text' name='name' value='<?php echo $todo->name;?>' class='text-3' <?php if($todo->type != 'custom') echo 'readonly';?> /></div></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->todo->pri;?></th>
        <td><?php echo html::select('pri', $lang->todo->priList, $todo->pri, 'class=select-3');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->todo->beginAndEnd;?></th>
        <td><?php echo html::select('begin', $times, $todo->begin, 'onchange=selectNext(); class=select-2') . html::select('end', $times, $todo->end, 'class=select-2');?></td>
      </tr>  

      <tr>
        <th class='rowhead'><?php echo $lang->todo->desc;?></th>
        <td><textarea name='desc' id='desc' rows='5' class='area-1'><?php echo $todo->desc;?></textarea>
      </tr>  
      <tr>
        <td colspan='2' class='a-center'>
          <?php echo html::submitButton() . html::resetButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>  
<script language='Javascript'>
account='<?php echo $app->user->account;?>';
customHtml = $('#nameBox').html();
function loadList(type)
{
    if(type == 'bug')
    {
        link = createLink('bug', 'ajaxGetUserBugs', 'account=' + account);
    }
    else if(type == 'task')
    {
        link = createLink('task', 'ajaxGetUserTasks', 'account=' + account);
    }
   
    if(type == 'bug' || type == 'task')
    {
        $('#nameBox').load(link);
        $('#desc').attr('readonly', 'readonly');
    }
     else if(type == 'custom')
    {
        $('#nameBox').html(customHtml);
        $('#desc').removeAttr('readonly');
    }

}
function selectNext()
{
    endIndex = $("#begin ").get(0).selectedIndex + 2;
    $("#end ").get(0).selectedIndex = endIndex;
}
</script>

<?php include '../../common/footer.html.php';?>
