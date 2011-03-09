<div id='featurebar'>
  <div class='f-left'>
  <?php
  echo "<span id='allTab'>"         ; common::printLink('project', 'task', "project=$project->id",                   $lang->project->allTasks);     echo  '</span>' ;
  echo "<span id='assignedtomeTab'>"; common::printLink('project', 'task', "project=$project->id&type=assignedtome", $lang->project->assignedToMe); echo  '</span>' ;
  echo "<span id='finishedbymeTab'>"; common::printLink('project', 'task', "project=$project->id&type=finishedbyme", $lang->project->finishedByMe); echo  '</span>' ;
  echo "<span id='waitTab'>"        ; common::printLink('project', 'task', "project=$project->id&type=wait",         $lang->project->statusWait);   echo  '</span>' ;
  echo "<span id='doingTab'>"       ; common::printLink('project', 'task', "project=$project->id&type=doing",        $lang->project->statusDoing);  echo  '</span>' ;
  echo "<span id='doneTab'>"        ; common::printLink('project', 'task', "project=$project->id&type=done",         $lang->project->statusDone);   echo  '</span>' ;
  echo "<span id='delayedTab'>"     ; common::printLink('project', 'task', "project=$project->id&type=delayed",      $lang->project->delayed);      echo  '</span>' ;

  echo "<span id='groupTab'>";
  echo html::select('groupBy', $lang->project->groups, isset($groupBy) ? $groupBy : '', "onchange='switchGroup({$project->id}, this.value)'");
  echo "</span>";
  echo "<span id='needconfirmTab'>"; common::printLink('project', 'task',  "project=$project->id&status=needConfirm",$lang->project->listTaskNeedConfrim); echo  '</span>' ;
  echo "<span id='bysearchTab' onclick=\"browseBySearch('$browseType')\"><a href='#'>{$lang->project->byQuery}</a></span> ";
  ?>
  </div>
  <div class='f-right'>
    <?php 
    echo html::export2csv($lang->exportCSV, $lang->setFileName);
    common::printLink('project', 'importTask', "project=$project->id", $lang->project->importTask);
    common::printLink('task', 'create', "project=$project->id", $lang->task->create);
    ?>
  </div>
</div>
