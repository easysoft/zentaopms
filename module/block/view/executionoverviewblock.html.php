<?php
/**
 * The execution overview block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<style>
.block-overview .tile-amount {font-size: 48px; margin-bottom: 10px;}
</style>
<div class="panel-body table-row">
  <div class="col-4 text-middle text-center">
    <div class="tile">
      <div class="tile-title"><?php echo $lang->execution->allExecutions;?></div>
      <div class="tile-amount"><?php echo $total;?></div>
      <a class="btn btn-primary btn-circle btn-icon-right btn-sm" href="<?php echo $this->createLink('execution', 'all', 'type=all');?>"><?php echo $lang->execution->viewAll;?> <span class="label label-badge label-icon"><i class="icon icon-arrow-right"></i></span></a>
    </div>
  </div>
  <div class="col-8 text-middle">
    <ul class="status-bars">
      <?php foreach($lang->execution->statusList as $statusKey => $statusName):?>
      <li>
        <span class="bar" style="height: <?php echo $overviewPercent[$statusKey];?>"><span class="value"><?php echo empty($overview[$statusKey]) ? 0 :html::a($this->createLink('execution', 'all', "type=$statusKey"), $overview[$statusKey]);?></span></span>
        <span class="title"><?php echo $statusName;?></span>
      </li>
      <?php endforeach;?>
    </ul>
  </div>
</div>
