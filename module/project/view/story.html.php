<?php
/**
 * The story view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: story.html.php 5117 2013-07-12 07:03:14Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('moduleID', ($type == 'byModule' ? $param : 0));?>
<?php js::set('productID', ($type == 'byProduct' ? $param : 0));?>
<?php js::set('confirmUnlinkStory', $lang->project->confirmUnlinkStory)?>
<div id='titlebar'>
  <div class='heading'><?php echo html::icon($lang->icons['story']);?> <?php echo $lang->project->story;?></div>
  <div class='actions'>
    <div class='btn-group'>
    <?php 
    common::printIcon('story', 'export', "productID=$productID&orderBy=id_desc", '', 'button', '', '', 'export');

    $this->lang->story->create = $this->lang->project->createStory;
    if($productID and !$this->loadModel('story')->checkForceReview()) common::printIcon('story', 'create', "productID=$productID&branch=&moduleID=0&story=0&project=$project->id");

    if(commonModel::isTutorialMode())
    {
        $wizardParams = helper::safe64Encode("project=$project->id");
        echo html::a($this->createLink('tutorial', 'wizard', "module=project&method=linkStory&params=$wizardParams"), "<i class='icon-link'></i> {$lang->project->linkStory}",'', "class='btn link-story-btn'");
    }
    else
    {
        common::printIcon('project', 'linkStory', "project=$project->id", '', 'button', 'link', '', 'link-story-btn');
    }
    ?>
    </div>
  </div>
  <div id='querybox' class='show'></div>
</div>

<div class='side' id='taskTree'>
  <a class='side-handle' data-id='projectTree'><i class='icon-caret-left'></i></a>
  <div class='side-body'>
    <div class='panel panel-sm'>
      <div class='panel-heading nobr'>
        <?php echo html::icon($lang->icons['project']);?> <strong><?php echo $project->name;?></strong>
      </div>
      <div class='panel-body'><?php echo $moduleTree;?></div>
    </div>
  </div>
</div>
<div class='main'>
  <script>setTreeBox();</script>
  <form method='post' id='projectStoryForm'>
    <table class='table tablesorter table-condensed table-fixed table-selectable' id='storyList'>
      <thead>
        <tr class='colhead'>
        <?php $vars = "projectID={$project->id}&orderBy=%s&type=$type&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
          <th class='w-id  {sorter:false}'>    <?php common::printOrderLink('id',         $orderBy, $vars, $lang->idAB);?></th>
          <th class='w-pri {sorter:false}'>    <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
          <th class='{sorter:false}'>          <?php common::printOrderLink('title',      $orderBy, $vars, $lang->story->title);?></th>
          <th class='w-user {sorter:false}'>   <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
          <th class='w-80px {sorter:false}'>   <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
          <th class='w-hour {sorter:false}'>   <?php common::printOrderLink('estimate',   $orderBy, $vars, $lang->story->estimateAB);?></th>
          <th class='w-hour {sorter:false}'>   <?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
          <th class='w-70px {sorter:false}'> <?php common::printOrderLink('stage',      $orderBy, $vars, $lang->story->stageAB);?></th>
          <th title='<?php echo $lang->story->taskCount?>' class='w-30px'><?php echo $lang->story->taskCountAB;?></th>
          <th title='<?php echo $lang->story->bugCount?>'  class='w-30px'><?php echo $lang->story->bugCountAB;?></th>
          <th title='<?php echo $lang->story->caseCount?>' class='w-30px'><?php echo $lang->story->caseCountAB;?></th>
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
        $storyLink      = $this->createLink('story', 'view', "storyID=$story->id&version=$story->version&from=project&param=$project->id");
        $totalEstimate += $story->estimate;
        ?>
        <tr class='text-center' id="story<?php echo $story->id?>">
          <td class='cell-id'>
            <?php if($canBatchEdit or $canBatchClose):?>
            <input type='checkbox' name='storyIDList[<?php echo $story->id;?>]' value='<?php echo $story->id;?>' /> 
            <?php endif;?>
            <?php echo html::a($storyLink, sprintf('%03d', $story->id));?>
          </td>
          <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
          <td class='text-left' title="<?php echo $story->title?>">
            <?php if(isset($branchGroups[$story->product][$story->branch])) echo "<span class='label label-info label-badge'>" . $branchGroups[$story->product][$story->branch] . '</span>';?>
            <?php echo html::a($storyLink,$story->title, null, "style='color: $story->color'");?>
          </td>
          <td><?php echo $users[$story->openedBy];?></td>
          <td><?php echo $users[$story->assignedTo];?></td>
          <td><?php echo $story->estimate;?></td>
          <td class='story-<?php echo $story->status;?>'><?php echo zget($lang->story->statusList, $story->status);?></td>
          <td><?php echo $lang->story->stageList[$story->stage];?></td>
          <td class='linkbox'>
            <?php
            $tasksLink = $this->createLink('story', 'tasks', "storyID=$story->id&projectID=$project->id");
            $storyTasks[$story->id] > 0 ? print(html::a($tasksLink, $storyTasks[$story->id], '', 'class="iframe"')) : print(0);
            ?> 
          <td>
            <?php 
            $bugsLink = $this->createLink('story', 'bugs', "storyID=$story->id&projectID=$project->id");
            $storyBugs[$story->id] > 0 ? print(html::a($bugsLink, $storyBugs[$story->id], '', 'class="iframe"')) : print(0);
            ?>
          </td>
          <td>
            <?php 
            $casesLink = $this->createLink('story', 'cases', "storyID=$story->id&projectID=$project->id");
            $storyCases[$story->id] > 0 ? print(html::a($casesLink, $storyCases[$story->id], '', 'class="iframe"')) : print(0);
            ?>
          </td>
          <td>
            <?php 
            $param = "projectID={$project->id}&story={$story->id}&moduleID={$story->module}";

            $lang->task->create = $lang->project->wbs;
            if(commonModel::isTutorialMode())
            {
                $wizardParams = helper::safe64Encode($param);
                echo html::a($this->createLink('tutorial', 'wizard', "module=task&method=create&params=$wizardParams"), "<i class='icon-plus-border'></i>",'', "class='btn-icon btn-task-create' title='{$lang->project->wbs}'");
            }
            else
            {
                common::printIcon('task', 'create', $param, '', 'list', 'plus-border', '', 'btn-task-create');
            }

            $lang->task->batchCreate = $lang->project->batchWBS;
            common::printIcon('task', 'batchCreate', "projectID={$project->id}&story={$story->id}", '', 'list', 'plus-sign');

            $lang->testcase->batchCreate = $lang->testcase->create;
            if($productID) common::printIcon('testcase', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=$story->module&storyID=$story->id", '', 'list', 'sitemap');

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
          <td colspan='12'>
            <div class='table-actions clearfix'>
            <?php
            $storyInfo = sprintf($lang->project->productStories, inlink('linkStory', "project={$project->id}"));
            if(count($stories))
            {
              if($canBatchEdit or $canBatchClose) echo html::selectButton();

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
                if(common::hasPriv('project', 'batchUnlinkStory'))
                {
                    $actionLink = $this->createLink('project', 'batchUnlinkStory', "projectID=$project->id");
                    $misc       = "onclick=\"setFormAction('$actionLink')\"";
                    echo '<li>' . html::a('#', $lang->project->unlinkStory, '', $misc) . '</li>';
                }
                echo '</ul></div>';
                $storyInfo = $summary;
            }
            echo "<div class='text'>{$storyInfo}</div>";
            ?>
            </div>
            <?php echo $pager->show();?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
