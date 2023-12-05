<?php
/**
 * The project list block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<style>
.table .c-progress {width: 60px;}
.table .c-estimate {width: 85px;}
.table .c-burn     {width: 150px;}
</style>
<?php if(empty($executionStats)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<div class="panel-body has-table scrollbar-hover">
  <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter block-projects tablesorter'>
    <thead>
      <tr class='text-center'>
        <th class='c-name text-left'><?php echo $lang->execution->name;?></th>
        <th class="c-date"><?php echo $lang->execution->end;?></th>
        <?php if($longBlock):?>
        <?php $thClass = common::checkNotCN() ? 'c-estimate' : 'c-hours';?>
        <th class="c-status"><?php echo $lang->statusAB;?></th>
        <th class='<?php echo $thClass?>'><?php echo $lang->execution->totalEstimate;?></th>
        <th class="c-hours"><?php echo $lang->execution->totalConsumed;?></th>
        <th class="c-hours"><?php echo $lang->execution->totalLeft;?></th>
        <?php endif;?>
        <th class="c-progress"><?php echo $lang->execution->progress;?></th>
        <?php if($longBlock):?>
        <th class='c-burn'><?php echo $lang->execution->burn;?></th>
        <?php endif;?>
      </tr>
    </thead>
    <tbody class="text-center">
     <?php $id = 0; ?>
     <?php foreach($executionStats as $execution):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn text-center' data-id='{$this->get->entry}'" : "class='text-center'";
      $viewLink = $this->createLink('execution', 'task', 'executionID=' . $execution->id);
      ?>
      <tr <?php echo $appid?>>
        <td class='c-name text-left' title='<?php echo $execution->name;?>'><nobr><?php echo html::a($viewLink, $execution->name, '', "title='$execution->name' class='text-primary'");?></nobr></td>
        <td class="c-date"><?php echo $execution->end;?></td>
        <?php if($longBlock):?>
        <td class="w-70px">
          <?php if(isset($execution->delay)):?>
          <span class="status-project status-delayed" title='<?php echo $lang->execution->delayed;?>'><?php echo $lang->execution->delayed;?></span>
          <?php else:?>
          <?php $statusName = $this->processStatus('execution', $execution);?>
          <span class="status-project status-<?php echo $execution->status?>" title='<?php echo $statusName;?>'><?php echo $statusName;?></span>
          <?php endif;?>
        </td>
        <td class="c-hours" title="<?php echo $execution->hours->totalEstimate . ' ' . $lang->execution->workHour;?>"><?php echo $execution->hours->totalEstimate . $lang->execution->workHourUnit;?></td>
        <td class="c-hours" title="<?php echo $execution->hours->totalConsumed . ' ' . $lang->execution->workHour;?>"><?php echo $execution->hours->totalConsumed . $lang->execution->workHourUnit;?></td>
        <td class="c-hours" title="<?php echo $execution->hours->totalLeft     . ' ' . $lang->execution->workHour;?>"><?php echo $execution->hours->totalLeft     . $lang->execution->workHourUnit;?></td>
        <?php endif;?>
        <td class="c-progress">
          <div class='progress-pie' data-doughnut-size='90' data-color='#3CB371' data-value='<?php echo round($execution->hours->progress);?>' data-width='24' data-height='24' data-back-color='#e8edf3'>
            <div class='progress-info'><?php echo round($execution->hours->progress);?></div>
          </div>
        </td>
        <?php if($longBlock):?>
        <td id='spark-<?php echo $id++?>' class='no-padding text-left sparkline' values='<?php echo join(',', $execution->burns);?>'></td>
        <?php endif;?>
     </tr>
     <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
