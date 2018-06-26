<?php if(empty($actions)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.block-dynamic .timeline > li .timeline-text {display: block; overflow: hidden; text-overflow: ellipsis; white-space: normal;}
.block-dynamic .timeline > li:hover .timeline-text {overflow: visible; text-overflow: normal; white-space: normal;}
.block-dynamic .panel-body {padding-top: 0;}
</style>
<div class='panel-body'>
  <ul class="timeline timeline-tag-left no-margin">
    <?php 
    $i = 0;
    foreach($actions as $action)
    {
        $user = isset($users[$action->actor]) ? $users[$action->actor] : $action->actor;
        if($action->action == 'login' or $action->action == 'logout' or empty($action->objectLink)) $action->objectName = $action->objectLabel = '';
        $class = $action->major ? "class='active'" : '';
        echo "<li $class><div>";
        printf($lang->block->dynamicInfo, $action->date, $user, $action->actionLabel, $action->objectLabel, $action->objectLink, $action->objectName);
        echo "</div></li>";
        $i++;
    }
    ?>
  </ul>
</div>
<?php endif;?>
