<?php js::set('projectID', $projectID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<input type='text' id='search' value='' onkeyup='searchItems(this.value, "project", projectID, module, method, extra)' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'>

<div id='searchResult'>
  <div id='defaultMenu' class='f-left'>
    <ul>
    <?php
      foreach($projects as $project)
      {
          $isOwner = $project->PO == $this->app->user->account or $project->PM == $this->app->user->account or $project->QD == $this->app->user->account or $project->RD == $this->app->user->account;
          if($project->status != 'done' and $isOwner) echo "<li>" . html::a(sprintf($link, $project->id), $project->name). "</li>";
      }
      foreach($projects as $project)
      {
          $isOwner = $project->PO == $this->app->user->account or $project->PM == $this->app->user->account or $project->QD == $this->app->user->account or $project->RD == $this->app->user->account;
          if($project->status != 'done' and !$isOwner) echo "<li>" . html::a(sprintf($link, $project->id), $project->name). "</li>";
      }
    ?>
    </ul>
    <div class='a-right'><a id='more' onClick='showMore()'><?php echo $lang->more;?>&raquo;</a></div>
  </div>

  <div id='moreMenu' class='hidden f-left'>
    <ul>
    <?php
      foreach($projects as $project)
      {
          if($project->status == 'done') echo "<li>" . html::a(sprintf($link, $project->id), $project->name). "</li>";
      }
    ?>
    </ul>
  </div>
</div>
