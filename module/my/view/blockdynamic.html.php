<div class='panel panel-block dynamic'>
  <div class='panel-heading'>
    <?php echo html::icon($lang->icons['dynamic']);?> <strong><?php echo $lang->my->home->latest;?></strong>
    <div class="panel-actions pull-right"><?php common::printLink('company', 'dynamic', '', $lang->more . "&nbsp;<i class='icon-th icon icon-double-angle-right'></i>");?></div>
  </div>
  <table class='table table-condensed table-hover table-striped table-borderless table-fixed'>
    <?php 
    foreach($actions as $action)
    {
        $canView = false;
        if(common::hasPriv('company', 'dynamic')) $canView = true;
        if($action->product == 0 and $action->project == 0) $canView = true;
        if(isset($productStats['products'][$action->product]) or isset($projectStats['projects'][$action->project])) $canView = true;

        if(!$canView) continue;
        $user = isset($users[$action->actor]) ? $users[$action->actor] : $action->actor;
        if($action->action == 'login' or $action->action == 'logout') $action->objectName = $action->objectLabel = '';
        echo "<tr><td class='nobr' width='100%'>";
        printf($lang->my->home->action, $action->date, $user, $action->actionLabel, $action->objectLabel, $action->objectLink, $action->objectName);
        echo "</td></tr>";
    }
   ?>
  </table>
</div>
