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
<?php js::set('confirmUnlinkStory', $lang->productplan->confirmUnlinkStory)?>
<?php js::set('confirmUnlinkBug', $lang->productplan->confirmUnlinkBug)?>
<div id='titlebar'>
  <div class='heading'>
  <span class='prefix'><?php echo html::icon($lang->icons['plan']);?> <strong><?php echo $plan->id;?></strong></span>
    <strong><?php echo $plan->title;?></strong>
    <?php if($plan->deleted):?>
    <span class='label label-danger'><?php echo $lang->plan->deleted;?></span>
    <?php endif; ?>
  </div>
  <div class='actions'>
  <?php
   $browseLink = $this->session->productPlanList ? $this->session->productPlanList : inlink('browse', "planID=$plan->id");
   if(!$plan->deleted)
   {
      ob_start();
      echo "<div class='btn-group'>";
      common::printIcon('productplan', 'linkStory',"planID=$plan->id", '', 'button', $lang->icons['link']);
      common::printIcon('productplan', 'linkBug',  "planID=$plan->id", '', 'button', $lang->icons['bug']);
      echo '</div>';
      echo "<div class='btn-group'>";
      common::printIcon('productplan', 'edit',     "planID=$plan->id");
      common::printIcon('productplan', 'delete',   "planID=$plan->id", '', 'button', '', 'hiddenwin');
      echo '</div>';
      $actionLinks = ob_get_contents();
      ob_end_clean();
      echo $actionLinks;
   }
   common::printRPN($browseLink);
  ?>
  </div>
