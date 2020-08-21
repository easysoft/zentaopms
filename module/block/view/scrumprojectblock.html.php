<?php
/**
 * The testtask block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(empty($projectOverview)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.status-bars {
    display: table;
    width: 60%;
    height: 140px;
    padding: 5px;
    padding-top: 50px;
    margin: 0;
    overflow: hidden;
}
</style>
<div class="panel-body table-row">
  <div class="col-4 text-middle text-center">
    <div class="tile">
      <div class="tile-title">所有阶段</div>
      <div class="tile-amount">25</div>
    </div>
  </div>
  <div class="col-8 text-middle">
    <ul class="status-bars" style="transform: rotate(90deg);">
      <li>
        <span class="bar" style="height: 0%"><span class="value">0</span></span>
        <span class="title">进行中</span>
      </li>
      <li>
        <span class="bar" style="height: 100%"><span class="value">100</span></span>
        <span class="title">已挂起</span>
      </li>
    </ul>
  </div>
</div>
<?php endif;?>
