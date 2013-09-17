<div class='dynamic'>
<table class='table-1 colored fixed'>
  <caption>
    <div class='f-left'><i class="icon icon-quote-right icon-large"></i>&nbsp; <?php echo $lang->my->home->latest;?></div>
    <div class='f-right'><?php common::printLink('company', 'dynamic', '', $lang->more . "&nbsp;<i class='icon-th icon icon-double-angle-right'></i>");?></div>
  </caption>
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
      echo "<tr><td class='nobr' width='95%'>";
      printf($lang->my->home->action, $action->date, $user, $action->actionLabel, $action->objectLabel, $action->objectLink, $action->objectName);
      echo "</td><td class='divider'></td></tr>";
  }
 ?>
</table>
</div>
