<?php
/**
 * The welcome view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<style>
.block-welcome .col-right > h4 {font-weight: bold; font-size: 15px;}
.block-welcome .col-right > h4 small {display: inline-block; margin-left: 10px; font-size: 14px;}
.block-welcome .col-right .tile {width: 33%;}
.block-welcome .panel-body {padding-top: 15px;}
.block-welcome .user-notification-icon {position: relative;display: inline-block;margin-left: 5px;}
.block-welcome .user-notification-icon .label-badge {position: absolute;top: 1px;right: -8px;min-width: 16px;padding: 2px;font-size: 12px;font-weight: normal;}
.block-welcome.block-sm .col-right {padding: 0;}
.block-welcome.block-sm .col-right .tiles {border-left: none; padding-left: 0}
.block-welcome.block-sm .tile-title {font-size: 12px; margin: 0 -10px;}
.block-welcome .progress-group{margin-top: 20px;}
.block-welcome .progress{margin-top: 10px; width: 85%}
</style>
<div class="panel-body conatiner-fluid">
  <div class="table-row">
    <div class="col col-right">
    <h4><?php echo $lang->block->leftToday?><small class="text-muted"><?php echo date(DT_DATE3)?></small></h4>
      <div class="row tiles">
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->undone?></div>
          <div class="tile-amount"><?php echo empty($data) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=task&type=assignedTo'), (int)$data['undone']);?></div>
        </div>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->delaying?></div>
          <div class="tile-amount"><?php echo empty($data) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=task&type=assignedTo'), (int)$data['delaying']);?></div>
        </div>
        <div class="col tile">
          <div class="tile-title"><?php echo $lang->block->delayed?></div>
          <div class="tile-amount"><?php echo empty($data) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=task&type=assignedTo'), (int)$data['delayed']);?></div>
        </div>
      </div>
    </div>
  </div>
</div>
