<?php $_GET['onlybody'] = 'no';?>
<table class='table-1 fixed colored tablesorter datatable' id='storyList'>
  <thead>
  <tr class='colhead'>
    <?php $vars = "productID=$productID&browseType=$browseType&param=$moduleID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
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
  <tbody>
  <?php foreach($stories as $key => $story):?>
  <?php
  $viewLink = $this->createLink('story', 'view', "storyID=$story->id");
  $canView  = common::hasPriv('story', 'view');
  ?>
  <tr class='a-center'>
    <td>
      <input type='checkbox' name='storyIDList[<?php echo $story->id;?>]' value='<?php echo $story->id;?>' /> 
      <?php if($canView) echo html::a($viewLink, sprintf('%03d', $story->id)); else printf('%03d', $story->id);?>
    </td>
    <td><span class='<?php echo 'pri' . $lang->story->priList[$story->pri];?>'><?php echo $lang->story->priList[$story->pri]?></span></td>
    <td class='a-left' title="<?php echo $story->title?>"><nobr><?php echo html::a($viewLink, $story->title);?></nobr></td>
    <td title="<?php echo $story->planTitle?>"><?php echo $story->planTitle;?></td>
    <td><?php echo $lang->story->sourceList[$story->source];?></td>
    <td><?php echo $users[$story->openedBy];?></td>
    <td><?php echo $users[$story->assignedTo];?></td>
    <td><?php echo $story->estimate;?></td>
    <td class='<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
    <td><?php echo $lang->story->stageList[$story->stage];?></td>
    <td class='a-right'>
      <?php 
      $vars = "story={$story->id}";
      common::printIcon('story', 'change',     $vars, $story, 'list');
      common::printIcon('story', 'review',     $vars, $story, 'list');
      common::printIcon('story', 'close',      $vars, $story, 'list', '', '', 'iframe', true);
      common::printIcon('story', 'edit',       $vars, $story, 'list');
      common::printIcon('story', 'createCase', "productID=$story->product&module=0&from=&param=0&$vars", $story, 'list', 'createCase');
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
  <tr>
    <td colspan='11' class='a-right'>
      <div class='f-left'>
      <?php
      if(count($stories))
      {
         echo html::selectAll() . html::selectReverse();
         
          if(common::hasPriv('story', 'batchEdit'))
          {
              $actionLink = $this->createLink('story', 'batchEdit', "from=productBrowse&productID=$productID&projectID=0&orderBy=$orderBy");
              echo html::commonButton($lang->edit, "onclick=\"changeAction('productStoryForm', 'batchEdit', '$actionLink')\"");
          }
          if(common::hasPriv('story', 'batchClose') and strtolower($browseType) != 'closedbyme' and strtolower($browseType) != 'closedstory')
          {
              $actionLink = $this->createLink('story', 'batchClose', "from=productBrowse&productID=$productID&projectID=0&orderBy=$orderBy");
              echo html::commonButton($lang->close, "onclick=\"changeAction('productStoryForm', 'batchClose', '$actionLink')\"");
          }
      }
      echo $summary;
      ?>
      </div>
      <?php $pager->show();?>
    </td>
  </tr>
  </tfoot>
</table>
