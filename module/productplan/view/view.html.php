<?php
/**
 * The view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: view.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->productplan->confirmUnlinkStory)?>
<?php js::set('confirmUnlinkBug', $lang->productplan->confirmUnlinkBug)?>
<div id='titlebar' <?php if($plan->deleted) echo "class='deleted'";?>>PLAN #<?php echo $plan->id . ' ' . $plan->title;?></div>
<form method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkStory');?>">
  <table class='cont-rt5'>
    <tr valign='top'>
      <td>
        <fieldset>
          <legend><?php echo $lang->productplan->desc;?></legend>
          <div class='content'><?php echo $plan->desc;?></div>
        </fieldset>
        <?php include '../../common/view/action.html.php';?>
        <div class='a-center f-16px strong'>
        <?php          
         $browseLink = $this->session->productPlanList ? $this->session->productPlanList : inlink('browse', "planID=$plan->id");
         if(!$plan->deleted)
         {
            common::printIcon('productplan', 'linkStory',"planID=$plan->id");
            common::printIcon('productplan', 'linkBug',  "planID=$plan->id");
            common::printIcon('productplan', 'edit',     "planID=$plan->id");
            common::printIcon('productplan', 'delete',   "planID=$plan->id", '', 'button', '', 'hiddenwin');
         }
         common::printRPN($browseLink);
        ?>
        </div>
      </td>
      <td class="divider"></td>
      <td class="side">
        <fieldset>
          <legend><?php echo $lang->productplan->basicInfo?></legend>
          <table class='table-1 a-left'>
            <tr>
              <th width='25%' class='a-right'><?php echo $lang->productplan->title;?></th> 
              <td><?php echo $plan->title;?></th>
            </tr>
            <tr>
              <th class='rowhead'><?php echo $lang->productplan->begin;?></th>
              <td><?php echo $plan->begin;?></th>
            </tr>
            <tr>
              <th class='rowhead'><?php echo $lang->productplan->end;?></th>
              <td><?php echo $plan->end;?></th>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>
  <table class='table-1 tablesorter a-center fixed' id='storyList'>
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
      <?php
      $totalEstimate = 0.0;
      $canBatchUnlink = common::hasPriv('productPlan', 'batchUnlinkStory');
      ?>
      <?php foreach($planStories as $story):?>
      <?php
      $viewLink = $this->createLink('story', 'view', "storyID=$story->id");
      $totalEstimate += $story->estimate;
      ?>
      <tr>
        <td class='a-center'>
          <?php if($canBatchUnlink):?>
          <input class='ml-10px' type='checkbox' name='unlinkStories[]'  value='<?php echo $story->id;?>'/> 
          <?php endif;?>
          <?php echo html::a($viewLink, sprintf("%03d", $story->id));?>
        </td>
        <td><span class='<?php echo 'pri' . $story->pri?>'><?php echo $story->pri;?></span></td>
        <td class='a-left nobr'><?php echo html::a($viewLink , $story->title);?></td>
        <td><?php echo $users[$story->openedBy];?></td>
        <td><?php echo $users[$story->assignedTo];?></td>
        <td><?php echo $story->estimate;?></td>
        <td><?php echo $lang->story->statusList[$story->status];?></td>
        <td><?php echo $lang->story->stageList[$story->stage];?></td>
        <td>
          <?php
          $unlinkURL = $this->createLink('productplan', 'unlinkStory', "story=$story->id&confirm=yes");
          echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"storyList\",confirmUnlinkStory)", '&nbsp;', '', "class='icon-green-productplan-unlinkStory' title='{$lang->productplan->unlinkStory}'");
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
    <tr>
      <td colspan='9'>
        <div class='f-left'>
        <?php 
        if(count($planStories) and $canBatchUnlink)
        {
            echo html::selectAll() . html::selectReverse();
            echo html::submitButton($lang->productplan->batchUnlink);
        }
        ?>
        </div>
        <div class='f-right'><?php echo $summary;?> </div>
      </td>
    </tr>
    </tfoot>
  </table>
</form>
<form method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkBug');?>">
  <table class='table-1 tablesorter a-center fixed' id='bugList'>
    <caption class='caption-tl'><?php echo $plan->title .$lang->colon . $lang->productplan->linkedBugs;?></caption>
    <thead>
    <tr class='colhead'>
      <th class='w-id'>    <?php echo $lang->idAB;?></th>
      <th class='w-pri'>   <?php echo $lang->priAB;?></th>
      <th>                 <?php echo $lang->bug->title;?></th>
      <th class='w-user'>  <?php echo $lang->openedByAB;?></th>
      <th class='w-user'>  <?php echo $lang->assignedToAB;?></th>
      <th class='w-status'><?php echo $lang->statusAB;?></th>
      <th class='w-50px {sorter:false}'><?php echo $lang->actions?></th>
    </tr>
    </thead>
    <tbody>
      <?php $canBatchUnlink = common::hasPriv('productPlan', 'batchUnlinkBug');?>
      <?php foreach($planBugs as $bug):?>
      <tr>
        <td class='a-center'>
          <?php if($canBatchUnlink):?>
          <input class='ml-10px' type='checkbox' name='unlinkBugs[]'  value='<?php echo $bug->id;?>'/> 
          <?php endif;?>
          <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), sprintf("%03d", $bug->id));?>
        </td>
        <td><span class='<?php echo 'pri' . $bug->pri?>'><?php echo $bug->pri;?></span></td>
        <td class='a-left nobr'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?></td>
        <td><?php echo $users[$bug->openedBy];?></td>
        <td><?php echo $users[$bug->assignedTo];?></td>
        <td><?php echo $lang->bug->statusList[$bug->status];?></td>
        <td>
          <?php
          $unlinkURL = $this->createLink('productplan', 'unlinkBug', "story=$bug->id&confirm=yes");
          echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"bugList\",confirmUnlinkBug)", '&nbsp;', '', "class='icon-green-productplan-unlinkBug' title='{$lang->productplan->unlinkBug}'");
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
    <tr>
      <td colspan='7'>
        <div class='f-left'>
          <?php 
          echo html::selectAll('linkedBugsForm') . html::selectReverse('linkedBugsForm');
          echo html::submitButton($lang->productplan->batchUnlink);
          ?>
        </div>
        <div class='f-right'><?php echo sprintf($lang->productplan->bugSummary, count($planStories));?> </div>
      </td>
    </tr>
    </tfoot>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
