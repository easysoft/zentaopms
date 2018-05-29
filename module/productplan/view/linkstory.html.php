<?php
/**
 * The link story view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: linkstory.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<div id='queryBox' class='show'></div>
<div id='unlinkStoryList'>
  <form class="main-table table-story" data-ride="table" method="post" target='hiddenwin' id='unlinkedStoriesForm' action="<?php echo $this->createLink('productplan', 'linkStory', "planID=$plan->id&browseType=$browseType&param=$param&orderBy=$orderBy")?>">
    <div class='table-header hl-primary text-primary strong'>
      <?php echo html::icon('unlink');?> <?php echo $lang->productplan->unlinkedStories;?>
    </div>
    <table class='table'>
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
          <th class='c-pri'><?php echo $lang->priAB;?></th>
          <th class='w-200px'><?php echo $lang->story->plan;?></th>
          <th class='w-150px'><?php echo $lang->story->module;?></th>
          <th class='text-left'><?php echo $lang->story->title;?></th>
          <th class='c-user'><?php echo $lang->openedByAB;?></th>
          <th class='c-user'><?php echo $lang->assignedToAB;?></th>
          <th class='w-50px'><?php echo $lang->story->estimateAB;?></th>
          <th class='w-80px'><?php echo $lang->statusAB;?></th>
          <th class='w-80px'><?php echo $lang->story->stageAB;?></th>
        </tr>
      </thead>
      <tbody class='text-center'>
        <?php $unlinkedCount = 0;?>
        <?php foreach($allStories as $story):?>
        <?php if(isset($planStories[$story->id])) continue;?>
        <tr>
          <td class='c-id text-left'>
            <?php echo html::checkbox('stories', array($story->id => sprintf('%03d', $story->id)));?>
          </td>
          <td><span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri)?></span></td>
          <td><?php echo $story->planTitle;?></td>
          <td title='<?php echo $modules[$story->module]?>'><?php echo $modules[$story->module];?></td>
          <td class='text-left nobr' title='<?php echo $story->title?>'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id", '', true), $story->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
          <td><?php echo zget($users, $story->openedBy);?></td>
          <td><?php echo zget($users, $story->assignedTo);?></td>
          <td><?php echo $story->estimate;?></td>
          <td>
            <span class='status-<?php echo $story->status?>'>
              <span class='label label-dot'></span>
              <?php echo $lang->story->statusList[$story->status];?>
            </span>
          </td>
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
        <?php echo html::submitButton($lang->productplan->linkStory, '', 'btn');?>
      </div>
      <?php endif;?>
      <div class="btn-toolbar">
        <?php echo html::a(inlink('view', "planID=$plan->id&type=story&orderBy=$orderBy"), $lang->goback, '', "class='btn'");?>
      </div>
      <div class='table-statistic'></div>
    </div>
  </form>
</div>