</div>
<div class='row'>
  <div class='col-sm-8 col-lg-9'>
    <div class='main'>
      <fieldset>
        <legend><?php echo $lang->productplan->desc;?></legend>
        <div class='article-content'><?php echo $plan->desc;?></div>
      </fieldset>
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='<?php if($type == 'story') echo 'active'?>'><a href='#batchUnlinkStory' data-toggle='tab'><?php echo  html::icon($lang->icons['story']) . ' ' . $lang->productplan->linkedStories;?></a></li>
          <li class='<?php if($type == 'bug') echo 'active'?>'><a href='#batchUnlinkBug' data-toggle='tab'><?php echo  html::icon($lang->icons['bug']) . ' ' . $lang->productplan->linkedBugs;?></a></li>
        </ul>
        <div class='tab-content'>
          <div id='batchUnlinkStory' class='tab-pane <?php if($type == 'story') echo 'active'?>'>
            <?php if(common::hasPriv('productplan', 'linkStory')):?>
            <div class='action'><?php echo html::a(inlink('linkStory',"planID=$plan->id"), '<i class="icon-link"></i> ' . $lang->productplan->linkStory, '', "class='btn btn-sm'");?></div>
            <?php endif;?>
            <form class='form-condensed' method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkStory');?>">
              <table class='table tablesorter table-condensed table-hover table-striped table-borderless table-fixed' id='storyList'>
                <?php $vars = "planID={$plan->id}&type=story&orderBy=%s"; ?>
                <thead>
                <tr>
                  <th class='w-id' >   <?php common::printOrderLink('id',         $orderBy, $vars, $lang->idAB);?></th>
                  <th class='w-pri'>   <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
                  <th>                 <?php common::printOrderLink('title',      $orderBy, $vars, $lang->story->title);?></th>
                  <th class='w-user'>  <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
                  <th class='w-user'>  <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
                  <th class='w-60px'>  <?php common::printOrderLink('estimate',   $orderBy, $vars, $lang->story->estimateAB);?></th>
                  <th class='w-status'><?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
                  <th class='w-80px'>  <?php common::printOrderLink('stage',      $orderBy, $vars, $lang->story->stageAB);?></th>
                  <th class='w-50px'><?php echo $lang->actions?></th>
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
                  <tr class='text-center'>
                    <td>
                      <?php if($canBatchUnlink):?>
                      <input class='ml-10px' type='checkbox' name='unlinkStories[]'  value='<?php echo $story->id;?>'/> 
                      <?php endif;?>
                      <?php echo html::a($viewLink, sprintf("%03d", $story->id));?>
                    </td>
                    <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
                    <td class='text-left nobr'><?php echo html::a($viewLink , $story->title);?></td>
                    <td><?php echo $users[$story->openedBy];?></td>
                    <td><?php echo $users[$story->assignedTo];?></td>
                    <td><?php echo $story->estimate;?></td>
                    <td class='story-<?php echo $story->status?>'><?php echo $lang->story->statusList[$story->status];?></td>
                    <td><?php echo $lang->story->stageList[$story->stage];?></td>
                    <td>
                      <?php
                      if(common::hasPriv('productplan', 'unlinkStory'))
                      {
                          $unlinkURL = $this->createLink('productplan', 'unlinkStory', "story=$story->id&confirm=yes");
                          echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"storyList\",confirmUnlinkStory)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->productplan->unlinkStory}'");
                      }
                      ?>
                    </td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                  <td colspan='9'>
                    <div class='table-actions clearfix'>
                      <?php 
                      if(count($planStories) and $canBatchUnlink)
                      {
                          echo "<div class='btn-group'>" . html::selectButton() . '</div>';
                          echo html::submitButton($lang->productplan->batchUnlink);
                      }
                      ?>
                      <div class='text'><?php echo $summary;?></div>
                    </div>
                  </td>
                </tr>
                </tfoot>
              </table>
            </form>
          </div>
          <div id='batchUnlinkBug' class='tab-pane <?php if($type == 'bug') echo 'active';?>'>
            <?php if(common::hasPriv('productplan', 'linkBug')):?>
            <div class='action'><?php echo html::a(inlink('linkBug',"planID=$plan->id"), '<i class="icon-bug"></i> ' . $lang->productplan->linkBug, '', "class='btn btn-sm'");?></div>
            <?php endif;?>
            <form method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkBug');?>">
              <table class='table tablesorter table-condensed table-hover table-striped table-borderless table-fixed' id='bugList'>
                <?php $vars = "planID={$plan->id}&type=bug&orderBy=%s"; ?>
                <thead>
                <tr>
                  <th class='w-id'>    <?php common::printOrderLink('id',         $orderBy, $vars, $lang->idAB);?></th>
                  <th class='w-pri'>   <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
                  <th>                 <?php common::printOrderLink('title',      $orderBy, $vars, $lang->bug->title);?></th>
                  <th class='w-user'>  <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
                  <th class='w-user'>  <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
                  <th class='w-status'><?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
                  <th class='w-50px'><?php echo $lang->actions?></th>
                </tr>
                </thead>
                <tbody>
                  <?php $canBatchUnlink = common::hasPriv('productPlan', 'batchUnlinkBug');?>
                  <?php foreach($planBugs as $bug):?>
                  <tr class='text-center'>
                    <td>
                      <?php if($canBatchUnlink):?>
                      <input class='ml-10px' type='checkbox' name='unlinkBugs[]'  value='<?php echo $bug->id;?>'/> 
                      <?php endif;?>
                      <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), sprintf("%03d", $bug->id));?>
                    </td>
                    <td><span class='<?php echo 'pri' . zget($lang->bug->priList, $bug->pri, $bug->pri)?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri);?></span></td>
                    <td class='text-left nobr'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?></td>
                    <td><?php echo $users[$bug->openedBy];?></td>
                    <td><?php echo $users[$bug->assignedTo];?></td>
                    <td class='bug-<?php echo $bug->status?>'><?php echo $lang->bug->statusList[$bug->status];?></td>
                    <td>
                      <?php
                      if(common::hasPriv('productplan', 'unlinkBug'))
                      {
                          $unlinkURL = $this->createLink('productplan', 'unlinkBug', "story=$bug->id&confirm=yes");
                          echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"bugList\",confirmUnlinkBug)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->productplan->unlinkBug}'");
                      }
                      ?>
                    </td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                  <td colspan='7'>
                    <div class='table-actions clearfix'>
                      <?php 
                      if(count($planBugs) and $canBatchUnlink)
                      {
                          echo "<div class='btn-group'>" . html::selectButton() . '</div>';
                          echo html::submitButton($lang->productplan->batchUnlink);
                      }
                      ?>
                      <div class='text'><?php echo sprintf($lang->productplan->bugSummary, count($planBugs));?> </div>
                    </div>
                  </td>
                </tr>
                </tfoot>
              </table>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class='col-sm-4 col-lg-3'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->productplan->basicInfo?></legend>
        <table class='table table-data table-condensed table-borderless'>
          <tr>
            <th class='w-80px strong'><?php echo $lang->productplan->title;?></th> 
            <td><?php echo $plan->title;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->productplan->begin;?></th>
            <td><?php echo $plan->begin;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->productplan->end;?></th>
            <td><?php echo $plan->end;?></td>
          </tr>
        </table>
      </fieldset>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
