<?php
/**
 * The link story view of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     build
 * @version     $Id: linkstory.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->build->confirmUnlinkStory)?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['build']);?> <strong><?php echo $build->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('build', 'view', 'build=' . $build->id), $build->name);?></strong>
    <small class='text-muted'> <?php echo $lang->build->linkStory;?> <?php echo html::icon($lang->icons['link']);?></small>
  </div>
  <div class='actions'><?php echo html::a(inlink('view', "buildID=$build->id"), '<i class="icon-goback icon-level-up icon-large icon-rotate-270"></i> ' . $lang->goback, '', "class='btn'")?></div>
</div>
<div id='querybox' class='show'></div>
<div id='storyList'>
  <form method='post' id='unlinkedStoriesForm'>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed'> 
    <caption class='text-left text-special'><?php echo html::icon('unlink');?> &nbsp;<strong><?php echo $lang->productplan->unlinkedStories;?></strong></caption>
      <thead>
        <tr>
          <th class='w-id {sorter:"currency"}'><?php echo $lang->idAB;?></th>
          <th class='w-pri'>   <?php echo $lang->priAB;?></th>
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
      <?php if(strpos(",{$build->stories},", ",{$story->id},") !== false) continue; ?>
      <tr>
        <td class='text-left'>
          <input class='ml-10px' type='checkbox' name='stories[]'  value='<?php echo $story->id;?>' <?php if($story->stage == 'developed' or $story->status == 'closed') echo 'checked';?> /> 
          <?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->id);?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri)?></span></td>
        <td class='text-left nobr'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?></td>
        <td><?php echo $users[$story->openedBy];?></td>
        <td><?php echo $users[$story->assignedTo];?></td>
        <td><?php echo $story->estimate;?></td>
        <td class='story-<?php echo $story->status?>'><?php echo $lang->story->statusList[$story->status];?></td>
        <td><?php echo $lang->story->stageList[$story->stage];?></td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='8' class='text-left'>
            <?php if(count($allStories)) echo "<div class='table-actions clearfix'><div class='btn-group'>" . html::selectAll('unlinkedStoriesForm') . html::selectReverse('unlinkedStoriesForm') . '</div>' . html::submitButton($lang->story->linkStory) . '</div>';?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
  <hr class='mg-0'>
  <form method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkStory', "buildID=$build->id");?>" id='linkedStoriesForm'>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed'> 
      <caption class='text-left text-important'><?php echo html::icon('link');?> &nbsp;<strong><?php echo $lang->productplan->linkedStories;?></strong> (<?php echo count($buildStories);?>)</caption>
      <thead>
      <tr class='colhead'>
        <th class='w-id {sorter:"currency"}'><?php echo $lang->idAB;?></th>
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
      <?php foreach($buildStories as $story):?>
      <tr>
        <td class='text-center'>
          <?php if($canBatchUnlink):?>
          <input class='ml-10px' type='checkbox' name='unlinkStories[]'  value='<?php echo $story->id;?>'/> 
          <?php endif;?>
          <?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), sprintf("%03d", $story->id));?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
        <td class='text-left nobr'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?></td>
        <td><?php echo $users[$story->openedBy];?></td>
        <td><?php echo $users[$story->assignedTo];?></td>
        <td><?php echo $story->estimate;?></td>
        <td class='story-<?php echo $story->status?>'><?php echo $lang->story->statusList[$story->status];?></td>
        <td><?php echo $lang->story->stageList[$story->stage];?></td>
        <td class='text-center'>
          <?php
          if(common::hasPriv('build', 'unlinkStory'))
          {
              $unlinkURL = $this->createLink('build', 'unlinkStory', "build=$build->id&storyID=$story->id");
              echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"storyList\",confirmUnlinkStory)", '<i class="icon-remove"></i>', '', "title='{$lang->build->unlinkStory}' class='btn-icon'");
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
      <?php if(count($buildStories) and $canBatchUnlink):?>
      <tfoot>
      <tr>
        <td colspan='9' class='text-left'>
        <?php 
        echo "<div class='table-actions clearfix'><div class='btn-group'>" . html::selectAll('linkedStoriesForm') . html::selectReverse('linkedStoriesForm') . '</div>' . html::submitButton($lang->productplan->batchUnlink) . '</div>';
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
