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
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed' id='storyList'>
      <thead>
      <tr>
        <th class='w-id'>  <?php common::printOrderLink('id',         $orderBy, $vars, $lang->idAB);?></th>
        <th class='w-pri'> <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
        <th class='w-p30'> <?php common::printOrderLink('title',      $orderBy, $vars, $lang->story->title);?></th>
        <th>               <?php common::printOrderLink('plan',       $orderBy, $vars, $lang->story->planAB);?></th>
        <th>               <?php common::printOrderLink('source',     $orderBy, $vars, $lang->story->source);?></th>
        <th>               <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
        <th>               <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
        <th class='w-hour'><?php common::printOrderLink('estimate',   $orderBy, $vars, $lang->story->estimateAB);?></th>
        <th>               <?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
        <th>               <?php common::printOrderLink('stage',      $orderBy, $vars, $lang->story->stageAB);?></th>
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
        <td class='text-left'>
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
        <td><?php echo $lang->story->sourceList[$story->source];?></td>
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
        <td class='text-right'>
          <?php 
          $vars = "story={$story->id}";
          common::printIcon('story', 'change',     $vars, $story, 'list', 'random');
          common::printIcon('story', 'review',     $vars, $story, 'list', 'search');
          common::printIcon('story', 'close',      $vars, $story, 'list', 'off', '', 'iframe', true);
          common::printIcon('story', 'edit',       $vars, $story, 'list', 'pencil');
          common::printIcon('story', 'createCase', "productID=$story->product&branch=$story->branch&module=0&from=&param=0&$vars", $story, 'list', 'sitemap');
          ?>
        </td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <?php endif;?>
