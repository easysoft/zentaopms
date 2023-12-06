<?php
/**
 * The statistic view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<style>
.block-docstatistic .flex {display: flex; flex-wrap: nowrap; flex-direction: row; justify-content: space-around; flex: auto;}
.block-docstatistic .flex-column {flex-direction: column; padding-left: 10px;}
.block-docstatistic .statistic {flex: 0 1 32%;}
.block-docstatistic .created {flex: 0 1 48%;}
.block-docstatistic .edited {flex: 0 1 16%; padding-left: 0px;}
.block-docstatistic .divider {border-right: 1px solid #eee; padding-right: 12%;}
.block-docstatistic .panel-body {padding-top: 0;}

.block-docstatistic.block-sm .flex {justify-content: start;}
.block-docstatistic.block-sm .panel-body.flex {flex-direction: column;}
.block-docstatistic.block-sm .divider {border-right: none;}
.block-docstatistic.block-sm .statistic .flex {margin-top: -28px;}
.block-docstatistic.block-sm .tile {padding: 10px 32px 20px;}
.block-docstatistic.block-sm .panel-body {margin-top: 0px; padding: 10px;}
.block-docstatistic.block-sm .flex-column .flex .tile:nth-child(1) {padding-left: 0;}
.block-docstatistic.block-sm .edited {padding-left: 10px;}
.block-docstatistic.block-sm .flex-column {border-bottom: 1px solid #eee;}
.block-docstatistic.block-sm .flex-column:last-child {border-bottom: none;}
</style>
<div class='panel-move-handler'></div>
<div class="panel-body flex">
  <div class='flex flex-column statistic'>
    <div class='flex'>
      <div class="tile">
        <div class="tile-amount"><?php echo (int)$statistic->totalDocs;?></div>
        <div class="tile-title"><?php echo $lang->doc->allDoc;?></div>
      </div>
      <div class="tile divider">
        <div class="tile-amount"><?php echo (int)$statistic->todayEditedDocs;?></div>
        <div class="tile-title"><?php echo $lang->doc->todayEdited;?></div>
      </div>
    </div>
  </div>
  <div class='flex flex-column created'>
    <div class='flex'>
      <?php if(common::hasPriv('doc', 'mySpace')):?>
      <a class="tile" href="<?php echo $this->createLink('doc', 'mySpace', 'type=createdBy');?>">
      <?php else:?>
      <div class="tile">
      <?php endif;?>
        <div class="tile-amount"><?php echo (int)$statistic->myDocs;?></div>
        <div class="tile-title"><?php echo $lang->doc->docCreated;?></div>
      <?php if(common::hasPriv('doc', 'mySpace')):?>
      </a>
      <?php else:?>
      </div>
      <?php endif;?>
      <div class="tile">
        <div class="tile-amount"><?php echo (int)$statistic->myDoc->docViews;?></div>
        <div class="tile-title"><?php echo $lang->doc->docViews;?></div>
      </div>
      <div class="tile divider">
        <div class="tile-amount"><?php echo (int)$statistic->myDoc->docCollects;?></div>
        <div class="tile-title"><?php echo $lang->doc->docCollects;?></div>
      </div>
    </div>
  </div>
  <div class='flex flex-column edited'>
    <div class='flex'>
      <div class="tile">
        <div class="tile-amount"><?php echo (int)$statistic->myEditedDocs;?></div>
        <div class="tile-title"><?php echo $lang->doc->docEdited;?></div>
      </div>
    </div>
  </div>
</div>
