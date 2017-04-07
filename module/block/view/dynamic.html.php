<table class='table table-condensed table-hover table-striped table-borderless table-fixed'>
  <?php 
  foreach($actions as $action)
  {
      $user = isset($users[$action->actor]) ? $users[$action->actor] : $action->actor;
      if($action->action == 'login' or $action->action == 'logout' or empty($action->objectLink)) $action->objectName = $action->objectLabel = '';
      echo "<tr><td class='nobr' width='100%'>";
      printf($lang->block->dynamicInfo, $action->date, $user, $action->actionLabel, $action->objectLabel, $action->objectLink, $action->objectName);
      echo "</td></tr>";
  }
 ?>
</table>
