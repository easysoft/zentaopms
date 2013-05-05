<?php
/**
 * The story view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include './featurebar.html.php';?>
<div id='featurebar'>
  <div class='f-left'>
    <?php
    $this->app->loadLang('my');
    echo "<span id='assignedToTab'>" . html::a(inlink('story', "account=$account&type=assignedTo"),  $lang->user->assignedTo) . "</span>";
    echo "<span id='openedByTab'>"   . html::a(inlink('story', "account=$account&type=openedBy"),    $lang->user->openedBy)   . "</span>";
    echo "<span id='reviewedByTab'>" . html::a(inlink('story', "account=$account&type=reviewedBy"),  $lang->user->reviewedBy) . "</span>";
    echo "<span id='closedByTab'>"   . html::a(inlink('story', "account=$account&type=closedBy"),    $lang->user->closedBy)   . "</span>";
    ?>
  </div>
</div>
<table class='table-1 tablesorter fixed'>
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
      <tr class='a-center'>
      <td><?php echo html::a($storyLink, sprintf('%03d', $story->id));?></td>
      <td><span class='<?php echo 'pri' . $story->pri;?>'><?php echo $story->pri?></span></td>
      <td><?php echo $story->productTitle;?></td>
      <td class='a-left nobr'><?php echo html::a($storyLink, $story->title);?></td>
      <td><?php echo $story->planTitle;?></td>
      <td><?php echo $users[$story->openedBy];?></td>
      <td><?php echo $story->estimate;?></td>
      <td class='<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
      <td><?php echo $lang->story->stageList[$story->stage];?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='9'><?php echo $pager->show();?></td></tr></tfoot>
</table>
<script language='javascript'>$("#<?php echo $type;?>Tab").addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
