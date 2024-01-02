<?php
/**
 * The task kanban view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong
 * @package     execution
 * @version     $Id: taskkanban.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kanban.html.php';?>
<?php if($groupBy == 'story' and $browseType == 'task'):?>
<style>
.kanban-cols {left: 0px !important;}
</style>
<?php endif;?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php if($features['qa']):?>
    <div class="input-control space c-type">
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
    <div class="input-group" id="taskKanbanSearch">
      <div class="input-control search-box" id="searchBox">
      <input type="text" name="taskKanbanSearchInput" id="taskKanbanSearchInput" value="" class="form-control" oninput="searchCards(this.value)" placeholder="<?php echo $lang->execution->pleaseInput;?>" autocomplete="off">
      </div>
    </div>
    <?php
    echo html::a('javascript:toggleSearchBox()', "<i class='icon-search muted'></i> " . $lang->searchAB, '', "class='btn btn-link querybox-toggle'");
    $link = $this->createLink('task', 'export', "execution=$executionID&orderBy=$orderBy&type=unclosed");
    if(common::hasPriv('task', 'export')) echo html::a($link, "<i class='icon-export muted'></i> " . $lang->export, '', "class='btn btn-link iframe export' data-width='700'");
    ?>
    <?php if($canBeChanged):?>
    <div class='btn-group' style="margin-right: 0">
      <button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown' id='importAction'>
        <i class='icon-import muted'></i> <?php echo $lang->import ?>
        <span class='caret'></span>
      </button>
      <ul class='dropdown-menu' id='importActionMenu'>
        <?php
        $misc = common::hasPriv('execution', 'importTask') ? '' : "class=disabled";
        $link = common::hasPriv('execution', 'importTask') ?  $this->createLink('execution', 'importTask', "execution=$execution->id") : '#';
        echo "<li $misc>" . html::a($link, $lang->execution->importTask, '', $misc) . "</li>";

        if($features['qa'])
        {
            $misc = common::hasPriv('execution', 'importBug') ? '' : "class=disabled";
            $link = common::hasPriv('execution', 'importBug') ?  $this->createLink('execution', 'importBug', "execution=$execution->id") : '#';
            echo "<li $misc>" . html::a($link, $lang->execution->importBug, '', $misc) . "</li>";
        }
        ?>
      </ul>
    </div>

    <?php
    $width = common::checkNotCN() ? '850px' : '700px';
    echo "<div class='btn-group menu-actions'>";
    echo html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");
    echo "<ul class='dropdown-menu pull-right'>";
    if(common::hasPriv('execution', 'setKanban'))   echo '<li>' . html::a(helper::createLink('execution', 'setKanban', "executionID=$execution->id", '', true), '<i class="icon icon-cog-outline"></i>' . $lang->execution->setKanban, '', "class='iframe btn btn-link text-left' data-width='$width'") . '</li>';
    if(common::hasPriv('execution', 'printKanban')) echo '<li>' . html::a($this->createLink('execution', 'printKanban', "executionID=$executionID"), "<i class='icon icon-printer muted'></i>" . $lang->execution->printKanban, '', "class='iframe btn btn-link' id='printKanban' title='{$lang->execution->printKanban}' data-width='500'") . '</li>';
    echo '<li>' .html::a('javascript:fullScreen()', "<i class='icon icon-fullscreen muted'></i>" . $lang->execution->fullScreen, '', "class='btn btn-link' title='{$lang->execution->fullScreen}' data-width='500'") . '</li>';
    echo '</ul></div>';
    ?>
    <?php
    $checkObject = new stdclass();
    $checkObject->execution = $executionID;
    $canCreateTask       = common::hasPriv('task', 'create', $checkObject);
    $canBatchCreateTask  = common::hasPriv('task', 'batchCreate', $checkObject);
    $canCreateBug        = ($productID and common::hasPriv('bug', 'create'));
    $canBatchCreateBug   = ($productID and common::hasPriv('bug', 'batchCreate'));
    $canImportBug        = ($productID and common::hasPriv('execution', 'importBug'));
    $canCreateStory      = ($productID and common::hasPriv('story', 'create'));
    $canBatchCreateStory = ($productID and common::hasPriv('story', 'batchCreate'));
    $canLinkStory        = ($productID and common::hasPriv('execution', 'linkStory') and !empty($execution->hasProduct));
    $canLinkStoryByPlan  = ($productID and common::hasPriv('execution', 'importplanstories') and !$hiddenPlan and !empty($execution->hasProduct));
    $hasStoryButton      = ($canCreateStory or $canBatchCreateStory or $canLinkStory or $canLinkStoryByPlan);
    $hasTaskButton       = ($canCreateTask or $canBatchCreateTask or $canImportBug);
    $hasBugButton        = ($canCreateBug or $canBatchCreateBug);
    ?>
    <?php if($canCreateTask or $canBatchCreateTask or $canImportBug or $canCreateBug or $canBatchCreateBug or $canCreateStory or $canBatchCreateStory or $canLinkStory or $canLinkStoryByPlan):?>
    <div class='dropdown' id='createDropdown'>
      <button class='btn btn-primary' type='button' data-toggle='dropdown'><i class='icon icon-plus'></i> <?php echo $this->lang->create;?> <span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <?php $showDivider = false;?>
        <?php if($features['story'] and $hasStoryButton):?>
        <?php if($canCreateStory) echo '<li>' . html::a(helper::createLink('story', 'create', "productID=$productID&branch=0&moduleID=0&story=0&execution=$execution->id", '', true), $lang->execution->createStory, '', "class='iframe' data-width='80%'") . '</li>';?>
        <?php if($canBatchCreateStory) echo '<li>' . html::a(helper::createLink('story', 'batchCreate', "productID=$productID&branch=0&moduleID=0&story=0&execution=$execution->id", '', true), $lang->execution->batchCreateStory, '', "class='iframe' data-width='90%'") . '</li>';?>
        <?php if($canLinkStory) echo '<li>' . html::a(helper::createLink('execution', 'linkStory', "execution=$execution->id", '', true), $lang->execution->linkStory, '', "class='iframe' data-width='90%'") . '</li>';?>
        <?php if($canLinkStoryByPlan) echo '<li>' . html::a('#linkStoryByPlan', $lang->execution->linkStoryByPlan, '', 'data-toggle="modal"') . '</li>';?>
        <?php $showDivider = true;?>
        <?php endif;?>
        <?php if($features['qa']):?>
        <?php if($showDivider) echo '<li class="divider"></li>';?>
        <?php if($canCreateBug) echo '<li>' . html::a(helper::createLink('bug', 'create', "productID=$productID&branch=0&extra=executionID=$execution->id", '', true), $lang->bug->create, '', "class='iframe'") . '</li>';?>
        <?php if($canBatchCreateBug)
        {
            $batchCreateBugLink = '<li>' . html::a(helper::createLink('bug', 'batchCreate', "productID=$productID&branch=0&executionID=$execution->id", '', true), $lang->bug->batchCreate, '', "class='iframe'") . '</li>';
            if($productNum > 1) $batchCreateBugLink = '<li>' . html::a('#batchCreateBug', $lang->bug->batchCreate, '', "data-toggle='modal'") . '</li>';
            echo $batchCreateBugLink;
        }?>
        <?php if($canImportBug) echo '<li>' . html::a(helper::createLink('execution', 'importBug', "execution=$execution->id", '', true), $lang->execution->importBug, '', "class='iframe' data-width='90%'") . '</li>';?>
        <?php endif;?>
        <?php if($showDivider) echo '<li class="divider"></li>';?>
        <?php if($canCreateTask) echo '<li>' . html::a(helper::createLink('task', 'create', "execution=$execution->id", '', true), $lang->task->create, '', "class='iframe' data-width='80%'") . '</li>';?>
        <?php if($canBatchCreateTask) echo '<li>' . html::a(helper::createLink('task', 'batchCreate', "execution=$execution->id", '', true), $lang->execution->batchCreateTask, '', "class='iframe' data-width=90%") . '</li>';?>
      </ul>
    </div>
    <?php endif;?>
    <?php else:?>
    <?php $canCreateTask = $canBatchCreateTask = $canImportBug = $canCreateBug = $canBatchCreateBug = $canCreateStory = $canBatchCreateStory = $canLinkStory = $canLinkStoryByPlan = false;?>
    <?php endif;?>
  </div>
</div>

<div class='panel' id='kanbanContainer'>
  <div class='panel-body'>
    <div id='kanbans'></div>
  </div>
  <div class='table-empty-tip hidden' id='emptyBox'>
    <p><span class="text-muted"><?php echo $lang->kanbancard->empty;?></span></p>
  </div>
</div>

<div class="modal fade" id="linkStoryByPlan">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->execution->linkStoryByPlan;?></h4><?php echo '(' . $lang->execution->linkStoryByPlanTips . ')';?>
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
          <span class='input-group-btn'><?php echo html::a(helper::createLink('bug', 'batchCreate', 'productID=' . key($productNames) . '&branch=&executionID=' . $executionID, '', true), $lang->bug->batchCreate, '', "id='batchCreateBugButton' class='btn btn-primary iframe' data-dismiss='modal' data-width='90%'");?></span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php js::set('executionID', $executionID);?>
<?php js::set('productID', $productID);?>
<?php js::set('kanbanGroup', $kanbanGroup);?>
<?php js::set('kanbanList', array_keys($kanbanGroup));?>
<?php js::set('browseType', $browseType);?>
<?php js::set('groupBy', $groupBy);?>
<?php js::set('productNum', $productNum);?>
<?php js::set('searchValue', '');?>
<?php
js::set('priv',
    array(
        'canEditName'         => common::hasPriv('kanban', 'setColumn'),
        'canSetWIP'           => common::hasPriv('kanban', 'setWIP'),
        'canSetLane'          => common::hasPriv('kanban', 'setLane'),
        'canSortCards'        => common::hasPriv('kanban', 'cardsSort'),
        'canCreateTask'       => $canCreateTask,
        'canBatchCreateTask'  => $canBatchCreateTask,
        'canImportBug'        => $canImportBug,
        'canCreateBug'        => $canCreateBug,
        'canBatchCreateBug'   => $canBatchCreateBug,
        'canCreateStory'      => $canCreateStory,
        'canBatchCreateStory' => $canBatchCreateStory,
        'canLinkStory'        => $canLinkStory,
        'canLinkStoryByPlan'  => $canLinkStoryByPlan,
        'canAssignTask'       => common::hasPriv('task', 'assignto'),
        'canAssignStory'      => common::hasPriv('story', 'assignto'),
        'canFinishTask'       => common::hasPriv('task', 'finish'),
        'canPauseTask'        => common::hasPriv('task', 'pause'),
        'canCancelTask'       => common::hasPriv('task', 'cancel'),
        'canCloseTask'        => common::hasPriv('task', 'close'),
        'canActivateTask'     => common::hasPriv('task', 'activate'),
        'canStartTask'        => common::hasPriv('task', 'start'),
        'canAssignBug'        => common::hasPriv('bug', 'assignto'),
        'canConfirmBug'       => common::hasPriv('bug', 'confirmBug'),
        'canActivateBug'      => common::hasPriv('bug', 'activate'),
        'canCloseStory'       => common::hasPriv('story', 'close')
    )
);
?>
<?php js::set('executionLang', $lang->execution);?>
<?php js::set('storyLang', $lang->story);?>
<?php js::set('taskLang', $lang->task);?>
<?php js::set('bugLang', $lang->bug);?>
<?php js::set('editName', $lang->execution->editName);?>
<?php js::set('setWIP', $lang->execution->setWIP);?>
<?php js::set('sortColumn', $lang->execution->sortColumn);?>
<?php js::set('kanbanLang', $lang->kanban);?>
<?php js::set('deadlineLang', $lang->task->deadlineAB);?>
<?php js::set('estStartedLang', $lang->task->estStarted);?>
<?php js::set('noAssigned', $lang->task->noAssigned);?>
<?php js::set('userList', $userList);?>
<?php js::set('entertime', time());?>
<?php js::set('fluidBoard', $execution->fluidBoard);?>
<?php js::set('minColWidth', $execution->fluidBoard == '0' ? $execution->colWidth : $execution->minColWidth);?>
<?php js::set('maxColWidth',$execution->fluidBoard == '0' ? $execution->colWidth : $execution->maxColWidth);?>
<?php js::set('displayCards', $execution->displayCards);?>
<?php js::set('needLinkProducts', $lang->execution->needLinkProducts);?>
<?php js::set('hourUnit', $config->hourUnit);?>
<?php js::set('orderBy', $storyOrder);?>
<?php js::set('defaultMinColWidth', $this->config->minColWidth);?>
<?php js::set('defaultMaxColWidth', $this->config->maxColWidth);?>
<?php js::set('teamWords', $lang->execution->teamWords);?>
<?php js::set('canImportBug', $features['qa']);?>
<?php js::set('canBeChanged', $canBeChanged);?>

<?php include '../../common/view/footer.html.php';?>
