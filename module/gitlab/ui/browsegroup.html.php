<?php
declare(strict_types=1);
/**
 * The browsegroup view file of gitlab module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */
namespace zin;

if(!empty($permissionError))
{
    jsCall('alertJump', array($permissionError, $errorJump));
    return;
}

jsVar('gitlabUrl', $gitlab->url);

$canCreate = hasPriv('instance', 'manage');

$menus   = $items = array();
$gitlabs = $this->gitlab->getPairs();
foreach($gitlabs as $id => $gitlabName)
{
    $menus[] = array('text' => $gitlabName, 'id' => $id, 'keys' => zget(common::convert2Pinyin(array($gitlabName), $gitlabName), ''), 'url' => helper::createLink('gitlab', zget($config->gitlab->menus, 'group', 'group'), "gitlabID={$id}"));
}
foreach($config->gitlab->menus as $key => $method)
{
    $langKey = 'browse' . ucwords($key);
    $items[] = li
    (
        setClass('nav-item'),
        a
        (
            setClass('' . ($key == 'group' ? 'active' : '')),
            set::href(createLink('gitlab', $method, "gitlabID={$gitlabID}")),
            $lang->gitlab->$langKey
        )
    );
}

foreach($gitlabGroupList as $gitlabGroup)
{
    $gitlabGroup->fullName = $gitlabGroup->full_name;
    $gitlabGroup->gitlabID = $gitlabID;
    $gitlabGroup->createOn = substr($gitlabGroup->created_at, 0, 10);
    $gitlabGroup->isAdmin  = $app->user->admin || in_array($gitlabGroup->id, $adminGroupIDList);
}
foreach($config->gitlab->dtable->group->fieldList['actions']['list'] as $action => $gitlabConfig)
{
    if(!$this->gitlab->isDisplay($gitlab, $action)) unset($config->gitlab->dtable->group->fieldList['actions']['list'][$action]);
}

$config->gitlab->dtable->group->fieldList['fullName']['avatarProps'] = jsRaw("(col, row) => ({text: row.data.name})");
$gitlabGroupList = initTableData($gitlabGroupList, $config->gitlab->dtable->group->fieldList, $this->gitlab);

featureBar
(
    dropmenu
    (
        set::id('groupDropmenu'),
        set::objectID($gitlabID),
        set::text($gitlab->name),
        set::data(array('data' => array('group' => $menus), 'tabs' => array(array('name' => 'group', 'text' => ''))))
    ),
    $items,
    form
    (
        set::id('searchForm'),
        set::actions(array()),
        formRow
        (
            input
            (
                set::placeholder($lang->gitlab->placeholderSearch),
                set::name('keyword'),
                set::value($keyword)
            ),
            btn
            (
                setClass('primary'),
                $lang->gitlab->search,
                on::click('searchGroup')
            )
        )
    )
);

toolBar
(
    $canCreate ? item(set(array
    (
        'text' => $lang->gitlab->group->create,
        'icon' => 'plus',
        'class' => 'btn primary',
        'url'   => createLink('gitlab', 'createGroup', "gitlabID={$gitlabID}")
    ))) : null
);

dtable
(
    set::cols($config->gitlab->dtable->group->fieldList),
    set::data($gitlabGroupList),
    set::sortLink(createLink('gitlab', 'browseGroup', "gitlabID={$gitlabID}&orderBy={name}_{sortType}")),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::orderBy($orderBy)
);

render();
