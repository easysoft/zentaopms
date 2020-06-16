<?php js::set('projectID', $projectID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<?php
$iCharges = 0;
$others   = 0;
$dones    = 0;
$projectNames = array();
$myProjectsHtml     = '';
$normalProjectsHtml = '';
$closedProjectsHtml = '';
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
        $myProjectsHtml .= html::a(sprintf($link, $project->id), "<i class='icon icon-folder-outline'></i> " . $project->name, '', "class='text-important' title='{$project->name}' data-key='" . zget($projectsPinYin, $project->name, '') . "'");
    }
    else if($project->status != 'done' and $project->status != 'closed' and !($project->PM == $this->app->user->account))
    {
        $normalProjectsHtml .= html::a(sprintf($link, $project->id), "<i class='icon icon-folder-outline'></i> " . $project->name, '', "title='{$project->name}' data-key='" . zget($projectsPinYin, $project->name, '') . "'");
    }
    else if($project->status == 'done' or $project->status == 'closed') $closedProjectsHtml .= html::a(sprintf($link, $project->id), "<i class='icon icon-folder-outline'></i> " . $project->name, '', "title='{$project->name}' data-key='" . zget($projectsPinYin, $project->name, '') . "'");
}
?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
    <?php
    if(!empty($myProjectsHtml))
    {
        echo "<div class='heading'>{$lang->project->mine}</div>";
        echo $myProjectsHtml;
        if(!empty($myProjectsHtml))
        {
            echo "<div class='heading'>{$lang->project->other}</div>";
        }
    }
    echo $normalProjectsHtml;
    ?>
    </div>
    <div class="col-footer">
      <?php echo html::a(helper::createLink('project', 'all'), '<i class="icon icon-cards-view muted"></i> ' . $lang->project->all, '', 'class="not-list-item"'); ?>
      <a class='pull-right toggle-right-col not-list-item'><?php echo $lang->project->doneProjects?><i class='icon icon-angle-right'></i></a>
    </div>
  </div>
  <div class="table-col col-right">
   <div class='list-group'>
    <?php
    echo $closedProjectsHtml;
    ?>
    </div>
  </div>
</div>
