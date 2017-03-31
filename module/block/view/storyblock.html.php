<?php
/**
 * The story block view file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<table class='table tablesorter table-data table-hover block-story table-fixed'>
  <thead>
  <tr>
    <th width='50'><?php echo $lang->idAB?></th>
    <th width='30'><?php echo $lang->priAB?></th>
    <th>           <?php echo $lang->story->title;?></th>
    <th width='50'><?php echo $lang->story->estimateAB;?></th>
    <th width='70'><?php echo $lang->statusAB;?></th>
    <th width='70'><?php echo $lang->story->stageAB;?></th>
  </tr>
  </thead>
  <?php foreach($stories as $story):?>
  <?php
  $appid = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
  $viewLink = $this->createLink('story', 'view', "storyID={$story->id}");
  ?>
  <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
    <td class='text-center'><?php echo $story->id;?></td>
    <td class='text-center'><?php echo zget($lang->story->priList, $story->pri, $story->pri)?></td>
    <td style='color: <?php echo $story->color?>' title='<?php echo $story->title?>'><?php echo $story->title?></td>
    <td class='text-center'><?php echo $story->estimate?></td>
    <td class='text-center'><?php echo zget($lang->story->statusList, $story->status, $story->status);?></th>
    <td class='text-center'><?php echo zget($lang->story->stageList, $story->stage, $story->stage);?></th>
  </tr>
  <?php endforeach;?>
</table>
<script>
if(typeof(dataTable) == 'function')$('.block-story').dataTable();
</script>
