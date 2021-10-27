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
    // TODO 暂时解决样式问题
    echo "<ul class='dropdown-menu pull-right' style='position: relative'>";
    if(common::hasPriv('execution', 'printKanban')) echo '<li>' .html::a($this->createLink('execution', 'printKanban', "executionID=$executionID"), "<i class='icon-printer muted'></i> " . $lang->execution->printKanban, '', "class='iframe btn btn-link' id='printKanban' title='{$lang->execution->printKanban}' data-width='500'") . '</li>';
    echo '<li>' .html::a('javascript:fullScreen()', "<i class='icon-fullscreen muted'></i> " . $lang->execution->fullScreen, '', "class='btn btn-link' title='{$lang->execution->fullScreen}' data-width='500'") . '</li>';
    echo '</ul></div>';
?>
    <?php
    $checkObject = new stdclass();
    $checkObject->execution = $executionID;
    $misc = common::hasPriv('task', 'create', $checkObject) ? "class='btn btn-primary iframe' data-width='1200px'" : "class='btn btn-primary disabled'";
    $link = common::hasPriv('task', 'create', $checkObject) ?  $this->createLink('task', 'create', "execution=$executionID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : ''), '', true) : '#';
    echo html::a($link, "<i class='icon icon-plus'></i> " . $lang->task->create, '', $misc);
    ?>
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
<?php js::set('executionID', $executionID);?>
<?php js::set('kanbanGroup', $kanbanGroup);?>
<?php include '../../common/view/footer.html.php';?>
