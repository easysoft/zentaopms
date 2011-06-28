<div class='block'>
<table class='table-1 colored fixed'>
  <caption>
    <div class='f-left'><?php echo $lang->index->latest;?></div>
    <div class='f-right'><?php common::printLink('company', 'dynamic', '', $lang->more);?></div>
  </caption>
  <?php 
  foreach($actions as $action)
  {
      $user = isset($users[$action->actor]) ? $users[$action->actor] : $action->actor;
      if($action->action == 'login' or $action->action == 'logout') $action->objectName = '';
      echo "<tr><td class='nobr' width='95%'>";
      printf($lang->index->action, $action->date, $user, $action->actionLabel, $action->objectLabel, $action->objectLink, $action->objectName);
      echo "</td><td class='divider'></td></tr>";
  }
 ?>
</table>
</div>
