<?php
/**
 * The bug view file of project module of ZenTaoMS.
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
      if(common::hasPriv('bug', 'create')) echo '<div>' . html::a($this->createLink('bug', 'create', "productID=0&project=$project->id"), $lang->bug->create) . '</div>';
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
          $link = helper::createLink('project', 'bug', $app->global->vars ."&orderBy=$orderBy");
          $fieldName = str_replace('`', '', $fieldName);
          echo html::a($link, $lang->bug->$fieldName);
      }
      ?>

      </div>
      <table class='table-1 tablesorter'>
        <thead>
        <tr class='colhead'>
          <th><?php echo $lang->bug->id;?></th>
          <th><?php echo $lang->bug->severity;?></th>
          <th><?php echo $lang->bug->title;?></th>
          <th><?php echo $lang->bug->openedBy;?></th>
          <th><?php echo $lang->bug->assignedTo;?></th>
          <th><?php echo $lang->bug->resolvedBy;?></th>
          <th><?php echo $lang->bug->resolution;?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($bugs as $bug):?>
        <tr class='a-center'>
          <td class='a-right'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->id);?></td>
          <td><?php echo $bug->severity?></td>
          <td width='50%' class='a-left'><?php echo $bug->title;?></td>
          <td><?php echo $users[$bug->openedBy];?></td>
          <td><?php echo $users[$bug->assignedTo];?></td>
          <td><?php echo $users[$bug->resolvedBy];?></td>
          <td><?php echo $bug->resolution;?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <div class='a-right'><?php echo $pager;?></div>
    </div>
  </div>
</div>  
<?php include '../../common/footer.html.php';?>
