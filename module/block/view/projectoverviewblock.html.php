<?php
/**
 * The project overview block view file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     block 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<style>
.block-overview .tile-amount {font-size: 48px; margin-bottom: 10px;}
</style>
<div class="panel-body table-row">
  <div class="col-4 text-middle text-center">
    <div class="tile">
      <div class="tile-title"><?php echo $lang->project->allProject;?></div>
      <div class="tile-amount"><?php echo $total;?></div>
      <a class="btn btn-primary btn-circle btn-icon-right btn-sm" href="<?php echo $this->createLink('project', 'all');?>"><?php echo $lang->project->viewAll;?> <span class="label label-badge label-icon"><i class="icon icon-arrow-right"></i></span></a>
    </div>
  </div>
  <div class="col-8 text-middle">
    <ul class="status-bars">
      <?php foreach($lang->project->statusList as $statusKey => $statusName):?>
      <li>
        <span class="bar" style="height: <?php echo $overviewPercent[$statusKey];?>"><span class="value"><?php echo $overview[$statusKey];?></span></span>
        <span class="title"><?php echo $statusName;?></span>
      </li>
      <?php endforeach;?>
    </ul>
  </div>
</div>
