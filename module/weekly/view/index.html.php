<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('selectedWeekBegin', $date);?>
<?php if(common::hasPriv('weekly', 'exportweeklyreport')):?>
<script>
$(function(){$('#exportreport').modalTrigger();});
</script>
<?php endif;?>
<div id="mainMenu" class="clearfix text-center">
  <div id='mainContent' >
    <div class='main-table'>
    <table class='table table-bordered'>
      <tr>
        <td><?php echo $lang->weekly->term;?></td>
        <td><?php echo $monday . ' ~ ' . $lastDay?></td>
        <td><?php echo $lang->weekly->master;?></td>
        <td><?php echo zget($users, $project->PM, '');?></td>
      </tr>
      <tr>
        <td><?php echo $lang->weekly->project;?></td>
        <td class='projectName' title='<?php echo $project->name;?>'><?php echo $project->name;?></td>
        <td><?php echo $lang->weekly->staff;?></td>
        <td><?php echo $staff;?></td>
      </tr>
    </table>
    <div class='page-title'><h4><?php echo $lang->weekly->summary;?></h4></div>
    <table class='table table-bordered'>
      <tr>
        <td>
          <?php echo $lang->weekly->progress;?>
          <div id='helpDropdown' class="dropdown dropdown-hover">
            <a data-toggle="dropdown"><i class="icon-help"></i></a>
            <div class="dropdown-menu">
              <?php echo $lang->weekly->reportHelpNotice;?>
            </div>
          </div>
        </td>
        <td></td>
        <td><?php echo $lang->weekly->analysisResult;?></td>
        <td></td>
      </tr>
      <tr>
        <td><?php echo $lang->weekly->pv;?></td>
        <td><?php echo $pv;?></td>
        <td rowspan='4'><?php echo $lang->weekly->progress;?></td>
        <td rowspan='4'><?php echo $this->weekly->getTips('progress', $sv);?></td>
      </tr>
      <tr>
        <td><?php echo $lang->weekly->ev;?></td>
        <td><?php echo $ev;?></td>
      </tr>
      <tr>
        <td><?php echo $lang->weekly->ac;?></td>
        <td><?php echo $ac;?></td>
      </tr>
      <tr>
        <td><?php echo $lang->weekly->sv;?></td>
        <td><?php echo $sv ? $sv . '%' : '';?></td>
      </tr>
      <tr>
        <td><?php echo $lang->weekly->cv;?></td>
        <td><?php echo $cv ? $cv . '%' : '';?></td>
        <td><?php echo $lang->weekly->cost;?></td>
        <?php $projectCost = zget($this->config->custom, 'cost', 1);?>
        <td class='projectCost'><?php echo empty($projectCost) ? 0 : $ac * $projectCost;?></td>
      </tr>
    </table>
    <div class='page-title'><h4><?php echo $lang->weekly->finished;?></h4></div>
    <table class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <th class='c-id'><?php echo $lang->idAB;?></th>
          <th class='text-left'><?php echo $lang->task->name;?></th>
          <th class='text-left w-120px'><?php echo $lang->task->estStarted;?></th>
          <th class='text-left w-120px'><?php echo $lang->task->deadline;?></th>
          <th class='text-left w-120px'><?php echo $lang->task->realStarted;?></th>
          <th class='text-left w-100px'><?php echo $lang->task->finishedBy;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($finished as $task):?>
        <tr data-id='<?php echo $task->id ?>'>
          <td class='c-id'>
            <?php printf('%03d', $task->id);?>
          </td>
          <td class='text-left' title='<?php echo $task->name?>'>
            <?php echo html::a($this->createLink('task', 'view', 'id=' . $task->id), $task->name); ?>
          </td>
          <td class='text-left'><?php echo $task->estStarted;?></td>
          <td class='text-left'><?php echo $task->deadline;?></td>
          <td class='text-left'><?php if(!helper::isZeroDate($task->realStarted)) echo substr($task->realStarted, 0, 11);?></td>
          <td class='text-left'><?php echo zget($users, $task->finishedBy);?></td>
        </tr>
        <?php endforeach;?>
        <td colspan='6' class='totalCount'><?php echo sprintf($lang->weekly->totalCount, count($finished));?></tr>
      </tbody>
    </table>

    <div class='page-title'><h4><?php echo $lang->weekly->postponed;?></h4></div>
    <table class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <th class='c-id'><?php echo $lang->idAB;?></th>
          <th class='text-left'><?php echo $lang->task->name;?></th>
          <th class='text-left w-100px'><?php echo $lang->task->assignedTo;?></th>
          <th class='text-left w-120px'><?php echo $lang->task->estStarted;?></th>
          <th class='text-left w-120px'><?php echo $lang->task->deadline;?></th>
          <th class='text-left w-120px'><?php echo $lang->task->realStarted;?></th>
          <th class='text-left w-100px'><?php echo $lang->task->progress;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($postponed as $task):?>
        <tr data-id='<?php echo $task->id ?>'>
          <td class='c-id'>
            <?php printf('%03d', $task->id);?>
          </td>
          <td class='text-left' title='<?php echo $task->name?>'>
            <?php echo html::a($this->createLink('task', 'view', 'id=' . $task->id), $task->name); ?>
          </td>
          <td class='text-left'><?php echo zget($users, $task->assignedTo);?></td>
          <td class='text-left'><?php echo $task->estStarted;?></td>
          <td class='text-left'><?php echo $task->deadline;?></td>
          <td class='text-left'><?php if(!helper::isZeroDate($task->realStarted)) echo substr($task->realStarted, 0, 11);?></td>
          <td class='text-left'><?php echo $task->progress;?>%</td>
        </tr>
        <?php endforeach;?>
        <td colspan='7' class='totalCount'><?php echo sprintf($lang->weekly->totalCount, count($postponed));?></tr>
      </tbody>
    </table>

    <div class='page-title'><h4><?php echo $lang->weekly->nextWeek;?></h4></div>
    <table class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <th class='c-id'><?php echo $lang->idAB;?></th>
          <th class='text-left'><?php echo $lang->task->name;?></th>
          <th class='text-left w-100px'><?php echo $lang->task->assignedTo;?></th>
          <th class='text-left w-120px'><?php echo $lang->task->estStarted;?></th>
          <th class='text-left w-120px'><?php echo $lang->task->deadline;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($nextWeek as $task):?>
        <tr data-id='<?php echo $task->id ?>'>
          <td class='c-id'>
            <?php printf('%03d', $task->id);?>
          </td>
          <td class='text-left' title='<?php echo $task->name?>'>
            <?php echo html::a($this->createLink('task', 'view', 'id=' . $task->id), $task->name); ?>
          </td>
          <td class='text-left'><?php echo zget($users, $task->assignedTo);?></td>
          <td class='text-left'><?php echo $task->estStarted;?></td>
          <td class='text-left'><?php echo $task->deadline;?></td>
        </tr>
        <?php endforeach;?>
        <td colspan='5' class='totalCount'><?php echo sprintf($lang->weekly->totalCount, count($nextWeek));?></tr>
      </tbody>
    </table>

    <div class='page-title'><h4><?php echo $lang->weekly->workloadByType;?></h4></div>
    <table class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <th><?php echo $lang->task->type;?></th>
          <?php foreach($lang->task->typeList as $type => $name):?>
          <?php if(!$name) continue;?>
          <th><?php echo $name;?></th>
          <?php endforeach;?>
          <th><?php echo $lang->weekly->total;?></th>
        </tr>
      </thead>
      <tbody class='sortable' id='taskTableList'>
        <tr>
        <th><?php echo $lang->weekly->workload;?></th>
        <?php $total = 0;?>
        <?php foreach($lang->task->typeList as $type => $name):?>
          <?php if(!$name) continue;?>
          <?php $worktimes = zget($workload, $type, 0);?>
          <td><?php echo $worktimes;?></td>
          <?php $total += $worktimes;?>
          <?php if(!$name) continue;?>
        <?php endforeach;?>
        <td><?php echo $total;?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
