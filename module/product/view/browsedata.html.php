<?php
/**
 * The browse data view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
    <?php include '../../common/view/tablesorter.html.php';?>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed table-selectable' id='storyList'>
      <thead>
      <tr>
        <th class='w-id {sorter:false}'>  <?php common::printOrderLink('id',         $orderBy, $vars, $lang->idAB);?></th>
        <th class='w-pri {sorter:false}'> <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
        <th class='w-p30 {sorter:false}'> <?php common::printOrderLink('title',      $orderBy, $vars, $lang->story->title);?></th>
        <th class='w-90px {sorter:false}'><?php common::printOrderLink('plan',       $orderBy, $vars, $lang->story->planAB);?></th>
        <th class='w-user {sorter:false}'><?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
        <th class='w-user {sorter:false}'><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
        <th class='w-hour {sorter:false}'><?php common::printOrderLink('estimate',   $orderBy, $vars, $lang->story->estimateAB);?></th>
        <th class='w-50px {sorter:false}'><?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
        <th class='w-70px {sorter:false}'><?php common::printOrderLink('stage',      $orderBy, $vars, $lang->story->stageAB);?></th>
        <th title='<?php echo $lang->story->taskCount?>' class='w-30px'><?php echo $lang->story->taskCountAB;?></th>
        <th title='<?php echo $lang->story->bugCount?>'  class='w-30px'><?php echo $lang->story->bugCountAB;?></th>
        <th title='<?php echo $lang->story->caseCount?>' class='w-30px'><?php echo $lang->story->caseCountAB;?></th>
        <th class='w-140px {sorter:false}'><?php echo $lang->actions;?></th>
      </tr>
      </thead>
      <?php if($stories):?>
      <tbody>
      <?php foreach($stories as $key => $story):?>
      <?php
      $viewLink = $this->createLink('story', 'view', "storyID=$story->id");
      $canView  = common::hasPriv('story', 'view');
      ?>
      <tr class='text-center'>
        <td class='cell-id'>
          <input type='checkbox' name='storyIDList[<?php echo $story->id;?>]' value='<?php echo $story->id;?>' /> 
          <?php echo $canView ? html::a($viewLink, sprintf('%03d', $story->id)): sprintf('%03d', $story->id);?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri)?></span></td>
        <td class='text-left' title="<?php echo $story->title?>"><nobr>
        <?php if($story->branch) echo "<span title='{$lang->product->branchName[$product->type]}' class='label label-branch label-badge'>{$branches[$story->branch]}</span>"?>
        <?php if($modulePairs and $story->module) echo "<span title='{$lang->story->module}' class='label label-info label-badge'>{$modulePairs[$story->module]}</span> "?>
        <?php echo html::a($viewLink, $story->title, null, "style='color: $story->color'");?>
        </nobr></td>
        <td title="<?php echo $story->planTitle?>"><?php echo $story->planTitle;?></td>
        <td><?php echo zget($users, $story->openedBy, $story->openedBy);?></td>
        <td><?php echo zget($users, $story->assignedTo, $story->assignedTo);?></td>
        <td><?php echo $story->estimate;?></td>
        <td class='story-<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
        <td>
          <?php
          echo "<div" . (isset($storyStages[$story->id]) ? " class='popoverStage' data-toggle='popover' data-placement='bottom' data-target='\$next'" : '') . "'>";
          echo $lang->story->stageList[$story->stage];
          if(isset($storyStages[$story->id])) echo "<span class='pl-5px'><i class='icon icon-caret-down'></i></span>";
          echo '</div>';
          if(isset($storyStages[$story->id]))
          {
              echo "<div class='popover'>";
              foreach($storyStages[$story->id] as $storyBranch => $storyStage) echo $branches[$storyBranch] . ": " . $lang->story->stageList[$storyStage->stage] . '<br />';
              echo "</div>";
          }
          ?>
        </td>
        <td class='linkbox'>
          <?php
          $tasksLink = $this->createLink('story', 'tasks', "storyID=$story->id");
          $storyTasks[$story->id] > 0 ? print(html::a($tasksLink, $storyTasks[$story->id], '', 'class="iframe"')) : print(0);
          ?>
        </td>
        <td class='linkbox'>
          <?php
          $bugsLink = $this->createLink('story', 'bugs', "storyID=$story->id");
          $storyBugs[$story->id] > 0 ? print(html::a($bugsLink, $storyBugs[$story->id], '', 'class="iframe"')) : print(0);
          ?>
        </td>
        <td class='linkbox'>
          <?php
          $casesLink = $this->createLink('story', 'cases', "storyID=$story->id");
          $storyCases[$story->id] > 0 ? print(html::a($casesLink, $storyCases[$story->id], '', 'class="iframe"')) : print(0);
          ?>
        </td>
        <td class='text-right'>
          <?php 
          $vars = "story={$story->id}";
          common::printIcon('story', 'change',     $vars, $story, 'list', 'random');
          common::printIcon('story', 'review',     $vars, $story, 'list', 'review');
          common::printIcon('story', 'close',      $vars, $story, 'list', 'off', '', 'iframe', true);
          common::printIcon('story', 'edit',       $vars, $story, 'list', 'pencil');
          if($this->config->global->flow != 'onlyStory')
          {
              common::printIcon('story', 'createCase', "productID=$story->product&branch=$story->branch&module=0&from=&param=0&$vars", $story, 'list', 'sitemap');
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <?php endif;?>
