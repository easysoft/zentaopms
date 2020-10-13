<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datatable.html.php';?>
<?php 
$insideWidth    = isset($stakeholders['inside']) ? count($stakeholders['inside']) * 200 : 200;
$outsideWidth   = isset($stakeholders['outside']) ? count($stakeholders['outside']) * 200 : 200;
$insideWidth    = "data-width=$insideWidth" . " style=$insideWidth";
$outsideWidth   = "data-width=$outsideWidth" . " style=$outsideWidth";
$insideList     = '<th></th>';
$outsideList    = '<th></th>';
$insideColspan  = 1;
$outsideColspan = 1;
if(isset($stakeholders['inside']))
{
    $insideColspan  = count($stakeholders['inside']);
    $insideList     = '';
    foreach($stakeholders['inside'] as $user) 
    {
        if($user->role) $user->name .= '(' . zget($lang->user->roleList, $user->role) . ')';
        $insideList .= "<th style='width: 200px;'>" . $user->name . '</th>';
    }
}

if(isset($stakeholders['outside']))
{
    $outsideColspan = count($stakeholders['outside']);
    $outsideList    = '';
    foreach($stakeholders['outside'] as $user) 
    {
        if($user->role) $user->name .= '(' . zget($lang->user->roleList, $user->role) . ')';
        $outsideList .= '<th style="width: 200px;">' . $user->name . '</th>';
    }
}
js::set('insideList', $insideList);
js::set('insideColspan', $insideColspan);
js::set('outsideList', $outsideList);
js::set('outsideColspan', $outsideColspan);
?>
<div id='mainContent' class='main-row fade'>
  <div class='main-col'>
    <?php if(!empty($processGroup)):?>
    <form class='main-table form-ajax' method='post'>
      <table class="table table-bordered" id='planList'>
        <thead>
          <tr class='text-center'>
            <th data-flex='false'><?php echo $lang->stakeholder->planField->process;?></th>
            <th data-flex='false'><?php echo $lang->stakeholder->planField->begin;?></th>
            <th data-flex='false'><?php echo $lang->stakeholder->planField->realBegin;?></th>
            <th data-flex='false'><?php echo $lang->stakeholder->planField->status;?></th>
            <th data-flex='false'><?php echo $lang->stakeholder->planField->situation;?></th>
            <th data-flex='true' <?php echo $insideWidth;?>><?php echo $lang->stakeholder->planField->inside;?></th>
            <th data-flex='true' <?php echo $outsideWidth;?>><?php echo $lang->stakeholder->planField->outside;?></th>
            <th data-flex='false'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($processGroup as $processID => $activityGroup):?>
          <tr class='process'> 
            <td colspan='5' title=<?php echo zget($processes, $processID);?>>
            <?php echo '<strong>' . zget($processes, $processID) . '</strong>';?>
            </td>
          </tr>
          <?php foreach($activityGroup as $activity):?>
          <tr> 
            <td title=<?php echo zget($activities, $activity->activity);?>>
            <?php echo zget($activities, $activity->activity);?>
            </td>
            <td class='text-center'><?php echo isset($plans[$activity->activity]) ? $plans[$activity->activity]->begin : '';?></td>
            <td class='text-center'><?php echo isset($plans[$activity->activity]) ? $plans[$activity->activity]->realBegin : '';?></td>
            <td class='text-center'><?php echo isset($plans[$activity->activity]) ? zget($lang->stakeholder->planField->stautsList, $plans[$activity->activity]->status) : '';?></td>
            <td class='text-center'><?php echo isset($plans[$activity->activity]) ? zget($lang->stakeholder->situationList, $plans[$activity->activity]->situation) : '';?></td>

            <td class='inside'>
            <?php 
            $partake =  isset($plans[$activity->activity]) ? json_decode($plans[$activity->activity]->partake) : new stdclass();
            if(isset($stakeholders['inside']))
            {
                foreach($stakeholders['inside'] as $user) 
                {
                    if(isset($partake->{$user->account}))
                    {
                        echo '<span class="text-center">' . zget($lang->stakeholder->planField->partakeList, $partake->{$user->account}) . '</span>';
                    }
                    else
                    {
                        echo '<span></span>';
                    }
                }
            }
            ?>
            </td>
            
            <td class='outside'>
            <?php 
            if(isset($stakeholders['outside']))
            {
                foreach($stakeholders['outside'] as $user) 
                {
                    if(isset($partake->{$user->account}))
                    {
                        echo '<span class="text-center">' . zget($lang->stakeholder->planField->partakeList, $partake->{$user->account}) . '</span>';
                    }
                    else
                    {
                        echo '<span></span>';
                    }
                }
            }
            ?>
            </td>
            <td class='c-actions text-center'>
            <?php echo html::a('javascript:void(0)', '<i class="icon icon-edit"></i>', '', "class='btn edit-btn' title=$lang->edit activity=$activity->activity");?>
            <?php common::printIcon('stakeholder', 'viewIssue', "activityID=$activity->activity", '', 'list', 'eye', '', 'iframe', true, '', $lang->stakeholder->planField->issue);?>
            </td>
          </tr>
          <?php endforeach;?>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer fixed-footer'>
        <div class="table-statistic"><?php echo $lang->stakeholder->planField->statusTips . '； ' . $lang->stakeholder->planField->partakeTips;?></div>
      </div>
    </form>
    <?php else:?>
    <div class="table-empty-tip">
      <p><span class="text-muted"><?php echo $lang->stakeholder->noPlan;?></span></p>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
