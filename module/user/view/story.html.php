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
<style>
.table td.estimate {padding-right: 12px;}
</style>
<div id='mainContent'>
  <nav id='contentNav'>
    <ul class='nav nav-default'>
      <?php
      $that   = zget($lang->user->thirdPerson, $user->gender);
      $active = $type == 'assignedTo' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('story', "userID={$user->id}&storyType=$storyType&type=assignedTo"),  sprintf($lang->user->assignedTo, $that)) . "</li>";

      $active = $type == 'openedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('story', "userID={$user->id}&storyType=$storyType&type=openedBy"),    sprintf($lang->user->openedBy, $that))   . "</li>";

      $active = $type == 'reviewedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('story', "userID={$user->id}&storyType=$storyType&type=reviewedBy"),  sprintf($lang->user->reviewedBy ,$that)) . "</li>";

      $active = $type == 'closedBy' ? 'active' : '';
      echo "<li class='$active'>" . html::a(inlink('story', "userID={$user->id}&storyType=$storyType&type=closedBy"),    sprintf($lang->user->closedBy ,$that))   . "</li>";
      ?>
    </ul>
  </nav>

  <div class='main-table'>
    <table class='table has-sort-head'>
      <?php $vars = "userID={$user->id}&storyType=$storyType&type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
      <thead>
        <tr class='colhead'>
          <th class='w-id'>   <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
          <th class='w-pri'>  <?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
          <th class='w-200px'><?php common::printOrderLink('product', $orderBy, $vars, $lang->story->product);?></th>
          <th>                <?php common::printOrderLink('title', $orderBy, $vars, $lang->story->title);?></th>
          <?php if($storyType != 'requirement'):?>
          <th class='w-150px'><?php common::printOrderLink('plan', $orderBy, $vars, $lang->story->plan);?></th>
          <?php endif;?>
          <th class='w-90px'> <?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->openedByAB);?></th>
          <th class='w-80px text-right'><?php common::printOrderLink('estimate', $orderBy, $vars, $lang->story->estimateAB);?></th>
          <th class='w-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->statusAB);?></th>
          <th class='w-100px'> <?php common::printOrderLink('stage', $orderBy, $vars, $lang->story->stageAB);?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($stories as $key => $story):?>
          <?php $storyLink = $this->createLink('story', 'view', "id=$story->id");?>
        <tr class='text-left'>
          <td><?php echo html::a($storyLink, sprintf('%03d', $story->id));?></td>
          <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
          <td><?php echo $story->productTitle;?></td>
          <td class='text-left nobr'><?php echo html::a($storyLink, $story->title);?></td>
          <?php if($storyType != 'requirement'):?>
          <td title='<?php echo $story->planTitle;?>'><?php echo $story->planTitle;?></td>
          <?php endif;?>
          <td><?php echo zget($users, $story->openedBy);?></td>
          <td class='estimate text-right' title="<?php echo $story->estimate . ' ' . $lang->hourCommon;?>"><?php echo $story->estimate . $config->hourUnit;?></td>
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
