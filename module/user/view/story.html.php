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
<div id='mainContent'>
  <nav id='contentNav'>
    <ul class='nav nav-default'>
      <?php
      $that   = zget($lang->user->thirdPerson, $user->gender);
      $active = $type == 'assignedTo' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('story', "account=$account&type=assignedTo"),  sprintf($lang->user->assignedTo, $that)) . "</li>";

      $active = $type == 'openedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('story', "account=$account&type=openedBy"),   sprintf($lang->user->openedBy, $that))   . "</li>";

      $active = $type == 'reviewedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('story', "account=$account&type=reviewedBy"),  sprintf($lang->user->reviewedBy ,$that)) . "</li>";

      $active = $type == 'closedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('story', "account=$account&type=closedBy"),    sprintf($lang->user->closedBy ,$that))   . "</li>";
      ?>
    </ul>
  </nav>

  <div class='main-table'>
    <table class='table has-sort-head tablesorter'>
      <thead>
        <tr class='colhead'>
          <th class='w-id'>    <?php echo $lang->idAB;?></th>
          <th class='w-pri'>   <?php echo $lang->priAB;?></th>
          <th class='w-200px'> <?php echo $lang->story->product;?></th>
          <th>                 <?php echo $lang->story->title;?></th>
          <th class='w-150px'> <?php echo $lang->story->plan;?></th>
          <th class='w-90px'>  <?php echo $lang->openedByAB;?></th>
          <th class='w-60px'>  <?php echo $lang->story->estimateAB;?></th>
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
          <td><?php echo zget($users, $story->openedBy);?></td>
          <td><?php echo $story->estimate;?></td>
          <td class='story-<?php echo $story->status;?>'><?php echo $this->processStatus('story', $story);?></td>
          <td><?php echo $lang->story->stageList[$story->stage];?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($stories):?>
    <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  <div>
</div>
<?php include '../../common/view/footer.html.php';?>
