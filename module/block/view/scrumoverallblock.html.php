<?php
/**
 * The project block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(empty($totalData)): ?>
<div class='empty-tip'><?php common::printLink('project', 'create', '', "<i class='icon-plus'></i> " . $lang->project->create, '', "class='btn btn-primary'")?></div>
<?php else:?>
<style>
#toal-investment{padding-bottom: 5px;}
.over-title{font-size: 14px; color: #838a9d;}
.extra-title{font-size: 12px; color: #a1a7b7;}
.progress{margin-bottom: 8px;}
.total-div{padding: 0px 25px;}
.extra-div{position: relative; padding: 0px 5px 5px; display: table-cell;text-align: center;}
.extra-div > small {display: block; color: #A6AAB8;}
.extra-div > span {display: block; color: #3c4353; font-weight: bold;}
</style>
<div class="panel-body conatiner-fluid">
  <div class="table-row">
    <div class="col-6 tile">
      <div class="text-left" id="toal-investment"><strong>总投入</strong></div>
      <div class="table-row">
        <div class="col-4 tile">
          <div class="over-title"><i class="icon-program-group icon-group"></i> 我的任务</div>
          <div class="tile-amount"><a href="/my-task-assignedTo.html">96</a>
          </div>
        </div>
        <div class="col-4 tile">
          <div class="over-title"><i class="icon icon-time"></i> 已消耗工时</div>
          <div class="tile-amount"><a href="/my-task-assignedTo.html">96</a>
          </div>
          <div class="tile-info">
          <span class="extra-title">总预计 10</span>
          </div>
        </div>
        <div class="col-4 tile">
          <div class="over-title"><i class="icon icon-circle"></i> 已花费</div>
          <div class="tile-amount"><a href="/my-task-assignedTo.html">￥200,00</a>
          </div>
          <div class="tile-info">
          <span class="extra-title">预算 ￥1000,00</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-3 total-div">
      <div><strong>总需求</strong></div>
      <div class="tile-amount"><a href="/my-task-assignedTo.html">80</a></div>
      <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%"></div>
      </div>
      <div class="extra-div">
        <small>已完成</small>
        <span>0</span>
      </div>
      <div class="extra-div">
        <small>剩余</small>
        <span>0</span>
      </div>
    </div>
    <div class="col-3 total-div">
      <div><strong>总Bug</strong></div>
      <div class="tile-amount"><a href="/my-task-assignedTo.html">80</a></div>
      <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%"></div>
      </div>
      <div class="extra-div">
        <small>已完成</small>
        <span>0</span>
      </div>
      <div class="extra-div">
        <small>剩余</small>
        <span>0</span>
      </div>
    </div>
  </div>
</div>
<?php endif;?>
