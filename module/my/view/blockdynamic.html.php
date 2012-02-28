<div class='block'>
<table class='table-1 colored fixed'>
  <caption>
    <div class='f-left'><span class='icon-z'></span><?php echo $lang->my->home->latest;?></div>
    <div class='f-right'><?php common::printLink('company', 'dynamic', '', $lang->more . "<span class='icon-g'></span>");?></div>
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
      if($action->action == 'login' or $action->action == 'logout') $action->objectName = '';
      echo "<tr><td class='nobr' width='95%'>";
      printf($lang->my->home->action, $action->date, $user, $action->actionLabel, $action->objectLabel, $action->objectLink, $action->objectName);
      echo "</td><td class='divider'></td></tr>";
  }
 ?>
</table>
</div>
