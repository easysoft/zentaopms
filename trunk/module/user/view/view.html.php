<?php
/**
 * The index view file of my module of ZenTaoMS.
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
 * @package     my
 * @version     $Id: view.html.php 1406 2009-10-12 07:49:52Z wwccss $
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/tablesorter.html.php';?>
<div class="yui-d0 yui-t3">                 
  <div class="yui-b">
    <table class='table-1'>
      <caption><?php echo $lang->my->profile;?></caption>
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
        <td><?php echo $user->last;?></td>
      </tr>
    </table>
    
  </div>
  <div class="yui-main">
    <div class="yui-b">
      <div id='tabbar' class='yui-d0' style='clear:right'>
        <ul>
          <?php
          echo "<li><nobr>{$user->realname}</nobr></li>";
          echo "<li id='tasktab'><nobr>"   . html::a($this->createLink('user', 'view', "account=$user->account&tabID=task"),    $lang->my->task)    . "</nobr></li>";
          //echo "<li id='todotab'><nobr>"   . html::a($this->createLink('user', 'view', "account=$user->account&tabID=todo"),    $lang->my->todo)    . "</nobr></li>";
          echo "<li id='projecttab'><nobr>". html::a($this->createLink('user', 'view', "account=$user->account&tabID=project"), $lang->my->project) . "</nobr></li>";
          //echo "<li id='storytab'><nobr>"  . html::a($this->createLink('user', 'view', "account=$user->account&tabID=story"),   $lang->my->story)   . "</nobr></li>";
          echo "<li id='bugtab'><nobr>"    . html::a($this->createLink('user', 'view', "account=$user->account&tabID=bug"),     $lang->my->bug)     . "</nobr></li>";
          //echo "<li id='teamtab'><nobr>"   . html::a($this->createLink('user', 'view', "account=$user->account&tabID=team"),    $lang->my->team)    . "</nobr></li>";
          echo <<<EOT
<script language="Javascript">
$("#{$tabID}tab").addClass('active');
</script>
EOT;
        ?>
        </ul>
      </div>
      <?php include $tabID . '.html.php';?>
    </div>
  </div>  
</div>
<?php include '../../common/footer.html.php';?>
