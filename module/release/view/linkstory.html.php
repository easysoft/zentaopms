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
<div id='queryBox' class='show'></div>
<div id='unlinkStoryList'>
  <form class='main-table' method='post' target='hiddenwin' id='unlinkedStoriesForm' action='<?php echo $this->createLink('release', 'linkStory', "releaseID=$release->id&browseType=$browseType&param=$param")?>'>
    <div class='table-header'>
      <div class='table-statistic'><?php echo html::icon('unlink');?> &nbsp;<strong><?php echo $lang->productplan->unlinkedStories;?></strong></div>
    </div>
    <table class='table'> 
      <thead>
        <tr>
          <th class='c-id'> <?php echo $lang->idAB;?></th>
          <th class='w-pri'><?php echo $lang->priAB;?></th>
          <th>              <?php echo $lang->story->title;?></th>
          <th class='w-user text-left'>  <?php echo $lang->openedByAB;?></th>
          <th class='w-user text-left'>  <?php echo $lang->assignedToAB;?></th>
          <th class='w-50px text-left'>  <?php echo $lang->story->estimateAB;?></th>
          <th class='w-status text-left'><?php echo $lang->statusAB;?></th>
          <th class='w-80px text-center'><?php echo $lang->story->stageAB;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $unlinkedCount = 0;?>
        <?php foreach($allStories as $story):?>
        <?php if(strpos(",{$release->stories},", ",{$story->id},") !== false) continue; ?>
        <?php if($release->product != $story->product) continue; ?>
        <tr>
          <td class='c-id'>
              <input class='ml-10px' type='checkbox' name='stories[]'  value='<?php echo $story->id;?>' <?php if($story->stage == 'developed' or $story->status == 'closed') echo 'checked';?> /> 
              <?php printf('%03d', $story->id);?>
          </td>
          <td><span class='label-pri <?php echo 'label-pri-' . $story->pri;?>'><?php echo zget($lang->story->priList, $story->pri)?></span></td>
          <td class='text-left nobr' title='<?php echo $story->title?>'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id", '', true), $story->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
          <td><?php echo zget($users, $story->openedBy);?></td>
          <td><?php echo zget($users, $story->assignedTo);?></td>
          <td><?php echo $story->estimate;?></td>
          <td><span class='status-<?php echo $story->status?>'><span class="label label-dot"></span> <?php echo zget($lang->story->statusList, $story->status);?></span></td>
          <td class='text-center'><?php echo zget($lang->story->stageList, $story->stage);?></td>
        </tr>
        <?php $unlinkedCount++;?>
        <?php endforeach;?>
      </tbody>
      <?php if($unlinkedCount):?>
      <tfoot>
        <tr>
          <td colspan='8' class='text-left table-footer'>
            <div class='clearfix'>
              <?php
              echo html::selectButton() . html::submitButton($lang->story->linkStory);
              echo html::a(inlink('view', "releaseID=$release->id&type=story"), $lang->goback, '', "class='btn'");
              ?>
            </div>
          </td>
        </tr>
      </tfoot>
      <?php endif;?>
    </table>
  </form>
</div>
<script>
$(function()
{
    ajaxGetSearchForm('#stories .linkBox #queryBox');
    setModal();
})
</script>
