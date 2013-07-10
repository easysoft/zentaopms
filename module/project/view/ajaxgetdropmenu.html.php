<?php js::set('projectID', $projectID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<input type='text' class='gray' id='search' value='' onkeyup='searchItems(this.value, "project", projectID, module, method, extra)' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'>

<div id='searchResult'>
  <div id='defaultMenu' class='f-left'>
    <ul>
    <?php
    $iCharges = 0;
    $others   = 0;
    $dones    = 0;
    foreach($projects as $project)
    {
        if($project->status != 'done' and $project->PM == $this->app->user->account) $iCharges++;
        if($project->status != 'done' and !($project->PM == $this->app->user->account)) $others++;
        if($project->status == 'done') $dones++;
    }

    if($iCharges and $others) echo "<span class='black'>{$lang->project->mine}</span>";
    foreach($projects as $project)
    {
        if($project->status != 'done' and $project->PM == $this->app->user->account)
        {
            echo "<li>" . html::a(sprintf($link, $project->id), $project->name). "</li>";
        }
    }

    if($iCharges and $others) echo "<span class='black'>{$lang->project->other}</span>";
    $class = ($iCharges and $others) ? "class='other'" : '';
    foreach($projects as $project)
    {
        if($project->status != 'done' and !($project->PM == $this->app->user->account))
        {
            echo "<li>" . html::a(sprintf($link, $project->id), $project->name, '', "$class"). "</li>";
        }
    }
    ?>
    </ul>

    <?php if($dones):?>
    <div class='a-right'><a id='more' onClick='switchMore()'><?php echo $lang->project->doneProjects . '&raquo;';?></a></div>
    <?php endif;?>

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
