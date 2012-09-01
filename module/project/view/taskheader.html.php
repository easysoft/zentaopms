<div id='featurebar'>
  <div class='f-left'>
  <?php
    echo "<span id='allTab'>"         ; common::printLink('project', 'task', "project=$projectID&type=all",          $lang->project->allTasks);     echo  '</span>' ;
    echo "<span id='assignedtomeTab'>"; common::printLink('project', 'task', "project=$projectID&type=assignedtome", $lang->project->assignedToMe); echo  '</span>' ;
    echo "<span id='delayedTab'>"     ; common::printLink('project', 'task', "project=$projectID&type=delayed",      $lang->project->delayed);      echo  '</span>' ;

    echo "<span id='statusTab'>";
    echo html::select('status', $lang->project->statusSelects, isset($status) ? $status : '', "onchange='switchStatus({$projectID}, this.value)'");
    echo "</span>";

    echo "<span id='groupTab'>";
    echo html::select('groupBy', $lang->project->groups, isset($groupBy) ? $groupBy : '', "onchange='switchGroup($projectID, this.value)'");
    echo "</span>";

    echo "<span id='byprojectTab' onclick='browseByProject()'>"; common::printLink('project', 'task',"project=$projectID&type=byProject", $lang->project->projectTasks); echo '</span>';
    echo "<span id='bymoduleTab'  onclick='browseByModule()'>";  common::printLink('project', 'task',"project=$projectID&type=byModule", $lang->project->moduleTask); echo '</span>';
    echo "<span id='bysearchTab'><a href='#'><span class='icon-search'></span>{$lang->project->byQuery}</a></span> ";
    ?>
  </div>
  <div class='f-right'>
    <?php 
    if($browseType != 'needconfirm') common::printIcon('task', 'export', "projectID=$projectID&orderBy=$orderBy");
    common::printIcon('task', 'report', "project=$projectID&browseType=$browseType");
    common::printIcon('task', 'batchCreate', "projectID=$projectID");
    common::printIcon('task', 'create', "project=$projectID");
    ?>
  </div>
</div>
