<?php
/**
 * The kanban file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Mengyi Liu<liumengyi@easycorp.ltd>
 * @package     execution
 * @version     $Id: kanban.html.php 935 2022-01-11 16:49:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kanban.html.php';?>

<?php
$laneCount = 0;
foreach($regions as $region) $laneCount += $region->laneCount;

js::set('regions', $regions);
js::set('execution', $execution);
js::set('kanbanLang', $lang->kanban);
js::set('kanbanlaneLang', $lang->kanbanlane);
js::set('kanbancolumnLang', $lang->kanbancolumn);
js::set('kanbancardLang', $lang->kanbancard);
js::set('executionID', $execution->id);
js::set('laneCount', $laneCount);
js::set('userList', $userList);
js::set('noAssigned', $lang->kanbancard->noAssigned);
js::set('users', $users);
js::set('colorListLang', $lang->kanbancard->colorList);
js::set('colorList', $this->config->kanban->cardColorList);

$canSortRegion   = commonModel::hasPriv('kanban', 'sortRegion') && count($regions) > 1;
$canEditRegion   = commonModel::hasPriv('kanban', 'editRegion');
$canDeleteRegion = commonModel::hasPriv('kanban', 'deleteRegion');
$canCreateLane   = commonModel::hasPriv('kanban', 'createLane');
?>

<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <div class="input-control space c-type">
      <?php echo html::select('type', $lang->kanban->type, $browseType, 'class="form-control chosen" data-max_drop_width="215"');?>
    </div>
    <?php if($browseType != 'all'):?>
    <div class="input-control space c-group">
      <?php echo html::select('group',  $lang->kanban->group->$browseType, $groupBy, 'class="form-control chosen" data-max_drop_width="215"');?>
    </div>
    <?php endif;?>
  </div>
  <div class='input-group pull-left not-fix-input-group' id='kanbanScaleControl'>
    <span class='input-group-btn'>
      <button class='btn btn-icon' type='button' data-type='-'><i class='icon icon-minuse-solid-circle text-muted'></i></button>
    </span>
    <span class='input-group-addon'>
      <span id='kanbanScaleSize'>1</span><?php echo $lang->execution->kanbanCardsUnit; ?>
    </span>
    <span class='input-group-btn'>
      <button class='btn btn-icon' type='button' data-type='+'><i class='icon icon-plus-solid-circle text-muted'></i></button>
    </span>
  </div>
</div>
<div class='panel' id='kanbanContainer'>
  <div class='panel-body'>
    <div id="kanban" data-id='<?php echo $execution->id;?>'>
      <?php foreach($regions as $region):?>
      <div class="region<?php if($canSortRegion) echo ' sort';?>" data-id="<?php echo $region->id;?>">
        <div class="region-header dropdown">
          <span class="strong"><?php echo $region->name;?></span>
          <label class="label label-region"><?php echo $this->lang->kanbanlane->common . ' ' . $region->laneCount;?></label>
          <span><i class="icon icon-chevron-double-up" data-id="<?php echo $region->id;?>"></i></span>
          <span class='regionActions'>
            <?php if($canEditRegion || $canCreateLane || $canDeleteRegion):?>
            <button class="btn btn-link action" type="button" data-toggle="dropdown"><i class="icon icon-ellipsis-v"></i></button>
            <ul class="dropdown-menu pull-right">
              <?php if($canEditRegion) echo '<li>' . html::a(helper::createLink('kanban', 'editRegion', "regionID={$region->id}", '', 1), '<i class="icon icon-edit"></i>' . $this->lang->kanban->editRegion, '', 'class="iframe" data-toggle="modal" data-width="600px"') . '</li>';?>
              <?php if($canCreateLane) echo '<li>' . html::a(helper::createLink('kanban', 'createLane', "executionID={$execution->id}&regionID={$region->id}", '', 1), '<i class="icon icon-plus"></i>' . $this->lang->kanban->createLane, '', "class='iframe'") . '</li>';?>
              <?php if($canDeleteRegion and count($regions) > 1) echo '<li>' . html::a(helper::createLink('kanban', 'deleteRegion', "regionID={$region->id}"), '<i class="icon icon-trash"></i>' . $this->lang->kanban->deleteRegion, "hiddenwin") . '</li>';?>
            </ul>
            <?php endif;?>
          </span>
        </div>
        <div id='kanban<?php echo $region->id;?>' data-id='<?php echo $region->id;?>' class='kanban'></div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
<div id='archivedCards'></div>
<div id='archivedColumns'></div>
<?php include '../../common/view/footer.html.php';?>
