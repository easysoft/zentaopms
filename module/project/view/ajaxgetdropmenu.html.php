<?php js::set('projectID', $projectID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<style>
.table-row .table-col .list-group .nav-tabs {position: sticky; top: 0; background: #fff; z-index: 950;}
.table-row .table-col .list-group .nav-tabs>li>span {display: inline-block; margin-left: -6px;}
.table-row .table-col .list-group .nav-tabs>li>a {padding: 8px 10px; display: inline-block}
.table-row .table-col .list-group .nav-tabs>li.active>a, .nav-tabs>li.active>span {font-weight: 700; color: #0c64eb;}
.table-row .table-col .list-group .nav-tabs>li.active>a:before {position: absolute; right: 0; bottom: -1px; left: 0; display: block; height: 2px; content: ' '; background: #0c64eb; }
.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {border: none;}

.table-row .table-col .list-group .tab-content {margin-top: 10px; z-index: 900;}
.table-row .table-col .list-group .tab-content ul {list-style: none; margin: 0}
.table-row .table-col .list-group .tab-content .tab-pane>ul {padding-left: 7px;}
.table-row .table-col .list-group .tab-content .tab-pane>ul>li.hide-in-search {position: relative;}
.table-row .table-col .list-group .tab-content .tab-pane>ul>li>label+a {padding-left: 55px;}
.table-row .table-col .list-group .tab-content .tab-pane>ul>li label {background: rgba(131,138,157,0.5); position: absolute; top: 0; left: 5px;}
.table-row .table-col .list-group .tab-content li a i.icon {font-size: 15px !important;}
.table-row .table-col .list-group .tab-content li a i.icon:before {min-width: 16px !important;}
.table-row .table-col .list-group .tab-content li .label {margin-top: 2px; position: unset;}
.table-row .table-col .list-group .tab-content li ul {padding-left: 15px;}
.table-row .table-col .list-group .tab-content li>a {margin-top: 5px;display: block; padding: 2px 10px 2px 5px; overflow: hidden; line-height: 20px; text-overflow: ellipsis; white-space: nowrap; border-radius: 4px;}
.table-row .table-col .list-group .tab-content li>a.selected {color: #e9f2fb; background-color: #0c64eb;}

#swapper li.hide-in-search>a:focus, #swapper li.hide-in-search>a:hover {color: #838a9d; cursor: default;}
#swapper li ul li a:focus, #swapper li ul li a:hover, .noProgram li a:focus, .noProgram li a:hover {background: #0c64eb; color: #fff;}
</style>
<?php
$projectCounts      = array();
$projectNames       = array();
$myProjectsHtml     = '';
$normalProjectsHtml = '';
$closedProjectsHtml = '';
$tabActive          = '';
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
    /* Add the program name before project. */
    if(isset($programs[$programID]))
    {
        $programName = zget($programs, $programID);

        if($projectCounts[$programID]['myProject']) $myProjectsHtml  .= '<ul><li class="hide-in-search"><label class="label">' . $lang->program->common . '</label> <a class="text-muted" title="' . $programName . '">' . $programName . '</a></li><li><ul>';
        if($projectCounts[$programID]['others']) $normalProjectsHtml .= '<ul><li class="hide-in-search"><label class="label">' . $lang->program->common . '</label>  <a class="text-muted" title="' . $programName . '">' . $programName . '</a></li><li><ul>';
    }
    else
    {
        if($projectCounts[$programID]['myProject']) $myProjectsHtml     .= '<ul class="noProgram">';
        if($projectCounts[$programID]['others'])    $normalProjectsHtml .= '<ul class="noProgram">';
    }

    foreach($programProjects as $index => $project)
    {
        $selected    = $project->id == $projectID ? 'selected' : '';
        $link        = helper::createLink('project', 'index', "projectID=%s", '', '', $project->id);
        $projectName = $project->name;

        /* If this version is maxVersion, add the execution icon before execution name. */
        if(isset($this->config->maxVersion)) $projectName = $project->model == 'scrum' ? '<i class="icon icon-sprint"></i> ' . $project->name : '<i class="icon icon-waterfall"></i> ' . $project->name;

        if($project->status != 'done' and $project->status != 'closed' and $project->PM == $this->app->user->account)
        {
            $myProjectsHtml .= '<li>' . html::a(sprintf($link, $project->id), $projectName, '', "class='$selected' title='{$project->name}' data-key='" . zget($projectsPinYin, $project->name, '') . "'") . '</li>';

            if($selected == 'selected') $tabActive = 'myProject';

            $iCharges++;
        }
        else if($project->status != 'done' and $project->status != 'closed' and !($project->PM == $this->app->user->account))
        {
            $normalProjectsHtml .= '<li>' . html::a(sprintf($link, $project->id), $projectName, '', "class='$selected' title='{$project->name}' data-key='" . zget($projectsPinYin, $project->name, '') . "'") . '</li>';

            if($selected == 'selected') $tabActive = 'other';

            $others++;
        }
        else if($project->status == 'done' or $project->status == 'closed')
        {
            $closedProjectsHtml .= html::a(sprintf($link, $project->id), $project->name, '', "class='$selected' title='{$project->name}' data-key='" . zget($projectsPinYin, $project->name, '') . "'");

            if($selected == 'selected') $tabActive = 'closed';
        }

        /* If the project is the last one in the program, print the closed label. */
        if(isset($programs[$programID]) and !isset($programProjects[$index + 1]))
        {
            if($projectCounts[$programID]['myProject']) $myProjectsHtml     .= '</ul></li>';
            if($projectCounts[$programID]['others'])    $normalProjectsHtml .= '</ul></li>';
        }
    }

    if($projectCounts[$programID]['myProject']) $myProjectsHtml     .= '</ul>';
    if($projectCounts[$programID]['others'])    $normalProjectsHtml .= '</ul>';
}
?>

<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
      <?php $tabActive = ($iCharges and ($tabActive == 'closed' or $tabActive == 'myProject')) ? 'myProject' : 'other';?>
      <?php if($iCharges): ?>
      <ul class="nav nav-tabs">
        <li class="<?php if($tabActive == 'myProject') echo 'active';?>"><?php echo html::a('#myProject', $lang->project->myProject, '', "data-toggle='tab' class='not-list-item not-clear-menu'");?><span class="text-muted"><?php echo $iCharges;?></span><li>
        <li class="<?php if($tabActive == 'other') echo 'active';?>"><?php echo html::a('#other', $lang->project->other, '', "data-toggle='tab' class='not-list-item not-clear-menu'")?><span class="text-muted"><?php echo $others;?></span><li>
      </ul>
      <?php endif;?>
      <div class="tab-content">
        <div class="tab-pane <?php if($tabActive == 'myProject') echo 'active';?>" id="myProject">
          <?php echo $myProjectsHtml;?>
        </div>
        <div class="tab-pane <?php if($tabActive == 'other') echo 'active';?>" id="other">
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
<script>
$(function()
{
    $('.nav-tabs li span').hide();
    $('.nav-tabs li.active').find('span').show();

    $('.nav-tabs>li').click(function()
    {
        $(this).find('span').show();
        $(this).siblings('li').find('span').hide();
        if($(this).attr('class') != 'active') $('#dropMenu').removeClass('show-right-col');
    })
})
</script>
