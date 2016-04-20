<div id='featurebar'>
  <ul class='nav'>
    <?php
    echo "<li id='listTab'>"; common::printLink('project', 'task', "project=$projectID&type=unclosed", $lang->project->list); echo '</li>';
    echo "<li id='kanbanTab'>"; common::printLink('product', 'kanban', "projectID=$projectID", $lang->project->kanban) . '</li>';
    if($project->type == 'sprint' or $project->type == 'waterfall') echo "<li id='burnTab'>"; common::printLink('project', 'burn', "project=$projectID", $lang->project->burn); echo '</li>';
    echo "<li id='treeTab'>"; common::printLink('project', 'tree', "projectID=$projectID", $lang->project->tree); echo '</li>';
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
        common::printLink('project', 'groupTask', "project=$projectID&groupBy=$key", $value);
    }
    echo '</ul></li>';
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
</div>
<?php
$headerHooks = glob(dirname(dirname(__FILE__)) . "/ext/view/featurebar.*.html.hook.php");
if(!empty($headerHooks))
{
    foreach($headerHooks as $fileName) include($fileName);
}
?>
