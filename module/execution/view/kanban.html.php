<?php
/**
 * The kanban view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong
 * @package     execution
 * @version     $Id: kanban.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kanban.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <div class="input-control space c-type">
      <i class="icon icon-list-all"></i>
      <?php echo html::select('type', $lang->kanban->type, $type, 'class="form-control chosen" data-max_drop_width="215"');?>
    </div>
    <div class="input-control space c-group">
      <?php echo html::select('group',  $lang->kanban->group->$type, $groupBy, 'class="form-control chosen" data-max_drop_width="215"');?>
    </div>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php
    $link = $this->createLink('task', 'export', "execution=$executionID&orderBy=$orderBy&type=kanban");
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

        $misc = common::hasPriv('execution', 'importBug') ? '' : "class=disabled";
        $link = common::hasPriv('execution', 'importBug') ?  $this->createLink('execution', 'importBug', "execution=$execution->id") : '#';
        echo "<li $misc>" . html::a($link, $lang->execution->importBug, '', $misc) . "</li>";
        ?>
      </ul>
    </div>

    <?php
    echo "<div class='btn-group menu-actions'>";
    echo html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");
    echo "<ul class='dropdown-menu pull-right'>";
    if(common::hasPriv('execution', 'printKanban')) echo '<li>' .html::a($this->createLink('execution', 'printKanban', "executionID=$executionID"), "<i class='icon-printer muted'></i> " . $lang->execution->printKanban, '', "class='iframe btn btn-link' id='printKanban' title='{$lang->execution->printKanban}' data-width='500'") . '</li>';
    echo '<li>' .html::a('javascript:fullScreen()', "<i class='icon-fullscreen muted'></i> " . $lang->execution->fullScreen, '', "class='btn btn-link' title='{$lang->execution->fullScreen}' data-width='500'") . '</li>';
    echo '</ul></div>';
?>
    <?php
    $checkObject = new stdclass();
    $checkObject->execution = $executionID;
    $canCreateTask       = common::hasPriv('task',  'create', $checkObject);
    $canBatchCreateTask  = common::hasPriv('task',  'batchCreate', $checkObject);
    $canCreateBug        = common::hasPriv('bug',   'create');
    $canBatchCreateBug   = common::hasPriv('bug',   'batchCreate');
    $canCreateStory      = ($productID and common::hasPriv('story', 'create'));
    $canBatchCreateStory = ($productID and common::hasPriv('story', 'batchCreate'));
    $canLinkStory        = ($productID and common::hasPriv('execution', 'linkStory'));
    $canLinkStoryByPlane = ($productID and common::hasPriv('execution', 'story'));
    ?>
    <?php if($canCreateTask or $canBatchCreateTask or $canCreateBug or $canBatchCreateBug or $canCreateStory or $canBatchCreateStory or $canLinkStory or $canLinkStoryByPlane):?>
    <div class='dropdown' id='createDropdown'>
      <button class='btn btn-primary' type='button' data-toggle='dropdown'><i class='icon icon-plus'></i> <?php echo $this->lang->create;?> <span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <?php if($canCreateStory) echo '<li>' . html::a(helper::createLink('story', 'create', "productID=$productID&branch=0&moduleID=0&story=0&execution=$execution->id"), $lang->story->create, '', "data-app='execution'") . '</li>';?>
        <?php if($canBatchCreateStory) echo '<li>' . html::a(helper::createLink('story', 'batchCreate', "productID=$productID&branch=0&moduleID=0&story=0&execution=$execution->id"), $lang->story->batchCreate, '', "data-app='execution'") . '</li>';?>
        <?php if($canLinkStory) echo '<li>' . html::a(helper::createLink('execution', 'linkStory', "execution=$execution->id", ''), $lang->execution->linkStory, '', "data-app='execution'") . '</li>';?>
        <?php if($canLinkStoryByPlane) echo '<li>' . html::a('#linkStoryByPlan', $lang->execution->linkStoryByPlan, '', 'data-toggle="modal"') . '</li>';?>
        <?php if(($canCreateStory or $canBatchCreateStory or $canLinkStory or $canLinkStoryByPlane) and ($canCreateTask or $canBatchCreateTask)) echo '<li class="divider"></li>';?>
        <?php if($canCreateTask) echo '<li>' . html::a(helper::createLink('task', 'create', "execution=$execution->id"), $lang->task->create) . '</li>';?>
        <?php if($canBatchCreateTask) echo '<li>' . html::a(helper::createLink('task', 'batchCreate', "execution=$execution->id"), $lang->task->batchCreate) . '</li>';?>
        <?php if(($canCreateTask or $canBatchCreateTask) and ($canCreateBug or $canBatchCreateBug)) echo '<li class="divider"></li>';?>
        <?php if($canCreateBug) echo '<li>' . html::a(helper::createLink('bug', 'create', "productID=$productID&branch=0&extra=execution=$execution->id"), $lang->task->create, '', "data-app='execution'") . '</li>';?>
        <?php if($canBatchCreateBug) echo '<li>' . html::a(helper::createLink('bug', 'batchCreate', "execution=$execution->id"), $lang->task->batchCreate, '', "data-app='execution'") . '</li>';?>
      </ul>
    </div>
    <?php endif;?>
    <?php endif;?>
  </div>
</div>

<div class='panel' id='kanbanContainer'>
  <div class='panel-heading'>
    <strong>Section</strong>
  </div>
  <div class='panel-body'>
    <div id='kanbans'></div>
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
          <span class='input-group-btn'><?php echo html::commonButton($lang->execution->linkStory, "id='toTaskButton'", 'btn btn-primary');?></span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php js::set('executionID', $executionID);?>
<?php js::set('kanbanGroup', $kanbanGroup);?>
<?php include '../../common/view/footer.html.php';?>
