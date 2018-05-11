<?php
/**
 * The link story view of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     build
 * @version     $Id: linkstory.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<div id='querybox' class='show'></div>
<div id='unlinkStoryList'>
  <form class='main-table table-story' data-ride='table' method='post' id='unlinkedStoriesForm' target='hiddenwin' action='<?php echo $this->createLink('build', 'linkStory', "buildID={$build->id}&browseType=$browseType&param=$param");?>'>
    <div class='table-header'>
      <div class='table-statistic'><?php echo html::icon('unlink');?> &nbsp;<strong><?php echo $lang->productplan->unlinkedStories;?></strong></div>
    </div>
    <table class='table'> 
      <thead>
        <tr>
          <th class='c-id'>
            <?php if($allStories):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php echo $lang->idAB;?>
          </th>
          <th class='w-pri'>   <?php echo $lang->priAB;?></th>
          <th class="text-left"><?php echo $lang->story->title;?></th>
          <th class='w-user'>  <?php echo $lang->openedByAB;?></th>
          <th class='w-user'>  <?php echo $lang->assignedToAB;?></th>
          <th class='w-60px'>  <?php echo $lang->story->estimateAB;?></th>
          <th class='w-status'><?php echo $lang->statusAB;?></th>
          <th class='w-80px'>  <?php echo $lang->story->stageAB;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $unlinkedCount = 0;?>
        <?php foreach($allStories as $story):?>
        <?php if(strpos(",{$build->stories},", ",{$story->id},") !== false) continue; ?>
        <?php if($build->product != $story->product) continue; ?>
        <tr>
          <td class='cell-id'>
            <?php echo html::checkbox('stories', array($story->id => sprintf('%03d', $story->id)), ($story->stage == 'developed' or $story->status == 'closed') ? $story->id : '');?>
          </td>
          <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri)?></span></td>
          <td class='text-left nobr' title='<?php echo $story->title?>'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id", '', true), $story->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
          <td class='text-center'><?php echo $users[$story->openedBy];?></td>
          <td class='text-center'><?php echo $users[$story->assignedTo];?></td>
          <td class='text-center'><?php echo $story->estimate;?></td>
          <td class='text-center story-<?php echo $story->status?>'><?php echo $lang->story->statusList[$story->status];?></td>
          <td class='text-center'><?php echo $lang->story->stageList[$story->stage];?></td>
        </tr>
        <?php $unlinkedCount++;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php if($unlinkedCount):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php echo html::submitButton($lang->build->linkStory);?>
      </div>
      <?php endif;?>
      <?php echo html::a(inlink('view', "buildID={$build->id}&type=story"), $lang->goback, '', "class='btn'");?>
    </div>
  </form>
</div>
<script>
$(function()
{
    ajaxGetSearchForm('#stories .linkBox #querybox');
    setModal();
})
</script>
