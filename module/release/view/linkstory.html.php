<?php
/**
 * The link story view of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     release
 * @version     $Id: linkstory.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<div id='querybox' class='show'></div>
<div id='unlinkStoryList'>
  <form method='post' target='hiddenwin' id='unlinkedStoriesForm' action='<?php echo $this->createLink('release', 'linkStory', "releaseID=$release->id&browseType=$browseType&param=$param")?>'>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed table-selectable'> 
      <caption class='text-left text-special'><?php echo html::icon('unlink');?> &nbsp;<strong><?php echo $lang->productplan->unlinkedStories;?></strong></caption>
      <thead>
        <tr>
          <th class='w-id {sorter:"currency"}'><?php echo $lang->idAB;?></th>
          <th class='w-pri'>   <?php echo $lang->priAB;?></th>
          <th>                 <?php echo $lang->story->title;?></th>
          <th class='w-user text-left'>  <?php echo $lang->openedByAB;?></th>
          <th class='w-user text-left'>  <?php echo $lang->assignedToAB;?></th>
          <th class='w-50px text-left'>  <?php echo $lang->story->estimateAB;?></th>
          <th class='w-status text-left'><?php echo $lang->statusAB;?></th>
          <th class='w-80px text-center'>  <?php echo $lang->story->stageAB;?></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($allStories as $story):?>
      <?php if(strpos(",{$release->stories},", ",{$story->id},") !== false) continue; ?>
      <?php if($release->product != $story->product) continue; ?>
      <tr>
        <td class='cell-id'>
          <input class='ml-10px' type='checkbox' name='stories[]'  value='<?php echo $story->id;?>' <?php if($story->stage == 'developed' or $story->status == 'closed') echo 'checked';?> /> 
          <?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->id);?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri)?></span></td>
        <td class='text-left nobr' title='<?php echo $story->title?>'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id", '', true), $story->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
        <td><?php echo $users[$story->openedBy];?></td>
        <td><?php echo $users[$story->assignedTo];?></td>
        <td><?php echo $story->estimate;?></td>
        <td class='story-<?php echo $story->status?>'><?php echo $lang->story->statusList[$story->status];?></td>
        <td class='text-center'><?php echo $lang->story->stageList[$story->stage];?></td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='8' class='text-left'>
            <?php if(count($allStories))
            {
                echo "<div class='table-actions clearfix'>";
                echo html::selectButton() . html::submitButton($lang->story->linkStory);
                echo html::a(inlink('view', "releaseID=$release->id&type=story"), $lang->goback, '', "class='btn'");
                echo '</div>';
            }
            ?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(function()
{
    ajaxGetSearchForm('#stories .linkBox #querybox');
    setModal();
})
</script>
