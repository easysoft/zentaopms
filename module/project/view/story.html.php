<?php
/**
 * The story view file of project module of ZenTaoMS.
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
      if(common::hasPriv('project', 'linkstory')) echo '<div>' . html::a($this->createLink('project', 'linkstory', "project=$project->id"), $lang->project->linkStory) . '</div>';
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
          $link = helper::createLink('project', 'story', $app->global->vars ."&orderBy=$orderBy");
          echo html::a($link, $lang->story->$fieldName);
      }
      ?>
      </div>

      <table align='center' class='table-1 tablesorter'>
        <thead>
          <tr>
            <th><?php printOrderLink('id');?></th>
            <th><?php printOrderLink('pri');?></th>
            <th><?php printOrderLink('title');?></th>
            <th><?php printOrderLink('assignedTo');?></th>
            <th><?php printOrderLink('openedBy');?></th>
            <th><?php printOrderLink('estimate');?></th>
            <th><?php printOrderLink('status');?></th>
            <th><?php echo $lang->action;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($stories as $key => $story):?>
          <?php
          $viewLink = $this->createLink('story', 'view', "storyID=$story->id");
          $canView  = common::hasPriv('story', 'view');
          ?>
          <tr class='a-center'>
            <td><?php if($canView) echo html::a($viewLink, sprintf('%03d', $story->id)); else printf('%03d', $story->id);?></td>
            <td><?php echo $story->pri;?></td>
            <td class='a-left'><nobr><?php echo $story->title . ' (' . $storyTasks[$story->id] . ')';?></nobr></td>
            <td><?php echo $users[$story->assignedTo];?></td>
            <td><?php echo $users[$story->openedBy];?></td>
            <td><?php echo $story->estimate;?></td>
            <td class='<?php echo $story->status;?>'><?php $statusList = (array)$lang->story->statusList; echo $statusList[$story->status];?></td>
            <td>
              <?php if(common::hasPriv('task', 'create'))         echo html::a($this->createLink('task', 'create',   "projectID={$project->id}&story={$story->id}"), $lang->task->create);?>
              <?php if(common::hasPriv('project', 'unlinkStory')) echo html::a($this->createLink('project', 'unlinkStory', "projectID={$project->id}&story={$story->id}&confirm=no"), $lang->project->unlinkStory, 'hiddenwin');?>
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
