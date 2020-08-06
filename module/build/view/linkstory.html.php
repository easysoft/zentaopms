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
<div id='queryBox' data-module='story' class='show'></div>
<div id='unlinkStoryList'>
  <form class='main-table table-story' data-ride='table' method='post' id='unlinkedStoriesForm' target='hiddenwin' action='<?php echo $this->createLink('build', 'linkStory', "buildID={$build->id}&browseType=$browseType&param=$param");?>'>
    <div class='table-header hl-primary text-primary strong'>
      <?php echo html::icon('unlink');?> <?php echo $lang->productplan->unlinkedStories;?>
    </div>
    <table class='table tablesorter'>
      <thead>
        <tr class='text-center'>
          <th class='c-id text-left'>
            <?php if($allStories):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-pri'>   <?php echo $lang->priAB;?></th>
          <th class="text-left"><?php echo $lang->story->title;?></th>
          <th class='c-user'>  <?php echo $lang->openedByAB;?></th>
          <th class='c-user'>  <?php echo $lang->assignedToAB;?></th>
          <th class='w-60px'>  <?php echo $lang->story->estimateAB;?></th>
          <th class='c-status'><?php echo $lang->statusAB;?></th>
          <th class='w-80px'>  <?php echo $lang->story->stageAB;?></th>
        </tr>
      </thead>
      <tbody class='text-center'>
        <?php $unlinkedCount = 0;?>
        <?php foreach($allStories as $story):?>
        <tr>
          <td class='c-id text-left'>
            <?php echo html::checkbox('stories', array($story->id => sprintf('%03d', $story->id)), ($story->stage == 'developed' or $story->status == 'closed') ? $story->id : '');?>
          </td>
          <td><span class='label-pri label-pri-<?php echo $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri)?></span></td>
          <td class='text-left nobr' title='<?php echo $story->title?>'>
            <?php
            if($story->parent > 0) echo "<span class='label'>{$lang->story->childrenAB}</span>";
            echo html::a($this->createLink('story', 'view', "storyID=$story->id", '', true), $story->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");
            ?>
          </td>
          <td><?php echo zget($users, $story->openedBy);?></td>
          <td><?php echo zget($users, $story->assignedTo);?></td>
          <td><?php echo $story->estimate;?></td>
          <td>
            <span class='status-story status-<?php echo $story->status?>'>
              <?php echo $this->processStatus('story', $story);?>
            </span>
          </td>
          <td><?php echo $lang->story->stageList[$story->stage];?></td>
        </tr>
        <?php $unlinkedCount++;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($unlinkedCount):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php echo html::submitButton($lang->build->linkStory, '', 'btn btn-secondary');?>
      </div>
      <div class="btn-toolbar">
        <?php echo html::a(inlink('view', "buildID={$build->id}&type=story"), $lang->goback, '', "class='btn'");?>
      </div>
      <div class='table-statistic'></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<script>
$(function()
{
    $('#unlinkStoryList .tablesorter').sortTable();
    setForm();
});
</script>
