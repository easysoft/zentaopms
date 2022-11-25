<?php if(empty($actions)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.timeline > li .timeline-text {display: block; overflow: hidden; text-overflow: ellipsis; max-height: 20px; -webkit-line-clamp: 1; -webkit-box-orient: vertical; }
.panel-body {padding-top: 0;}

.timeline >li:before {left: -26px;}
.timeline >li + li:after {left: -23px;}
.timeline-text {margin-left: -18px;}
.block-projectdynamic .label-action {padding: 0 6px;}
.block-projectdynamic .label-action + a {padding-left: 6px;}
.timeline > li.active:before {left: -30px;}
.timeline > li > div:after {left: -27px;}
.timeline .timeline-text {display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
.timeline > li > div > .timeline-tag, .timeline > li > div > .timeline-text > .label-action {color: #838A9D;}
.timeline > li > div > .timeline-text > a {color: #313C52;}
</style>
<div class='panel-body scrollbar-hover'>
  <ul class="timeline timeline-tag-left no-margin">
    <?php
    $i = 0;
    foreach($actions as $action)
    {
        $user = zget($users, $action->actor);
        if($action->action == 'login' or $action->action == 'logout') $action->objectName = $action->objectLabel = '';
        $class = $action->major ? "class='active'" : '';
        echo "<li $class><div>";
        printf($lang->block->dynamicInfo, $action->date, $user, $action->actionLabel, $action->objectLabel, $action->objectLink, $action->objectName, $action->objectName);
        echo "</div></li>";
        $i++;
    }
    ?>
  </ul>
</div>
<?php endif;?>
