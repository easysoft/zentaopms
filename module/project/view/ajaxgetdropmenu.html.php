<?php js::set('projectID', $projectID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<input type='text' class='gray' id='search' value='' onkeyup='searchItems(this.value, "project", projectID, module, method, extra)' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'>

<div id='searchResult'>
  <div id='defaultMenu' class='f-left'>
    <ul>
    <?php
      $i = 0;
      foreach($projects as $project)
      {
          if($project->status != 'done' and $project->PM == $this->app->user->account)
          {
              if(!$i) echo "<span class='black'>{$lang->project->mine}</span>";
              echo "<li>" . html::a(sprintf($link, $project->id), $project->name). "</li>";
              $i++;
          }
      }

      if($i) echo "<span class='black'>{$lang->project->other}</span>";
      $class = $i ? "class='other'" : '';
      foreach($projects as $project)
      {
          if($project->status != 'done' and !$project->PM == $this->app->user->account)
          {
              echo "<li>" . html::a(sprintf($link, $project->id), $project->name, '', "$class"). "</li>";
          }
      }
    ?>
    </ul>
    <div class='a-right'><a id='more' onClick='switchMore()'><?php echo $lang->project->doneProjects . '&raquo;';?></a></div>
  </div>

  <div id='moreMenu' class='hidden f-left'>
    <ul>
    <?php
      foreach($projects as $project)
      {
          if($project->status == 'done') echo "<li>" . html::a(sprintf($link, $project->id), $project->name, '', "class='done'"). "</li>";
      }
    ?>
    </ul>
  </div>
</div>
