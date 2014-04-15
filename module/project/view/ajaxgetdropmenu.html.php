<?php js::set('projectID', $projectID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<input type='text' class='form-control' id='search' value='' onkeyup='searchItems(this.value, "project", projectID, module, method, extra)' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'/>

<div id='searchResult'>
  <div id='defaultMenu'>
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
 
    if($iCharges and $others) echo "<li class='heading'>{$lang->project->mine}</li>";
    foreach($projects as $project)
    {
        if($project->status != 'done' and $project->PM == $this->app->user->account)
        {
            echo "<li>" . html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i> " . $project->name). "</li>";
        }
    }
 
    if($iCharges and $others) echo "<li class='heading'>{$lang->project->other}</li>";
    $class = ($iCharges and $others) ? "class='other'" : '';
    foreach($projects as $project)
    {
        if($project->status != 'done' and !($project->PM == $this->app->user->account))
        {
            echo "<li>" . html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i> " . $project->name, '', "$class"). "</li>";
        }
    }
    ?>
    </ul>
 
    <?php if($dones):?>
      <div class='actions'><a id='more' href='javascript:switchMore()'><?php echo $lang->project->doneProjects . ' <i class="icon-angle-right"></i>';?></a></div>
    <?php endif;?>
  </div>
  <div id='moreMenu'>
    <ul>
    <?php
      foreach($projects as $project)
      {
        if($project->status == 'done') echo "<li>" . html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i> " . $project->name, '', "class='done'"). "</li>";
      }
    ?>
    </ul>
  </div>
</div>
