<?php
/**
 * The view file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
js::set('users', $users);
js::set('colorListLang', $lang->kanbancard->colorList);
js::set('colorList', $this->config->kanban->cardColorList);
js::set('displayCards', $kanban->displayCards);
js::set('fluidBoard', $kanban->fluidBoard);
js::set('mode', $config->systemMode);
js::set('alignment', $kanban->alignment);
js::set('priv', array('canAssignCard' => common::hasPriv('kanban', 'assigncard')));

$canSortRegion         = commonModel::hasPriv('kanban', 'sortRegion') && count($regions) > 1;
$canEditRegion         = commonModel::hasPriv('kanban', 'editRegion');
$canCreateRegion       = commonModel::hasPriv('kanban', 'createRegion');
$canDeleteRegion       = commonModel::hasPriv('kanban', 'deleteRegion');
$canCreateLane         = commonModel::hasPriv('kanban', 'createLane');
$canViewArchivedCard   = commonModel::hasPriv('kanban', 'viewArchivedCard');
$canViewArchivedColumn = commonModel::hasPriv('kanban', 'viewArchivedColumn');
?>

<div id='kanbanBox'>
  <?php if(count($regions) > 1):?>
  <div id='regionTabs'>
    <div class='leftBtn hidden disabled'><a><i class='icon icon-angle-left'></i></a></div>
    <div id='regionNavTabs'>
      <ul class="nav nav-tabs">
        <li data-id='all' class="<?php echo $regionID === 'all' ? 'active' : '';?>" title="<?php echo $lang->kanbanregion->all;?>">
          <a href="<?php echo inlink('view', "kanbanID=$kanban->id&regionID=all");?>" class="<?php echo $regionID === 'all' ? 'btn-active-text' : '';?>">
            <span class='text'><?php echo $lang->kanbanregion->all;?></span>
          </a>
        </li>
        <?php foreach($regions as $region):?>
        <li data-id="<?php echo $region->id;?>" class="<?php echo $region->id == $regionID ? 'active' : '';?>" title="<?php echo $region->name;?>">
          <a href="<?php echo inlink('view', "kanbanID=$kanban->id&regionID=$region->id");?>" class="<?php echo $region->id == $regionID ? 'btn-active-text' : '';?>">
            <span class='text'><?php echo $region->name;?></span>
          </a>
        </li>
        <?php endforeach;?>
      </ul>
    </div>
    <div class='rightBtn hidden disabled'><a><i class='icon icon-angle-right'></i></a></div>
    <?php if($regionID !== 'all'):?>
    <div id='region-tab-actions'>
      <div class='region-actions'>
        <?php if(($canViewArchivedCard or $canViewArchivedColumn) and $kanban->archived):?>
        <div>
          <button data-toggle="dropdown" class="btn btn-link action" type="button" title=<?php echo $this->lang->kanban->archived;?>>
            <span><?php echo $this->lang->kanban->archived;?></span>
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu pull-right">
            <?php if($canViewArchivedCard) echo '<li>' . html::a("javascript:loadMore(\"Card\", $regionID)", '<i class="icon icon-card-archive"></i>' . $this->lang->kanban->viewArchivedCard) . '</li>';?>
            <?php if($canViewArchivedColumn) echo '<li>' . html::a("javascript:loadMore(\"Column\", $regionID)", '<i class="icon icon-col-archive"></i>' . $this->lang->kanban->viewArchivedColumn) . '</li>';?>
          </ul>
        </div>
        <?php endif;?>
        <ul class="dropdown-menu pull-right">
          <?php echo '<li>' . html::a(inlink('viewArchivedCard', "", '', 1), '<i class="icon icon-card-archive"></i>' . $this->lang->kanban->viewArchivedCard, '', 'class="iframe" data-toggle="modal" data-width="600px"') . '</li>';?>
          <?php echo '<li>' . html::a(inlink('viewArchivedColumn', "", '', 1), '<i class="icon icon-col-archive"></i>' . $this->lang->kanban->viewArchivedColumn, '', 'class="iframe" data-toggle="modal" data-width="600px"') . '</li>';?>
        </ul>
      </div>
      <div class='region-actions'>
        <?php if(($canEditRegion || $canCreateLane || $canDeleteRegion || $canCreateRegion) and !(isset($this->config->CRKanban) and $this->config->CRKanban == '0' and $kanban->status == 'closed')):?>
        <button class="btn btn-link action" type="button" data-toggle="dropdown"><i class="icon icon-ellipsis-v"></i></button>
        <ul class="dropdown-menu pull-right">
          <?php if($canCreateRegion) echo '<li>' . html::a(inlink('createRegion', "kanbanID={$kanban->id}", '', 1), '<i class="icon icon-plus"></i>' . $this->lang->kanban->createRegion, '', 'class="iframe" data-toggle="modal" data-width="600px"') . '</li>';?>
          <?php if($canEditRegion)   echo '<li>' . html::a(inlink('editRegion', "regionID={$regionID}", '', 1), '<i class="icon icon-edit"></i>' . $this->lang->kanban->editRegion, '', 'class="iframe" data-toggle="modal" data-width="600px"') . '</li>';?>
          <?php if($canCreateLane)   echo '<li>' . html::a(inlink('createLane', "kanbanID={$kanban->id}&regionID={$regionID}", '', 1), '<i class="icon icon-plus"></i>' . $this->lang->kanban->createLane, '', "class='iframe'") . '</li>';?>
          <?php if($canDeleteRegion and count($regions) > 1) echo '<li>' . html::a(inlink('deleteRegion', "regionID={$regionID}"), '<i class="icon icon-trash"></i>' . $this->lang->kanban->deleteRegion, "hiddenwin") . '</li>';?>
        </ul>
        <?php endif;?>
      </div>
    </div>
    <?php endif;?>
  </div>
  <?php endif;?>
  <div class='panel' id='kanbanContainer'>
    <div class='panel-body'>
      <div id="kanban" data-id='<?php echo $kanban->id;?>'>
        <?php $regionGroup = ($regionID !== 'all' and isset($regions[$regionID])) ? array($regionID => $regions[$regionID]) : $regions;?>
        <?php foreach($regionGroup as $region):?>
        <div class="region<?php if($canSortRegion) echo ' sort';?>" data-id="<?php echo $region->id;?>">
          <div class="region-header dropdown">
            <?php if($regionID === 'all'):?>
            <strong><?php echo $region->name;?></strong>
            <i class="icon icon-angle-top btn-link" data-id="<?php echo $region->id;?>"></i>
            <div class='region-actions'>
              <?php if(($canEditRegion || $canCreateLane || $canDeleteRegion || $canCreateRegion) and !(isset($this->config->CRKanban) and $this->config->CRKanban == '0' and $kanban->status == 'closed')):?>
              <button class="btn btn-link action" type="button" data-toggle="dropdown"><i class="icon icon-ellipsis-v"></i></button>
              <ul class="dropdown-menu pull-right">
                <?php if($canCreateRegion) echo '<li>' . html::a(inlink('createRegion', "kanbanID={$kanban->id}", '', 1), '<i class="icon icon-plus"></i>' . $this->lang->kanban->createRegion, '', 'class="iframe" data-toggle="modal" data-width="600px"') . '</li>';?>
                <?php if($canEditRegion)   echo '<li>' . html::a(inlink('editRegion', "regionID={$region->id}", '', 1), '<i class="icon icon-edit"></i>' . $this->lang->kanban->editRegion, '', 'class="iframe" data-toggle="modal" data-width="600px"') . '</li>';?>
                <?php if($canCreateLane)   echo '<li>' . html::a(inlink('createLane', "kanbanID={$kanban->id}&regionID={$region->id}", '', 1), '<i class="icon icon-plus"></i>' . $this->lang->kanban->createLane, '', "class='iframe'") . '</li>';?>
                <?php if($canDeleteRegion and count($regions) > 1) echo '<li>' . html::a(inlink('deleteRegion', "regionID={$region->id}"), '<i class="icon icon-trash"></i>' . $this->lang->kanban->deleteRegion, "hiddenwin") . '</li>';?>
              </ul>
              <?php endif;?>
            </div>
            <div class='region-actions'>
              <?php if(($canViewArchivedCard or $canViewArchivedColumn) and $kanban->archived):?>
              <div>
                <button data-toggle="dropdown" class="btn btn-link action" type="button" title=<?php echo $this->lang->kanban->archived;?>>
                  <span><?php echo $this->lang->kanban->archived;?></span>
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu pull-right">
                  <?php if($canViewArchivedCard) echo '<li>' . html::a("javascript:loadMore(\"Card\", $region->id)", '<i class="icon icon-card-archive"></i>' . $this->lang->kanban->viewArchivedCard) . '</li>';?>
                  <?php if($canViewArchivedColumn) echo '<li>' . html::a("javascript:loadMore(\"Column\", $region->id)", '<i class="icon icon-col-archive"></i>' . $this->lang->kanban->viewArchivedColumn) . '</li>';?>
                </ul>
              </div>
              <?php endif;?>
              <ul class="dropdown-menu pull-right">
                <?php echo '<li>' . html::a(inlink('viewArchivedCard', "", '', 1), '<i class="icon icon-card-archive"></i>' . $this->lang->kanban->viewArchivedCard, '', 'class="iframe" data-toggle="modal" data-width="600px"') . '</li>';?>
                <?php echo '<li>' . html::a(inlink('viewArchivedColumn', "", '', 1), '<i class="icon icon-col-archive"></i>' . $this->lang->kanban->viewArchivedColumn, '', 'class="iframe" data-toggle="modal" data-width="600px"') . '</li>';?>
              </ul>
            </div>
            <?php endif;?>
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
