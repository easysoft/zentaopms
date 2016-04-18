<div id='featurebar'>
  <ul class='nav'>
    <?php if(isset($moduleID)):?>
    <li>
      <span>
        <?php
        echo $moduleName;
        if($moduleID)
        {
            $removeLink = $browseType == 'bymodule' ? inlink('task', "projectID=$projectID&browseType=$status&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("taskModule")';
            echo '&nbsp;' . html::a($removeLink, "<i class='icon icon-remove'></i>") . '&nbsp;';
        }
        echo " <i class='icon-angle-right'></i>&nbsp; ";
        ?>
      </span>
    </li>
    <?php endif;?>
    <?php
    $hasBrowsePriv    = common::hasPriv('project', 'task');
    $hasKanbanPriv    = common::hasPriv('project', 'kanban');
    $hasBurnPriv      = common::hasPriv('project', 'burn');
    $hasGroupTaskPriv = common::hasPriv('project', 'groupTask');
    ?>
    <?php foreach($app->customMenu['featurebar'] as $type => $featurebar):?>
    <?php
    if($featurebar['status'] == 'hide') continue;
    if($hasBrowsePriv and ($type == 'unclosed' or $type == 'all' or $type == 'assignedtome')) echo "<li id='{$type}Tab'>" . html::a(inlink('task', "project=$projectID&type=$type"), $featurebar['link']) . '</li>' ;
    if($hasKanbanPriv and $type == 'kanban') echo "<li id='kanbanTab'>" . html::a(inlink('kanban', "projectID=$projectID"), $featurebar['link']) . '</li>';
    if($hasBurnPriv   and $type == 'burn' and ($project->type == 'sprint' or $project->type == 'waterfall')) echo "<li id='burnTab'>" . html::a(inlink('burn', "project=$projectID"), $featurebar['link']) . '</li>' ;
    if($hasBrowsePriv and $type == 'status')
    {
        echo "<li id='statusTab' class='dropdown'>";
        $taskBrowseType = isset($status) ? $this->session->taskBrowseType : '';
        $current        = zget($lang->project->statusSelects, $taskBrowseType, '');
        if(empty($current)) $current = $featurebar['link'];
        echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown'");
        echo "<ul class='dropdown-menu'>";
        foreach ($lang->project->statusSelects as $key => $value)
        {
            if($key == '') continue;
            echo '<li' . ($key == $taskBrowseType ? " class='active'" : '') . '>';
            echo html::a($this->createLink('project', 'task', "project=$projectID&type=$key"), $value);
        }
        echo '</ul></li>';
    }
    elseif($hasGroupTaskPriv and $type == 'group')
    {
        echo "<li id='groupTab' class='dropdown'>";
        $groupBy = isset($groupBy) ? $groupBy : '';
        $current = zget($lang->project->groups, isset($groupBy) ? $groupBy : '', '');
        if(empty($current)) $current = $featurebar['link'];
        echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown'");
        echo "<ul class='dropdown-menu'>";
        foreach ($lang->project->groups as $key => $value)
        {
            if($key == '') continue;
            echo '<li' . ($key == $groupBy ? " class='active'" : '') . '>';
            echo html::a($this->createLink('project', 'groupTask', "project=$projectID&groupBy=$key"), $value);
        }
        echo '</ul></li>';
    }
    ?>
    <?php endforeach;?>

    <?php
    if($this->methodName == 'task') echo "<li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;{$lang->project->byQuery}</a></li> ";
    ?>
  </ul>
  <div class='actions'>
    <div class='btn-group'>
      <?php 
      if(!isset($browseType)) $browseType = '';
      if(!isset($orderBy))    $orderBy = '';
      common::printIcon('task', 'report', "project=$projectID&browseType=$browseType");
      ?>

      <div class='btn-group'>
        <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' id='exportAction'>
            <i class='icon-download-alt'></i> <?php echo $lang->export ?>
            <span class='caret'></span>
        </button>
        <ul class='dropdown-menu' id='exportActionMenu'>
        <?php 
        $misc = common::hasPriv('task', 'export') ? "class='export iframe' data-width='700'" : "class=disabled";
        $link = common::hasPriv('task', 'export') ?  $this->createLink('task', 'export', "project=$projectID&orderBy=$orderBy") : '#';
        echo "<li>" . html::a($link, $lang->task->export, '', $misc) . "</li>";
        ?>
        </ul>
      </div>

      <div class='btn-group'>
        <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' id='importAction'>
            <i class='icon-upload-alt'></i> <?php echo $lang->import ?>
            <span class='caret'></span>
        </button>
        <ul class='dropdown-menu' id='importActionMenu'>
        <?php 
        $misc = common::hasPriv('project', 'importTask') ? '' : "class=disabled";
        $link = common::hasPriv('project', 'importTask') ?  $this->createLink('project', 'importTask', "project=$project->id") : '#';
        echo "<li>" . html::a($link, $lang->project->importTask, '', $misc) . "</li>";

        $misc = common::hasPriv('project', 'importBug') ? '' : "class=disabled";
        $link = common::hasPriv('project', 'importBug') ?  $this->createLink('project', 'importBug', "project=$project->id") : '#';
        echo "<li>" . html::a($link, $lang->project->importBug, '', $misc) . "</li>";
        ?>
        </ul>
      </div>
    </div>
    <div class='btn-group'>
    <?php
    common::printIcon('task', 'batchCreate', "projectID=$projectID");
    common::printIcon('task', 'create', "project=$projectID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : ''), '', 'button', 'sitemap');
    ?>
    </div>
  </div>
  <div id='querybox' class='<?php if($browseType == 'bysearch') echo 'show';?>'></div>
</div>
<?php
$headerHooks = glob(dirname(dirname(__FILE__)) . "/ext/view/featurebar.*.html.hook.php");
if(!empty($headerHooks))
{
    foreach($headerHooks as $fileName) include($fileName);
}
?>
