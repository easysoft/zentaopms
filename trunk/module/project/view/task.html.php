<?php
/**
 * The task view file of project module of ZenTaoMS.
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
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<script language='javascript'>
function selectProject(projectID)
{
    link = createLink('project', 'browse', 'projectID=' + projectID);
    location.href=link;
}
</script>
<div class="yui-d0 yui-t3">                 
  <div class="yui-b"><?php include './project.html.php';?></div>
  <div class="yui-main">
    <div class="yui-b">
      <div id='tabbar' class='yui-d0'>
      <?php 
      include './tabbar.html.php';
      if(common::hasPriv('task', 'create')) echo '<div>' . html::a($this->createLink('task', 'create', "project=$project->id"), $lang->task->create) . '</div>';
      $app->global->vars    = "projectID=$project->id";
      $app->global->orderBy = $orderBy;
      function printOrderLink($fieldName)
      {
          global $app, $lang;
          if(strpos($app->global->orderBy, $fieldName) !== false)
          {
              if(stripos($app->global->orderBy, 'desc') !== false) $orderBy = str_replace('desc', 'asc', $app->global->orderBy);
              if(stripos($app->global->orderBy, 'asc')  !== false) $orderBy = str_replace('asc', 'desc', $app->global->orderBy);
          }
          else
          {
              $orderBy = $fieldName . '|' . 'asc';
          }
          $link = helper::createLink('project', 'task', $app->global->vars ."&orderBy=$orderBy");
          $fieldName = str_replace('`', '', $fieldName);
          echo html::a($link, $lang->task->$fieldName);
      }
      ?>

      </div>
      <table align='center' class='table-1'>
        <thead>
        <tr>
          <th><?php printOrderLink('id');?></th>
          <th><?php printOrderLink('pri');?></th>
          <th><?php printOrderLink('name');?></th>
          <th><?php printOrderLink('owner');?></th>
          <th><?php printOrderLink('estimate');?></th>
          <th><?php printOrderLink('consumed');?></th>
          <th><?php printOrderLink('`left`');?></th>
          <th><?php printOrderLink('status');?></th>
          <th><?php printOrderLink('story');?></th>
          <th><?php echo $lang->action;?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($tasks as $task):?>
        <?php $class = $task->owner == $app->user->account ? 'style=color:red' : '';?>
        <tr class='a-center'>
          <td class='a-right'><?php if(common::hasPriv('task', 'view')) echo html::a($this->createLink('task', 'view', "task=$task->id"), sprintf('%03d', $task->id)); else printf('%03d', $task->id);?></td>
          <td><?php echo $task->pri;?></td>
          <td class='a-left'><?php echo $task->name;?></td>
          <td <?php echo $class;?>><?php echo $task->ownerRealName;?></td>
          <td><?php echo $task->estimate;?></td>
          <td><?php echo $task->consumed;?></td>
          <td><?php echo $task->left;?></td>
          <td class=<?php echo $task->status;?> ><?php echo $lang->task->statusList->{$task->status};?></td>
          <td class='a-left'>
            <?php 
            if($task->storyID)
            {
                if(common::hasPriv('story', 'view')) echo html::a($this->createLink('story', 'view', "storyid=$task->storyID"), $task->storyTitle);
                else echo $task->storyTitle;
            }
            ?>
          </td>
          <td>
            <?php if(common::hasPriv('task', 'edit'))   echo html::a($this->createLink('task', 'edit',   "taskid=$task->id"), $lang->task->edit);?>
            <?php if(common::hasPriv('task', 'delete')) echo html::a($this->createLink('task', 'delete', "projectID=$task->project&taskid=$task->id"), $lang->task->delete, 'hiddenwin');?>
          </td>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <div class='a-right'><?php echo $pager;?></div>
    </div>
  </div>
</div>  
<?php include '../../common/footer.html.php';?>
