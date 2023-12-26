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
jsVar('window.selectedPrivIdList', $selectedPrivList);
jsVar('allPrivList', $allPrivList);
jsVar('groupID', $groupID);
jsVar('type', $type);
jsVar('nav', $nav);

if($group->role == 'limited')
{
    div
    (
        setID('featureBar'),
        menu
        (
            setClass('nav nav-feature w-full'),
            li
            (
                setclass('nav-item'),
                a
                (
                    setclass('active'),
                    span($group->name)
                )
            )
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
                        h::th($lang->group->method)
                    )
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
                            set::text($lang->my->limited)
                        )
                    ),
                    formHidden('noChecked', '')
                )
            )
        ),
        toolbar
        (
            setClass('form-actions w-1/2'),
            btn(set(array('text' => $lang->save, 'btnType' => 'submit', 'type' => 'primary', 'onclick' => 'setNoChecked()'))),
            btn(set(array('text' => $lang->goback, 'url' => createLink('group', 'browse'), 'back' => true)))
        )
    );
}
else
{
    $params        = "type=byPackage&param=$groupID&nav=%s&version=$version";
    $mainNavItems  = null;
    $i             = 0;
    $dropDownItems = array();
    foreach($lang->mainNav as $navKey => $title)
    {
        if(!is_string($title)) continue;

        $i++;
        if($i >= $config->group->maxToolBarCount)
        {
            $dropDownItems[] = array
                (
                    'text'  => strip_tags(substr($title, 0, strpos($title, '|'))),
                    'url'   => inlink('managePriv', sprintf($params, $navKey)),
                    'class' => $nav == $navKey ? 'active' : ''
                );
        }
    }

    $i = 0;
    foreach($lang->mainNav as $navKey => $title)
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
                        set::items($dropDownItems)
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
                        setClass($nav == $navKey ? 'active' : ''),
                        set::href(inlink('managePriv', sprintf($params, $navKey))),
                        strip_tags(substr($title, 0, strpos($title, '|')))
                    )
                );
        }
    }

    function getPrivsItemsHtml($privs, $moduleName, $packageID, $groupPrivs)
    {
        global $lang;
        $html = '';
        foreach($privs as $privID => $priv)
        {
            if(!empty($lang->$moduleName->menus) && ($priv->method == 'browse' or in_array($priv->method, array_keys($lang->$moduleName->menus)))) continue;
            $privMethod = isset($groupPrivs[$priv->module][$priv->method]) ? $priv->method : '';

            $checked = $priv->method == $privMethod;
            $checkID = "actions[{$priv->module}]{$priv->method}";
            $html .= "<div class='group-item' data-module='$moduleName' data-package='$packageID' data-divid='{$moduleName}{$packageID}' data-id='$privID'><div class='checkbox-primary'>";
            $html .= "<input type='checkbox' id='$checkID' value='$priv->method' name='actions[{$priv->module}][]' data-id='$privID'". ($checked ? ' checked' : '') . ">";
            $html .= "<label for='$checkID'>$priv->name</label>";
            $html .= '</div></div>';
        }

        return $html;
    };

    $getPackagesBoxHtml = function($subsetName, $packages, $groupPrivs)
    {
        global $lang;
        $html = '';
        foreach($packages as $packageID => $package)
        {
            $packagePrivs  = $package->allCount;
            $packageSelect = $package->selectCount;

            $checked = $packagePrivs == $packageSelect;
            $checkID = "allCheckerModule{$subsetName}Package{$packageID}";

            $html .= "<div class='package' data-module='$subsetName' data-package='$packageID' all-privs='$packagePrivs' select-privs='$packageSelect' data-divid='{$subsetName}{$packageID}'>";
            $html .= "<div class='checkbox-primary checkbox-inline checkbox-left check-all'>";
            $html .= "<input type='checkbox' id='$checkID' value='1'". ($checked ? ' checked' : '') . ">";
            $html .= "<label class='" . (!empty($packageSelect) && $packagePrivs != $packageSelect ? 'text-left checkbox-indeterminate-block' : 'text-left') . "' for='$checkID'>{$lang->group->package->$packageID}</label>";
            $html .= '</div>';
            $html .= '<i class="icon priv-toggle"></i>';
            $html .= '</div>';

            $html .= "<div class='privs hidden' data-module='$subsetName' data-package='$packageID' data-divid='{$subsetName}{$packageID}'>";
            $html .= '<div class="arrow"></div>';
            $html .= '<div class="popover-content">';
            $html .= getPrivsItemsHtml($package->privs, $subsetName, $packageID, $groupPrivs);
            $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    };

    $privBodyHtml = array();
    foreach($subsets as $subsetName => $subset)
    {
        if($subset->allCount == 0) continue;
        $subsetTitle = isset($lang->$subsetName) && isset($lang->$subsetName->common) ? $lang->$subsetName->common : $subsetName;

        $privBodyHtml[] = '<tr>';

        $checked = $subset->selectCount && $subset->selectCount == $subset->allCount;
        $checkID = "allChecker{$subsetName}";
        $privBodyHtml[] = "<th class='text-middle text-left module' data-module='$subsetName' all-privs='$subset->allCount' select-privs='$subset->selectCount'>";
        $privBodyHtml[] = '<div class="checkbox-inline checkbox-left check-all"><div class="checkbox-primary">';
        $privBodyHtml[] = "<input type='checkbox' id='$checkID' value='1'" . ($checked ? ' checked' : '') . ">";
        $privBodyHtml[] = "<label for='$checkID' class='" . ($subset->selectCount && $subset->selectCount != $subset->allCount ? 'text-left checkbox-indeterminate-block' : 'text-left') . "'>{$subsetTitle}</label>";
        $privBodyHtml[] = '</div></div>';
        $privBodyHtml[] = '</th>';

        $privBodyHtml[] = "<td class='td-sm text-middle text-left package-column' data-module='$subsetName'>";
        $privBodyHtml[] = $getPackagesBoxHtml($subsetName, $packages[$subsetName], $groupPrivs);
        $privBodyHtml[] = '</td>';

        $privBodyHtml[] = '</tr>';
    }

    $dependTree = null;
    foreach($relatedPrivData['depend'] as $dependPrivs)
    {
        $dependTree[] = checkboxGroup
            (
                set::title(array('text' => $dependPrivs['text'], 'id' => "dependPrivs[{$dependPrivs['id']}]", 'name' => 'dependPrivs[]', 'data-id' => $dependPrivs['id'], 'data-has-children' => !empty($dependPrivs['children']), 'disabled' => true, 'checked' => true)),
                !empty($dependPrivs['children']) ? set::items($dependPrivs['children']) : null
            );
    }

    $recommendTree = null;
    foreach($relatedPrivData['recommend'] as $recommendPrivs)
    {
        $recommendTree[] = checkboxGroup
            (
                set::title(array('text' => $recommendPrivs['text'], 'id' => "recommendPrivs[{$recommendPrivs['id']}]", 'name' => 'recommendPrivs[]', 'data-id' => $recommendPrivs['id'], 'data-has-children' => !empty($recommendPrivs['children']))),
                !empty($recommendPrivs['children']) ? set::items($recommendPrivs['children']) : null
            );
    }

    div
    (
        setID('featureBar'),
        menu
        (
            setClass('nav nav-feature w-full'),
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
                    setclass(empty($nav) ? 'active' : ''),
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
                    setClass($nav == 'general' ? 'active' : ''),
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
                on::change('showPriv')
            )
        )
    );

    formBase
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
                setClass('main main-content canvas'),
                div
                (
                    setClass('btn-group'),
                    a
                    (
                        setClass('btn switchBtn text-primary'),
                        set::href(inlink('managePriv', "type=byPackage&param=$groupID&nav=$nav&version=$version")),
                        html("<i class='icon-has-authority-pack'></i>")
                    ),
                    a
                    (
                        setClass('btn switchBtn'),
                        set::href(inlink('managePriv', "type=byGroup&param=$groupID&nav=$nav&version=$version")),
                        html("<i class='icon-without-authority-pack'></i>")
                    )
                ),
                h::table
                (
                    setID('privPackageList'),
                    setClass('table table-striped table-bordered'),
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
                    h::tbody(html($privBodyHtml))
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
                            setClass('text-gray')
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
                            )
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
                            setClass('text-gray')
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
                            )
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
                set::text($lang->selectAll)
            ),
            btn(set(array('text' => $lang->save, 'btnType' => 'submit', 'type' => 'primary', 'onclick' => 'setNoChecked()'))),
            btn(set(array('text' => $lang->goback, 'url' => createLink('group', 'browse'), 'back' => true)))
        )
    );
}

/* ====== Render page ====== */
render();
