<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<style>
#navTabs {position: sticky; top: 0; background: #fff; z-index: 950;}
#navTabs>li {padding: 0px 10px; display: inline-block}
#navTabs>li>span {display: inline-block;}
#navTabs>li>a {margin: 0!important; padding: 8px 0px; display: inline-block}

#tabContent {margin-top: 5px; z-index: 900; max-width: 220px}
.executionTree ul {list-style: none; margin: 0}
.executionTree .executions>ul>li>div {display: flex; flex-flow: row nowrap; justify-content: flex-start; align-items: center;}
.executionTree .executions>ul>li label {background: rgba(255,255,255,0.5); line-height: unset; color: #838a9d; border: 1px solid #d8d8d8; border-radius: 2px; padding: 1px 4px;}
.executionTree li a i.icon {font-size: 15px !important;}
.executionTree li a i.icon:before {min-width: 16px !important;}
.executionTree li .label {position: unset; margin-bottom: 0;}
.executionTree li>a, div.hide-in-search>a {display: block; padding: 2px 10px 2px 5px; overflow: hidden; line-height: 20px; text-overflow: ellipsis; white-space: nowrap; border-radius: 4px;}
.executionTree .tree li>.list-toggle {line-height: 24px;}
.executionTree .tree li.has-list.open:before {content: unset;}

#swapper li>div.hide-in-search>a:focus, #swapper li>div.hide-in-search>a:hover {color: #838a9d; cursor: default;}
#swapper li > a {margin-top: 4px; margin-bottom: 4px;}
#swapper li {padding-top: 0; padding-bottom: 0;}
#swapper .tree li>.list-toggle {top: -1px;}

#closed {width: 90px; height: 25px; line-height: 25px; background-color: #ddd; color: #3c495c; text-align: center; margin-left: 15px; border-radius: 2px;}
#gray-line {width: 230px;height: 1px; margin-left: 10px; margin-bottom:2px; background-color: #ddd;}
#dropMenu.has-search-text .hide-in-search {display: flex;}
#swapper li>.selected {color: #0c64eb!important; background: #e9f2fb!important;}
</style>
<?php
$executionCounts      = array();
$executionNames       = array();
$tabActive            = '';
$myExecutions         = 0;
$others               = 0;
$dones                = 0;

foreach($executions as $projectID => $projectExecutions)
{
    $executionCounts[$projectID]['myExecution'] = 0;
    $executionCounts[$projectID]['others']      = 0;
    $executionCounts[$projectID]['closed']      = 0;

    foreach($projectExecutions as $execution)
    {
        if($execution->status != 'done' and $execution->status != 'closed' and ($execution->PM == $this->app->user->account or isset($execution->teams[$this->app->user->account]))) $executionCounts[$projectID]['myExecution'] ++;
        if($execution->status != 'done' and $execution->status != 'closed' and $execution->PM != $this->app->user->account and !isset($execution->teams[$this->app->user->account])) $executionCounts[$projectID]['others'] ++;
        if($execution->status == 'done' or $execution->status == 'closed') $executionCounts[$projectID]['closed'] ++;
        $onlyChildStage = $execution->grade == 2 and $execution->project != $execution->parent;
        $executionNames[$execution->id] = $execution->name;
        if($onlyChildStage and isset($parents[$execution->parent]))
        {
            $executionNames[$execution->id] = $parents[$execution->parent]->name . '/' . $execution->name;
        }
    }
}
$executionsPinYin = common::convert2Pinyin($executionNames);

$myExecutionsHtml     = $config->systemMode == 'new' ? '<ul class="tree tree-angles" data-ride="tree">' : '<ul class="noProject">';
$normalExecutionsHtml = $config->systemMode == 'new' ? '<ul class="tree tree-angles" data-ride="tree">' : '<ul class="noProject">';
$closedExecutionsHtml = $config->systemMode == 'new' ? '<ul class="tree tree-angles" data-ride="tree">' : '<ul class="noProject">';

$kanbanLink = $this->createLink('execution', 'kanban', "executionID=%s");
$taskLink   = $this->createLink('execution', 'task', "executionID=%s");
foreach($executions as $projectID => $projectExecutions)
{
    /* Adapt to the old version. */
    if($projectID and $config->systemMode == 'new')
    {
        $projectName = zget($projects, $projectID);

        if($executionCounts[$projectID]['myExecution']) $myExecutionsHtml .= '<li><div class="hide-in-search"><a class="text-muted not-list-item" title="' . $projectName . '">' . $projectName . '</a> <label class="label">' . $lang->project->common . '</label></div><ul>';
        if($executionCounts[$projectID]['others']) $normalExecutionsHtml  .= '<li><div class="hide-in-search"><a class="text-muted not-list-item" title="' . $projectName . '">' . $projectName . '</a> <label class="label">' . $lang->project->common . '</label></div><ul>';
        if($executionCounts[$projectID]['closed']) $closedExecutionsHtml  .= '<li><div class="hide-in-search"><a class="text-muted not-list-item" title="' . $projectName . '">' . $projectName . '</a> <label class="label">' . $lang->project->common . '</label></div><ul>';
    }

    foreach($projectExecutions as $index => $execution)
    {
        $executionLink  = $link;
        $isKanbanMethod = ((in_array($method, $config->execution->kanbanMethod) and $module == 'execution') or (strpos(',create,edit,', ",$method,") !== false and $module == 'build'));
        if(isset($execution->type) and $execution->type == 'kanban' and !$isKanbanMethod) $executionLink = $kanbanLink;
        if(isset($execution->type) and $execution->type != 'kanban' and strpos(',kanban,cfd,', ",$method,") !== false) $executionLink = $taskLink;

        $selected = $execution->id == $executionID ? 'selected' : '';
        if(!empty($execution->children))
        {
            foreach($execution->children as $id)
            {
                $selected = $id == $executionID ? 'selected' : '';
                if($execution->status != 'done' and $execution->status != 'closed' and ($execution->PM == $this->app->user->account or isset($execution->teams[$this->app->user->account])))
                {
                    $myExecutionsHtml .= '<li>' . html::a(sprintf($executionLink, $id), $executionNames[$id], '', "class='$selected clickable' title='{$executionNames[$id]}' data-key='" . zget($executionsPinYin, $executionNames[$id], '') . "' data-app='{$this->app->tab}'") . '</li>';

                    if($selected == 'selected') $tabActive = 'myExecution';

                    $myExecutions ++;
                }
                else if($execution->status != 'done' and $execution->status != 'closed' and $execution->PM != $this->app->user->account and !isset($execution->teams[$this->app->user->account]))
                {
                    $normalExecutionsHtml .= '<li>' . html::a(sprintf($executionLink, $id), $executionNames[$id], '', "class='$selected clickable' title='{$executionNames[$id]}' data-key='" . zget($executionsPinYin, $executionNames[$id], '') . "' data-app='{$this->app->tab}'") . '</li>';

                    if($selected == 'selected') $tabActive = 'other';

                    $others ++;
                }
                else if($execution->status == 'done' or $execution->status == 'closed')
                {
                    $closedExecutionsHtml .= '<li>' . html::a(sprintf($executionLink, $id), $executionNames[$id], '', "class='$selected clickable' title='{$executionNames[$id]}' data-key='" . zget($executionsPinYin, $executionNames[$id], '') . "' data-app='{$this->app->tab}'") . '</li>';

                    if($selected == 'selected') $tabActive = 'closed';
                }
            }
        }
        else if($execution->grade == 1 or $config->systemMode == 'classic')
        {
            if($execution->status != 'done' and $execution->status != 'closed' and ($execution->PM == $this->app->user->account or isset($execution->teams[$this->app->user->account])))
            {
                $myExecutionsHtml .= '<li>' . html::a(sprintf($executionLink, $execution->id), $executionNames[$execution->id], '', "class='$selected clickable' title='{$executionNames[$execution->id]}' data-key='" . zget($executionsPinYin, $execution->name, '') . "' data-app='{$this->app->tab}'") . '</li>';

                if($selected == 'selected') $tabActive = 'myExecution';

                $myExecutions ++;
            }
            else if($execution->status != 'done' and $execution->status != 'closed' and $execution->PM != $this->app->user->account and !isset($execution->teams[$this->app->user->account]))
            {
                $normalExecutionsHtml .= '<li>' . html::a(sprintf($executionLink, $execution->id), $executionNames[$execution->id], '', "class='$selected clickable' title='{$executionNames[$execution->id]}' data-key='" . zget($executionsPinYin, $execution->name, '') . "' data-app='{$this->app->tab}'") . '</li>';

                if($selected == 'selected') $tabActive = 'other';

                $others ++;
            }
            else if($execution->status == 'done' or $execution->status == 'closed')
            {
                $closedExecutionsHtml .= '<li>' . html::a(sprintf($executionLink, $execution->id), $executionNames[$execution->id], '', "class='$selected clickable' title='{$executionNames[$execution->id]}' data-key='" . zget($executionsPinYin, $execution->name, '') . "' data-app='{$this->app->tab}'") . '</li>';

                if($selected == 'selected') $tabActive = 'closed';
            }
        }

        /* If the execution is the last one in the project, print the closed label. */
        if(!isset($projectExecutions[$index + 1]))
        {
            if($executionCounts[$projectID]['myExecution']) $myExecutionsHtml     .= '</ul></li>';
            if($executionCounts[$projectID]['others'])      $normalExecutionsHtml .= '</ul></li>';
            if($executionCounts[$projectID]['closed'])      $closedExecutionsHtml .= '</ul></li>';
        }
    }
}
$myExecutionsHtml     .= '</ul>';
$normalExecutionsHtml .= '</ul>';
$closedExecutionsHtml .= '</ul>';
?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
      <?php $tabActive = ($myExecutions and ($tabActive == 'closed' or $tabActive == 'myExecution')) ? 'myExecution' : 'other';?>
      <?php if($myExecutions): ?>
      <ul class="nav nav-tabs nav-tabs-primary" id="navTabs">
        <li class="<?php if($tabActive == 'myExecution') echo 'active';?>"><?php echo html::a('#myExecution', $lang->execution->involved, '', "data-toggle='tab' class='not-list-item not-clear-menu'");?><span class="label label-light label-badge"><?php echo $myExecutions;?></span><li>
        <li class="<?php if($tabActive == 'other') echo 'active';?>"><?php echo html::a('#other', $lang->project->other, '', "data-toggle='tab' class='not-list-item not-clear-menu'")?><span class="label label-light label-badge"><?php echo $others;?></span><li>
      </ul>
      <?php endif;?>
      <div class="tab-content executionTree" id="tabContent">
        <div class="tab-pane executions <?php if($tabActive == 'myExecution') echo 'active';?>" id="myExecution">
          <?php echo $myExecutionsHtml;?>
        </div>
        <div class="tab-pane executions <?php if($tabActive == 'other') echo 'active';?>" id="other">
          <?php echo $normalExecutionsHtml;?>
        </div>
      </div>
    </div>
    <div class="col-footer">
      <a class='pull-right toggle-right-col not-list-item'><?php echo $lang->execution->doneExecutions;?><i class='icon icon-angle-right'></i></a>
    </div>
  </div>
  <div id="gray-line" hidden></div>
  <div id="closed" hidden><?php echo $lang->execution->closedExecution?></div>
  <div class="table-col col-right executionTree">
   <div class='list-group executions'><?php echo $closedExecutionsHtml;?></div>
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
        if($('#swapper input[type="search"]').val() == '')
        {
            $(this).siblings().show();
            $(this).parent().siblings('li').find('span').hide();
        }
    })

    $('#swapper [data-ride="tree"]').tree('expand');

    $('#swapper #dropMenu .search-box').on('onSearchChange', function(event, value)
    {
        if(value != '')
        {
            $('div.hide-in-search').siblings('i').addClass('hide-in-search');
            $('.nav-tabs li span').hide();
        }
        else
        {
            $('div.hide-in-search').siblings('i').removeClass('hide-in-search');
            $('li.has-list div.hide-in-search').removeClass('hidden');
            $('.nav-tabs li.active').find('span').show();
        }

        if($('.form-control.search-input').val().length > 0)
        {
            $('#closed').attr("hidden", false);
            $('#gray-line').attr("hidden", false);
        }
        else
        {
            $('#closed').attr("hidden", true);
            $('#gray-line').attr("hidden", true);
        }
    });

    $('#swapper #dropMenu').on('onSearchComplete', function(event, value)
    {
        if($('.list-group.executions').height() == 0)
        {
            $('#closed').attr("hidden", true);
            $('#gray-line').attr("hidden", true);
        }

        var listItem = $(this).find('.has-list');
        listItem.each(function ()
        {
            $(this).css('display','')
            var $hidden = $(this).find('.hidden');
            var $item   = $(this).find('.search-list-item');
            if($hidden.length == $item.length) $(this).css('display','none');
        });
    });
})
</script>
