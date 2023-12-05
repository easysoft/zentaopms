<?php
/**
 * The view file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun<sungangming@easycorp.ltd>
 * @package     kanban
 * @version     $Id: view.html.php 935 2021-12-09 10:49:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kanban.html.php';?>

<?php
$app->loadLang('execution');
$app->loadLang('release');
$app->loadLang('productplan');
$laneCount = 0;
foreach($regions as $region) $laneCount += $region->laneCount;

js::set('vision', $this->config->vision);
js::set('CRKanban', isset($this->config->CRKanban) ? $this->config->CRKanban : 1);
js::set('regions', $regions);
js::set('kanban', $kanban);
js::set('kanbanInfo', $kanban);
js::set('kanbanLang', $lang->kanban);
js::set('kanbanlaneLang', $lang->kanbanlane);
js::set('kanbancolumnLang', $lang->kanbancolumn);
js::set('kanbancardLang', $lang->kanbancard);
js::set('executionLang', $lang->execution);
js::set('releaseLang', $lang->release);
js::set('productplanLang', $lang->productplan);
js::set('kanbanID', $kanban->id);
js::set('laneCount', $laneCount);
js::set('userList', $userList);
js::set('noAssigned', $lang->kanbancard->noAssigned);
js::set('mode', $config->systemMode);
js::set('users', $users);
js::set('colorListLang', $lang->kanbancard->colorList);
js::set('colorList', $this->config->kanban->cardColorList);
js::set('displayCards', $kanban->displayCards);
js::set('fluidBoard', $kanban->fluidBoard);
js::set('minColWidth', $kanban->fluidBoard == '0' ? $kanban->colWidth : $kanban->minColWidth);
js::set('maxColWidth',$kanban->fluidBoard == '0' ? $kanban->colWidth : $kanban->maxColWidth);
js::set('alignment', $kanban->alignment);
js::set('defaultMinColWidth', $this->config->minColWidth);
js::set('defaultMaxColWidth', $this->config->maxColWidth);
js::set('priv', array('canAssignCard' => common::hasPriv('kanban', 'assigncard')));

$canSortRegion         = commonModel::hasPriv('kanban', 'sortRegion') && count($regions) > 1;
$canEditRegion         = commonModel::hasPriv('kanban', 'editRegion');
$canCreateRegion       = commonModel::hasPriv('kanban', 'createRegion');
$canDeleteRegion       = commonModel::hasPriv('kanban', 'deleteRegion');
$canCreateLane         = commonModel::hasPriv('kanban', 'createLane');
$canViewArchivedCard   = commonModel::hasPriv('kanban', 'viewArchivedCard');
$canViewArchivedColumn = commonModel::hasPriv('kanban', 'viewArchivedColumn');
?>

<div id='kanbanBox' class='hidden'>
  <?php if(count($regions) > 1):?>
  <div id='regionTabs'>
    <div class='leftBtn hidden disabled'><a><i class='icon icon-angle-left'></i></a></div>
    <div id='regionNavTabs'>
      <ul class="nav nav-tabs">
        <li data-id='all' class="<?php echo $regionID === 'all' ? 'active' : '';?>" title="<?php echo $lang->kanbanregion->all;?>">
          <?php $active = $regionID == 'all' ? 'btn-active-text' : '';?>
          <?php echo html::a('all', "<span class='text'>{$lang->kanbanregion->all}</span>", '', "class='$active' data-tab");?>
        </li>
        <?php foreach($regions as $region):?>
        <li data-id="<?php echo $region->id;?>" class="<?php echo $region->id == $regionID ? 'active' : '';?>" title="<?php echo $region->name;?>">
          <?php $active = $region->id == $regionID ? 'btn-active-text' : '';?>
          <?php echo html::a("#region{$region->id}", "<span class='text'>$region->name</span>", '', "class='$active' data-tab");?>
        </li>
        <?php endforeach;?>
      </ul>
    </div>
    <div class='rightBtn hidden disabled'><a><i class='icon icon-angle-right'></i></a></div>
    <div id='region-tab-actions' class="<?php echo $regionID !== 'all' ? 'active' : '';?>">
      <div class='region-actions'>
        <?php if(($canEditRegion or $canCreateLane or $canDeleteRegion or $canCreateRegion or ($kanban->archived and ($canViewArchivedCard or $canViewArchivedColumn))) and !(isset($this->config->CRKanban) and $this->config->CRKanban == '0' and $kanban->status == 'closed')):?>
        <button class="btn btn-link action" type="button" data-toggle="dropdown"><i class="icon icon-ellipsis-v"></i></button>
        <ul class="dropdown-menu pull-right">
          <?php if($canCreateRegion) echo '<li>' . html::a(inlink('createRegion', "kanbanID={$kanban->id}", '', 1), '<i class="icon icon-plus"></i>' . $this->lang->kanban->createRegion, '', 'class="iframe" data-toggle="modal" data-width="600px"') . '</li>';?>
          <?php if($canEditRegion)   echo "<li class='editRegion'>" . html::a(inlink('editRegion', "regionID={$regionID}", '', 1), '<i class="icon icon-edit"></i>' . $this->lang->kanban->editRegion, '', 'class="iframe" data-toggle="modal" data-width="600px"') . '</li>';?>
          <?php if($canCreateLane)   echo "<li class='createLane'>" . html::a(inlink('createLane', "kanbanID={$kanban->id}&regionID={$regionID}", '', 1), '<i class="icon icon-plus"></i>' . $this->lang->kanban->createLane, '', "class='iframe'") . '</li>';?>
          <?php if($canDeleteRegion and count($regions) > 1) echo "<li class='deleteRegion'>" . html::a(inlink('deleteRegion', "regionID={$regionID}"), '<i class="icon icon-trash"></i>' . $this->lang->kanban->deleteRegion, "hiddenwin") . '</li>';?>
          <?php if(($canCreateRegion or $canEditRegion or $canCreateLane or ($canDeleteRegion and count($regions) > 1)) and ($kanban->archived and ($canViewArchivedCard or $canViewArchivedColumn)))echo "<li class='divider'></li>"?>
          <?php if($canViewArchivedCard and $kanban->archived) echo "<li class='archivedCard'>" . html::a("javascript:loadMore(\"Card\", $regionID)", '<i class="icon icon-card-archive"></i>' . $this->lang->kanban->viewArchivedCard) . '</li>';?>
          <?php if($canViewArchivedColumn and $kanban->archived) echo "<li class='archivedColumn'>" . html::a("javascript:loadMore(\"Column\", $regionID)", '<i class="icon icon-col-archive"></i>' . $this->lang->kanban->viewArchivedColumn) . '</li>';?>
        </ul>
        <?php endif;?>
      </div>
    </div>
  </div>
  <?php endif;?>
  <div class='panel' id='kanbanContainer'>
    <div class='panel-body'>
      <div id="kanban" data-id='<?php echo $kanban->id;?>'>
        <?php foreach($regions as $region):?>
        <?php $regionClass = ($regionID == 'all' or $region->id == $regionID) ? 'active' : '';?>
        <?php if($canSortRegion) $regionClass .= ' sort';?>
        <?php if($regionID !== 'all') $regionClass .= ' notAll';?>
        <div class="region <?php echo $regionClass;?>" data-id="<?php echo $region->id;?>" id="<?php echo 'region' . $region->id?>">
          <div class="region-header dropdown">
            <strong><?php echo $region->name;?></strong>
            <i class="icon icon-angle-top btn-link" data-id="<?php echo $region->id;?>"></i>
            <div class='region-actions'>
              <?php if(($canEditRegion or $canCreateLane or $canDeleteRegion or $canCreateRegion or ($kanban->archived and ($canViewArchivedCard or $canViewArchivedColumn))) and !(isset($this->config->CRKanban) and $this->config->CRKanban == '0' and $kanban->status == 'closed')):?>
              <button class="btn btn-link action" type="button" data-toggle="dropdown"><i class="icon icon-ellipsis-v"></i></button>
              <ul class="dropdown-menu pull-right">
                <?php if($canCreateRegion) echo '<li>' . html::a(inlink('createRegion', "kanbanID={$kanban->id}", '', true), '<i class="icon icon-plus"></i>' . $this->lang->kanban->createRegion, '', 'class="iframe" data-toggle="modal" data-width="600px"') . '</li>';?>
                <?php if($canEditRegion)   echo '<li>' . html::a(inlink('editRegion', "regionID={$region->id}", '', true), '<i class="icon icon-edit"></i>' . $this->lang->kanban->editRegion, '', 'class="iframe" data-toggle="modal" data-width="600px"') . '</li>';?>
                <?php if($canCreateLane)   echo '<li>' . html::a(inlink('createLane', "kanbanID={$kanban->id}&regionID={$region->id}", '', true), '<i class="icon icon-plus"></i>' . $this->lang->kanban->createLane, '', "class='iframe'") . '</li>';?>
                <?php if($canDeleteRegion and count($regions) > 1) echo '<li>' . html::a(inlink('deleteRegion', "regionID={$region->id}"), '<i class="icon icon-trash"></i>' . $this->lang->kanban->deleteRegion, "hiddenwin") . '</li>';?>
                <?php if(($canCreateRegion or $canEditRegion or $canCreateLane or ($canDeleteRegion and count($regions) > 1)) and ($kanban->archived and ($canViewArchivedCard or $canViewArchivedColumn)))echo "<li class='divider'></li>"?>
                <?php if($canViewArchivedCard and $kanban->archived) echo '<li>' . html::a("javascript:loadMore(\"Card\", $region->id)", '<i class="icon icon-card-archive"></i>' . $this->lang->kanban->viewArchivedCard) . '</li>';?>
                <?php if($canViewArchivedColumn and $kanban->archived) echo '<li>' . html::a("javascript:loadMore(\"Column\", $region->id)", '<i class="icon icon-col-archive"></i>' . $this->lang->kanban->viewArchivedColumn) . '</li>';?>
              </ul>
              <?php endif;?>
            </div>
          </div>
          <div id='kanban<?php echo $region->id;?>' data-id='<?php echo $region->id;?>' class='kanban'></div>
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </div>
</div>
<div id='archivedCards'></div>
<div id='archivedColumns'></div>
<?php include '../../common/view/footer.html.php';?>
