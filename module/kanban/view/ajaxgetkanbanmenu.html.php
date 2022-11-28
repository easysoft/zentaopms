<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<style>
#navTabs {position: sticky; top: 0; background: #fff; z-index: 950;}
#navTabs>li {padding: 0px 10px; display: inline-block}
#navTabs>li>span {display: inline-block;}
#navTabs>li>a {margin: 0!important; padding: 8px 0px; display: inline-block}

#tabContent {margin-top: 5px; z-index: 900; max-width: 220px}
.kanbanTree ul {list-style: none; margin: 0}
.kanbanTree .kanbans>ul>li>div {display: flex; flex-flow: row nowrap; justify-content: flex-start; align-items: center;}
.kanbanTree .kanbans>ul>li label {background: rgba(255,255,255,0.5); line-height: unset; color: #838a9d; border: 1px solid #d8d8d8; border-radius: 2px; padding: 1px 4px;}
.kanbanTree li a i.icon {font-size: 15px !important;}
.kanbanTree li a i.icon:before {min-width: 16px !important;}
.kanbanTree li .label {position: unset; margin-bottom: 0;}
.kanbanTree li>a, div.hide-in-search>a {display: block; padding: 2px 10px 2px 5px; overflow: hidden; line-height: 20px; text-overflow: ellipsis; white-space: nowrap; border-radius: 4px;}
.kanbanTree .tree li>.list-toggle {line-height: 24px;}
.kanbanTree .tree li.has-list.open:before {content: unset;}

#swapper li>div.hide-in-search>a:focus, #swapper li>div.hide-in-search>a:hover {color: #838a9d; cursor: default;}
#swapper li > a {margin-top: 4px; margin-bottom: 4px;}
#swapper li {padding-top: 0; padding-bottom: 0;}
#swapper .tree li>.list-toggle {top: -1px;}
</style>
<?php
$kanbanCounts  = array();
$kanbanNames   = array();
$tabActive     = '';
$myKanbans     = 0;
$others        = 0;
$dones         = 0;
$currentKanban = '';

foreach($kanbanList as $spaceID => $spaceKanbans)
{
    $kanbanCounts[$spaceID]['myKanban'] = 0;
    $kanbanCounts[$spaceID]['others']   = 0;
    $kanbanCounts[$spaceID]['closed']   = 0;

    foreach($spaceKanbans as $kanban)
    {
        if($kanban->status != 'closed' and $kanban->owner == $this->app->user->account) $kanbanCounts[$spaceID]['myKanban'] ++;
        if($kanban->status != 'closed' and !($kanban->owner == $this->app->user->account)) $kanbanCounts[$spaceID]['others'] ++;
        if($kanban->status == 'closed') $kanbanCounts[$spaceID]['closed'] ++;
        $kanbanNames[] = $kanban->name;
    }
}
$kanbansPinYin = common::convert2Pinyin($kanbanNames);

$myKanbansHtml     = '<ul class="tree tree-angles" data-ride="tree">';
$normalKanbansHtml = '<ul class="tree tree-angles" data-ride="tree">';
$closedKanbansHtml = '<ul class="tree tree-angles" data-ride="tree">';

foreach($kanbanList as $spaceID => $spaceKanbans)
{
    /* Add the space name before kanban. */
    if(isset($spaceList[$spaceID]))
    {
        $spaceName = zget($spaceList, $spaceID);

        if($kanbanCounts[$spaceID]['myKanban']) $myKanbansHtml  .= '<li><div class="hide-in-search"><a class="text-muted not-list-item" title="' . $spaceName . '">' . $spaceName . '</a> <label class="label">' . $lang->kanbanspace->common . '</label></div><ul>';
        if($kanbanCounts[$spaceID]['others']) $normalKanbansHtml .= '<li><div class="hide-in-search"><a class="text-muted not-list-item" title="' . $spaceName . '">' . $spaceName . '</a> <label class="label">' . $lang->kanbanspace->common . '</label></div><ul>';
        if($kanbanCounts[$spaceID]['closed']) $closedKanbansHtml .= '<li><div class="hide-in-search"><a class="text-muted not-list-item" title="' . $spaceName . '">' . $spaceName . '</a> <label class="label">' . $lang->kanbanspace->common . '</label></div><ul>';
    }

    foreach($spaceKanbans as $index => $kanban)
    {
        if($kanban->id == $kanbanID) $currentKanban = $kanban;
        $selected   = $kanban->id == $kanbanID ? 'selected' : '';
        $link       = helper::createLink('kanban', 'view', "kanbanID=%s", '', '', $kanban->id);
        $kanbanName = '<i class="icon icon-kanban"></i> ' . $kanban->name;

        if($kanban->status != 'closed' and $kanban->owner == $this->app->user->account)
        {
            $myKanbansHtml .= '<li>' . html::a(sprintf($link, $kanban->id), $kanbanName, '', "class='$selected clickable' title='{$kanban->name}' data-key='" . zget($kanbansPinYin, $kanban->name, '') . "'") . '</li>';

            if($selected == 'selected') $tabActive = 'myKanban';

            $myKanbans ++;
        }
        elseif($kanban->status != 'closed' and !($kanban->owner == $this->app->user->account))
        {
            $normalKanbansHtml .= '<li>' . html::a(sprintf($link, $kanban->id), $kanbanName, '', "class='$selected clickable' title='{$kanban->name}' data-key='" . zget($kanbansPinYin, $kanban->name, '') . "'") . '</li>';

            if($selected == 'selected') $tabActive = 'other';

            $others ++;
        }
        elseif($kanban->status == 'closed')
        {
            $closedKanbansHtml .= '<li>' . html::a(sprintf($link, $kanban->id), $kanbanName, '', "class='$selected clickable' title='{$kanban->name}' data-key='" . zget($kanbansPinYin, $kanban->name, '') . "'") . '</li>';

            if($selected == 'selected') $tabActive = 'closed';
        }

        /* If the kanban is the last one in the space, print the closed label. */
        if(isset($spaceList[$spaceID]) and !isset($spaceKanbans[$index + 1]))
        {
            if($kanbanCounts[$spaceID]['myKanban']) $myKanbansHtml     .= '</ul></li>';
            if($kanbanCounts[$spaceID]['others'])   $normalKanbansHtml .= '</ul></li>';
            if($kanbanCounts[$spaceID]['closed'])   $closedKanbansHtml .= '</ul></li>';
        }
    }
}
$myKanbansHtml     .= '</ul>';
$normalKanbansHtml .= '</ul>';
$closedKanbansHtml .= '</ul>';
?>

<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
      <?php $tabActive = ($myKanbans and ($tabActive == 'closed' or $tabActive == 'myKanban')) ? 'myKanban' : 'other';?>
      <?php if($myKanbans): ?>
      <ul class="nav nav-tabs  nav-tabs-primary" id="navTabs">
        <li class="<?php if($tabActive == 'myKanban') echo 'active';?>"><?php echo html::a('#myKanban', $lang->kanban->my, '', "data-toggle='tab' class='not-list-item not-clear-menu'");?><span class="label label-light label-badge"><?php echo $myKanbans;?></span><li>
        <li class="<?php if($tabActive == 'other') echo 'active';?>"><?php echo html::a('#other', $lang->kanban->other, '', "data-toggle='tab' class='not-list-item not-clear-menu'")?><span class="label label-light label-badge"><?php echo $others;?></span><li>
      </ul>
      <?php endif;?>
      <div class="tab-content kanbanTree" id="tabContent">
        <div class="tab-pane kanbans <?php if($tabActive == 'myKanban') echo 'active';?>" id="myKanban">
          <?php echo $myKanbansHtml;?>
        </div>
        <div class="tab-pane kanbans <?php if($tabActive == 'other') echo 'active';?>" id="other">
          <?php echo $normalKanbansHtml;?>
        </div>
      </div>
    </div>
    <div class="col-footer">
      <a class='pull-right toggle-right-col not-list-item'><?php echo $lang->kanban->closed;?><i class='icon icon-angle-right'></i></a>
    </div>
  </div>
  <div class="table-col col-right kanbanTree">
   <div class='list-group kanbans'><?php echo $closedKanbansHtml;?></div>
  </div>
</div>
<script>
$(function()
{
    <?php if($currentKanban->status == 'closed'):?>
    $('.col-footer .toggle-right-col').click(function(){ scrollToSelected(); })
    <?php else:?>
    scrollToSelected();
    <?php endif;?>

    $('#dropMenu .nav-tabs li span').hide();
    $('#dropMenu .nav-tabs li.active').find('span').show();

    $('#dropMenu .nav-tabs>li a').click(function()
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
            $('#dropMenu div.hide-in-search').siblings('i').addClass('hide-in-search');
            $('#dropMenu .nav-tabs li span').hide();
        }
        else
        {
            $('#dropMenu div.hide-in-search').siblings('i').removeClass('hide-in-search');
            $('#dropMenu li.has-list div.hide-in-search').removeClass('hidden');
            $('#dropMenu .nav-tabs li.active').find('span').show();
        }
    })
})
</script>
