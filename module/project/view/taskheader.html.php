<div id='featurebar'>
  <div class='f-left'>
  <?php
  echo "<span id='allTab'>"         ; common::printLink('project', 'task', "project=$project->id",                   $lang->project->allTasks);     echo  '</span>' ;
  echo "<span id='assignedtomeTab'>"; common::printLink('project', 'task', "project=$project->id&type=assignedtome", $lang->project->assignedToMe); echo  '</span>' ;
  echo "<span id='finishedbymeTab'>"; common::printLink('project', 'task', "project=$project->id&type=finishedbyme", $lang->project->finishedByMe); echo  '</span>' ;
  echo "<span id='waitTab'>"        ; common::printLink('project', 'task', "project=$project->id&type=wait",         $lang->project->statusWait);   echo  '</span>' ;
  echo "<span id='doingTab'>"       ; common::printLink('project', 'task', "project=$project->id&type=doing",        $lang->project->statusDoing);  echo  '</span>' ;
  echo "<span id='doneTab'>"        ; common::printLink('project', 'task', "project=$project->id&type=done",         $lang->project->statusDone);   echo  '</span>' ;
  echo "<span id='closedTab'>"      ; common::printLink('project', 'task', "project=$project->id&type=closed",       $lang->project->statusClosed); echo  '</span>' ;
  echo "<span id='delayedTab'>"     ; common::printLink('project', 'task', "project=$project->id&type=delayed",      $lang->project->delayed);      echo  '</span>' ;

  echo "<span id='groupTab'>";
  echo html::select('groupBy', $lang->project->groups, isset($groupBy) ? $groupBy : '', "onchange='switchGroup({$project->id}, this.value)'");
  echo "</span>";
  echo "<span id='needconfirmTab'>"; common::printLink('project', 'task',  "project=$project->id&status=needConfirm",$lang->project->listTaskNeedConfrim); echo  '</span>' ;
  echo "<span id='bysearchTab'><a href='#'>{$lang->project->byQuery}</a></span> ";
  ?>
  </div>
  <div class='f-right'>
    <?php 
    if($browseType != 'needconfirm') common::printLink('task', 'export', "projectID=$projectID&orderBy=$orderBy", $lang->export, '', 'class="export"');
    common::printLink('project', 'importTask', "project=$project->id", $lang->project->importTask);
    common::printLink('task', 'importFromBug', "projectID=$project->id", $lang->task->importFromBug);
    common::printLink('task', 'report', "project=$project->id&browseType=$browseType", $lang->task->report->common);
    common::printLink('task', 'batchCreate', "projectID=$project->id", $lang->task->batchCreate);
    common::printLink('task', 'create', "project=$project->id", $lang->task->create);
    ?>
  </div>
</div>
