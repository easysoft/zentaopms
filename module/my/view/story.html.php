<?php
/**
 * The story view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: story.html.php 5116 2013-07-12 06:37:48Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='featurebar'>
  <nav class='nav'>
    <?php
    echo "<li id='assignedToTab'>" . html::a(inlink('story', "type=assignedTo"),  $lang->my->storyMenu->assignedToMe) . "</li>";
    echo "<li id='openedByTab'>"   . html::a(inlink('story', "type=openedBy"),    $lang->my->storyMenu->openedByMe)   . "</li>";
    echo "<li id='reviewedByTab'>" . html::a(inlink('story', "type=reviewedBy"),  $lang->my->storyMenu->reviewedByMe) . "</li>";
    echo "<li id='closedByTab'>"   . html::a(inlink('story', "type=closedBy"),    $lang->my->storyMenu->closedByMe)   . "</li>";
    ?>
  </nav>
</div>
<form method='post' id='myStoryForm'>
<table class='table table-condensed table-hover table-striped tablesorter table-fixed'>
  <?php $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
  <thead>
    <tr class='text-center'>
      <th class='w-id'>    <?php common::printOrderLink('id',           $orderBy, $vars, $lang->idAB);?></th>
      <th class='w-pri'>   <?php common::printOrderLink('pri',          $orderBy, $vars, $lang->priAB);?></th>
      <th class='w-200px'> <?php common::printOrderLink('productTitle', $orderBy, $vars, $lang->story->product);?></th>
      <th>                 <?php common::printOrderLink('title',        $orderBy, $vars, $lang->story->title);?></th>
      <th class='w-150px'> <?php common::printOrderLink('planTitle',    $orderBy, $vars, $lang->story->plan);?></th>
      <th class='w-user'>  <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->openedByAB);?></th>
      <th class='w-hour'>  <?php common::printOrderLink('estimate',     $orderBy, $vars, $lang->story->estimateAB);?></th>
      <th class='w-status'><?php common::printOrderLink('status',       $orderBy, $vars, $lang->statusAB);?></th>
      <th class='w-100px'> <?php common::printOrderLink('stage',        $orderBy, $vars, $lang->story->stageAB);?></th>
      <th class='w-140px'><?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <tbody>
    <?php
    $canBatchEdit  = common::hasPriv('story', 'batchEdit');
    $canBatchClose = common::hasPriv('story', 'batchClose') and strtolower($type) != 'closedbyme';
    ?>
    <?php foreach($stories as $key => $story):?>
    <?php $storyLink = $this->createLink('story', 'view', "id=$story->id");?>
    <tr class='text-center'>
      <td>
        <?php if($canBatchEdit or $canBatchClose):?>
        <input type='checkbox' name='storyIDList[<?php echo $story->id;?>]' value='<?php echo $story->id;?>' /> 
        <?php endif;?>
        <?php echo html::a($storyLink, sprintf('%03d', $story->id));?>
      </td>
      <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
      <td><?php echo $story->productTitle;?></td>
      <td class='text-left nobr'><?php echo html::a($storyLink, $story->title);?></td>
      <td><?php echo $story->planTitle;?></td>
      <td><?php echo $users[$story->openedBy];?></td>
      <td><?php echo $story->estimate;?></td>
      <td class='story-<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
      <td><?php echo $lang->story->stageList[$story->stage];?></td>
      <td class='text-right'>
        <?php
        common::printIcon('story', 'change',     "storyID=$story->id", $story, 'list', 'random');
        common::printIcon('story', 'review',     "storyID=$story->id", $story, 'list', 'search');
        common::printIcon('story', 'close',      "storyID=$story->id", $story, 'list', 'off', '', 'text-danger');
        common::printIcon('story', 'edit',       "storyID=$story->id", $story, 'list', 'pencil');
        common::printIcon('story', 'createCase', "productID=$story->product&moduleID=0&from=&param=0&storyID=$story->id", '', 'list', 'sitemap');
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
          if($canBatchEdit or $canBatchRun) echo "<div class='btn-group'>" . html::selectButton() . '</div>';
         
          if($canBatchEdit)
          {
              $actionLink = $this->createLink('story', 'batchEdit');
              echo html::commonButton($lang->edit, "onclick=\"setFormAction('$actionLink')\"");
          }
          if($canBatchClose)
          {
              $actionLink = $this->createLink('story', 'batchClose');
              echo html::commonButton($lang->close, "onclick=\"setFormAction('$actionLink')\"");
          }
      }
      ?>
      </div>
      <?php $pager->show();?>
    </td>
  </tr>
  </tfoot>
</table>
</form>
<script language='javascript'>$("#<?php echo $type;?>Tab").addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
