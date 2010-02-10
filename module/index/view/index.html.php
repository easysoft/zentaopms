<?php
/**
 * The html template file of index method of index module of ZenTaoMS.
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
 * @package     ZenTaoMS
 * @version     $Id$
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/colorize.html.php';?>
<div class="yui-d0 yui-t6">  
  <div class='yui-main'>
    <div class='yui-b'>
      <?php foreach($projectGroups as $projects):?>
      <div class="yui-gb">  
        <?php foreach($projects as $key => $project):?>
        <?php
        $class = 0;
        if($key == 0) $class = 'first';
        if($key == 3) break;
        ?>
        <div class="yui-u <?php echo $class;?>">  
          <table class='table-1'>
            <caption class='a-center'><?php echo html::a($this->createLink('project', 'browse', "projectid=$project->id"), $project->name);?></caption>
            <tr>
              <th><?php echo $lang->project->beginAndEnd;?></th>
              <td><?php echo $project->begin . ' ~ ' . $project->end;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->project->status;?></th>
              <td class='<?php echo $project->status;?>'><?php $lang->show($lang->project->statusList, $project->status);?></td>
            </tr>
            <tr><td colspan='2' class='a-center'><?php echo $burns[$project->id];?></td></tr>
          </table>
        </div>  
        <?php endforeach;?>
      </div>
      <?php endforeach;?>
    </div>
  </div>
  <div class='yui-b'>
    <table class='table-1 colored'>
      <caption><?php echo $lang->index->latest;?></caption>
      <?php 
      foreach($actions as $action)
      {
          echo "<tr><td>";
          printf($lang->index->action, $action->date, $users[$action->actor], $action->actionLabel, $action->objectLabel, $action->objectLink);
          echo "</td></tr>";
      }
      ?>
      </table>
  </div>
</div>
<script language='Javascript'><?php for($i = 1; $i <= $counts; $i ++) echo "createChart$i();"; ?></script>
<?php include '../../common/footer.html.php';?>
