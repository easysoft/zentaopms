<?php
/**
 * The statistic view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<style>
.block-statistic .flex {display: flex; flex-wrap: nowrap; flex-direction: row; justify-content: space-between; flex: auto;}
.block-statistic .flex-column {flex-direction: column; padding-left: 10px;}
.block-statistic .statistic {flex: 0 1 32%;}
.block-statistic .created {flex: 0 1 48%;}
.block-statistic .edited {flex: 0 1 16%; padding-left: 20px;}
.block-statistic .divider {border-right: 1px solid #eee; padding-right: 35px;}
.block-statistic .title-line {height: 32px;}
.block-statistic .panel-title {display: none;}
.block-statistic .panel-body {margin-top: -32px; padding: 30px;}
.block-statistic .tile-title {white-space: nowrap;}
</style>
<div class="panel-body flex">
  <div class='flex flex-column statistic'>
    <h4 class="title-line"><?php echo $lang->doc->docStatistic;?></h4>
    <div class='flex'>
      <div class="tile">
        <div class="tile-amount text-primary"><?php echo (int)$statistic->totalDocs;?></div>
        <div class="tile-title"><?php echo $lang->doc->allDoc;?></div>
      </div>
      <div class="tile divider">
        <div class="tile-amount text-primary"><?php echo (int)$statistic->todayEditedDocs;?></div>
        <div class="tile-title"><?php echo $lang->doc->todayEdited;?></div>
      </div>
    </div>
  </div>
  <div class='flex flex-column created'>
    <h4 class="title-line"><?php echo $lang->doc->openedByMe;?></h4>
    <div class='flex'>
      <div class="tile">
        <div class="tile-amount text-primary"><?php echo (int)$statistic->myDocs;?></div>
        <div class="tile-title"><?php echo $lang->doc->docCreated;?></div>
      </div>
      <div class="tile">
        <div class="tile-amount text-primary"><?php echo (int)$statistic->myDoc->docViews;?></div>
        <div class="tile-title"><?php echo $lang->doc->docViews;?></div>
      </div>
      <div class="tile divider">
        <div class="tile-amount text-primary"><?php echo (int)$statistic->myDoc->docCollects;?></div>
        <div class="tile-title"><?php echo $lang->doc->docCollects;?></div>
      </div>
    </div>
  </div>
  <div class='flex flex-column edited'>
    <h4 class="title-line"><?php echo $lang->doc->editedByMe;?></h4>
    <div class='flex'>
      <div class="tile">
        <div class="tile-amount text-primary"><?php echo (int)$statistic->myEditedDocs;?></div>
        <div class="tile-title"><?php echo $lang->doc->docEdited;?></div>
      </div>
    </div>
  </div>
</div>
