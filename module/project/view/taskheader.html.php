<div id='featurebar'>
  <div class='f-left'>
  <?php
    echo "<span id='allTab'>"; common::printLink('project', 'task', "project=$projectID&type=all", $lang->project->allTasks); echo '</span>' ;
    if($project->type == 'sprint') print "<span id='burnTab'>" and common::printLink('project', 'burn', "project=$projectID", $lang->project->burn); print '</span>' ;
    echo "<span id='assignedtomeTab'>"; common::printLink('project', 'task', "project=$projectID&type=assignedtome", $lang->project->assignedToMe); echo  '</span>' ;

    echo "<span id='statusTab'>";
    echo html::select('status', $lang->project->statusSelects, isset($status) ? $status : '', "onchange='switchStatus({$projectID}, this.value)'");
    echo "</span>";

    echo "<span id='groupTab'>";
    echo html::select('groupBy', $lang->project->groups, isset($groupBy) ? $groupBy : '', "onchange='switchGroup($projectID, this.value)'");
    echo "</span>";

    if($this->methodName == 'task') echo "<span id='bysearchTab'><a href='#'><span class='icon-search'></span>{$lang->project->byQuery}</a></span> ";
    ?>
  </div>
  <div class='f-right'>
    <?php 
    if(!isset($browseType)) $browseType = '';
    if(!isset($orderBy))    $orderBy = '';
    common::printIcon('task', 'report', "project=$projectID&browseType=$browseType");
    if($browseType != 'needconfirm') common::printIcon('task', 'export', "projectID=$projectID&orderBy=$orderBy");
    common::printIcon('task', 'batchCreate', "projectID=$projectID");
    common::printIcon('task', 'create', "project=$projectID");
    ?>
  </div>
</div>
<?php foreach(glob(dirname(dirname(__FILE__)) . "/ext/view/featurebar.*.html.hook.php") as $fileName) include_once $fileName; ?>
