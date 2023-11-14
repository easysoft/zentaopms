<?php
declare(strict_types=1);
/**
 * The browseuser view file of gitlab module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */
namespace zin;

$canCreate = hasPriv('instance', 'manage');

$menus   = $items = array();
$gitlabs = $this->gitlab->getPairs();
foreach($gitlabs as $id => $gitlabName)
{
    $menus[] = array('text' => $gitlabName, 'id' => $id, 'keys' => zget(common::convert2Pinyin(array($gitlabName), $gitlabName), ''), 'url' => helper::createLink('gitlab', zget($config->gitlab->menus, 'user', 'user'), "gitlabID={$id}"));
}
foreach($config->gitlab->menus as $key => $method)
{
    $langKey = 'browse' . ucwords($key);
    $items[] = li
    (
        setClass('nav-item'),
        a
        (
            setClass('' . ($key == 'user' ? 'active' : '')),
            set::href(createLink('gitlab', $method, "gitlabID={$gitlabID}")),
            $lang->gitlab->$langKey
        )
    );
}

foreach($gitlabUserList as $gitlabUser)
{
    $gitlabUser->gitlabID     = $gitlabID;
    $gitlabUser->createOn     = substr($gitlabUser->createdAt, 0, 10);
    $gitlabUser->lastActivity = substr($gitlabUser->lastActivityOn, 0, 10);
    $gitlabUser->isAdmin      = $isAdmin;
    $gitlabUser->name         = $gitlabUser->realname . ' ' . $gitlabUser->account . ' ' . $gitlabUser->email;
    $gitlabUser->nameAvatar   = $gitlabUser->avatar;
}

$gitlabUserList = initTableData($gitlabUserList, $config->gitlab->dtable->user->fieldList, $this->gitlab);

featureBar
(
    dropmenu
    (
        set::id('userDropmenu'),
        set::objectID($gitlabID),
        set::text($gitlabs[$gitlabID]),
        set::data(array('data' => array('user' => $menus), 'tabs' => array(array('name' => 'user', 'text' => ''))))
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
                on::click('searchUser')
            )
        )
    )
);

toolBar
(
    $canCreate ? item(set(array
    (
        'text' => $lang->gitlab->user->create,
        'icon' => 'plus',
        'class' => 'btn primary',
        'url'   => createLink('gitlab', 'createUser', "gitlabID={$gitlabID}")
    ))) : null
);

dtable
(
    set::cols($config->gitlab->dtable->user->fieldList),
    set::data($gitlabUserList),
    set::sortLink(createLink('gitlab', 'browseUser', "gitlabID={$gitlabID}&orderBy={name}_{sortType}")),
    set::orderBy($orderBy)
);
