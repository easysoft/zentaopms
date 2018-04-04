<?php js::set('projectID', $projectID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<div class="input-control search-box search-box-circle has-icon-left has-icon-right search-example">
  <input type="search" class="form-control search-input" />
  <label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
  <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
</div>
<div class="list-group">
  <?php
  $iCharges = 0;
  $others   = 0;
  $dones    = 0;
  $projectNames = array();
  foreach($projects as $project)
  {
      if($project->status != 'done' and $project->PM == $this->app->user->account) $iCharges++;
      if($project->status != 'done' and !($project->PM == $this->app->user->account)) $others++;
      if($project->status == 'done') $dones++;
      $projectNames[] = $project->name;
  }
  $projectsPinYin = common::convert2Pinyin($projectNames);
 
  foreach($projects as $project)
  {
      if($project->status != 'done' and $project->PM == $this->app->user->account)
      {
          echo html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i> " . $project->name, '', "data-filter='" . zget($projectsPinYin, $project->name, '') . "'");
      }
  }
 
  foreach($projects as $project)
  {
      if($project->status != 'done' and !($project->PM == $this->app->user->account))
      {
          echo html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i> " . $project->name, '', "data-filter='" . zget($projectsPinYin, $project->name, '') . "'");
      }
  }

  foreach($projects as $project)
  {
    if($project->status == 'done') echo html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i> " . $project->name, '', "data-filter='" . zget($projectsPinYin, $project->name, '') . "'");
  }
  ?>
</div>
