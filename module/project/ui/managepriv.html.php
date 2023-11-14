<?php
declare(strict_types=1);
/**
 * The managePriv view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

div
(
    setID('featureBar'),
    menu
    (
        setClass('nav nav-feature'),
        li
        (
            span
            (
                icon('lock mr-2'),
                $group->name
            )
        ),
        li
        (
            span
            (
                set::className('text-md text-gray'),
                html($lang->arrow)
            )
        ),
        li
        (
            setclass('nav-item'),
            a
            (
                setclass('active'),
                set::href(inlink('managepriv', "projectID=$projectID&type=byGroup&param=$groupID")),
                span($lang->group->all)
            )
        )
    )
);

$getActions = function($moduleActions, $moduleName, $groupPrivs)
{
    global $lang;

    $i = 1;
    $actionItems = null;
    foreach($moduleActions as $action => $actionLabel)
    {
        if(!empty($lang->$moduleName->menus) and $action == 'browse') continue;

        $actionItems[] = div
            (
                setClass('group-item'),
                checkbox
                (
                    setID("actions[{$moduleName}]{$action}"),
                    set::name("actions[{$moduleName}][]"),
                    set::text($lang->$moduleName->$actionLabel),
                    set::value($action),
                    set::checked(isset($groupPrivs[$moduleName][$action]) && $groupPrivs[$moduleName][$action] == $action)
                )
            );
    }

    return $actionItems;
};

$tableBody    = null;
$modulePrivs  = 0;
$moduleSelect = 0;
foreach($lang->resource as $moduleName => $moduleActions)
{
    if(!count((array)$moduleActions)) continue;

    $methodPrivs  = 0;
    $mehtodSelect = isset($groupPrivs[$moduleName]) ? count($groupPrivs[$moduleName]) : 0;
    foreach($moduleActions as $action => $actionLabel)
    {
        if(!empty($lang->$moduleName->menus) and $action == 'browse') continue;
        $methodPrivs ++;
    }

    $tableBody [] = h::tr
        (
            setClass(cycle('even, bg-gray')),
            h::th
            (
                setClass('module'),
                checkbox
                (
                    set::rootClass('check-all'),
                    setID("allChecker{$moduleName}"),
                    set::text($lang->$moduleName->common),
                    set::checked($methodPrivs == $mehtodSelect)
                )
            ),
            isset($lang->$moduleName->menus) ? h::td
            (
                setClass('menus'),
                checkbox
                (
                    setID("actions[{$moduleName}]browse"),
                    set::name("actions[{$moduleName}][]"),
                    set::text($lang->$moduleName->browse),
                    set::value('browse'),
                    set::checked(isset($groupPrivs[$moduleName]) && $groupPrivs[$moduleName] == 'browse')
                ),
                icon('plus'),
                checkList
                (
                    set::items($lang->$moduleName->menus),
                    set::name("actions[{$moduleName}][]"),
                    set::value(isset($groupPrivs[$moduleName]) ? implode(',', $groupPrivs[$moduleName]) : '')
                )
            ) : null,
            h::td
            (
                set('id', $moduleName),
                set('colspan', !empty($lang->$moduleName->menus) ? 1 : 2),
                $getActions($moduleActions, $moduleName, $groupPrivs)
            )
        );

    $modulePrivs ++;
    if(isset($groupPrivs[$moduleName])) $moduleSelect ++;
}

panel
(
    setID('managePrivPanel'),
    form
    (
        setID('managePrivForm'),
        set::actions(array()),
        h::table
        (
            setID('privList'),
            setClass('table table-hover table-striped table-bordered'),
            h::thead
            (
                h::tr
                (
                    h::th
                    (
                        setClass('module'),
                        $lang->group->module
                    ),
                    h::th
                    (
                        set('colspan', 2),
                        $lang->group->method
                    )
                )
            ),
            $tableBody,
            h::tr
            (
                h::th
                (
                    checkbox
                    (
                        set::rootClass('check-all'),
                        setID('allChecker'),
                        set::text($lang->selectAll),
                        set::checked($modulePrivs == $moduleSelect)
                    )
                ),
                h::td
                (
                    set('colspan', 2),
                    setClass('form-actions'),
                    toolbar
                    (
                        btn(set(array('text' => $lang->save, 'btnType' => 'submit', 'type' => 'primary', 'class' => 'mx-6'))),
                        btn(set(array('text' => $lang->goback, 'url' => createLink('project', 'group', "projectID={$projectID}"), 'back' => true)))
                    ),
                    formHidden('noChecked', 1)
                )
            )
        )
    )
);

/* ====== Render page ====== */
render();
