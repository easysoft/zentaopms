<?php
declare(strict_types=1);
/**
 * The browse view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('repoID', $repoID);

/* Show source project column if the user browse the Merge Requests of all the repos. */
if(empty($repoID))
{
    $sourceProject['repoName']['name']  = 'repoID';
    $sourceProject['repoName']['title'] = $lang->repo->common;
    $sourceProject['repoName']['type']  = 'text';
    $sourceProject['repoName']['hint']  = true;
    $sourceProject['repoName']['map']   = $repoPairs;

    $offset = array_search('sourceBranch', array_keys($config->ppm->dtable->fieldList));

    $config->ppm->dtable->fieldList = array_slice($config->ppm->dtable->fieldList, 0, $offset, true) + $sourceProject + array_slice($config->ppm->dtable->fieldList, $offset, NULL, true);
}

$mrs = initTableData($ppmList, $config->ppm->dtable->fieldList, $this->ppm);

featureBar
(
    set::current($mode != 'status' ? $mode : $param),
    set::linkParams("repoID={$repoID}&mode=status&param={key}&objectID={$objectID}")
);

/* 项目/执行视角下的镜像代码库限制：全镜像 → 按钮禁用 + 黄条提示；部分镜像 → 按钮正常 + 黄条提示；无镜像 → 静默；无创建权限 → 整条工具条沉默。 */
$canCreate       = hasPriv($app->rawModule, 'create');
$totalRepoCount  = 0;
$mirrorRepoCount = 0;
foreach($repoList as $repoItem)
{
    $totalRepoCount++;
    if(!empty($repoItem->mirror)) $mirrorRepoCount++;
}
$hasMirrorRepo = $mirrorRepoCount > 0;
$allMirrorRepo = $totalRepoCount > 0 && $mirrorRepoCount === $totalRepoCount;

$createBtnClass = 'btn primary';
if($allMirrorRepo) $createBtnClass .= ' disabled';
$targetRepoID = $repoID ? $repoID : key($repoList);
$createBtnUrl  = $allMirrorRepo ? 'javascript:;' : createLink($app->rawModule, 'create', "repoID={$targetRepoID}&objectID={$objectID}");

$mirrorRepoAlert = ($canCreate && $hasMirrorRepo) ? div
(
    setClass('alert with-icon mr-3 text-warning flex items-center mb-0'),
    /* 覆盖 .alert 默认 gap:.75rem，压紧惊叹号与文字的间隔。 */
    setStyle(array('--alert-bg' => 'var(--color-warning-50)', 'gap' => '.25rem')),
    h::span(setClass('icon icon-exclamation-sign')),
    h::span($lang->ppm->mirrorRepoTip)
) : null;

if($canCreate) toolBar
(
    $mirrorRepoAlert,
    item(
        set::text($lang->ppm->create),
        set::icon('plus'),
        set::className($createBtnClass),
        set::url($createBtnUrl),
        set('data-app', $app->tab)
    )
);

dtable
(
    set::userMap($users),
    set::cols($config->ppm->dtable->fieldList),
    set::data($mrs),
    set::sortLink(createLink($app->rawModule, 'browse', "repoID={$repoID}&mode={$mode}&param={$param}&objectID={$objectID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);
