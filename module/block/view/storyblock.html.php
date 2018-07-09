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
<?php if(empty($stories)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.block-stories .c-id {width: 55px;}
.block-stories .c-pri {width: 45px;text-align: center;}
.block-stories .c-estimate {width: 65px;}
.block-stories .c-status {width: 80px;}
.block-stories .c-stage {width: 80px;}
.block-stories.block-sm .estimate,
.block-stories.block-sm .c-stage,
.block-stories.block-sm .c-status .label-dot {display: none;}
.block-stories.block-sm .c-status {text-align: center;}
</style>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed-head tablesorter block-stories <?php if(!$longBlock) echo 'block-sm'?>'>
    <thead>
      <tr>
        <?php if($longBlock):?>
        <th class="c-id-xs"><?php echo $lang->idAB?></th>
        <?php endif;?>
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
    <tbody>
      <?php foreach($stories as $story):?>
      <?php
      $appid = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      $viewLink = $this->createLink('story', 'view', "storyID={$story->id}");
      ?>
      <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
        <?php if($longBlock):?>
        <td class="c-id-xs"><?php echo sprintf('%03d', $story->id);?></td>
        <?php endif;?>
        <td class="c-pri"><span class="label-pri label-pri-<?php echo $story->pri?>" title="<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>"><?php echo zget($lang->story->priList, $story->pri, $story->pri)?></span></td>
        <td class="c-name" style='color: <?php echo $story->color?>' title='<?php echo $story->title?>'><?php echo $story->title?></td>
        <?php if($longBlock):?>
        <td class='c-estimate text-center'><?php echo $story->estimate?></td>
        <?php endif;?>
        <td class='c-status' title='<?php echo zget($lang->story->statusList, $story->status);?>'>
          <span class="status-<?php echo $story->status?>">
            <span class="label label-dot"></span>
            <span class='status-text'><?php echo zget($lang->story->statusList, $story->status);?></span>
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
    </tbody>
  </table>
</div>
<?php endif;?>
