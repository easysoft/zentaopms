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
<div class='panel-body has-table'>
  <table class='table table-borderless table-hover table-fixed-head block-stories <?php if(!$longBlock) echo 'block-sm'?>'>
    <thead>
      <tr>
        <th class="c-id"><?php echo $lang->idAB?></th>
        <th class="c-pri"><?php echo $lang->priAB?></th>
        <th class="c-name"><?php echo $lang->story->title;?></th>
        <?php if($longBlock):?>
        <th class='c-estimate'><?php echo $lang->story->estimateAB;?></th>
        <?php endif;?>
        <th class="c-status"><?php echo $lang->statusAB;?></th>
        <?php if($longBlock):?>
        <th class='c-stage'><?php echo $lang->story->stageAB;?></th>
        <?php endif;?>
      </tr>
    </thead>
    <?php foreach($stories as $story):?>
    <?php
    $appid = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
    $viewLink = $this->createLink('story', 'view', "storyID={$story->id}");
    ?>
    <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
      <td class="c-id"><?php echo $story->id;?></td>
      <?php $pri = zget($lang->story->priList, $story->pri, $story->pri);?>
      <td class="c-pri"><span class="label-pri label-pri-<?php echo $pri?>"><?php echo $pri?></span></td>
      <td class="c-name" style='color: <?php echo $story->color?>' title='<?php echo $story->title?>'><?php echo $story->title?></td>
      <?php if($longBlock):?>
      <td class='c-estimate'><?php echo $story->estimate?></td>
      <?php endif;?>
      <td class='c-status' title='<?php echo zget($lang->story->statusList, $story->status)?>'>
        <span class='story-status-<?php echo $story->status?>'>
          <span class='label label-dot'></span><span class='status-text'><?php echo zget($lang->story->statusList, $story->status);?></span>
        </span>
      </td>
      <?php if($longBlock):?>
      <td class='c-stage'>
        <span class='story-stage-<?php echo $story->stage?>'>
          <?php echo zget($lang->story->stageList, $story->stage, $story->stage);?>
        </span>
      </td>
      <?php endif;?>
    </tr>
    <?php endforeach;?>
  </table>
</div>
