<div class="yui-d0">
  <div class='bg-gray mb-10px padding-5px'>
    <?php include './project.html.php';?>
  </div>
  <div id='featurebar'>
    <div class='f-left'>
    <?php
    echo "<span id='bylist'>"    ; common::printLink('project', 'task',      "project=$project->id",                 $lang->project->listTask);            echo  '</span>' ;
    echo "<span id='bystory'>"   ; common::printLink('project', 'groupTask', "project=$project->id&groupby=story",   $lang->project->groupTaskByStory);    echo  '</span>' ;
    echo "<span id='bystatus'>"  ; common::printLink('project', 'groupTask', "project=$project->id&groupby=status",  $lang->project->groupTaskByStatus);   echo  '</span>' ;
    echo "<span id='bypri'>"     ; common::printLink('project', 'groupTask', "project=$project->id&groupby=pri",     $lang->project->groupTaskByPri);      echo  '</span>' ;
    echo "<span id='byowner'>"   ; common::printLink('project', 'groupTask', "project=$project->id&groupby=owner",   $lang->project->groupTaskByOwner);    echo  '</span>' ;
    echo "<span id='byestimate'>"; common::printLink('project', 'groupTask', "project=$project->id&groupby=estimate",$lang->project->groupTaskByEstimate); echo  '</span>' ;
    echo "<span id='byconsumed'>"; common::printLink('project', 'groupTask', "project=$project->id&groupby=consumed",$lang->project->groupTaskByConsumed); echo  '</span>' ;
    echo "<span id='byleft'>"    ; common::printLink('project', 'groupTask', "project=$project->id&groupby=`left`",  $lang->project->groupTaskByLeft);     echo  '</span>' ;
    echo "<span id='bytype'>"    ; common::printLink('project', 'groupTask', "project=$project->id&groupby=type",    $lang->project->groupTaskByType);     echo  '</span>' ;
    echo "<span id='bydeadline'>"; common::printLink('project', 'groupTask', "project=$project->id&groupby=deadline",$lang->project->groupTaskByDeadline); echo  '</span>' ;
    ?>
    </div>
    <div class='f-right'><?php common::printLink('task', 'create', "project=$project->id", $lang->task->create); ?></div>
  </div>
</div>
