<?php
/**
 * The story view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: story.html.php 4771 2013-05-05 07:41:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include './featurebar.html.php';?>
<div class='sub-featurebar'>
  <ul class='nav'>
    <?php
    echo "<li id='assignedToTab'>" . html::a(inlink('story', "account=$account&type=assignedTo"),  $lang->user->assignedTo) . "</li>";
    echo "<li id='openedByTab'>"   . html::a(inlink('story', "account=$account&type=openedBy"),    $lang->user->openedBy)   . "</li>";
    echo "<li id='reviewedByTab'>" . html::a(inlink('story', "account=$account&type=reviewedBy"),  $lang->user->reviewedBy) . "</li>";
    echo "<li id='closedByTab'>"   . html::a(inlink('story', "account=$account&type=closedBy"),    $lang->user->closedBy)   . "</li>";
    ?>
  </ul>
</div>
<table class='table tablesorter table-fixed'>
  <thead>
    <tr class='colhead'>
      <th class='w-id'>    <?php echo $lang->idAB;?></th>
      <th class='w-pri'>   <?php echo $lang->priAB;?></th>
      <th class='w-200px'> <?php echo $lang->story->product;?></th>
      <th>                 <?php echo $lang->story->title;?></th>
      <th class='w-150px'> <?php echo $lang->story->plan;?></th>
      <th class='w-user'>  <?php echo $lang->openedByAB;?></th>
      <th class='w-hour'>  <?php echo $lang->story->estimateAB;?></th>
      <th class='w-status'><?php echo $lang->statusAB;?></th>
      <th class='w-100px'> <?php echo $lang->story->stageAB;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($stories as $key => $story):?>
      <?php $storyLink = $this->createLink('story', 'view', "id=$story->id");?>
      <tr class='text-center'>
      <td><?php echo html::a($storyLink, sprintf('%03d', $story->id));?></td>
      <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
      <td><?php echo $story->productTitle;?></td>
      <td class='text-left nobr'><?php echo html::a($storyLink, $story->title);?></td>
      <td title='<?php echo $story->planTitle;?>'><?php echo $story->planTitle;?></td>
      <td><?php echo $users[$story->openedBy];?></td>
      <td><?php echo $story->estimate;?></td>
      <td class='story-<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
      <td><?php echo $lang->story->stageList[$story->stage];?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='9'><?php echo $pager->show();?></td></tr></tfoot>
</table>
<script language='javascript'>$("#<?php echo $type;?>Tab").addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
