<?php
declare(strict_types=1);
/**
 * The privbypackage view file of group module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     group
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('window.selectedPrivIdList', $selectedPrivIdList);
jsVar('excludeIdList', $excludePrivsIdList);
jsVar('groupID', $groupID);
jsVar('type', $type);
jsVar('menu', $menu);

if($group->role == 'limited')
{
    div
    (
        setID('featureBar'),
        menu
        (
            setClass('nav nav-feature'),
            li
            (
                setclass('nav-item'),
                a
                (
                    setclass('active'),
                    span($group->name)
                )
            ),
        )
    );

    form
    (
        setID('managePrivForm'),
        set::actions(array()),
        div
        (
            setID('mainContent'),
            setClass('main main-content manageLimitGroup'),
            h::table
            (
                setClass('table table-hover table-striped table-bordered'),
                h::thead
                (
                    h::tr
                    (
                        h::th($lang->group->module),
                        h::th($lang->group->method),
                    ),
                ),
                h::tr
                (
                    setClass(cycle('even, bg-gray')),
                    h::th
                    (
                        setClass('text-right w-40'),
                        $lang->my->common
                    ),
                    h::td
                    (
                        setID('my'),
                        checkbox
                        (
                            setID('my-limited'),
                            set::labelClass('priv'),
                            set::name('actions[my][]'),
                            set::value('limited'),
                            set::checked(isset($groupPrivs['my']['limited'])),
                            set::text($lang->my->limited),
                        )
                    ),
                    formHidden('noChecked', ''),
                )
            ),
        ),
        toolbar
        (
            setClass('form-actions w-1/2'),
            btn(set(array('text' => $lang->save, 'btnType' => 'submit', 'type' => 'primary', 'onclick' => 'setNoChecked()'))),
            btn(set(array('text' => $lang->goback, 'url' => createLink('group', 'browse'), 'back' => true))),
        ),
    );
}
else
{
    $params        = "type=byPackage&param=$groupID&menu=%s&version=$version";
    $mainNavItems  = null;
    $i             = 0;
    $dropDownItems = array();
    foreach($lang->mainNav as $module => $title)
    {
        if(!is_string($title)) continue;

        $i++;
        if($i >= $config->group->maxToolBarCount)
        {
            $dropDownItems[] = array
                (
                    'text'  => strip_tags(substr($title, 0, strpos($title, '|'))),
                    'url'   => inlink('managePriv', sprintf($params, $module)),
                    'class' => $menu == $module ? 'active' : ''
                );
        }
    }

    $i = 0;
    foreach($lang->mainNav as $module => $title)
    {
        if(!is_string($title) || $i >= $config->group->maxToolBarCount) continue;

        $i++;
        if($i == $config->group->maxToolBarCount)
        {
            $mainNavItems[] = li
                (
                    setClass('nav-item'),
                    dropdown
                    (
                        btn(
                            setClass('ghost btn square btn-default'),
                            $lang->group->more
                        ),
                        set::items($dropDownItems),
                    )
                );
        }
        else
        {
            $mainNavItems[] = li
                (
                    setClass('nav-item'),
                    a
                    (
                        setClass($menu == $module ? 'active' : ''),
                        set::href(inlink('managePriv', sprintf($params, $module))),
                        strip_tags(substr($title, 0, strpos($title, '|')))
                    )
                );
        }
    }

    $getMethodItems = function($moduleName, $groupPrivs)
    {
        global $lang;
        $methodItems = array();
        foreach($lang->$moduleName->menus as $method => $name)
        {
            $methodItems[]= div
                (
                    setClass('group-item menus-item'),
                    set('data-id', "{$moduleName}-{$method}"),
                    set('data-module', $moduleName),
                    set('data-package', 0),
                    set('data-divid', "{$moduleName}0"),
                    checkbox
                    (
                        set::name("actions[{$moduleName}][]"),
                        set::value(isset($groupPrivs[$moduleName][$method]) ? $groupPrivs[$moduleName][$method] : ''),
                        setID("actions[{$moduleName}]{$method}"),
                        set::text($name),
                        set('data-id', "{$moduleName}-{$method}'"),
                    )
                );
        }
        return $methodItems;
    };

    function getPrivsItems($privs, $moduleName, $packageID, $groupPrivs)
    {
        global $lang;
        $privsBox = array();
        foreach($privs as $privID => $priv)
        {
            if(!empty($lang->$moduleName->menus) && ($priv->method == 'browse' or in_array($priv->method, array_keys($lang->$moduleName->menus)))) continue;
            $privMethod = isset($groupPrivs[$priv->module][$priv->method]) ? $priv->method : '';
            $privsBox[] = div
                (
                    setClass('group-item'),
                    set('data-id', zget($priv, 'id', 0)),
                    set('data-module', $moduleName),
                    set('data-package', $packageID),
                    set('data-divid', "{$moduleName}{$packageID}"),
                    div
                    (
                        setClass('checkbox-primary'),
                        checkbox
                        (
                            set::name("actions[{$priv->module}][]"),
                            setID("actions[{$priv->module}]{$priv->method}"),
                            set::value($priv->method),
                            set::checked($priv->method == $privMethod),
                            set::text($priv->name),
                            set('data-id', $priv->action),
                        )
                    )
                );
        }

        return $privsBox;
    };

    $getPackagesBox = function($moduleName, $packages, $groupPrivs, $privPackages, $selectPrivs)
    {
        global $lang;
        $packagesBox = array();
        foreach($packages as $packageID => $privs)
        {
            $packagePrivs  = count($privs);
            $packageSelect = $selectPrivs[$moduleName][$packageID];
            if(isset($lang->$moduleName->menus))
            {
                $menusPrivs  = count($lang->$moduleName->menus);
                $menusSelect = count(array_intersect(array_keys($lang->$moduleName->menus), array_keys(zget($groupPrivs, $moduleName, array()))));
            }

            $packagesBox[] = array
                (
                    div
                    (
                        setClass('package'),
                        set('data-module', $moduleName),
                        set('data-package', $packageID),
                        set('all-privs', $packagePrivs),
                        set('select-privs', $packageSelect),
                        set('data-divid', "{$moduleName}{$packageID}"),
                        div
                        (
                            setClass('checkbox-primary checkbox-inline checkbox-left check-all'),
                            checkbox
                            (
                                setID("allCheckerModule{$moduleName}Package{$packageID}"),
                                set::value(1),
                                set::checked($packagePrivs == $packageSelect),
                                set::text(zget($privPackages, $packageID, $lang->group->other)),
                                set::labelClass(!empty($packageSelect) && $packagePrivs != $packageSelect ? 'text-left checkbox-indeterminate-block' : 'text-left'),
                            ),
                        ),
                        h::i(setClass('priv-toggle icon')),
                    ),
                    div
                    (
                        setClass('privs hidden'),
                        set('data-module', $moduleName),
                        set('data-package', $packageID),
                        set('data-divid', "{$moduleName}{$packageID}"),
                        div(setClass('arrow')),
                        div
                        (
                            setClass('popover-content'),
                            isset($lang->$moduleName->menus) ? div
                            (
                                setClass('group-item menus-browse'),
                                set('data-id', 0),
                                set('data-module', $moduleName),
                                set('data-package', 0),
                                set('data-divid', "{$moduleName}0"),
                                div
                                (
                                    setClass('checkbox-primary checkbox-inline checkbox-left check-all'),
                                    checkBox
                                    (
                                        set::value('browse'),
                                        set::checked($menusPrivs == $menusSelect),
                                        set::text($lang->$moduleName->browse),
                                        set::labelClass(!empty($menusSelect) && $menusPrivs != $menusSelect ? 'text-left checkbox-indeterminate-block' : 'text-left'),
                                    )
                                ),
                                h::i(setClass('priv-toggle icon')),
                                div
                                (
                                    setClass('menus-privs hidden'),
                                    set('data-module', $moduleName),
                                    set('data-package', $packageID),
                                    set('data-divid', "{$moduleName}{$packageID}"),
                                    div(setClass('arrow')),
                                    div
                                    (
                                        setClass('popover-content'),
                                        $getMethodItems($moduleName, $groupPrivs)
                                    )
                                )
                            ) : null,
                            getPrivsItems($privs, $moduleName, $packageID, $groupPrivs)
                        )
                    )
                );
        }
        return $packagesBox;
    };

    $privBody = null;
    foreach($privList as $moduleName => $packages)
    {
        if(!count((array)$packages)) continue;
        $i = 1;
        $modulePrivs  = count($privList[$moduleName], 1) - count($selectPrivs[$moduleName], 1);
        $moduleSelect = array_sum($selectPrivs[$moduleName]);
        $moduleTitle  = $lang->$moduleName->common;
        if(in_array($moduleName, array('doc', 'api'))) $moduleTitle = $lang->$moduleName->manage;

        $privBody[] = h::tr
            (
                setClass(cycle('even, bg-gray')),
                h::th
                (
                    setClass('text-middle text-left module'),
                    set('data-module', $moduleName),
                    set('all-privs', $modulePrivs),
                    set('select-privs', $moduleSelect),
                    div
                    (
                        setClass('checkbox-primary checkbox-inline checkbox-left check-all'),
                        checkbox
                        (
                            setID("allChecker{$moduleName}"),
                            set::labelClass(!empty($moduleSelect) && $modulePrivs != $moduleSelect ? 'text-left checkbox-indeterminate-block' : 'text-left'),
                            set::value(1),
                            set::checked(!empty($moduleSelect) && $modulePrivs == $moduleSelect),
                            set::text($moduleTitle),
                        ),
                    )
                ),
                h::td
                (
                    setClass('td-sm text-middle text-left package-column'),
                    set('data-module', $moduleName),
                    $getPackagesBox($moduleName, $packages, $groupPrivs, $privPackages, $selectPrivs),
                )
            );
    }

    $dependTree = null;
    foreach($relatedPrivData['depend'] as $dependPrivs)
    {
        $dependTree[] = checkboxGroup
            (
                set::title(array('text' => $dependPrivs['title'], 'id' => "dependPrivs[{$dependPrivs['id']}]", 'name' => 'dependPrivs[]', 'data-id' => $dependPrivs['id'], 'data-has-children' => !empty($dependPrivs['children']), 'disabled' => true, 'checked' => true)),
                !empty($dependPrivs['children']) ? set::items($dependPrivs['children']) : null,
            );
    }

    $recommendTree = null;
    foreach($relatedPrivData['recommend'] as $recommendPrivs)
    {
        $recommendTree[] = checkboxGroup
            (
                set::title(array('text' => $recommendPrivs['title'], 'id' => "recommendPrivs[{$recommendPrivs['id']}]", 'name' => 'recommendPrivs[]', 'data-id' => $recommendPrivs['id'], 'data-has-children' => !empty($recommendPrivs['children']))),
                !empty($recommendPrivs['children']) ? set::items($recommendPrivs['children']) : null,
            );
    }

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
                    $group->name,
                ),
            ),
            li
            (
                span
                (
                    set::class('text-md text-gray'),
                    html($lang->arrow),
                ),
            ),
            li
            (
                setclass('nav-item'),
                a
                (
                    setclass(empty($menu) ? 'active' : ''),
                    set::href(inlink('managepriv', sprintf($params, ''))),
                    span($lang->group->all)
                )
            ),
            $mainNavItems,
            li
            (
                setClass('nav-item'),
                a
                (
                    setClass($menu == 'general' ? 'active' : ''),
                    set::href(inlink('managePriv', sprintf($params, 'general'))),
                    span($lang->group->general)
                )
            ),
            picker
            (
                setID('versionSelect'),
                set::name('version'),
                set::items($this->lang->group->versions),
                set::value($version),
                set::placeholder($this->lang->group->versions['']),
                on::change('showPriv'),
            )
        ),
    );

    form
    (
        setID('managePrivForm'),
        formHidden('actions[][]', ''),
        formHidden('noChecked', ''),
        set::actions(array()),
        div
        (
            setID('mainContainer'),
            setClass('flex'),
            div
            (
                setClass('main main-content'),
                div
                (
                    setClass('btn-group'),
                    a
                    (
                        setClass('btn switchBtn text-primary'),
                        set::href(inlink('managePriv', "type=byPackage&param=$groupID&menu=$menu&version=$version")),
                        html("<i class='icon-has-authority-pack'></i>"),
                    ),
                    a
                    (
                        setClass('btn switchBtn'),
                        set::href(inlink('managePriv', "type=byGroup&param=$groupID&menu=$menu&version=$version")),
                        html("<i class='icon-without-authority-pack'></i>"),
                    ),
                ),
                h::table
                (
                    setID('privPackageList'),
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
                                setClass('package'),
                                $lang->privpackage->common
                            )
                        )
                    ),
                    h::tbody
                    (
                        $privBody
                    ),
                )
            ),
            div
            (
                setClass('side'),
                div
                (
                    setClass('priv-panel'),
                    div
                    (
                        setClass('panel-title'),
                        $lang->group->dependentPrivs,
                        icon
                        (
                            'help',
                            set('data-toggle', 'tooltip'),
                            set('data-title', $lang->group->dependPrivTips),
                            set('data-placement', 'right'),
                            set('data-type', 'white'),
                            set('data-class-name', 'text-gray border border-light w-40'),
                            setClass('text-gray'),
                        )
                    ),
                    div
                    (
                        setClass('panel-content'),
                        div
                        (
                            setClass('menuTree depend menu-active-primary menu-hover-primary'),
                            setClass(count($relatedPrivData['depend']) == 0 ? 'hidden' : ''),
                            $dependTree
                        ),
                        div
                        (
                            setClass('table-empty-tip text-center'),
                            setClass(count($relatedPrivData['depend']) > 0 ? 'hidden' : ''),
                            span
                            (
                                setClass('text-gray'),
                                $lang->noData
                            ),
                        )
                    )
                ),
                div
                (
                    setClass('priv-panel mt-m'),
                    div
                    (
                        setClass('panel-title'),
                        $lang->group->recommendPrivs,
                        icon
                        (
                            'help',
                            set('data-toggle', 'tooltip'),
                            set('data-title', $lang->group->recommendPrivTips),
                            set('data-placement', 'right'),
                            set('data-type', 'white'),
                            set('data-class-name', 'text-gray border border-light w-40'),
                            setClass('text-gray'),
                        )
                    ),
                    div
                    (
                        setClass('panel-content'),
                        div
                        (
                            setClass('menuTree recommend menu-active-primary menu-hover-primary'),
                            setClass(count($relatedPrivData['recommend']) == 0 ? 'hidden' : ''),
                            $recommendTree
                        ),
                        div
                        (
                            setClass('table-empty-tip text-center'),
                            setClass(count($relatedPrivData['recommend']) > 0 ? 'hidden' : ''),
                            span
                            (
                                setClass('text-gray'),
                                $lang->noData
                            ),
                        )
                    )
                )
            )
        ),
        toolbar
        (
            setClass('form-actions priv-footer'),
            checkbox
            (
                setID('allChecker'),
                set::rootClass('check-all'),
                set::text($lang->selectAll),
            ),
            btn(set(array('text' => $lang->save, 'btnType' => 'submit', 'type' => 'primary', 'onclick' => 'setNoChecked()'))),
            btn(set(array('text' => $lang->goback, 'url' => createLink('group', 'browse'), 'back' => true))),
        ),
    );
}

/* ====== Render page ====== */
render();
