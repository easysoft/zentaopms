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
<?php
$jsRoot = $this->app->getWebRoot() . "js/";
include '../../common/view/tablesorter.html.php';
?>
<div id='querybox' class='show'></div>
<div id='unlinkStoryList'>
  <form method='post' id='unlinkedStoriesForm' target='hiddenwin' action='<?php echo $this->createLink('productplan', 'linkStory', "planID=$plan->id&browseType=$browseType&param=$param&orderBy=$orderBy")?>'>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed table-selectable'> 
    <caption class='text-left text-special'><?php echo html::icon('unlink');?> &nbsp;<strong><?php echo $lang->productplan->unlinkedStories;?></strong></caption>
      <thead>
        <tr>
          <th class='w-id {sorter:"currency"}'><?php echo $lang->idAB;?></th>
          <th class='w-pri'>   <?php echo $lang->priAB;?></th>
          <th class='w-200px'> <?php echo $lang->story->plan;?></th>
          <th class='w-150px'> <?php echo $lang->story->module;?></th>
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
      <?php if(isset($planStories[$story->id])) continue; ?>
      <tr>
        <td class='cell-id'>
          <input type='checkbox' name='stories[]'  value='<?php echo $story->id;?>'/> 
          <?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->id);?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri)?></span></td>
        <td><?php echo $story->planTitle;?></td>
        <td title='<?php echo $modules[$story->module]?>'><?php echo $modules[$story->module];?></td>
        <td class='text-left nobr' title='<?php echo $story->title?>'><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id", '', true), $story->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
        <td class='text-center'><?php echo zget($users, $story->openedBy);?></td>
        <td class='text-center'><?php echo zget($users, $story->assignedTo);?></td>
        <td class='text-center'><?php echo $story->estimate;?></td>
        <td class='text-center story-<?php echo $story->status?>'><?php echo $lang->story->statusList[$story->status];?></td>
        <td class='text-center'><?php echo $lang->story->stageList[$story->stage];?></td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='10' class='text-left'>
            <?php if(count($allStories))
            {
                echo "<div class='table-actions clearfix'>";
                echo html::selectButton() . html::submitButton($lang->story->linkStory);
                echo html::a(inlink('view', "planID=$plan->id&type=story&orderBy=$orderBy"), $lang->goback, '', "class='btn'");
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
$(function(){
    ajaxGetSearchForm('#stories .linkBox #querybox');
    setModal();
})
</script>
