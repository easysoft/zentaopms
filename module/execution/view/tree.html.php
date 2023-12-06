<?php
/**
 * The execution tree view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     execution
 * @version     $Id: tree.html.php 4894 2013-06-25 01:28:39Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    foreach($lang->execution->treeLevel as $name => $btnLevel)
    {
        if(empty($tree) && ($name == 'root' or $name == 'all')) continue;
        $icon = '';
        if($name == 'root') $icon = ' <i class="icon-fold-all"></i>';
        if($name == 'all')  $icon = ' <i class="icon-unfold-all"></i>';
        echo html::a('javascript:;', "<span class='text'>$btnLevel$icon</span>", '', "class='btn btn-link btn-tree-view' data-type='{$name}'");
    }
    ?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php
    if(!isset($browseType)) $browseType = 'all';
    if(!isset($orderBy))    $orderBy = '';
    $link = common::hasPriv('task', 'report', $execution) ?  $this->createLink('task', 'report', "execution=$executionID&browseType=$browseType") : '#';
    echo html::a($link, "<i class='icon icon-bar-chart muted'></i> <span class='text'>{$lang->task->reportChart}</span>", '', 'class="btn btn-link"');
    ?>
    <?php if(common::canModify('execution', $execution)):?>
    <div class="btn-group">
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-import muted"></i> <span class="text"><?php echo $lang->import ?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu">
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
    <?php endif;?>
    <?php
    $misc = "class='btn btn-link iframe" . (common::hasPriv('task', 'export', $execution) ? '' : ' disabled') . "' data-width='700'";
    $link = common::hasPriv('task', 'export') ? $this->createLink('task', 'export', "execution=$executionID&orderBy=$orderBy&type=tree") : '#';
    echo html::a($link, "<i class='icon icon-export muted'></i> <span class='text'>{$lang->export}</span>", '', $misc);

    $checkObject = new stdclass();
    $checkObject->execution = $executionID;
    $misc = common::hasPriv('task', 'create', $checkObject) ? "class='btn btn-primary'" : "class='btn btn-primary disabled'";
    $link = common::hasPriv('task', 'create', $checkObject) ?  $this->createLink('task', 'create', "execution=$executionID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : '')) : '#';
    echo html::a($link, "<i class='icon icon-plus'></i> " . $lang->task->create, '', $misc);
    ?>
  </div>
</div>

<div id="mainContent" class="main-row hide-side">
  <?php if(empty($tree)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->task->noTask;?></span>
      <?php if(common::hasPriv('task', 'create', $checkObject)):?>
      <?php echo html::a($this->createLink('task', 'create', "execution=$executionID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : '')), "<i class='icon icon-plus'></i> " . $lang->task->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <div class="main-col">
    <div class="cell">
      <ul class="tree" id="taskTree">
        <?php echo $tree;?>
      </ul>
    </div>
  </div>
  <div class="side-col">
    <div class="cell">
      <div id="itemContent" class="load-indicator loading"></div>
    </div>
  </div>
  <?php endif;?>
</div>
<?php js::set('type', $level);?>
<?php js::set('collapse', false);?>
<script>
$(function()
{
    $('[data-type=<?php echo $level;?>]').addClass('btn-active-text');
})
</script>
<?php include '../../common/view/footer.html.php';?>
