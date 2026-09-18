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

/* 空间、代码库筛选链接统一保留状态、排序和每页条数，并回到第一页。链接参数顺序需与 browse() 的形参顺序一致：repoID、mode、param、objectID、orderBy、recTotal、recPerPage、pageID、spaceID。 */
$filterParams = "mode={$mode}&param={$param}&objectID={$objectID}&orderBy={$orderBy}&recTotal=0&recPerPage={$pager->recPerPage}&pageID=1";

$spaceItems = array();
$spaceItems[] = array('text' => $lang->ppm->allSpace, 'url' => createLink('ppm', 'browse', "repoID=0&{$filterParams}"));
foreach($spaces as $id => $spaceName)
{
    $spaceItems[] = array('text' => $spaceName, 'url' => createLink('ppm', 'browse', "repoID=0&{$filterParams}&spaceID={$id}"));
}

/* 代码库下拉选项随空间变化：未选择空间时展示全部代码库，选中空间后仅展示该空间下的代码库。 */
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
        /* 代码库筛选仅在代码评审一级导航下展示，节点需内联创建，避免在代码库tab下作为游离节点被渲染。 */
        $config->vision == 'gitfox' ? dropdown
        (
            to('trigger', btn(zget($spaceRepos, $repoID, $lang->ppm->allRepo), setID('repoDropdown'), setClass('ghost text-ellipsis text-left'))),
            set::items($repoItems)
        ) : null
    ),
    set::current($mode != 'status' ? $mode : $param),
    set::linkParams("repoID={$repoID}&mode=status&param={key}&objectID={$objectID}&orderBy={$orderBy}&recTotal=0&recPerPage={$pager->recPerPage}&pageID=1&spaceID={$spaceID}")
);

/* 代码库tab（独立版）和devops（开源版）下的列表按代码库过滤，创建时带上当前代码库；评审一级导航下由用户在表单里选择。 */
$linkParams = in_array($app->tab, array('devops', 'repo')) ? "repoID={$repo->id}" : '';
/* 镜像代码库不能创建合并请求：镜像库不会出现在代码库筛选选项里，仓库级视图下也直接不展示创建入口，无需再提示。 */
$canCreate    = hasPriv($app->rawModule, 'create') && !($repoID && !empty($repo->mirror));
$createBtnUrl = createLink($app->rawModule, 'create', $linkParams);

if($canCreate) toolBar
(
    item(set(array
    (
        'text'     => $lang->ppm->create,
        'icon'     => 'plus',
        'class'    => 'btn primary',
        'data-app' => $app->tab,
        'url'      => $createBtnUrl
    )))
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
