<?php if(!empty($task) and !empty($task->team) and $task->mode == 'linear'):?>
<style>
#mainContent {min-height: 500px;}
#mainContent .main-header {padding-bottom: 5px;}
#linearefforts {margin-top: -18px;}
#linearefforts .nav-tabs {margin-bottom: 10px;}
#linearefforts .nav-tabs > li.active > a {color: #313c52 !important;}
#linearefforts div.caption {height:25px; margin: 10px 0px;}
#linearefforts div.caption .account{font-weight: bolder;}
#linearefforts .tabs ul > li > a {padding-top: 6px; padding-bottom: 4px;}
#linearefforts .table thead > tr > th {padding-right: 10px;}
#toggleFoldIcon {margin-bottom: 10px;}
</style>
<?php
$this->app->loadLang('execution');
$teamOrders = array();
foreach($task->team as $team) $teamOrders[$team->order] = $team->account;

$index       = 0;
$efforts     = array_values($efforts);
$recorders   = array();
$allOrders   = array();
$allEfforts  = array();
$myOrders    = array();
$myCountList = array();
$myLastID    = array();
$myEfforts   = array();
$myLastOrder = 0;
foreach($efforts as $key => $effort)
{
    $prevEffort = $key > 0 ? $efforts[$key - 1] : null;
    $order      = (!$prevEffort or $prevEffort->order == $effort->order) ? $index : ++$index;
    $account    = $effort->account;

    $allEfforts[$order][]        = $effort;
    $recorders[$order][$account] = $account;

    $allOrders[$order] = $effort->order + 1;
    if($app->user->account == $account)
    {
        if($allOrders[$myLastOrder] != $effort->order + 1) $myLastOrder = $order;
        $myCountList[$myLastOrder] = isset($myCountList[$myLastOrder]) ? ++$myCountList[$myLastOrder] : 1;
        $myLastID[$myLastOrder]    = isset($myLastID[$myLastOrder]) ? ($myLastID[$myLastOrder] < $effort->id ? $effort->id : $myLastID[$myLastOrder]) : $effort->id;
        $myEfforts[$myLastOrder][] = $effort;

        if(!isset($myOrders[$effort->order])) $myOrders[$effort->order] = 0;
        $myOrders[$effort->order] += 1;
    }
}
ksort($myOrders);
?>
<div id='linearefforts'>
  <div class='tabs'>
    <ul class='nav nav-tabs'>
      <li class='my-effort'><a href='#legendMyEffort' data-toggle='tab'><?php echo $lang->task->myEffort;?></a></li>
      <li class='all-effort'><a href='#legendAllEffort' data-toggle='tab'><?php echo $lang->task->allEffort;?></a></li>
    </ul>
    <div class='tab-content'>
      <div class='tab-pane' id='legendMyEffort'>
        <?php if(!empty($myCountList)):?>
        <table class='table table-bordered table-fixed table-recorded has-sort-head taskEffort'>
          <thead>
            <tr class='text-center'>
              <?php
              $vars    = (isset($objectType) ? "objectType=$objectType&" : '') . "taskID=$task->id&from=$from&orderBy=%s";
              $sort    = explode(',', $orderBy);
              $orderBy = zget($sort, '0', '');
              if(!strpos($orderBy, '_')) $orderBy .= '_asc';
              ?>
              <th class="w-60px"><?php common::printOrderLink('order', $orderBy, $vars, $lang->task->teamOrder);?></th>
              <th class="w-120px"><?php common::printOrderLink('date', $orderBy, $vars, $lang->task->date);?></th>
              <th class="w-120px"><?php echo $lang->task->recordedBy;?></th>
              <th class='text-left'><?php echo $lang->task->work;?></th>
              <th class="thWidth"><?php echo $lang->task->consumedAB;?></th>
              <th class="thWidth"><?php echo $lang->task->left;?></th>
              <th class='c-actions-2'><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1;?>
            <?php foreach($myCountList as $order => $count):?>
            <?php $showOrder = false;?>
            <?php foreach($myEfforts[$order] as $effort):?>
            <?php if($effort->account != $this->app->user->account) continue;?>
            <?php $hidden = ($taskEffortFold and $i > 3) ? 'hidden' : ''?>
            <tr class="text-center <?php echo $hidden;?>">
              <?php if(!$showOrder):?>
              <td rowspan='<?php echo $count;?>'><?php echo $allOrders[$order];?></td>
              <?php $showOrder = true;?>
              <?php endif;?>
              <td><?php echo $effort->date;?></td>
              <td class='text-center'><?php echo zget($users, $effort->account);?></td>
              <td class="text-left" title="<?php echo $effort->work;?>"><?php echo $effort->work;?></td>
              <td class='text-center' title="<?php echo $effort->consumed . ' ' . $lang->execution->workHour;?>"><?php echo $effort->consumed . ' ' . $lang->execution->workHourUnit;?></td>
              <td class='text-center' title="<?php echo $effort->left     . ' ' . $lang->execution->workHour;?>"><?php echo $effort->left     . ' ' . $lang->execution->workHourUnit;?></td>
              <td align='center' class='c-actions'>
                <?php
                $canOperateEffort = $this->task->canOperateEffort($task, $effort);
                common::printIcon($this->config->edition == 'open' ? 'task' : 'effort', $this->config->edition == 'open' ? 'editEstimate' : 'edit', "effortID=$effort->id", '', 'list', 'edit', '', 'showinonlybody', true, $canOperateEffort ? '' : 'disabled');
                $deleteDisable = false;
                if(!$canOperateEffort or ($myLastID[$order] == $effort->id and $effort->left == 0)) $deleteDisable = true;
                common::printIcon($this->config->edition == 'open' ? 'task' : 'effort', $this->config->edition == 'open' ? 'deleteEstimate' : 'delete', "effortID=$effort->id", '', 'list', 'trash', 'hiddenwin', 'showinonlybody', false, $deleteDisable ? 'disabled' : '');
                ?>
              </td>
            </tr>
            <?php $i ++;?>
            <?php endforeach;?>
            <?php endforeach;?>
          </tbody>
        </table>
        <?php if($i > 4):?>
        <div id='toggleFoldIcon'>
          <?php $icon     = $taskEffortFold ? 'icon-angle-down' : 'icon-angle-top'?>
          <?php $iconText = $taskEffortFold ? $lang->task->unfoldEffort : $lang->task->foldEffort;?>
          <span class='icon-border'><i class="icon <?php echo $icon;?>"></i></span>
          <span class='text'><?php echo $iconText;?></span>
        </div>
        <?php endif;?>
        <?php endif;?>
      </div>
      <div class='tab-pane' id='legendAllEffort'>
        <table class='table table-bordered table-fixed table-recorded has-sort-head'>
          <thead>
            <tr class='text-center'>
              <?php $vars = (isset($objectType) ? "objectType=$objectType&" : '') . "taskID=$task->id&from=$from&orderBy=%s";?>
              <th class="w-60px  order-btn"><?php common::printOrderLink('order', $orderBy, $vars, $lang->task->teamOrder);?></th>
              <th class="w-120px order-btn"><?php common::printOrderLink('date', $orderBy, $vars, $lang->task->date);?></th>
              <th class="w-120px"><?php echo $lang->task->recordedBy;?></th>
              <th class='text-left'><?php echo $lang->task->work;?></th>
              <th class="thWidth"><?php echo $lang->task->consumedAB;?></th>
              <th class="thWidth"><?php echo $lang->task->left;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($recorders as $order => $accounts):?>
            <?php $showOrder = false;?>
            <?php $count     = count($allEfforts[$order]);?>
            <?php foreach($allEfforts[$order] as $effort):?>
            <tr class="text-center">
              <?php if(!$showOrder):?>
              <td rowspan='<?php echo $count;?>'><?php echo $allOrders[$order];?></td>
              <?php $showOrder = true;?>
              <?php endif;?>
              <td><?php echo $effort->date;?></td>
              <td class='text-center'><?php echo zget($users, $effort->account);?></td>
              <td class="text-left" title="<?php echo $effort->work;?>"><?php echo $effort->work;?></td>
              <td class='text-center' title="<?php echo $effort->consumed . ' ' . $lang->execution->workHour;?>"><?php echo $effort->consumed . ' ' . $lang->execution->workHourUnit;?></td>
              <td class='text-center' title="<?php echo $effort->left     . ' ' . $lang->execution->workHour;?>"><?php echo $effort->left     . ' ' . $lang->execution->workHourUnit;?></td>
            </tr>
            <?php endforeach;?>
            <?php endforeach;?>
          </tbody>
        </table>
      <?php if(!empty($myCountList)):?>
      </div>
    </div>
    <?php endif;?>
  </div>
</div>
<?php endif;?>
<?php if(!empty($myCountList) and $i > 4):?>
<style>#legendMyEffort > .table {margin-bottom: 5px;}</style>
<?php endif;?>
