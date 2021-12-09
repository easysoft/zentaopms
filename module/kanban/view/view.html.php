<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kanban.html.php';?>

<?php
$laneCount = 0;
foreach($regions as $region) $laneCount += $region->laneCount;

js::set('regions', $regions);
js::set('kanbanLang', $lang->kanban);
js::set('kanbanlaneLang', $lang->kanbanlane);
js::set('kanbancolumnLang', $lang->kanbancolumn);
js::set('taskLang', $lang->kanbancard);
js::set('kanbanID', $kanban->id);
//js::set('members', $members);
js::set('laneCount', $laneCount);
js::set('today', helper::today());

$canSortRegion   = commonModel::hasPriv('kanban', 'sortRegion') && count($regions) > 1;
$canEditRegion   = commonModel::hasPriv('kanban', 'editRegion');
$canDeleteRegion = commonModel::hasPriv('kanban', 'deleteRegion');
$canCreateLane   = commonModel::hasPriv('kanban', 'createLane');
?>

<div id="kanban">
  <?php foreach($regions as $region):?>
  <div class="region <?php if($canSortRegion) echo 'sort';?>" data-id="<?php echo $region->id;?>">
    <div class="region-header dropdown">
      <span class="strong"><?php echo $region->name;?></span>
      <label class="label label-region"><?php echo $this->lang->kanbanlane->common . ' ' . $region->laneCount;?></label>
      <i class="icon icon-double-angle-up"></i>
      <?php if($canEditRegion || $canCreateLane || $canDeleteRegion):?>
      <button class="btn btn-link action" type="button" data-toggle="dropdown"><i class="icon icon-more-v"></i></button>
      <ul class="dropdown-menu pull-right">
        <?php if($canEditRegion) echo '<li>' . html::a(inlink('editRegion', "regionID={$region->id}"), $this->lang->kanban->editRegion, "data-toggle='modal'") . '</li>';?>
        <?php if($canCreateLane) echo '<li>' . html::a(inlink('createLane', "kanbanID={$project->id}&regionID={$region->id}"), $this->lang->kanban->createLane, "data-toggle='modal'") . '</li>';?>
        <?php if($canDeleteRegion) echo '<li>' . html::a(inlink('deleteRegion', "regionID={$region->id}"), $this->lang->kanban->deleteRegion, "class='confirmer' data-confirmTitle='{$lang->kanbanregion->confirmDelete}' data-confirmDetail='{$lang->kanbanregion->confirmDeleteDetail}'") . '</li>';?>
      </ul>
      <?php endif;?>
    </div>
    <div id='kanban<?php echo $region->id;?>' data-id='<?php echo $region->id;?>' class='kanban'></div>
  </div>
  <?php endforeach;?>
</div>
<div id='moreTasks'></div>
<div id='moreColumns'></div>
<?php include '../../common/view/footer.html.php';?>
