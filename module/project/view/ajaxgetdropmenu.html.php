<?php js::set('projectID', $projectID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<div class="list-group">
  <?php
  $iCharges = 0;
  $others   = 0;
  $dones    = 0;
  $projectNames = array();
  foreach($projects as $project)
  {
      if($project->status != 'done' and $project->status != 'closed' and $project->PM == $this->app->user->account) $iCharges++;
      if($project->status != 'done' and $project->status != 'closed' and !($project->PM == $this->app->user->account)) $others++;
      if($project->status == 'done' or $project->status == 'closed') $dones++;
      $projectNames[] = $project->name;
  }
  $projectsPinYin = common::convert2Pinyin($projectNames);
 
  foreach($projects as $project)
  {
      if($project->status != 'done' and $project->status != 'closed' and $project->PM == $this->app->user->account)
      {
          echo html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i> " . $project->name, '', "title='{$project->name}' data-key='" . zget($projectsPinYin, $project->name, '') . "'");
      }
  }
 
  foreach($projects as $project)
  {
      if($project->status != 'done' and $project->status != 'closed' and !($project->PM == $this->app->user->account))
      {
          echo html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i> " . $project->name, '', "title='{$project->name}' data-key='" . zget($projectsPinYin, $project->name, '') . "'");
      }
  }

  foreach($projects as $project)
  {
      if($project->status == 'done' or $project->status == 'closed') echo html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i> " . $project->name, '', "title='{$project->name}' data-key='" . zget($projectsPinYin, $project->name, '') . "'");
  }
  ?>
</div>
