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
<table class='table table-data table-hover block-story table-fixed'>
  <thead>
  <tr>
    <th width='50'><?php echo $lang->idAB?></th>
    <th width='30'><?php echo $lang->priAB?></th>
    <th>           <?php echo $lang->story->title;?></th>
    <th width='50'><?php echo $lang->story->estimateAB;?></th>
    <th width='50'><?php echo $lang->statusAB;?></th>
    <th width='60'><?php echo $lang->story->stageAB;?></th>
  </tr>
  </thead>
  <?php foreach($stories as $story):?>
  <?php $appid = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : ''?>
  <tr data-url='<?php echo $sso . $sign . 'referer=' . base64_encode($this->createLink('story', 'view', "storyID={$story->id}")); ?>' <?php echo $appid?>>
    <td><?php echo $story->id;?></td>
    <td><?php echo zget($lang->story->priList, $story->pri, $story->pri)?></td>
    <td title='<?php echo $story->title?>'><?php echo $story->title?></td>
    <td><?php echo $story->estimate?></td>
    <td ><?php echo zget($lang->story->statusList, $story->status, $story->status);?></th>
    <td ><?php echo zget($lang->story->stageList, $story->stage, $story->stage);?></th>
  </tr>
  <?php endforeach;?>
</table>
<p class='hide block-story-link'><?php echo $listLink;?></p>
<script>
$('.block-story').dataTable();
$('.block-story-link').closest('.panel').find('.panel-heading .more').attr('href', $('.block-story-link').html());
</script>
