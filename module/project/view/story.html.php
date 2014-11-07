<?php
/**
 * The story view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: story.html.php 5117 2013-07-12 07:03:14Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->project->confirmUnlinkStory)?>
<div id='titlebar'>
  <div class='heading'><?php echo html::icon($lang->icons['story']);?> <?php echo $lang->project->story;?></div>
  <div class='actions'>
    <div class='btn-group'>
    <?php 
    common::printIcon('story', 'export', "productID=$productID&orderBy=id_desc", '', 'button', '', '', 'export');

    $this->lang->story->create = $this->lang->project->createStory;
    if($productID) common::printIcon('story', 'create', "productID=$productID&moduleID=0&story=0&project=$project->id");

    common::printIcon('project', 'linkStory', "project=$project->id", '', 'button', 'link');
    ?>
    </div>
  </div>
</div>
<form method='post' id='projectStoryForm'>
  <table class='table tablesorter table-condensed table-fixed' id='storyList'>
    <thead>
      <tr class='colhead'>
      <?php $vars = "projectID={$project->id}&orderBy=%s"; ?>
        <th class='w-id  {sorter:false}'>    <?php common::printOrderLink('id',         $orderBy, $vars, $lang->idAB);?></th>
        <th class='w-pri {sorter:false}'>    <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
        <th class='{sorter:false}'>          <?php common::printOrderLink('title',      $orderBy, $vars, $lang->story->title);?></th>
        <th class='w-user {sorter:false}'>   <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
        <th class='w-80px {sorter:false}'>   <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
        <th class='w-hour {sorter:false}'>   <?php common::printOrderLink('estimate',   $orderBy, $vars, $lang->story->estimateAB);?></th>
        <th class='w-hour {sorter:false}'>   <?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
        <th class='w-70px {sorter:false}'> <?php common::printOrderLink('stage',      $orderBy, $vars, $lang->story->stageAB);?></th>
        <th class='w-70px'>                  <?php echo $lang->story->taskCount;?></th>
        <th class='w-110px {sorter:false}'>  <?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      $totalEstimate = 0;
      $canBatchEdit  = common::hasPriv('story', 'batchEdit');
      $canBatchClose = common::hasPriv('story', 'batchClose');
      ?>
      <?php foreach($stories as $key => $story):?>
      <?php
      $storyLink      = $this->createLink('story', 'view', "storyID=$story->id");
      $totalEstimate += $story->estimate;
      ?>
      <tr class='text-center' id="story<?php echo $story->id?>">
        <td class='text-left'>
          <?php if($canBatchEdit or $canBatchClose):?>
          <input type='checkbox' name='storyIDList[<?php echo $story->id;?>]' value='<?php echo $story->id;?>' /> 
          <?php endif;?>
          <?php echo html::a($storyLink, sprintf('%03d', $story->id));?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
        <td class='text-left' title="<?php echo $story->title?>"><?php echo html::a($storyLink,$story->title);?></td>
        <td><?php echo $users[$story->openedBy];?></td>
        <td><?php echo $users[$story->assignedTo];?></td>
        <td><?php echo $story->estimate;?></td>
        <td class='story-<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
        <td><?php echo $lang->story->stageList[$story->stage];?></td>
        <td class='linkbox'>
          <?php
          $tasksLink = $this->createLink('story', 'tasks', "storyID=$story->id&projectID=$project->id");
          $storyTasks[$story->id] > 0 ? print(html::a($tasksLink, $storyTasks[$story->id], '', 'class="iframe"')) : print(0);
          ?> 
        </td>
        <td>
          <?php 
          $param = "projectID={$project->id}&story={$story->id}&moduleID={$story->module}";

          $lang->task->create = $lang->project->wbs;
          common::printIcon('task', 'create', $param, '', 'list', 'smile');

          $lang->task->batchCreate = $lang->project->batchWBS;
          common::printIcon('task', 'batchCreate', "projectID={$project->id}&story={$story->id}", '', 'list', 'stack');

          $lang->testcase->batchCreate = $lang->testcase->create;
          if($productID) common::printIcon('testcase', 'batchCreate', "productID=$story->product&moduleID=$story->module&storyID=$story->id", '', 'list', 'sitemap');

          if(common::hasPriv('project', 'unlinkStory'))
          {
              $unlinkURL = $this->createLink('project', 'unlinkStory', "projectID=$project->id&storyID=$story->id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"storyList\",confirmUnlinkStory)", '<i class="icon-unlink"></i>', '', "class='btn-icon' title='{$lang->project->unlinkStory}'");
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan='10'>
          <div class='table-actions clearfix'>
          <?php
          if(count($stories))
          {
            if($canBatchEdit or $canBatchClose) echo "<div class='btn-group'>" . html::selectButton() . '</div>';

              echo "<div class='btn-group dropup'>";
              if($canBatchEdit)
              {
                  $actionLink = $this->createLink('story', 'batchEdit', "productID=0&projectID=$project->id");
                  echo html::commonButton($lang->edit, "onclick=\"setFormAction('$actionLink')\"");
              }
              echo "<button id='moreAction' type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
              echo "<ul class='dropdown-menu' id='moreActionMenu'>";
              if($canBatchClose)
              {
                  $actionLink = $this->createLink('story', 'batchClose', "productID=0&projectID=$project->id");
                  $misc       = "onclick=\"setFormAction('$actionLink')\"";
                  echo '<li>' . html::a('#', $lang->close, '', $misc) . '</li>';
              }
              if(common::hasPriv('story', 'batchUnlinkStory'))
              {
                  $actionLink = $this->createLink('project', 'batchUnlinkStory', "projectID=$project->id");
                  $misc       = "onclick=\"setFormAction('$actionLink')\"";
                  echo '<li>' . html::a('#', $lang->project->unlinkStory, '', $misc) . '</li>';
              }
              echo '</ul></div>';
          }
          echo "<div class='text'>" . $summary . '</div>';
          ?>
          </div>
        </td>
      </tr>
    </tfoot>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
