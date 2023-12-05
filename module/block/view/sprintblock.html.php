<?php
/**
 * The sprint block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(!$summary->total): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.status-bars {display: table; width: 80%; height: 140px; padding: 5px; padding-top: 50px; margin: 0; overflow: hidden;}
</style>
<div class="panel-body table-row">
  <div class="col-4 text-middle text-center">
    <div class="tile">
      <div class="tile-title"><?php echo $lang->block->allExecutions;?></div>
      <?php $projectLink = $this->createLink('project', 'execution')?>
      <div class="tile-amount"><?php echo $summary->total ? html::a($projectLink, $summary->total) : 0;?></div>
    </div>
  </div>
  <div class="col-8 text-middle">
    <ul class="status-bars  all-statistics">
      <li>
        <span class="bar" style="height: <?php echo $progress->doing * 100;?>%">
          <span class="value"><?php echo $summary->doing;?></span>
        </span>
        <span class="title"><?php echo $lang->block->doingExecution;?></span>
      </li>
      <li>
        <span class="bar" style="height: <?php echo $progress->closed * 100;?>%">
          <span class="value"><?php echo $summary->closed;?></span>
        </span>
        <span class="title"><?php echo $lang->block->finishExecution;?></span>
      </li>
    </ul>
  </div>
</div>
<?php endif;?>
