<?php
declare(strict_types=1);
/**
 * The manageView view file of group module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     group
 * @link        https://www.zentao.net
 */
namespace zin;

$viewCheckList = null;
foreach($lang->mainNav as $menuKey => $menu)
{
    if(!is_string($menu)) continue;
    list($moduleName, $module) = explode('|', $menu);
    if($menuKey == 'my') continue;

    $moduleName      = strip_tags($moduleName);
    $viewCheckList[] = checkbox
    (
        set::rootClass('group-item'),
        set::id($menuKey),
        set::name('actions[views][' . strtolower($menuKey) . ']'),
        set::text($moduleName),
        set::checked(isset($group->acl['views'][$menuKey]) || empty($group->acl['views'])),
        on::change('toggleBox')
    );
}

$viewCheckList[] = checkbox
(
    set::rootClass('group-item'),
    set::name('actionallchecker'),
    set::text($lang->selectAll),
    set::checked(empty($group->acl['views'])),
    on::click('selectAll'),
    on::change('toggleBox')
);

function getActionsBox($navGroup, $group, $module)
{
    global $lang;
    $actionsBox = null;
    if(isset($lang->action->dynamicAction->$module))
    {
        foreach($lang->action->dynamicAction->$module as $action => $actionTitle)
        {
            $actionsBox[] = div
            (
                set::className('action-item'),
                checkbox
                (
                    set::id("{$module}-{$action}"),
                    set::name("actions[actions][$module][$action]"),
                    set::text($actionTitle),
                    set::checked(isset($group->acl['actions'][$module][$action]) || !isset($group->acl['actions']))
                )
            );
        }
    }

    if(isset($navGroup[$module]))
    {
        foreach($navGroup[$module] as $subModule)
        {
            if(isset($lang->action->dynamicAction->$subModule))
            {
                foreach($lang->action->dynamicAction->$subModule as $action => $actionTitle)
                {
                    $actionsBox[] = div
                    (
                        set::className('action-item'),
                        checkbox
                        (
                            set::id("$subModule-$action"),
                            set::name("actions[actions][$subModule][$action]"),
                            set::text($actionTitle),
                            set::checked(isset($group->acl['actions'][$subModule][$action]) || !isset($group->acl['actions']))
                        )
                    );
                }
            }
        }
    }

    return $actionsBox;
}

$dynamicActionList = null;
foreach($lang->mainNav as $module => $title)
{
    if(!is_string($title)) continue;

    /* Ignore null actions menus. */
    $isNullActions = true;
    if(isset($lang->action->dynamicAction->$module)) $isNullActions = false;
    if(isset($navGroup[$module]) and $isNullActions)
    {
        foreach($navGroup[$module] as $subModule)
        {
            if(isset($lang->action->dynamicAction->$subModule))
            {
                $isNullActions = false;
                break;
            }
        }
    }
    if($isNullActions) continue;
    $dynamicActionList[] = div
    (
        set::id("{$module}ActionBox"),
        set::className('flex w-full'),
        div
        (
            set::className('flex item-center action-title'),
            checkbox
            (
                set::name('allchecker'),
                on::click('selectItems')
            ),
            span(html(substr($title, 0, strpos($title, '|'))))
        ),
        div
        (
            set::className('flex check-list-inline w-full justify-start'),
            getActionsBox($navGroup, $group, $module)
        )
    );
}


formPanel
(
    set::id('manageViewForm'),
    span
    (
        set::className('text-md font-bold'),
        icon('lock mr-2'),
        $group->name
    ),
    span
    (
        set::className('text-md text-gray'),
        html($lang->arrow),
        $lang->group->manageView
    ),
    formGroup
    (
        set::label($lang->group->viewList),
        set::className('items-center'),
        div
        (
            set::className('viewBox check-list-inline flex flex-wrap w-full'),
            $viewCheckList
        )
    ),
    $config->systemMode == 'ALM' ? formRow
    (
        set::id('programBox'),
        set::className('items-center hidden'),
        formGroup
        (
            setClass('items-center'),
            set::label($lang->group->programList),
            $programs ? inputGroup
            (
                setClass('input-control'),
                picker
                (
                    set::name('actions[programs][]'),
                    set::items($programs),
                    set::value(isset($group->acl['programs']) ? join(',', $group->acl['programs']) : ''),
                    set::multiple(true)
                ),
                div
                (
                    setClass('input-group-btn flex'),
                    div
                    (
                        setClass('btn btn-default cursor-text'),
                        icon('info text-warning'),
                        $lang->group->noticeVisit
                    )
                )
            ) : span(
                set::className('flex items-center'),
                icon('info text-warning mr-2'),
                $lang->group->noneProgram
            )
        )
    ) : null,
    formRow
    (
        set::id('productBox'),
        set::className('items-center hidden'),
        formGroup
        (
            setClass('items-center'),
            set::label($lang->group->productList),
            $products ? inputGroup
            (
                picker
                (
                    set::name('actions[products][]'),
                    set::items($products),
                    set::value(isset($group->acl['products']) ? join(',', $group->acl['products']) : ''),
                    set::multiple(true)
                ),
                div
                (
                    setClass('input-group-btn flex'),
                    div
                    (
                        setClass('btn btn-default cursor-text'),
                        icon('info text-warning'),
                        $lang->group->noticeVisit
                    )
                )
            ) : span(
                set::className('flex items-center'),
                icon('info text-warning mr-2'),
                $lang->group->noneProduct
            )
        )
    ),
    formRow
    (
        set::className('items-center hidden'),
        set::id('projectBox'),
        formGroup
        (
            setClass('items-center'),
            set::label($lang->group->projectList),
            $projects ? inputGroup
            (
                picker
                (
                    set::name('actions[projects][]'),
                    set::items($projects),
                    set::value(isset($group->acl['projects']) ? join(',', $group->acl['projects']) : ''),
                    set::multiple(true)
                ),
                div
                (
                    setClass('input-group-btn flex'),
                    div
                    (
                        setClass('btn btn-default cursor-text'),
                        icon('info text-warning'),
                        $lang->group->noticeVisit
                    )
                ),
            ) : span(
                set::className('flex items-center'),
                icon('info text-warning mr-2'),
                $lang->group->noneProject
            )
        )
    ),
    formRow
    (
        set::className('items-center hidden'),
        set::id('executionBox'),
        formGroup
        (
            setClass('items-center'),
            set::label($lang->group->executionList),
            $executions ? inputGroup
            (
                picker
                (
                    set::name('actions[sprints][]'),
                    set::items($executions),
                    set::value(isset($group->acl['sprints']) ? join(',', $group->acl['sprints']) : ''),
                    set::multiple(true)
                ),
                div
                (
                    setClass('input-group-btn flex'),
                    div
                    (
                        setClass('btn btn-default cursor-text'),
                        icon('info text-warning'),
                        $lang->group->noticeVisit
                    )
                )
            ) : span(
                set::className('flex items-center'),
                icon('info text-warning mr-2'),
                $lang->group->noneExecution
            )
        )
    ),
    $config->vision != 'or' ? formGroup
    (
        set::label($lang->group->dynamic),
        $dynamicActionList
    ) : null
);

/* ====== Render page ====== */
render();
