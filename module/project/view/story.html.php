<?php
/**
 * The story view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<table class='table-1 fixed colored tablesorter datatable'>
  <caption class='caption-tl'>
    <div class='f-left'><?php echo $lang->project->story;?></div>
    <div class='f-right'>
      <?php if(common::hasPriv('project', 'linkstory')) echo html::a($this->createLink('project', 'linkstory', "project=$project->id"), $lang->project->linkStory);?>
    </div>
  </caption>
  <thead>
    <tr class='colhead'>
    <?php $vars = "projectID={$project->id}&orderBy=%s"; ?>
      <th class='w-id'>     <?php common::printOrderLink('id',           $orderBy, $vars, $lang->idAB);?></th>
      <th class='w-pri'>    <?php common::printOrderLink('pri',          $orderBy, $vars, $lang->priAB);?></th>
      <th>                  <?php common::printOrderLink('title',        $orderBy, $vars, $lang->story->title);?></th>
      <th class='w-user'>   <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->openedByAB);?></th>
      <th class='w-hour'>   <?php common::printOrderLink('assignedTo',   $orderBy, $vars, $lang->assignedToAB);?></th>
      <th class='w-hour'>   <?php common::printOrderLink('estimate',     $orderBy, $vars, $lang->story->estimateAB);?></th>
      <th class='w-hour'>   <?php common::printOrderLink('status',       $orderBy, $vars, $lang->statusAB);?></th>
      <th class='w-status'> <?php common::printOrderLink('stage',        $orderBy, $vars, $lang->story->stageAB);?></th>
      <th class='w-50px'>   <?php echo $lang->story->taskCount;?></th>
      <th class='w-100px'>  <?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <tbody>
    <?php $totalEstimate = 0;?>
    <?php foreach($stories as $key => $story):?>
    <?php
    $storyLink      = $this->createLink('story', 'view', "storyID=$story->id");
    $totalEstimate += $story->estimate;
    ?>
    <tr class='a-center'>
      <td><?php echo html::a($storyLink, sprintf('%03d', $story->id));?></td>
      <td><?php echo $lang->story->priList[$story->pri];?></td>
      <td class='a-left nobr'><?php echo html::a($storyLink,$story->title);?></td>
      <td><?php echo $users[$story->openedBy];?></td>
      <td><?php echo $users[$story->assignedTo];?></td>
      <td><?php echo $story->estimate;?></td>
      <td class='<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
      <td><?php echo $lang->story->stageList[$story->stage];?></td>
      <td class='linkbox'>
        <?php
        $tasksLink = $this->createLink('story', 'tasks', "storyID=$story->id&projectID=$project->id");
        $storyTasks[$story->id] > 0 ? print(html::a($tasksLink, $storyTasks[$story->id], '', 'class="iframe"')) : print(0);
        ?> 
      </td>
      <td>
        <?php 
        $param = "projectID={$project->id}&story={$story->id}";
        common::printLink('task', 'create', $param, $lang->project->wbs);
        common::printLink('project', 'unlinkStory', $param, $lang->unlink, 'hiddenwin');
        ?>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr><td colspan='10' class='a-right'><?php printf($lang->product->storySummary, count($stories), $totalEstimate);?></td></tr>
  </tfoot>
</table>
<?php include '../../common/view/footer.html.php';?>
