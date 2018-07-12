<div id='featurebar'>
  <ul class='nav'>
    <?php
    echo '<li>';
    if(!empty($productID))
    {
        echo '<div class="label-angle with-close">';
        $product    = $this->product->getById($productID);
        $removeLink = $browseType == 'byproduct' ? inlink('task', "projectID=$projectID&browseType=$status&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("productBrowseParam")';
        echo '<i class="icon icon-cube"></i> ' . $product->name;
        echo html::a($removeLink, "<span class='close'><i class='icon icon-close'></i></span>", '', "class='text-muted'");
    }
    elseif(!empty($moduleID))
    {
        echo '<div class="label-angle with-close">';
        $module     = $this->tree->getById($moduleID);
        $removeLink = $browseType == 'bymodule' ? inlink('task', "projectID=$projectID&browseType=$status&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("moduleBrowseParam")';
        echo $module->name;
        echo html::a($removeLink, "<span class='close'><i class='icon icon-close'></i></span>", '', "class='text-muted'");
    }
    else
    {
        echo '<div class="label-angle">';
        $this->app->loadLang('tree');
        echo $this->lang->tree->all;
    }
    echo "</div></li>";

    foreach(customModel::getFeatureMenu('project', 'task') as $menuItem)
    {
        if($project->type == 'ops' && $menuItem->name == 'needconfirm') continue;
        if(isset($menuItem->hidden)) continue;
        $menuType = $menuItem->name;
        if(strpos($menuType, 'QUERY') === 0)
        {
            $queryID = (int)substr($menuType, 5);
            echo "<li id='{$menuType}Tab'>" . html::a(inlink('task', "project=$projectID&type=bySearch&param=$queryID"), $menuItem->text) . '</li>' ;
        }
        elseif($menuType != 'status')
        {
            echo "<li id='{$menuType}Tab'>" . html::a(inlink('task', "project=$projectID&type=$menuType"), $menuItem->text) . '</li>' ;
        }
        elseif($menuType == 'status')
        {
            echo "<li id='statusTab' class='dropdown'>";
            $taskBrowseType = isset($status) ? $this->session->taskBrowseType : '';
            $current        = zget($lang->project->statusSelects, $taskBrowseType, '');
            if(empty($current)) $current = $menuItem->text;
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
    }

    echo "<li id='kanbanTab'>"; common::printLink('project', 'kanban', "projectID=$projectID", $lang->project->kanban); echo '</li>';
    if($project->type == 'sprint' or $project->type == 'waterfall')
    {
        echo "<li id='burnTab'>";
        common::printLink('project', 'burn', "project=$projectID", $lang->project->burn);
        echo '</li>';
    }
    echo "<li id='treeTab'>"; common::printLink('project', 'tree', "projectID=$projectID", $lang->project->tree); echo '</li>';
    echo "<li id='groupTab' class='dropdown'>";
    $groupBy = isset($groupBy) ? $groupBy : '';
    $current = zget($lang->project->groups, isset($groupBy) ? $groupBy : '', '');
    echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown'");
    echo "<ul class='dropdown-menu'>";
    foreach ($lang->project->groups as $key => $value)
    {
        if($key == '') continue;
        if($project->type == 'ops' && $key == 'story') continue;
        echo '<li' . ($key == $groupBy ? " class='active'" : '') . '>';
        common::printLink('project', 'groupTask', "project=$projectID&groupBy=$key", $value);
    }
    echo '</ul></li>';
    if($this->methodName === 'task') echo "<li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;{$lang->project->byQuery}</a></li> ";
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
        <button type='button' class='btn dropdown-toggle' data-toggle='dropdown' id='exportAction'>
            <i class='icon-download-alt'></i> <?php echo $lang->export ?>
            <span class='caret'></span>
        </button>
        <ul class='dropdown-menu' id='exportActionMenu'>
        <?php 
        $misc = common::hasPriv('task', 'export') ? "class='export'" : "class=disabled";
        $link = common::hasPriv('task', 'export') ? $this->createLink('task', 'export', "project=$projectID&orderBy=$orderBy&type=$browseType") : '#';
        echo "<li>" . html::a($link, $lang->task->export, '', $misc) . "</li>";
        ?>
        </ul>
      </div>

      <div class='btn-group'>
        <button type='button' class='btn dropdown-toggle' data-toggle='dropdown' id='importAction'>
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
      <div class='btn-group' id='createActionMenu'>
        <?php 
        $checkObject = new stdclass();
        $checkObject->project = $projectID;
        $misc = common::hasPriv('task', 'create', $checkObject) ? "class='btn btn-primary'" : "class='btn btn-primary disabled'";
        $link = common::hasPriv('task', 'create', $checkObject) ?  $this->createLink('task', 'create', "project=$projectID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : '')) : '#';
        echo html::a($link, "<i class='icon icon-plus'></i>" . $lang->task->create, '', $misc);

        $misc = common::hasPriv('task', 'batchCreate', $checkObject) ? '' : "disabled";
        $link = common::hasPriv('task', 'batchCreate', $checkObject) ?  $this->createLink('task', 'batchCreate', "project=$projectID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : '')) : '#';
        ?>
        <button type='button' class='btn btn-primary dropdown-toggle <?php echo $misc?>' data-toggle='dropdown'><span class='caret'></span></button>
        <ul class='dropdown-menu pull-right'>
        <?php echo "<li>" . html::a($link, $lang->task->batchCreate, '', "class='$misc'") . "</li>";?>
        </ul>
      </div>
    </div>
  </div>
  <div id='queryBox' class='<?php if($browseType =='bysearch') echo 'show';?>'></div>
</div>
<?php
$headerHooks = glob(dirname(dirname(__FILE__)) . "/ext/view/featurebar.*.html.hook.php");
if(!empty($headerHooks))
{
    foreach($headerHooks as $fileName) include($fileName);
}
?>
