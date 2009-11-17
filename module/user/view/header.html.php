<?php
/**
 * The header view file of dashboard module of ZenTaoMS.
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
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/tablesorter.html.php';?>
<script language='Javascript'>
account='<?php echo $user->account;?>'
function changeDate(date)
{
    link = createLink('user', 'todo', 'account=' + account + '&date=' + date);
    location.href=link;
}
</script>
<div class="yui-d0 yui-t3">                 
  <div class="yui-b">
    <table class='table-1'>
      <caption><?php echo $lang->user->profile;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->user->account;?></th>
        <td><?php echo $user->account;?></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->user->realname;?></th>
        <td><?php echo $user->realname;?></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->user->nickname;?></th>
        <td><?php echo $user->nickname;?></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->user->join;?></th>
        <td><?php echo $user->join;?></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->user->visits;?></th>
        <td><?php echo $user->visits;?></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->user->ip;?></th>
        <td><?php echo $user->ip;?></td>
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->user->last;?></th>
        <td><?php echo date('Y-m-d H:i:s', $user->last);?></td>
      </tr>
    </table>
    <div class='a-right'>
      <?php 
      if(common::hasPriv('user', 'edit')) echo html::a($this->createLink('user', 'edit', "userID=$user->id&from=company"), $lang->user->editProfile);
      ?>
    </div>
  </div>
  <div class="yui-main">
    <div class="yui-b">
      <div id='tabbar' class='yui-d0' style='clear:right'>
        <ul>
          <?php
          echo "<li><nobr>{$user->realname}</nobr></li>";
          echo "<li id='todotab'><nobr>"   . html::a($this->createLink('user', 'todo',    "account=$user->account"), $lang->user->todo)    . "</nobr></li>";
          echo "<li id='tasktab'><nobr>"   . html::a($this->createLink('user', 'task',    "account=$user->account"), $lang->user->task)    . "</nobr></li>";
          echo "<li id='projecttab'><nobr>". html::a($this->createLink('user', 'project', "account=$user->account"), $lang->user->project) . "</nobr></li>";
          echo "<li id='bugtab'><nobr>"    . html::a($this->createLink('user', 'bug',     "account=$user->account"), $lang->user->bug)     . "</nobr></li>";
          echo <<<EOT
<script language="Javascript">
$("#{$tabID}tab").addClass('active');
</script>
EOT;
        ?>
        </ul>
      </div>  
      <?php if($tabID == 'todo'):?>
      <div id='subtab'>
        <?php 
        echo html::a($this->createLink('user', 'todo', "account=$user->account&date=today"),     $lang->todo->todayTodos);
        echo html::a($this->createLink('user', 'todo', "account=$user->account&date=thisweek"),  $lang->todo->thisWeekTodos);
        echo html::a($this->createLink('user', 'todo', "account=$user->account&date=lastweek"),  $lang->todo->lastWeekTodos);
        echo html::a($this->createLink('user', 'todo', "account=$user->account&date=all"),   $lang->todo->allDaysTodos);
        echo html::a($this->createLink('user', 'todo', "account=$user->account&date=before&account={$user->account}&status=wait,doing"), $lang->todo->allUndone);
        echo html::select('date', $dates, $date, 'onchange=changeDate(this.value)');
      ?>
      </div>
      <?php endif;?>
