<?php
/**
 * The project overview block view file of block module of ZenTaoPMS.
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
  <div class="col-6 text-middle text-center">
    <div class="tile">
      <div class="tile-title"><?php echo $lang->testcase->allTestcases;?></div>
      <div class="tile-amount"><?php echo $total;?></div>
      <?php if(common::hasPriv('testcase', 'browse')):?>
      <a href='<?php echo $this->createLink('testcase', 'browse');?>' class="btn btn-primary btn-circle btn-icon-right btn-sm"><?php echo $lang->testcase->viewAll;?><span class="label label-badge label-icon"><i class="icon icon-arrow-right"></i></span></a>
      <?php endif;?>
    </div>
  </div>
  <div class="col-6 text-middle">
    <ul class="status-bars-h">
      <?php foreach(array('pass', 'fail', 'blocked') as $result):?>
      <li><span class="bar" style="width: <?php echo $casePercents[$result];?>%"><span class="title"><?php echo $lang->testcase->resultList[$result];?></span><span class="value"><?php echo $casePairs[$result];?></span></span></li>
      <?php endforeach;?>
    </ul>
  </div>
</div>
