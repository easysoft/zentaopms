<div class="yui-d0">
  <div id='featurebar'>
    <div class='f-left'>
    <?php
    echo "<span id='list'>"      ; common::printLink('project', 'task',      "project=$project->id",                   $lang->project->listTask);            echo  '</span>' ;
    echo "<span id='story'>"     ; common::printLink('project', 'groupTask', "project=$project->id&groupby=story",     $lang->project->groupTaskByStory);    echo  '</span>' ;
    echo "<span id='status'>"    ; common::printLink('project', 'groupTask', "project=$project->id&groupby=status",    $lang->project->groupTaskByStatus);   echo  '</span>' ;
    echo "<span id='pri'>"       ; common::printLink('project', 'groupTask', "project=$project->id&groupby=pri",       $lang->project->groupTaskByPri);      echo  '</span>' ;
    echo "<span id='assignedto'>"; common::printLink('project', 'groupTask', "project=$project->id&groupby=assignedTo",     $lang->project->groupTaskByOwner);    echo  '</span>' ;
    echo "<span id='estimate'>"  ; common::printLink('project', 'groupTask', "project=$project->id&groupby=estimate",  $lang->project->groupTaskByEstimate); echo  '</span>' ;
    echo "<span id='consumed'>"  ; common::printLink('project', 'groupTask', "project=$project->id&groupby=consumed",  $lang->project->groupTaskByConsumed); echo  '</span>' ;
    echo "<span id='left'>"      ; common::printLink('project', 'groupTask', "project=$project->id&groupby=left",      $lang->project->groupTaskByLeft);     echo  '</span>' ;
    echo "<span id='type'>"      ; common::printLink('project', 'groupTask', "project=$project->id&groupby=type",      $lang->project->groupTaskByType);     echo  '</span>' ;
    echo "<span id='deadline'>"  ; common::printLink('project', 'groupTask', "project=$project->id&groupby=deadline",  $lang->project->groupTaskByDeadline); echo  '</span>' ;
    echo "<span id='needconfirm'>"; common::printLink('project', 'task',  "project=$project->id&status=needConfirm",$lang->project->listTaskNeedConfrim); echo  '</span>' ;
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
</div>
