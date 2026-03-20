<?php
declare(strict_types=1);
/**
 * The privbygroup view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('window.selectedPrivIdList', $selectedPrivList);
jsVar('allPrivList', $allPrivList);
jsVar('groupID', $groupID);
jsVar('projectID', $projectID);
jsVar('type', $type);

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
            btn(set(array('text' => $lang->goback, 'url' => inlink('group', "project={$projectID}"), 'back' => true)))
        )
    );
}
else
{
    $params = "projectID={$projectID}&groupID=$groupID&type=byGroup";
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
            )
        ),
    );

    $getMethodItemsHtml = function($package, $subsetName, $packageID, $groupPrivs)
    {
        $html = '';
        foreach($package->privs as $privID => $priv)
        {
            $checked = isset($groupPrivs[$priv->module][$priv->method]);
            $checkID = "actions[{$priv->module}][{$priv->method}]";
            $html .= "<div class='group-item' data-module='$subsetName' data-package='$packageID' data-divid='{$subsetName}{$packageID}' data-id='$privID'><div class='checkbox-primary'>";
            $html .= "<input type='checkbox' id='$checkID' value='$priv->method' name='actions[{$priv->module}][]' data-id='$privID'". ($checked ? ' checked' : '') . ">";
            $html .= "<label for='$checkID'>$priv->name</label>";
            $html .= '</div></div>';
        }
        return $html;
    };

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

    $privBodyHtml = array();
    foreach($subsets as $subsetName => $subset)
    {
        if($subset->allCount == 0) continue;

        $i = 1;
        foreach($packages[$subsetName] as $packageID => $package)
        {
            $subsetTitle = isset($lang->$subsetName) && isset($lang->$subsetName->common) ? $lang->$subsetName->common : $subsetName;

            $privBodyHtml[] = "<tr zui-key='$packageID'>";
            if($i == 1)
            {
                $rowspan = count($packages[$subsetName]) ? count($packages[$subsetName]) : 1;
                $checked = $subset->selectCount && $subset->selectCount == $subset->allCount;
                $checkID = "allChecker{$subsetName}";
                $privBodyHtml[] = "<th zui-key='module' class='text-middle text-left  module' rowspan='$rowspan' data-module='$subsetName' all-privs='$subset->allCount' select-privs='$subset->selectCount'>";
                $privBodyHtml[] = '<div class="checkbox-inline checkbox-left check-all"><div class="checkbox-primary">';
                $privBodyHtml[] = "<input type='checkbox' id='$checkID' value='1'" . ($checked ? ' checked' : '') . ">";
                $privBodyHtml[] = "<label for='$checkID' class='" . ($subset->selectCount && $subset->selectCount != $subset->allCount ? 'text-left checkbox-indeterminate-block' : 'text-left') . "'>$subsetTitle</label>";
                $privBodyHtml[] = '</div></div>';
                $privBodyHtml[] = '</th>';
            }

            $thClass = $i == 1 ? ' td-sm' : ' td-md';
            $checked = $package->allCount == $package->selectCount;
            $checkID = "allCheckerModule{$subsetName}Package{$packageID}";
            $privBodyHtml[] = "<th zui-key='package' class='text-middle text-left package $thClass' data-module='$subsetName' data-package='$packageID' data-divid='{$subsetName}{$packageID}' all-privs='$package->allCount' select-privs='$package->selectCount'>";
            $privBodyHtml[] = '<div class="checkbox-inline checkbox-left check-all"><div class="checkbox-primary">';
            $privBodyHtml[] = "<input type='checkbox' id='$checkID' value='browse'" . ($checked ? ' checked' : '') . ">";
            $privBodyHtml[] = "<label for='$checkID' class='" . ($package->selectCount && $package->selectCount != $package->allCount ? 'text-left checkbox-indeterminate-block' : 'text-left') . "'>{$lang->group->package->$packageID}</label>";
            $privBodyHtml[] = '</div></div>';
            $privBodyHtml[] = '</th>';

            $privBodyHtml[] = "<td zui-key='privs' id='$subsetName'>";
            $privBodyHtml[] = $getMethodItemsHtml($package, $subsetName, $packageID, $groupPrivs);
            $privBodyHtml[] = '</td>';

            $privBodyHtml[] = '</tr>';
            $i ++;
        }
    }

    formBase
    (
        setID('managePrivForm'),
        setClass('gap-0'),
        formHidden('actions[][]', ''),
        formHidden('noChecked', ''),
        set::actions(array()),
        div
        (
            setID('managePrivFormContainer'),
            setClass('flex'),
            div
            (
                setClass('main main-content canvas'),
                h::table
                (
                    setID('privList'),
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
                            ),
                            h::th
                            (
                                setClass('method'),
                                $lang->group->method
                            )
                        )
                    ),
                    h::tbody(html($privBodyHtml))
                )
            ),
            div
            (
                setClass('side relative'),
                on::click('.recommend input[type=checkbox]')->call('handleSideRecommentCheckClick', jsRaw('$this')),
                div
                (
                    setClass('btn-group absolute top-0.5 right-full z-10 mr-8'),
                    setID('switchBtnGroup'),
                    a
                    (
                        setClass('btn switchBtn'),
                        set::href(inlink('managePriv', "projectID=$projectID&groupID=$groupID&type=byPackage")),
                        html("<i class='icon-has-authority-pack'></i>")
                    ),
                    a
                    (
                        setClass('btn switchBtn text-primary'),
                        set::href(inlink('managePriv', "projectID=$projectID&groupID=$groupID&type=byGroup")),
                        html("<i class='icon-without-authority-pack'></i>")
                    )
                ),
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
            btn(set(array('text' => $lang->goback, 'url' => inlink('group', "project={$projectID}"), 'back' => true)))
        )
    );
}

/* ====== Render page ====== */
render();
