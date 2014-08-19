<div id='featurebar'>
  <ul class='nav'>
  <?php
    echo "<li id='unclosedTab'>"; common::printLink('project', 'task', "project=$projectID&type=unclosed", $lang->project->unclosed); echo '</li>' ;
    echo "<li id='allTab'>"; common::printLink('project', 'task', "project=$projectID&type=all", $lang->project->allTasks); echo '</li>' ;
    if($project->type == 'sprint' or $project->type == 'waterfall') print "<li id='burnTab'>" and common::printLink('project', 'burn', "project=$projectID", $lang->project->burn); print '</li>' ;
    echo "<li id='assignedtomeTab'>"; common::printLink('project', 'task', "project=$projectID&type=assignedtome", $lang->project->assignedToMe); echo  '</li>' ;

    echo "<li id='statusTab' class='dropdown'>";
    $status = isset($status) ? $status : '';
    $current = zget($lang->project->statusSelects, $status, '');
    if(empty($current)) $current = $lang->project->statusSelects[''];
    echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown'");
    echo "<ul class='dropdown-menu'>";
    foreach ($lang->project->statusSelects as $key => $value)
    {
        if($key == '') continue;
        echo '<li' . ($key == $status ? " class='active'" : '') . '>';
        echo html::a($this->createLink('project', 'task', "project=$projectID&type=$key"), $value);
    }
    echo '</ul></li>';

    echo "<li id='groupTab' class='dropdown'>";
    $groupBy = isset($groupBy) ? $groupBy : '';
    $current = zget($lang->project->groups, isset($groupBy) ? $groupBy : '', '');
    if(empty($current)) $current = $lang->project->groups[''];
    echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown'");
    echo "<ul class='dropdown-menu'>";
    foreach ($lang->project->groups as $key => $value)
    {
        if($key == '') continue;
        echo '<li' . ($key == $groupBy ? " class='active'" : '') . '>';
        echo html::a($this->createLink('project', 'groupTask', "project=$projectID&groupBy=$key"), $value);
    }
    echo '</ul></li>';


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
    common::printIcon('task', 'create', "project=$projectID", '', 'button', 'sitemap');
    ?>
    </div>
  </div>
  <div id='querybox' class='<?php if($browseType == 'bysearch') echo 'show';?>'></div>
</div>

<?php foreach(glob(dirname(dirname(__FILE__)) . "/ext/view/featurebar.*.html.hook.php") as $fileName) include_once $fileName; ?>
