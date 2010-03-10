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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/tablesorter.html.php';?>
<script language='Javascript'>
function changeDate(date)
{
    link = createLink('my', 'todo', 'date=' + date);
    location.href=link;
}
</script>
<div class='yui-d0'>
<form method='post' target='hiddenwin' action='<?php echo $this->createLink('todo', 'import2Today');?>' id='todoform'>
   <div id='featurebar'>
     <div class='f-left'>
       <?php 
       echo '<span id="today">'    . html::a($this->createLink('my', 'todo', "date=today"),     $lang->todo->todayTodos)    . '</span>';
       echo '<span id="thisweek">' . html::a($this->createLink('my', 'todo', "date=thisweek"),  $lang->todo->thisWeekTodos) . '</span>';
       echo '<span id="lastweek">' . html::a($this->createLink('my', 'todo', "date=lastweek"),  $lang->todo->lastWeekTodos) . '</span>';
       echo '<span id="all">'      . html::a($this->createLink('my', 'todo', "date=all"),       $lang->todo->allDaysTodos)  . '</span>';
       echo '<span id="before">'   . html::a($this->createLink('my', 'todo', "date=before&account={$app->user->account}&status=undone"), $lang->todo->allUndone) . '</span>';
       echo "<span id='$date'>"    . html::select('date', $dates, $date, 'onchange=changeDate(this.value)') . '</span>';
       ?>
       <script>$('#<?php echo $type;?>').addClass('active')</script>
    </div>
    <div class='f-right'>
      <?php echo html::a($this->createLink('todo', 'create', "date=$date"), $lang->todo->create);?>
    </div>
  </div>
  <table class='table-1 tablesorter'>
    <thead>
    <tr class='colhead'>
      <th><?php echo $lang->todo->id;?></th>
      <th><?php echo $lang->todo->date;?></th>
      <th><?php echo $lang->todo->type;?></th>
      <th><?php echo $lang->todo->pri;?></th>
      <th><?php echo $lang->todo->name;?></th>
      <th><?php echo $lang->todo->begin;?></th>
      <th><?php echo $lang->todo->end;?></th>
      <th><?php echo $lang->todo->status;?></th>
      <th><?php echo $lang->actions;?></th>
    </tr>
    </thead>

    <tbody>
    <?php foreach($todos as $todo):?>
    <tr class='a-center'>
      <td>
        <?php
        if($importFeature) echo "<input type='checkbox' name='todos[]' value='$todo->id' /> ";
        echo $todo->id;
        ?>
      </td>
      <td><?php echo $todo->date;?></td>
      <td><?php echo $lang->todo->typeList->{$todo->type};?></td>
      <td><?php echo $todo->pri;?></td>
      <td class='a-left'>
        <?php 
        if($todo->type == 'bug')    $link = $this->createLink('bug',  'view', "id={$todo->idvalue}");
        if($todo->type == 'task')   $link = $this->createLink('task', 'edit', "id={$todo->idvalue}");
        if($todo->type == 'custom') $link = $this->createLink('todo', 'edit', "id={$todo->id}");
        echo html::a($link, $todo->name);
        ?>
      </td>
      <td><?php echo $todo->begin;?></td>
      <td><?php echo $todo->end;?></td>
      <td class='<?php echo $todo->status;?>'><?php echo $lang->todo->statusList[$todo->status];?></td>
      <td>
        <?php 
        echo html::a($this->createLink('todo', 'mark',   "id=$todo->id&status=$todo->status"), $lang->todo->{'mark'.ucfirst($todo->status)}, 'hiddenwin');
        echo html::a($this->createLink('todo', 'edit',   "id=$todo->id"), $lang->edit);
        echo html::a($this->createLink('todo', 'delete', "id=$todo->id"), $lang->delete, 'hiddenwin');
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
    <?php if($importFeature):?>
    <tfoot>
    <tr>
      <td colspan='9'>
        <input type='submit' value='<?php echo $lang->todo->import2Today;?>' />
      </td>
    </tr>
    <?php endif;?>
    </tfoot>
  </table>
</form>
</div>
<?php include '../../common/footer.html.php';?>
