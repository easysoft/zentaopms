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
#tabContent .tab-pane>ul>li>div {display: flex; flex-flow: row nowrap; justify-content: flex-start; align-items: center;}
#tabContent .tab-pane>ul>li label {background: rgba(255,255,255,0.5); line-height: unset; color: #838a9d; border: 1px solid #d8d8d8; border-radius: 2px; padding: 1px 4px;}
#tabContent li a i.icon {font-size: 15px !important;}
#tabContent li a i.icon:before {min-width: 16px !important;}
#tabContent li .label {position: unset; margin-bottom: 0;}
#tabContent li>a, #tabContent li>div>a {display: block; padding: 2px 10px 2px 5px; overflow: hidden; line-height: 20px; text-overflow: ellipsis; white-space: nowrap; border-radius: 4px;}
#tabContent li>a.selected {color: #e9f2fb; background-color: #0c64eb;}
#tabContent .tree li>.list-toggle {line-height: 24px;}
#tabContent .tree li.has-list.open:before {content: unset;}

#swapper li>div.hide-in-search>a:focus, #swapper li>div.hide-in-search>a:hover {color: #838a9d; cursor: default;}
a.executionName:focus, a.executionName:hover {background: #0c64eb; color: #fff !important;}

#swapper li > a {padding-top: 4px; padding-bottom: 4px;}
#swapper li {padding-top: 0; padding-bottom: 0;}
#swapper .tree li>.list-toggle {top: -1px;}
</style>
<?php
$executionCounts      = array();
$executionNames       = array();
$preFix               = '';
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
        if($execution->status != 'done' and $execution->status != 'closed' and ($execution->PM == $this->app->user->account or isset($execution->teams[$this->app->user->account]))) $executionCounts[$projectID]['myExecution'] ++;
        if($execution->status != 'done' and $execution->status != 'closed' and $execution->PM != $this->app->user->account and !isset($execution->teams[$this->app->user->account])) $executionCounts[$projectID]['others'] ++;
        if($execution->status == 'done' or $execution->status == 'closed') $dones ++;
        $executionNames[] = $execution->name;
    }
}
$executionsPinYin = common::convert2Pinyin($executionNames);

$myExecutionsHtml     = $config->systemMode == 'new' ? '<ul class="tree tree-angles" data-ride="tree">' : '<ul class="noProgram">';
$normalExecutionsHtml = $config->systemMode == 'new' ? '<ul class="tree tree-angles" data-ride="tree">' : '<ul class="noProgram">';

foreach($executions as $projectID => $projectExecutions)
{
    /* Adapt to the old version. */
    if($projectID and $config->systemMode == 'new')
    {
        $projectName = zget($projects, $projectID);
        $preFix      = $projectName . ' / ';

        if($executionCounts[$projectID]['myExecution']) $myExecutionsHtml .= '<li><div class="hide-in-search"><a class="text-muted" title="' . $projectName . '">' . $projectName . '</a> <label class="label">' . $lang->project->common . '</label></div><ul>';
        if($executionCounts[$projectID]['others']) $normalExecutionsHtml  .= '<li><div class="hide-in-search"><a class="text-muted" title="' . $projectName . '">' . $projectName . '</a> <label class="label">' . $lang->project->common . '</label></div><ul>';
    }

    foreach($projectExecutions as $index => $execution)
    {
        $selected = $execution->id == $executionID ? 'selected' : '';
        if($execution->status != 'done' and $execution->status != 'closed' and ($execution->PM == $this->app->user->account or isset($execution->teams[$this->app->user->account])))
        {
            $myExecutionsHtml .= '<li>' . html::a(sprintf($link, $execution->id), $execution->name, '', "class='$selected executionName' title='{$execution->name}' data-key='" . zget($executionsPinYin, $execution->name, '') . "' data-app='{$this->app->openApp}'") . '</li>';

            if($selected == 'selected') $tabActive = 'myExecution';

            $myExecutions ++;
        }
        else if($execution->status != 'done' and $execution->status != 'closed' and $execution->PM != $this->app->user->account and !isset($execution->teams[$this->app->user->account]))
        {
            $normalExecutionsHtml .= '<li>' . html::a(sprintf($link, $execution->id), $execution->name, '', "class='$selected executionName' title='{$execution->name}' data-key='" . zget($executionsPinYin, $execution->name, '') . "' data-app='{$this->app->openApp}'") . '</li>';

            if($selected == 'selected') $tabActive = 'other';

            $others ++;
        }
        else if($execution->status == 'done' or $execution->status == 'closed')
        {
            $closedExecutionsHtml .= html::a(sprintf($link, $execution->id), $preFix . $execution->name, '', "class='$selected' title='" . $preFix . $execution->name . "' data-key='" . zget($executionsPinYin, $execution->name, '') . "' data-app='{$this->app->openApp}'");

            if($selected == 'selected') $tabActive = 'closed';
        }

        /* If the execution is the last one in the project, print the closed label. */
        if(!isset($projectExecutions[$index + 1]))
        {
            if($executionCounts[$projectID]['myExecution']) $myExecutionsHtml     .= '</ul></li>';
            if($executionCounts[$projectID]['others'])      $normalExecutionsHtml .= '</ul></li>';
        }
    }
}
$myExecutionsHtml     .= '</ul>';
$normalExecutionsHtml .= '</ul>';
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
    })

    $('#tabContent [data-ride="tree"]').tree('expand');

    $('#swapper #dropMenu .search-box').on('onSearchChange', function(event, value)
    {
        if(value != '')
        {
            $('div.hide-in-search').siblings('i').addClass('hide-in-search');
        }
        else
        {
            $('div.hide-in-search').siblings('i').removeClass('hide-in-search');
            $('li.has-list div.hide-in-search').removeClass('hidden');
        }
    })
})
</script>
