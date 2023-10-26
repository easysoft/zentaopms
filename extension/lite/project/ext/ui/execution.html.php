<?php
declare(strict_types=1);
/**
 * The all view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     execution
 * @link        http://www.zentao.net
 */

namespace zin;

$this->app->loadLang('kanban');
$kanbanview = 'kanban';
if(!empty($_COOKIE['kanbanview'])) $kanbanview = $_COOKIE['kanbanview'];
if(!common::hasPriv('execution', $kanbanview))
{
    foreach(explode('|', 'kanban|task|calendar|gantt|tree|grouptask') as $view)
    {
        if(!common::hasPriv('execution', $view)) continue;
        $kanbanview = $view;
        break;
    }
}

$fnGetKanbanTeams = function($kanbanID) use ($memberGroup, $users, $usersAvatar)
{
    $teams = zget($memberGroup, $kanbanID, array());
    $count = 0;
    $teamElements = array();
    foreach($teams as $member)
    {
        if($count > 2) break;
        $count ++;
        $teamElements[] = div
        (
            set::title(zget($users, $member->account)),
            userAvatar(set::avatar(zget($usersAvatar, $member->account)), set::account($member->account), set::realname(zget($member, 'realname', ''))),
        );
    }
    if(count($teams) > 4) $teamElements[] = span('… ');
    if(count($teams) > 3)
    {
        $lastMember = end($teams);
        $teamElements[] = div
        (
            set::title(zget($users, $lastMember->account)),
            userAvatar(set::avatar(zget($usersAvatar, $lastMember->account)), set::account($lastMember->account), set::realname(zget($lastMember, 'realname', '')), set::style(array('width' => '24px', 'height' => '24px'))),
        );
    }
    return $teamElements;
};

$fnBuildSingleCard = function($kanban) use ($executionActions, $lang, $kanbanview, $memberGroup, $fnGetKanbanTeams)
{
    $canActions  = (common::hasPriv('execution','edit') or (!empty($executionActions) and isset($executionActions[$kanban->id])));
    $teams       = zget($memberGroup, $kanban->id, array());
    $actionItems = array();
    if(common::hasPriv('execution','edit')) $actionItems[] = array('url' => createLink('execution', 'edit', "executionID={$kanban->id}"), 'text' => $lang->kanban->edit, 'icon' => 'edit', 'data-toggle' => 'modal', 'data-width' => '75%');
    if(!empty($executionActions[$kanban->id]))
    {
        if(in_array('start', $executionActions[$kanban->id]))    $actionItems[] = array('url' => createLink('execution', 'start',    "executionID={$kanban->id}"), 'text' => $lang->execution->start,    'icon' => 'play',     'data-toggle' => 'modal', 'data-width' => '75%');
        if(in_array('putoff', $executionActions[$kanban->id]))   $actionItems[] = array('url' => createLink('execution', 'putoff',   "executionID={$kanban->id}"), 'text' => $lang->execution->putoff,   'icon' => 'calendar', 'data-toggle' => 'modal', 'data-width' => '75%');
        if(in_array('suspend', $executionActions[$kanban->id]))  $actionItems[] = array('url' => createLink('execution', 'suspend',  "executionID={$kanban->id}"), 'text' => $lang->execution->suspend,  'icon' => 'pause',    'data-toggle' => 'modal', 'data-width' => '75%');
        if(in_array('close', $executionActions[$kanban->id]))    $actionItems[] = array('url' => createLink('execution', 'close',    "executionID={$kanban->id}"), 'text' => $lang->execution->close,    'icon' => 'off',      'data-toggle' => 'modal', 'data-width' => '75%');
        if(in_array('activate', $executionActions[$kanban->id])) $actionItems[] = array('url' => createLink('execution', 'activate', "executionID={$kanban->id}"), 'text' => $lang->execution->activate, 'icon' => 'magic',    'data-toggle' => 'modal', 'data-width' => '75%');
        if(in_array('delete', $executionActions[$kanban->id]))   $actionItems[] = array('url' => createLink('execution', 'delete',   "executionID={$kanban->id}"), 'text' => $lang->execution->delete,   'icon' => 'trash');
    }

    return cell
    (
        setID("kanban-{$kanban->id}"),
        setClass('kanban-card'),
        set::width('1/4'),
        set('data-url', createLink('execution', $kanbanview, "kanbanID=$kanban->id")),
        div
        (
            setClass('panel'),
            div
            (
                setClass('panel-heading justify-start'),
                set::style(array('position' => 'relative')),
                span(setClass("label kanban-status-{$kanban->status}"), zget($lang->execution->statusList, $kanban->status)),
                h::strong(setClass('kanban-name'), set::title($kanban->name), $kanban->name),
                $canActions ? div
                (
                    setClass("kanban-actions kanban-actions{$kanban->id}"),
                    dropdown
                    (
                        icon('ellipsis-v'),
                        set::items($actionItems),
                        set::hasIcons(true),
                    ),
                ) : null,
            ),
            div
            (
                setClass('panel-body'),
                div(setClass('kanban-desc'), $kanban->desc),
                row
                (
                    setClass('kanban-footer'),
                    set::style(array('position' => 'relative')),
                    cell
                    (
                        setClass('kanban-members'),
                        $fnGetKanbanTeams($kanban->id),
                    ),
                    cell
                    (
                        setClass('kanban-members-count'),
                        sprintf($lang->project->teamSumCount, count($teams)),
                    ),
                    cell
                    (
                        setClass('kanban-acl'),
                        span(icon($kanban->acl == 'private' ? 'lock' : 'unlock-alt'), zget($lang->project->acls, $kanban->acl, '')),
                    ),
                )
            )
        )
    );
};

$fnShowCards = function($kanbanList) use ($fnBuildSingleCard)
{
    $cards = array();
    foreach($kanbanList as $kanban) $cards[] = $fnBuildSingleCard($kanban);

    return row(setClass('kanban-cards'), $cards);
};

data('recTotal', count($kanbanList));
featureBar
(
    set::current($status),
    set::linkParams("status={key}&projectID={$projectID}&orderBy=&productID=0&recTotal=0&recPerPage=10&pageID=1"),
);

toolbar
(
    hasPriv('execution', 'create') ? item(set(array
    (
        'icon' => 'plus',
        'text' => $lang->project->createKanban,
        'class' => "primary create-execution-btn",
        'url'   => createLink('execution', 'create', "projectID={$projectID}"),
    ))) : null
);

$fnEmptyTip = function() use($allExecutionsNum, $lang, $projectID)
{
    return div
    (
        setClass('dtable-empty-tip'),
        p
        (
            span(setClass('text-muted'), $lang->execution->noExecution),
            (common::hasPriv('execution', 'create') && $allExecutionsNum) ? a(set::href(createLink('execution', 'create', "projectID=$projectID")), setClass('btn primary-pale border-primary'), set('data-app', 'project'), icon('plus'),  $lang->project->createKanban) : null,
        )
    );
};

div
(
    setClass('dtable'),
    empty($kanbanList) ? $fnEmptyTip() : $fnShowCards($kanbanList),
);
