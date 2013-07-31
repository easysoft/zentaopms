<?php
/**
 * The link story view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: linkstory.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->productplan->confirmUnlinkStory)?>
<div id='querybox'></div>
<div id='storyList'>
  <form method='post' id='unlinkedStoriesForm'>
    <table class='table-1 tablesorter a-center fixed'> 
      <caption class='caption-tl'><?php echo $plan->title .$lang->colon . $lang->productplan->unlinkedStories;?></caption>
      <thead>
      <tr class='colhead'>
        <th class='w-id'>    <?php echo $lang->idAB;?></th>
        <th class='w-pri'>   <?php echo $lang->priAB;?></th>
        <th class='w-200px'> <?php echo $lang->story->plan;?></th>
        <th>                 <?php echo $lang->story->title;?></th>
        <th class='w-user'>  <?php echo $lang->openedByAB;?></th>
        <th class='w-user'>  <?php echo $lang->assignedToAB;?></th>
        <th class='w-30px'>  <?php echo $lang->story->estimateAB;?></th>
        <th class='w-status'><?php echo $lang->statusAB;?></th>
        <th class='w-60px'>  <?php echo $lang->story->stageAB;?></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach($allStories as $story):?>
      <?php
      if(isset($planStories[$story->id])) continue;
      if($story->plan and helper::diffDate($plans[$story->plan], helper::today()) > 0) continue;
      ?>
      <tr>
        <td class='a-left'>
          <input class='ml-10px' type='checkbox' name='stories[]'  value='<?php echo $story->id;?>'/> 
          <?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->id);?>
        </td>
        <td><span class='<?php echo 'pri' . $story->pri;?>'><?php echo $story->pri?></span></td>
        <td><?php echo $story->planTitle;?></td>
        <td class='a-left nobr'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?></td>
        <td><?php echo $users[$story->openedBy];?></td>
        <td><?php echo $users[$story->assignedTo];?></td>
        <td><?php echo $story->estimate;?></td>
        <td><?php echo $lang->story->statusList[$story->status];?></td>
        <td><?php echo $lang->story->stageList[$story->stage];?></td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tfoot>
      <tr>
        <td colspan='9' class='a-left'>
          <?php if(count($allStories)) echo html::selectAll('unlinkedStoriesForm') . html::selectReverse('unlinkedStoriesForm') .  html::submitButton($lang->story->linkStory);?>
        </td>
      </tr>
      </tfoot>
    </table>
  </form>
   
  <form method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkStory');?>" id='linkedStoriesForm'>
    <table class='table-1 tablesorter a-center fixed'> 
      <caption class='caption-tl'><?php echo $plan->title .$lang->colon . $lang->productplan->linkedStories;?></caption>
      <thead>
      <tr class='colhead'>
        <th class='w-id'>    <?php echo $lang->idAB;?></th>
        <th class='w-pri'>   <?php echo $lang->priAB;?></th>
        <th>                 <?php echo $lang->story->title;?></th>
        <th class='w-user'>  <?php echo $lang->openedByAB;?></th>
        <th class='w-user'>  <?php echo $lang->assignedToAB;?></th>
        <th class='w-30px'>  <?php echo $lang->story->estimateAB;?></th>
        <th class='w-status'><?php echo $lang->statusAB;?></th>
        <th class='w-60px'>  <?php echo $lang->story->stageAB;?></th>
        <th class='w-50px {sorter:false}'><?php echo $lang->actions?></th>
      </tr>
      </thead>
      <tbody>
      <?php $canBatchUnlink = common::hasPriv('productPlan', 'batchUnlinkStory');?>
      <?php foreach($planStories as $story):?>
      <tr>
        <td class='a-center'>
          <?php if($canBatchUnlink):?>
          <input class='ml-10px' type='checkbox' name='unlinkStories[]'  value='<?php echo $story->id;?>'/> 
          <?php endif;?>
          <?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), sprintf("%03d", $story->id));?>
        </td>
        <td><span class='<?php echo 'pri' . $story->pri;?>'><?php echo $story->pri?></span></td>
        <td class='a-left nobr'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?></td>
        <td><?php echo $users[$story->openedBy];?></td>
        <td><?php echo $users[$story->assignedTo];?></td>
        <td><?php echo $story->estimate;?></td>
        <td><?php echo $lang->story->statusList[$story->status];?></td>
        <td><?php echo $lang->story->stageList[$story->stage];?></td>
        <td>
          <?php
          $unlinkURL = $this->createLink('productplan', 'unlinkStory', "storyID=$story->id&confirm=yes");
          echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"storyList\",confirmUnlinkStory)", '&nbsp;', '', "class='icon-green-productplan-unlinkStory' title='{$lang->productplan->unlinkStory}'");
          ?>
        </td>
      </tr>
      <?php endforeach;?>
      <?php if(count($planStories) and $canBatchUnlink):?>
      <tfoot>
      <tr>
        <td colspan='9' class='a-left'>
        <?php 
        echo html::selectAll('linkedStoriesForm') . html::selectReverse('linkedStoriesForm');
        echo html::submitButton($lang->productplan->batchUnlink);
        ?>
        </td>
      </tr>
      </tfoot>
      <?php endif;?>
      </tbody>
    </table>
  </form>
</div>
<script>$(function(){ajaxGetSearchForm()})</script>
<?php include '../../common/view/footer.html.php';?>
