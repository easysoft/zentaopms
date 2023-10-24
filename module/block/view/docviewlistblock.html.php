<?php
/**
 * The docviewlistblock view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<style>
.block-docviewlist .panel-body {padding-top: 0px;}
.block-docviewlist .doc-list > .doc-title {display: flex; overflow: hidden; text-overflow: clip; white-space: nowrap; padding: 8px 0;}
.block-docviewlist .doc-list > .doc-title > .label-rank {margin-right: 10px; padding-top: 1px; border: unset; color: #2E7FFF; background-color: #D5E5FF;}
.block-docviewlist .doc-list > .doc-title > .label-rank-1 {color: #FC5959; background-color: #FFE2D9;}
.block-docviewlist .doc-list > .doc-title > .label-rank-2 {color: #FF8058; background-color: #FFE2D9;}
.block-docviewlist .doc-list > .doc-title > .label-rank-3 {color: #FF9F46; background-color: #FFECDB;}
.block-docviewlist .doc-list > .doc-title > .doc-name {overflow: hidden; max-width: calc(100% - 110px);}
.block-docviewlist .doc-list > .doc-title > .label-view-count {margin-left: 10px;}
.block-docviewlist .doc-list > .doc-title > .label-view-count > .icon-flame {margin-bottom: 3px;}
.block-docviewlist .doc-list > .doc-title > .label-view-count > .icon-flame.gray {filter: grayscale(100%);}
.block-docviewlist .doc-list > .doc-title > .label-view-count > .view-text {color: #838A9D;}
</style>
<div class="panel-body">
  <?php if(empty($docList)):?>
    <div class='table-empty-tip'><p><span class='text-muted'><?php echo $lang->doc->noDoc;?></p></span></div>
  <?php else:?>
  <div class="doc-list table-row">
    <?php $rank = 1;?>
    <?php foreach($docList as $doc):?>
    <div class='doc-title'>
      <span class="label-pri label-rank label-rank-<?php echo $rank;?>"><?php echo $rank;?></span>
      <?php
      if(common::hasPriv('doc', 'view'))
      {
          echo html::a($this->createLink('doc', 'view', "docID=$doc->id"), $doc->title, '', "title='{$doc->title}' class='doc-name' data-app='{$this->app->tab}'");
      }
      else
      {
          echo "<span class='doc-name' title='{$doc->title}'>{$doc->title}</span>";
      }
      ?>
      <div class='label-view-count'>
        <?php $flameClass = $rank < 4 ? '' : 'gray';?>
        <?php echo html::image("static/svg/flame.svg", "class='icon-flame $flameClass'");?>
        <span class='view-text'><?php echo sprintf($lang->doc->viewCount, $doc->views);?></span>
      </div>
    </div>
    <?php $rank ++;?>
    <?php endforeach;?>
  </div>
  <?php endif;?>
</div>
