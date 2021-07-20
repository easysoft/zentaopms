<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<style>
#navTabs {position: sticky; top: 0; background: #fff; z-index: 950;}
#navTabs>li {padding: 0px 10px; display: inline-block}
#navTabs>li>span {display: inline-block;}
#navTabs>li>a {padding: 8px 0px; display: inline-block}
#navTabs>li.active>a {font-weight: 700; color: #0c64eb;}
#navTabs>li.active>a:before {position: absolute; right: 0; bottom: -1px; left: 0; display: block; height: 2px; content: ' '; background: #0c64eb; }
#navTabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {border: none;}

#tabContent {margin-top: 10px; z-index: 900;}
#tabContent ul {list-style: none; margin: 0}
#tabContent .tab-pane>ul {padding-left: 7px;}
#tabContent .tab-pane>ul>li.hide-in-search {position: relative;}
#tabContent .tab-pane>ul>li>label+a {padding-left: 45px;}
#tabContent .tab-pane>ul>li label {background: rgba(131,138,157,0.5); position: absolute; top: 0; left: 5px;}
#tabContent li a i.icon {font-size: 15px !important;}
#tabContent li a i.icon:before {min-width: 16px !important;}
#tabContent li .label {margin-top: 2px; position: unset;}
#tabContent li ul {padding-left: 15px;}
#tabContent li>a {margin-top: 5px;display: block; padding: 2px 10px 2px 5px; overflow: hidden; line-height: 20px; text-overflow: ellipsis; white-space: nowrap; border-radius: 4px;}
#tabContent li>a.selected {color: #e9f2fb; background-color: #0c64eb;}

#swapper li.hide-in-search>a:focus, #swapper li.hide-in-search>a:hover {color: #838a9d; cursor: default;}
#swapper li ul li a:focus, #swapper li ul li a:hover, .noProject li a:focus, .noProject li a:hover {background: #0c64eb; color: #fff;}
</style>
<?php
$executionCounts      = array();
$executionNames       = array();
$myExecutionsHtml     = '';
$normalExecutionsHtml = '';
$closedExecutionsHtml = '';
$tabActive            = '';
$myExecutions         = 0;
$others               = 0;
$dones                = 0;

foreach($executions as $projectID => $projectExecutions)
{
    $executionCounts[$projectID]['myExecution'] = 0;
    $executionCounts[$projectID]['others']      = 0;

    foreach($projectExecutions as $execution)
    {
        if($execution->status != 'done' and $execution->status != 'closed' and ($execution->PM == $this->app->user->account or isset($execution->teams[$this->app->user->account]))) $executionCounts[$projectID]['myExecution']++;
        if($execution->status != 'done' and $execution->status != 'closed' and $execution->PM != $this->app->user->account and !isset($execution->teams[$this->app->user->account])) $executionCounts[$projectID]['others']++;
        if($execution->status == 'done' or $execution->status == 'closed') $dones++;
        $executionNames[] = $execution->name;
    }
}
$executionsPinYin = common::convert2Pinyin($executionNames);

