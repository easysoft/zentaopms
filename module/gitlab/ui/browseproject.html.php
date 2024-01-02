<?php
declare(strict_types=1);
/**
 * The browseproject view file of gitlab module of ZenTaoPMS.
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

jsVar('projectStar', $lang->gitlab->project->star);
jsVar('projectFork', $lang->gitlab->project->fork);

$canCreate = hasPriv('instance', 'manage');

$menus   = $items = array();
$gitlabs = $this->gitlab->getPairs();
foreach($gitlabs as $id => $gitlabName)
{
    $menus[] = array('text' => $gitlabName, 'id' => $id, 'keys' => zget(common::convert2Pinyin(array($gitlabName), $gitlabName), ''), 'url' => helper::createLink('gitlab', zget($config->gitlab->menus, 'project', 'project'), "gitlabID={$id}"));
}
foreach($config->gitlab->menus as $key => $method)
{
    $langKey = 'browse' . ucwords($key);
    $items[] = li
    (
        setClass('nav-item'),
        a
        (
            setClass('' . ($key == 'project' ? 'active' : '')),
            set::href(createLink('gitlab', $method, "gitlabID={$gitlabID}")),
            $lang->gitlab->$langKey
        )
    );
}

foreach($gitlabProjectList as $gitlabProject)
{
    $gitlabProject->lastUpdate    = substr($gitlabProject->last_activity_at, 0, 10);
    $gitlabProject->hasRepo       = isset($repoPairs[$gitlabProject->id]) ? true : false;
    $gitlabProject->defaultBranch = $gitlabProject->adminer || $gitlabProject->isMaintainer;
    $gitlabProject->repoID        = zget($repoPairs, $gitlabProject->id);
    $gitlabProject->gitlabID      = $gitlabID;
    $gitlabProject->name          = $gitlabProject->name_with_namespace;
}
foreach($config->gitlab->dtable->project->fieldList['actions']['list'] as $action => $gitlabConfig)
{
    if(!$this->gitlab->isDisplay($gitlab, $action)) unset($config->gitlab->dtable->project->fieldList['actions']['list'][$action]);
}

$gitlabProjectList = initTableData($gitlabProjectList, $config->gitlab->dtable->project->fieldList, $this->gitlab);

featureBar
(
    dropmenu
    (
        set::id('projectDropmenu'),
        set::objectID($gitlabID),
        set::text($gitlab->name),
        set::data(array('data' => array('project' => $menus), 'tabs' => array(array('name' => 'project', 'text' => ''))))
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
                on::click('searchProject')
            )
        )
    )
);

toolBar
(
    $canCreate ? item(set(array
    (
        'text' => $lang->gitlab->project->create,
        'icon' => 'plus',
        'class' => 'btn primary',
        'url'   => createLink('gitlab', 'createProject', "gitlabID={$gitlabID}")
    ))) : null
);

dtable
(
    set::cols($config->gitlab->dtable->project->fieldList),
    set::data($gitlabProjectList),
    set::orderBy($orderBy),
    set::sortLink(createLink('gitlab', 'browseProject', "gitlabID={$gitlabID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager())
);

render();
