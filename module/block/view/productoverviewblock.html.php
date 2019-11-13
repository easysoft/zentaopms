<?php
/**
 * The overview block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<style>
  .block-overview .status-list li + li {margin-top: 5px;}
  .block-overview .status-list li > strong {font-size: 16px;}
  .progress-pie {position: relative;}
  .progress-pie .progress-info {position: absolute; width: 100%; height: 100%; left: 0; top: 0; text-align: center; padding-top: 25px;}
  .progress-pie .progress-info > small {display: block; color: #A6AAB8; line-height: 14px;}
  .progress-pie .progress-info > strong {display: block; font-size: 36px; line-height: 40px;}
</style>
<div class="panel-body table-row">
  <div class="col-6 text-middle text-center">
    <div class="progress-pie inline-block" data-value="<?php echo $normalPercent;?>" data-doughnut-size="80">
      <canvas width="100" height="100" style="width: 100px; height: 100px;"></canvas>
      <div class="progress-info">
      <small><?php echo $lang->product->all;?></small>
      <strong><?php echo empty($total) ? 0 : html::a($this->createLink('product', 'all', "product=&branch=&type=all"), $total);?></strong>
      </div>
    </div>
  </div>
  <div class="col-6 text-middle">
    <ul class="list-unstyled status-list">
    <li><span class="status-product status-unclosed"><?php echo $lang->product->statusList['normal'];?></span> &nbsp; <strong><?php echo empty($normal) ? 0 : html::a($this->createLink('product', 'all', "product=&branch=&type=noclosed"), $normal);?></strong></li>
    <li><span class="status-product status-closed"><?php echo $lang->product->statusList['closed'];?></span> &nbsp; <strong><?php echo empty($closed) ? 0 : html::a($this->createLink('product', 'all', "product=&branch=&type=closed"), $closed);?></strong></li>
    </ul>
  </div>
</div>