foreach($executions as $projectID => $projectExecutions)
{
    /* Adapt to the old version. */
    if($projectID and $config->systemMode == 'new')
    {
        $projectName = zget($projects, $projectID);

        if($executionCounts[$projectID]['myExecution']) $myExecutionsHtml .= '<ul><li class="hide-in-search"><label class="label">' . $lang->project->common . '</label><a class="text-muted" title="' . $projectName . '">' . $projectName . '</a></li><li><ul>';
        if($executionCounts[$projectID]['others']) $normalExecutionsHtml .= '<ul><li class="hide-in-search"><label class="label">' . $lang->project->common . '</label><a class="text-muted" title="' . $projectName . '">' . $projectName . '</a></li><li><ul>';
    }
    else
    {
        if($executionCounts[$projectID]['myExecution']) $myExecutionsHtml .= '<ul class="noProject">';
        if($executionCounts[$projectID]['others']) $normalExecutionsHtml  .= '<ul class="noProject">';
    }

    foreach($projectExecutions as $index => $execution)
    {
        $selected = $execution->id == $executionID ? 'selected' : '';
        if($execution->status != 'done' and $execution->status != 'closed' and ($execution->PM == $this->app->user->account or isset($execution->teams[$this->app->user->account])))
        {
            $myExecutionsHtml .= '<li>' . html::a(sprintf($link, $execution->id), $execution->name, '', "class='$selected' title='{$execution->name}' data-key='" . zget($executionsPinYin, $execution->name, '') . "' data-app='{$this->app->openApp}'") . '</li>';

            if($selected == 'selected') $tabActive = 'myExecution';

            $myExecutions++;
        }
        else if($execution->status != 'done' and $execution->status != 'closed' and $execution->PM != $this->app->user->account and !isset($execution->teams[$this->app->user->account]))
        {
            $normalExecutionsHtml .= '<li>' . html::a(sprintf($link, $execution->id), $execution->name, '', "class='$selected' title='{$execution->name}' data-key='" . zget($executionsPinYin, $execution->name, '') . "' data-app='{$this->app->openApp}'") . '</li>';

            if($selected == 'selected') $tabActive = 'other';

            $others++;
        }
        else if($execution->status == 'done' or $execution->status == 'closed')
        {
            $closedExecutionsHtml .= html::a(sprintf($link, $execution->id), $execution->name, '', "class='$selected' title='{$execution->name}' data-key='" . zget($executionsPinYin, $execution->name, '') . "' data-app='{$this->app->openApp}'");

            if($selected == 'selected') $tabActive = 'closed';
        }

        /* If the execution is the last one in the project, print the closed label. */
        if(!isset($projectExecutions[$index + 1]))
        {
            if($executionCounts[$projectID]['myExecution']) $myExecutionsHtml     .= '</ul></li>';
            if($executionCounts[$projectID]['others'])      $normalExecutionsHtml .= '</ul></li>';
        }
    }

    if($executionCounts[$projectID]['myExecution']) $myExecutionsHtml     .= '</ul>';
    if($executionCounts[$projectID]['others'])      $normalExecutionsHtml .= '</ul>';
}
?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
      <?php $tabActive = ($myExecutions and ($tabActive == 'closed' or $tabActive == 'myExecution')) ? 'myExecution' : 'other';?>
      <?php if($myExecutions): ?>
      <ul class="nav nav-tabs" id="navTabs">
        <li class="<?php if($tabActive == 'myExecution') echo 'active';?>"><?php echo html::a('#myExecution', $lang->execution->involved, '', "data-toggle='tab' class='not-list-item not-clear-menu'");?><span class="label label-light label-badge"><?php echo $myExecutions;?></span><li>
        <li class="<?php if($tabActive == 'other') echo 'active';?>"><?php echo html::a('#other', $lang->project->other, '', "data-toggle='tab' class='not-list-item not-clear-menu'")?><span class="label label-light label-badge"><?php echo $others;?></span><li>
      </ul>
      <?php endif;?>
      <div class="tab-content" id="tabContent">
        <div class="tab-pane <?php if($tabActive == 'myExecution') echo 'active';?>" id="myExecution">
          <?php echo $myExecutionsHtml;?>
        </div>
        <div class="tab-pane <?php if($tabActive == 'other') echo 'active';?>" id="other">
          <?php echo $normalExecutionsHtml;?>
        </div>
      </div>
    </div>
    <div class="col-footer">
      <a class='pull-right toggle-right-col not-list-item'><?php echo $lang->execution->doneExecutions;?><i class='icon icon-angle-right'></i></a>
    </div>
  </div>
  <div class="table-col col-right">
   <div class='list-group'><?php echo $closedExecutionsHtml;?></div>
  </div>
</div>
<script>scrollToSelected();</script>
<script>
$(function()
{
    $('.nav-tabs li span').hide();
    $('.nav-tabs li.active').find('span').show();

    $('.nav-tabs>li a').click(function()
    {
        $(this).siblings().show();
        $(this).parent().siblings('li').find('span').hide();
        if($(this).attr('class') != 'active') $('#dropMenu').removeClass('show-right-col');
        $("#dropMenu .search-box").width('auto');
    })
})
</script>
