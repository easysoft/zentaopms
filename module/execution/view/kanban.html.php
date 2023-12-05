<?php
/**
 * The kanban file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
if($groupBy == 'default')
{
    foreach($regions as $region) $laneCount += $region->laneCount;
}

js::set('regions', $regions);
js::set('browseType', $browseType);
js::set('kanbanData', $kanbanData);
js::set('orderBy', $orderBy);
js::set('groupBy', $groupBy);
js::set('execution', $execution);
js::set('productID', $productID);
js::set('hourUnit', $config->hourUnit);
js::set('kanbanLang', $lang->kanban);
js::set('kanbanlaneLang', $lang->kanbanlane);
js::set('storyLang', $lang->story);
js::set('executionLang', $lang->execution);
js::set('bugLang', $lang->bug);
js::set('taskLang', $lang->task);
js::set('deadlineLang', $lang->task->deadlineAB);
js::set('estStartedLang', $lang->task->estStarted);
js::set('kanbancolumnLang', $lang->kanbancolumn);
js::set('kanbancardLang', $lang->kanbancard);
js::set('executionID', $execution->id);
js::set('laneCount', $laneCount);
js::set('userList', $userList);
js::set('noAssigned', $lang->kanbancard->noAssigned);
js::set('users', $users);
js::set('entertime', time());
js::set('displayCards', $execution->displayCards);
js::set('productNum', $productNum);
js::set('fluidBoard', $execution->fluidBoard);
js::set('minColWidth', $execution->fluidBoard == '0' ? $execution->colWidth : $execution->minColWidth);
js::set('maxColWidth',$execution->fluidBoard == '0' ? $execution->colWidth : $execution->maxColWidth);
js::set('colorListLang', $lang->kanbancard->colorList);
js::set('colorList', $this->config->kanban->cardColorList);
js::set('projectID', $projectID);
js::set('vision', $this->config->vision);
js::set('productCount', count($productNames));
js::set('executionID', $execution->id);
js::set('needLinkProducts', $lang->execution->needLinkProducts);
js::set('lastUpdateData', '');
js::set('rdSearchValue', '');
js::set('defaultMinColWidth', $this->config->minColWidth);
js::set('defaultMaxColWidth', $this->config->maxColWidth);

$canSortRegion      = commonModel::hasPriv('kanban', 'sortRegion') && count($regions) > 1;
$canCreateRegion    = common::hasPriv('kanban', 'createRegion') && $groupBy == 'default';
$canEditRegion      = commonModel::hasPriv('kanban', 'editRegion');
$canDeleteRegion    = commonModel::hasPriv('kanban', 'deleteRegion');
$canCreateLane      = commonModel::hasPriv('kanban', 'createLane');
$canCreateTask      = common::hasPriv('task', 'create');
$canBatchCreateTask = common::hasPriv('task', 'batchCreate');
$canImportTask      = common::hasPriv('execution', 'importTask') && $execution->multiple;

$canCreateBug        = $features['qa'] && $productID && common::hasPriv('bug', 'create');
$canBatchCreateBug   = $features['qa'] && $productID && common::hasPriv('bug', 'batchCreate') && $execution->multiple;
$canImportBug        = $features['qa'] && $productID && common::hasPriv('execution', 'importBug') && $execution->multiple;
$hasBugButton        = $features['qa'] && ($canCreateBug || $canBatchCreateBug);

$canCreateStory      = $features['story'] && $productID && common::hasPriv('story', 'create');
$canBatchCreateStory = $features['story'] && $productID && common::hasPriv('story', 'batchCreate');
$canLinkStory        = $features['story'] && $productID && common::hasPriv('execution', 'linkStory') && !empty($execution->hasProduct);
$canLinkStoryByPlan  = $features['story'] && $productID && common::hasPriv('execution', 'importplanstories') && !empty($project->hasProduct);
$hasStoryButton      = $features['story'] && ($canCreateStory || $canBatchCreateStory || $canLinkStory || $canLinkStoryByPlan);

$hasTaskButton = $canCreateTask || $canBatchCreateTask || $canImportBug;

js::set('priv',
    array(
        'canCreateTask'         => $canCreateTask,
        'canBatchCreateTask'    => $canBatchCreateTask,
        'canImportBug'          => $canImportBug,
        'canCreateBug'          => $canCreateBug,
        'canBatchCreateBug'     => $canBatchCreateBug,
        'canCreateStory'        => $canCreateStory,
        'canBatchCreateStory'   => $canBatchCreateStory,
        'canLinkStory'          => $canLinkStory,
        'canLinkStoryByPlan'    => $canLinkStoryByPlan,
        'canViewBug'            => common::hasPriv('bug', 'view'),
        'canAssignBug'          => common::hasPriv('bug', 'assignto'),
        'canConfirmBug'         => common::hasPriv('bug', 'confirm'),
        'canResolveBug'         => common::hasPriv('bug', 'resolve'),
        'canCopyBug'            => common::hasPriv('bug', 'create'),
        'canEditBug'            => common::hasPriv('bug', 'edit'),
        'canDeleteBug'          => common::hasPriv('bug', 'delete'),
        'canActivateBug'        => common::hasPriv('bug', 'activate'),
        'canViewTask'           => common::hasPriv('task', 'view'),
        'canAssignTask'         => common::hasPriv('task', 'assignto'),
        'canFinishTask'         => common::hasPriv('task', 'finish'),
        'canPauseTask'          => common::hasPriv('task', 'pause'),
        'canCancelTask'         => common::hasPriv('task', 'cancel'),
        'canCloseTask'          => common::hasPriv('task', 'close'),
        'canActivateTask'       => common::hasPriv('task', 'activate'),
        'canActivateStory'      => common::hasPriv('story', 'activate'),
        'canStartTask'          => common::hasPriv('task', 'start'),
        'canRestartTask'        => common::hasPriv('task', 'restart'),
        'canEditTask'           => common::hasPriv('task', 'edit'),
        'canDeleteTask'         => common::hasPriv('task', 'delete'),
        'canRecordWorkhourTask' => common::hasPriv('task', 'recordWorkhour'),
        'canToStoryBug'         => common::hasPriv('story', 'create'),
        'canAssignStory'        => common::hasPriv('story', 'assignto'),
        'canEditStory'          => common::hasPriv('story', 'edit'),
        'canDeleteStory'        => common::hasPriv('story', 'delete'),
        'canChangeStory'        => common::hasPriv('story', 'change'),
        'canCloseStory'         => common::hasPriv('story', 'close'),
        'canUnlinkStory'        => (common::hasPriv('execution', 'unlinkStory') && !empty($execution->hasProduct)),
        'canViewStory'          => common::hasPriv('execution', 'storyView'),
    )
);
?>
<?php if($groupBy == 'story' and $browseType == 'task'):?>
<style>.kanban-cols {left: 0px !important;}</style>
<?php endif;?>
<?php if(!($features['story'] or $features['qa'])):?>
<style>#mainMenu .c-group{margin-left: 0px;}</style>
<?php endif;?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php if($features['story'] or $features['qa']):?>
    <div class="input-control space c-type">
      <?php
      if(!$features['story']) unset($lang->kanban->type['story']);
      if(!$features['qa']) unset($lang->kanban->type['bug']);
      ?>
      <?php echo html::select('type', $lang->kanban->type, $browseType, 'class="form-control chosen" data-max_drop_width="215"');?>
    </div>
    <?php endif;?>
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
  <div class='btn-toolbar pull-right'>
    <div class="input-group" id="rdKanbanSearch">
      <div class="input-control search-box" id="rdSearchBox">
        <input type="text" name="rdKanbanSearchInput" id="rdKanbanSearchInput" value="" class="form-control" oninput="searchCards(this.value)" placeholder="<?php echo $lang->execution->pleaseInput?>" autocomplete="off">
      </div>
    </div>
    <?php
    $width = common::checkNotCN() ? '850px' : '700px';
    echo html::a('javascript:toggleRDSearchBox()', "<i class='icon-search muted'></i> " . $lang->searchAB, '', "class='btn btn-link querybox-toggle'");
    echo html::a('javascript:fullScreen()', "<i class='icon-fullscreen muted'></i> " . $lang->kanban->fullScreen, '', "class='btn btn-link'");
    if(common::hasPriv('execution', 'setKanban')) echo html::a(helper::createLink('execution', 'setKanban', "executionID=$execution->id", '', true), '<i class="icon icon-cog-outline"></i> ' . $lang->settings, '', "class='iframe btn btn-link text-left' data-width='$width'");
    if(!$execution->multiple and common::hasPriv('project', 'edit'))  $editURL = helper::createLink('project',   'edit', "projectID=$execution->project", '', true);
    if($execution->multiple and common::hasPriv('execution', 'edit')) $editURL = helper::createLink('execution', 'edit', "executionID=$execution->id", '', true);
    if(!empty($editURL)) echo html::a($editURL, '<i class="icon icon-edit"></i> ' . $lang->edit, '', "class='iframe btn btn-link text-left' data-width='80%'");
    $actions           = '';
    $printSettingBtn   = (common::hasPriv('execution', 'close') or common::hasPriv('execution', 'delete') or !empty($executionActions));

    if($printSettingBtn and $execution->multiple)
    {
        $actions .= "<div class='btn-group menu-actions'>";
        $actions .= html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");
        $actions .= "<ul class='dropdown-menu pull-right'>";
        $kanbanActions = '';
        if(common::hasPriv('execution', 'start')) $kanbanActions .= '<li class="startButton hidden">' . html::a(helper::createLink('execution', 'start', "executionID=$execution->id&from=kanban", '', true), '<i class="icon icon-play"></i>' . $lang->execution->start, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
        if(common::hasPriv('execution', 'putoff')) $kanbanActions .= '<li class="putoffButton hidden">' . html::a(helper::createLink('execution', 'putoff', "executionID=$execution->id&from=kanban", '', true), '<i class="icon icon-calendar"></i>' . $lang->execution->putoff, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
        if(common::hasPriv('execution', 'suspend')) $kanbanActions .= '<li class="suspendButton hidden">' . html::a(helper::createLink('execution', 'suspend', "executionID=$execution->id&from=kanban", '', true), '<i class="icon icon-pause"></i>' . $lang->execution->suspend, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
        if(common::hasPriv('execution', 'close')) $kanbanActions .= '<li class="closeButton hidden">' . html::a(helper::createLink('execution', 'close', "executionID=$execution->id&from=kanban", '', true), '<i class="icon icon-off"></i>' . $lang->execution->close, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
        if(common::hasPriv('execution', 'activate')) $kanbanActions .= '<li class="activateButton hidden">' . html::a(helper::createLink('execution', 'activate', "executionID=$execution->id&from=kanban", '', true), '<i class="icon icon-magic"></i>' . $lang->execution->activate, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
        if(common::hasPriv('execution', 'delete')) $kanbanActions .= '<li>' . html::a(helper::createLink('execution', 'delete', "executionID=$execution->id"), '<i class="icon icon-trash"></i>' . $lang->delete, 'hiddenwin', "class='btn btn-link text-left'") . '</li>';
        if($kanbanActions) $actions .= $kanbanActions;
        $actions .= '</ul></div>';
    }

    echo $actions;
    ?>
    <?php if($canCreateTask or $canBatchCreateTask or $canImportBug or $canCreateBug or $canBatchCreateBug or $canCreateStory or $canBatchCreateStory or $canLinkStory or $canLinkStoryByPlan):?>
    <div class='dropdown' id='createDropdown'>
      <button class='btn btn-primary' type='button' data-toggle='dropdown'><i class='icon icon-plus'></i> <?php echo $this->lang->create;?> <span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <?php if($canCreateStory) echo '<li>' . html::a(helper::createLink('story', 'create', "productID=$productID&branch=0&moduleID=0&story=0&execution=$execution->id", '', true), $lang->execution->createStory, '', "class='iframe' data-width=80%") . '</li>';?>
        <?php
        if($canBatchCreateStory)
        {
            if(count($productNames) > 1)
            {
                echo '<li>' . html::a('#batchCreateStory', $lang->execution->batchCreateStory, '', 'data-toggle="modal"') . '</li>';
            }
            else
            {
                echo '<li>' . html::a(helper::createLink('story', 'batchCreate', "productID=$productID&branch=$branchID&moduleID=0&story=0&execution=$execution->id", '', true), $lang->execution->batchCreateStory, '', "class='iframe' data-width='90%'") . '</li>';
            }
        }
        ?>
        <?php if($canLinkStory) echo '<li>' . html::a(helper::createLink('execution', 'linkStory', "execution=$execution->id", '', true), $lang->execution->linkStory, '', "class='iframe' data-width='90%'") . '</li>';?>
        <?php if($canLinkStoryByPlan) echo '<li>' . html::a('#linkStoryByPlan', $lang->execution->linkStoryByPlan, '', 'data-toggle="modal"') . '</li>';?>
        <?php if($hasStoryButton and $hasBugButton) echo '<li class="divider"></li>';?>
        <?php if($canCreateBug) echo '<li>' . html::a(helper::createLink('bug', 'create', "productID=$productID&branch=0&extra=executionID=$execution->id", '', true), $lang->bug->create, '', "class='iframe'") . '</li>';?>
        <?php
        if($canBatchCreateBug)
        {
            $batchCreateBugLink = '<li>' . html::a(helper::createLink('bug', 'batchCreate', "productID=$productID&branch=$branchID&executionID=$execution->id", '', true), $lang->bug->batchCreate, '', "class='iframe'") . '</li>';
            if($productNum > 1) $batchCreateBugLink = '<li>' . html::a('#batchCreateBug', $lang->bug->batchCreate, '', "data-toggle='modal'") . '</li>';
            echo $batchCreateBugLink;
        }
        ?>
        <?php if(($hasStoryButton or $hasBugButton) and $hasTaskButton) echo '<li class="divider"></li>';?>
        <?php if($canCreateTask) echo '<li>' . html::a(helper::createLink('task', 'create', "execution=$execution->id", '', true), $lang->task->create, '', "class='iframe'") . '</li>';?>
        <?php if($canImportBug) echo '<li>' . html::a(helper::createLink('execution', 'importBug', "executionID=$execution->id", '', true), $lang->execution->importBug, '', "class='iframe' data-width=90%") . '</li>';?>
        <?php if($canImportTask) echo '<li>' . html::a(helper::createLink('execution', 'importTask', "toExecution=$execution->id", '', true), $lang->execution->importTask, '', "class='iframe' data-width=90%") . '</li>';?>
        <?php if($canBatchCreateTask) echo '<li>' . html::a(helper::createLink('task', 'batchCreate', "execution=$execution->id", '', true), $lang->execution->batchCreateTask, '', "class='iframe' data-width=90%") . '</li>';?>
      </ul>
    </div>
    <?php endif;?>
  </div>
</div>
<?php if($groupBy == 'default'):?>
<div class='panel' id='kanbanContainer'>
  <div class='panel-body'>
    <div id="kanban" data-id='<?php echo $execution->id;?>'>
      <?php foreach($regions as $region):?>
      <div class="region<?php if($canSortRegion) echo ' sort';?>" data-id="<?php echo $region->id;?>">
        <div class="region-header dropdown">
          <strong><?php echo $region->name;?></strong>
          <a class="text-muted"><i class="icon icon-chevron-double-up" data-id="<?php echo $region->id;?>"></i></a>
          <div class='region-actions'>
            <?php if($canEditRegion || $canCreateLane || $canDeleteRegion || $canCreateRegion):?>
            <button class="btn btn-link action" type="button" data-toggle="dropdown"><i class="icon icon-ellipsis-v"></i></button>
            <ul class="dropdown-menu pull-right">
              <?php if($canCreateRegion) echo '<li>' . html::a(helper::createLink('kanban', 'createRegion', "kanbanID=$execution->id&from=execution", '', true), '<i class="icon icon-plus"></i>' . $lang->kanban->createRegion, '', "class='iframe btn btn-link text-left'") . '</li>';?>
              <?php if($canEditRegion) echo '<li>' . html::a(helper::createLink('kanban', 'editRegion', "regionID={$region->id}", '', 1), '<i class="icon icon-edit"></i>' . $this->lang->kanban->editRegion, '', 'class="iframe" data-toggle="modal" data-width="600px"') . '</li>';?>
              <?php if($canCreateLane) echo '<li>' . html::a(helper::createLink('kanban', 'createLane', "executionID={$execution->id}&regionID={$region->id}&from=execution", '', 1), '<i class="icon icon-plus"></i>' . $this->lang->kanban->createLane, '', "class='iframe'") . '</li>';?>
              <?php if($canDeleteRegion and count($regions) > 1) echo '<li>' . html::a(helper::createLink('kanban', 'deleteRegion', "regionID={$region->id}"), '<i class="icon icon-trash"></i>' . $this->lang->kanban->deleteRegion, "hiddenwin") . '</li>';?>
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
<?php else:?>
<div class='panel' id='kanbanContainer'>
  <div class='panel-body'>
    <div id='kanban'>
      <div class='region'>
        <div id='kanban<?php echo $execution->id;?>' data-id='<?php echo $execution->id;?>' class='kanban'></div>
      </div>
    </div>
  </div>
</div>
<?php endif;?>
<div class="modal fade" id="linkStoryByPlan">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->execution->linkStoryByPlan;?></h4><?php echo str_replace($lang->executionCommon, $kanban, $lang->execution->linkStoryByPlanTips);?>
      </div>
      <div class="modal-body">
        <div class='input-group'>
          <?php echo html::select('plan', $allPlans, '', "class='form-control chosen' id='plan'");?>
          <span class='input-group-btn'><?php echo html::commonButton($lang->execution->linkStory, "id='toStoryButton'", 'btn btn-primary');?></span>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="batchCreateStory">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->execution->batchCreateStoryTips;?></h4>
      </div>
      <div class="modal-body">
        <div class='input-group'>
          <?php echo html::select('products', $productNames, '', "class='form-control chosen' id='products'");?>
          <span class='input-group-btn'><?php echo html::a(helper::createLink('story', 'batchCreate', "productID=$productID&branch=$branchID&moduleID=0&story=0&execution=$execution->id", '', true), $lang->execution->batchCreateStory, '', "class='btn btn-primary iframe' data-width='90%' id='batchCreateStoryButton' data-dismiss='modal'");?></span>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="batchCreateBug">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->bug->product;?></h4>
      </div>
      <div class="modal-body">
        <div class='input-group'>
          <?php echo html::select('productName', $productNames, '', "class='form-control chosen' id='product'");?>
          <span class='input-group-btn'><?php echo html::a(helper::createLink('bug', 'batchCreate', 'productID=' . key($productNames) . '&branch=' . $branchID . '&executionID=' . $executionID,     '', true), $lang->bug->batchCreate, '', "id='batchCreateBugButton' class='btn btn-primary iframe' data-dismiss='modal' data-width='90%'");?></span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php js::set('taskToOpen', $taskToOpen);?>
<script>
$(function()
{
    /* Open the task details popup from dynamic. */
    if(taskToOpen)
    {
        var kanban   = document.querySelector('#kanban');
        var observer = new MutationObserver(function (mutationsList, observer)
        {
            for(var mutation of mutationsList)
            {
                var target = mutation.target;
                if(mutation.type == 'childList' && target.tagName.toLowerCase() == 'a' && target.classList.contains('title') && target.parentElement.parentElement.dataset.id == taskToOpen && target.parentElement.className.includes('kanban-item-task'))
                {
                    var a = document.querySelector('[data-id="' + taskToOpen +'"] a.title');
                    if(a)
                    {
                        observer.disconnect();
                        a.click();
                        break;
                    }
                }
            }
        });
        observer.observe(kanban, {subtree: true, childList: true});
    }
})
</script>
<?php include '../../common/view/footer.html.php';?>
