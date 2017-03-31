<?php js::set('projectID', $projectID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<input type='text' class='form-control' id='search' value='' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'/>

<div id='searchResult'>
  <div id='defaultMenu' class='search-list'>
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
            echo "<li data-id='{$project->id}' data-tag=':{$project->status} @{$project->PM} @mine' data-key='{$project->key}'>" . html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i> " . $project->name). "</li>";
        }
    }
 
    if($iCharges and $others) echo "<li class='heading'>{$lang->project->other}</li>";
    $class = ($iCharges and $others) ? "class='other'" : '';
    foreach($projects as $project)
    {
        if($project->status != 'done' and !($project->PM == $this->app->user->account))
        {
            echo "<li data-id='{$project->id}' data-tag=':{$project->status} @{$project->PM}' data-key='{$project->key}'>" . html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i> " . $project->name, '', "$class"). "</li>";
        }
    }
    ?>
    </ul>
 
    <div>
    <?php echo html::a($this->createLink('project', 'all', "status=undone&projectID=$projectID"), "<i class='icon-th-large mgr-5px'></i> " . $lang->project->allProject)?>
    <?php if($dones):?>
      <div class='pull-right actions'><a id='more' href='javascript:switchMore()'><?php echo $lang->project->doneProjects . ' <i class="icon-angle-right"></i>';?></a></div>
    <?php endif;?>
    </div>
  </div>
  <div id='moreMenu'>
    <ul>
    <?php
      foreach($projects as $project)
      {
        if($project->status == 'done') echo "<li data-id='{$project->id}' data-tag=':{$project->status} @{$project->PM}' data-key='{$project->key}'>" . html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i> " . $project->name, '', "class='done'"). "</li>";
      }
    ?>
    </ul>
  </div>
</div>
