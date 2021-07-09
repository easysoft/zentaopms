<?php js::set('projectID', $projectID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<style>
.table-row .table-col .list-group .nav-tabs {position: sticky; top: 0;}
.table-row .table-col .list-group .tab-content {margin-top: 10px; padding-left: 15px;}
.table-row .table-col .list-group .nav-tabs>li.active>a:before {position: absolute; right: 0; bottom: -1px; left: 0; display: block; height: 2px; content: ' '; background: #0c64eb; }
.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {border: none;}
.table-row .table-col .list-group .tab-content li .label {margin-top: 2px;}
.table-row .table-col .list-group .tab-content li ul {padding-left: 15px;}
</style>
<?php
$projectCounts      = array();
$projectNames       = array();
$myProjectsHtml     = '';
$normalProjectsHtml = '';
$closedProjectsHtml = '';
$iCharges           = 0;
$others             = 0;
$dones              = 0;
foreach($projects as $programID => $programProjects)
{
    $projectCounts[$programID]['myProject'] = 0;
    $projectCounts[$programID]['others']    = 0;

    foreach($programProjects as $project)
    {
        if($project->status != 'done' and $project->status != 'closed' and $project->PM == $this->app->user->account) $projectCounts[$programID]['myProject']++;
        if($project->status != 'done' and $project->status != 'closed' and !($project->PM == $this->app->user->account)) $projectCounts[$programID]['others']++;
        if($project->status == 'done' or $project->status == 'closed') $dones++;
        $projectNames[] = $project->name;
    }
}
$projectsPinYin = common::convert2Pinyin($projectNames);

foreach($projects as $programID => $programProjects)
{
    if($programID)
    {
        if($projectCounts[$programID]['myProject']) $myProjectsHtml .= '<ul class="list-unstyled"><li><span class="text-muted">' . zget($programs, $programID) . '</span> <label class="label">' . $lang->program->common . '</label></li><li><ul>';
        if($projectCounts[$programID]['others']) $normalProjectsHtml .= '<ul><li>' . zget($programs, $programID) . ' <label class="label">' . $lang->program->common . '</label></li><li><ul>';
    }

    foreach($programProjects as $index => $project)
    {
        $selected = $project->id == $projectID ? 'selected' : '';
        $link     = helper::createLink('project', 'index', "projectID=%s", '', '', $project->id);

        if(isset($this->config->maxVersion)) $project->name = $project->model == 'scrum' ? '<i class="icon icon-sprint"></i> ' . $project->name : '<i class="icon icon-waterfall"></i> ' . $project->name;
        if($project->status != 'done' and $project->status != 'closed' and $project->PM == $this->app->user->account)
        {
            $myProjectsHtml .= '<li>' . html::a(sprintf($link, $project->id), $project->name, '', "class='text-muted $selected' title='{$project->name}' data-key='" . zget($projectsPinYin, $project->name, '') . "'") . '</li>';

            $iCharges++;
        }
        else if($project->status != 'done' and $project->status != 'closed' and !($project->PM == $this->app->user->account))
        {
            $normalProjectsHtml .= '<li>' . html::a(sprintf($link, $project->id), $project->name, '', "class='$selected' title='{$project->name}' data-key='" . zget($projectsPinYin, $project->name, '') . "'") . '</li>';

            $others++;
        }
        else if($project->status == 'done' or $project->status == 'closed') $closedProjectsHtml .= html::a(sprintf($link, $project->id), $project->name, '', "class='$selected' title='{$project->name}' data-key='" . zget($projectsPinYin, $project->name, '') . "'");

        if($programID and !isset($programProjects[$index + 1]))
        {
            if($projectCounts[$programID]['myProject']) $myProjectsHtml .= '</ul></li>';
            if($projectCounts[$programID]['others']) $normalProjectsHtml .= '</ul></li>';
        }
    }

    if($projectCounts[$programID]['myProject']) $myProjectsHtml .= '</ul>';
    if($projectCounts[$programID]['others']) $normalProjectsHtml .= '</ul>';
}
?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
      <ul class="nav nav-tabs">
        <li class="active"><?php echo html::a('#myProject', $lang->project->myProject, '', "data-toggle='tab' class='not-list-item not-clear-menu'");?><li>
        <li><?php echo html::a('#other', $lang->project->other, '', "data-toggle='tab' class='not-list-item not-clear-menu'")?><li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="myProject">
          <?php echo $myProjectsHtml;?>
        </div>
        <div class="tab-pane" id="other">
          <?php echo $normalProjectsHtml;?>
        </div>
      </div>
    </div>
    <div class="col-footer">
      <?php //echo html::a(helper::createLink('project', 'browse', 'programID=0&browseType=all'), '<i class="icon icon-cards-view muted"></i> ' . $lang->project->all, '', 'class="not-list-item"'); ?>
      <a class='pull-right toggle-right-col not-list-item'><?php echo $lang->project->doneProjects?><i class='icon icon-angle-right'></i></a>
    </div>
  </div>
  <div class="table-col col-right">
   <div class='list-group'><?php echo $closedProjectsHtml;?></div>
  </div>
</div>
<script>scrollToSelected();</script>
