<?php
declare(strict_types=1);
/**
 * The browse view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang <zenggang@easycorp.ltd>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('repoID', $repoID);

dropmenu(set::objectID($repo->id), set::text($repo->name), set::tab('repo'));
if(isset($config->ppm->dtable->fieldList['repo'])) $config->ppm->dtable->fieldList['repo']['map'] = $repos;

$ppms = initTableData($ppmList, $config->ppm->dtable->fieldList, $this->ppm);

$filterParams = "mode={$mode}&param={$param}&objectID={$objectID}&orderBy={$orderBy}&recTotal=0&recPerPage={$pager->recPerPage}&pageID=1";

$spaceItems = array();
$spaceItems[] = array('text' => $lang->ppm->allSpace, 'url' => createLink('ppm', 'browse', "repoID=0&{$filterParams}"));
foreach($spaces as $id => $spaceName)
{
    $spaceItems[] = array('text' => $spaceName, 'url' => createLink('ppm', 'browse', "repoID=0&{$filterParams}&spaceID={$id}"));
}

$repoItems = array();
$repoItems[] = array('text' => $lang->ppm->allRepo, 'url' => createLink('ppm', 'browse', "repoID=0&{$filterParams}&spaceID={$spaceID}"));
foreach($spaceRepos as $id => $repoName)
{
    $repoItems[] = array('text' => $repoName, 'url' => createLink('ppm', 'browse', "repoID={$id}&{$filterParams}&spaceID={$spaceID}"));
}

featureBar
(
    $app->tab != 'ppm' ? null : to::before
    (
        dropdown
        (
            to('trigger', btn(zget($spaces, $spaceID, $lang->ppm->allSpace), setID('spaceDropdown'), setClass('ghost text-ellipsis text-left'))),
            set::items($spaceItems)
        ),
        $config->vision == 'gitfox' ? dropdown
        (
            to('trigger', btn(zget($spaceRepos, $repoID, $lang->ppm->allRepo), setID('repoDropdown'), setClass('ghost text-ellipsis text-left'))),
            set::items($repoItems)
        ) : null
    ),
    set::current($mode != 'status' ? $mode : $param),
    set::linkParams("repoID={$repoID}&mode=status&param={key}&objectID={$objectID}&orderBy={$orderBy}&recTotal=0&recPerPage={$pager->recPerPage}&pageID=1&spaceID={$spaceID}")
);

$linkParams   = in_array($app->tab, array('devops', 'repo')) ? "repoID={$repo->id}" : '';
$isMirrorRepo = (bool)($repoID && !empty($repo->mirror));
$canCreate    = hasPriv($app->rawModule, 'create') && !$isMirrorRepo;
$createBtnUrl = createLink($app->rawModule, 'create', $linkParams);

$mirrorRepoAlert = $isMirrorRepo ? div
(
    setClass('alert with-icon mr-3 text-warning flex items-center mb-0'),
    setStyle(array('--alert-bg' => 'var(--color-warning-50)', 'gap' => '.25rem')),
    h::span(setClass('icon icon-exclamation-sign')),
    h::span($lang->ppm->mirrorRepoTip)
) : null;

if($canCreate || $mirrorRepoAlert) toolBar
(
    $mirrorRepoAlert,
    $canCreate ? item(set(array
    (
        'text'     => $lang->ppm->create,
        'icon'     => 'plus',
        'class'    => 'btn primary',
        'data-app' => $app->tab,
        'url'      => $createBtnUrl
    ))) : null
);

dtable
(
    set::userMap($users),
    set::cols($config->ppm->dtable->fieldList),
    set::data($ppms),
    set::sortLink(createLink($app->rawModule, 'browse', "repoID={$repoID}&mode={$mode}&param={$param}&objectID={$objectID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID=1&spaceID={$spaceID}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);
