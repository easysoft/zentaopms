<?php
/**
 * The story block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php $this->app->loadLang('story');?>
<?php if(empty($stories)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.block-stories .c-id {width: 55px;}
.block-stories .c-pri {width: 45px;text-align: center;}
.block-stories .c-estimate {width: 65px; text-align: right;}
.block-stories .c-status {width: 80px;}
.block-stories .c-stage {width: 80px;}
.block-stories.block-sm .estimate,
.block-stories.block-sm .c-stage,
.block-stories.block-sm .c-status {text-align: center;}
</style>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter block-stories <?php if(!$longBlock) echo 'block-sm'?>'>
    <thead>
      <tr>
        <th class="c-id"><?php echo $lang->idAB?></th>
        <th class="c-name"><?php echo $lang->story->title;?></th>
        <th class="c-pri"><?php echo $lang->priAB?></th>
        <?php if($longBlock):?>
        <th class="c-status"><?php echo $lang->statusAB;?></th>
        <th class='c-estimate'><?php echo $lang->story->estimateAB;?></th>
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
      <tr <?php echo $appid?>>
        <td class="c-id-xs"><?php echo sprintf('%03d', $story->id);?></td>
        <td class="c-name" style='color: <?php echo $story->color?>' title='<?php echo $story->title?>'><?php echo html::a($viewLink, $story->title);?></td>
        <td class="c-pri"><span class="label-pri label-pri-<?php echo $story->pri?>" title="<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>"><?php echo zget($lang->story->priList, $story->pri, $story->pri)?></span></td>
        <?php if($longBlock):?>
        <?php $status = $this->processStatus('story', $story);?>
        <td class='c-status' title='<?php echo $status;?>'>
          <span class="status-story status-<?php echo $story->status?>"><?php echo $status;?></span>
        </td>
        <td class='c-estimate text-center' title="<?php echo $story->estimate . ' ' . $lang->hourCommon;?>"><?php echo $story->estimate . $config->hourUnit?></td>
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
