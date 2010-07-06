<?php
/**
 * The todo view file of dashboard module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<script language='Javascript'>
var account='<?php echo $account;?>'
function changeDate(date)
{
    link = createLink('user', 'todo', 'account=' + account + '&date=' + date);
    location.href=link;
}
</script>
<div class='yui-d0'>
<form method='post' target='hiddenwin' action='<?php echo $this->createLink('todo', 'import2Today');?>' id='todoform'>
   <div id='featurebar'>
     <div class='f-left'>
       <?php 
       echo '<span id="today">'    . html::a(inlink('todo', "account=$account&date=today"),     $lang->todo->todayTodos)    . '</span>';
       echo '<span id="thisweek">' . html::a(inlink('todo', "account=$account&date=thisweek"),  $lang->todo->thisWeekTodos) . '</span>';
       echo '<span id="lastweek">' . html::a(inlink('todo', "account=$account&date=lastweek"),  $lang->todo->lastWeekTodos) . '</span>';
       echo '<span id="all">'      . html::a(inlink('todo', "account=$account&date=all"),       $lang->todo->allDaysTodos)  . '</span>';
       echo '<span id="before">'   . html::a(inlink('todo', "account=$account&date=before&status=undone"), $lang->todo->allUndone) . '</span>';
       echo "<span id='$date'>"    . html::select('date', $dates, $date, 'onchange=changeDate(this.value)') . '</span>';
       ?>
       <script>$('#<?php echo $type;?>').addClass('active')</script>
    </div>
  </div>
  <table class='table-1 tablesorter'>
    <thead>
    <tr class='colhead'>
	  <th class='w-id'><?php echo $lang->idAB;?></th>
      <th class='w-date'><?php echo $lang->todo->date;?></th>
      <th class='w-type'><?php echo $lang->todo->type;?></th>
      <th class='w-pri'><?php echo $lang->priAB;?></th>
      <th><?php echo $lang->todo->name;?></th>
      <th class='w-hour'><?php echo $lang->todo->beginAB;?></th>
      <th class='w-hour'><?php echo $lang->todo->endAB;?></th>
      <th class='w-status'><?php echo $lang->todo->status;?></th>
    </tr>
    </thead>

    <tbody>
    <?php foreach($todos as $todo):?>
    <tr class='a-center'>
      <td><?php echo $todo->id;?></td>
      <td><?php echo $todo->date;?></td>
      <td><?php echo $lang->todo->typeList->{$todo->type};?></td>
      <td><?php echo $todo->pri;?></td>
      <td class='a-left'><?php if(!common::printLink('todo', 'view', "todo=$todo->id", $todo->name)) echo $todo->name;?></td>
      <td><?php echo $todo->begin;?></td>
      <td><?php echo $todo->end;?></td>
      <td class='<?php echo $todo->status;?>'><?php echo $lang->todo->statusList[$todo->status];?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</form>
</div>
<?php include '../../common/view/footer.html.php';?>
