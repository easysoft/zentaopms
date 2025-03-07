<?php
declare(strict_types=1);
/**
 * The link view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

jsVar('appTab', $app->tab);

foreach($bugs as $bug)
{
    $repo          = zget($repos, $bug->repo, $repo);
    $bug->repoName = $repo->name;

    $v1 = $bug->v1;
    $v2 = $bug->v2;

    $bug->entry     = $this->repo->decodePath($bug->entry);
    $bug->revisionA = substr($repo->SCM != 'Subversion' ? strtr($v2, '*', '-') : $v2, 0, 10);

    $lines     = trim($bug->lines, ',');
    $fileEntry = $this->repo->encodePath("{$bug->entry}#{$bug->lines}");
    if(empty($v1))
    {
        $v2   = $repo->SCM != 'Subversion' ? strtr($v2, '*', '-') : $v2;
        $revision  = $repo->SCM != 'Subversion' ? $this->repo->getGitRevisionName($v2, (int)zget($historys, $v2)) : $v2;
        $bug->link = $this->repo->createLink('view', "repoID={$bug->repo}&objectID={$objectID}&entry={$fileEntry}&revision={$v2}");
    }
    else
    {
        $revision  = $repo->SCM != 'Subversion' ? substr($v1, 0, 10) : $v1;
        $revision .= ' : ';
        $revision .= $repo->SCM != 'Subversion' ? substr($v2, 0, 10) : $v2;
        if($repo->SCM != 'Subversion') $revision .= ' (' . zget($historys, $v1) . ' : ' . zget($historys, $v2) . ')';
        if(strpos($v1, $v2) === 0 || strpos($v2, $v1) === 0) $v1 = $v2;
        $bug->link = $this->repo->createLink('diff', "repoID={$bug->repo}&objectID={$objectID}&entry={$fileEntry}&oldRevision={$v1}&newRevision={$v2}");
    }
}
$bugs = initTableData($bugs, $config->repo->reviewDtable->fieldList);

if($app->tab == 'project' || $app->tab == 'execution') $repoID = 0;
\zin\featureBar
(
    set::current($browseType),
    set::linkParams("repoID={$repoID}&browseType={key}&objectID={$objectID}")
);

dtable
(
    set::userMap($users),
    set::cols($config->repo->reviewDtable->fieldList),
    set::data($bugs),
    set::sortLink(createLink('repo', 'review', "repoID=$repoID&browseType=$browseType&objectID={$objectID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderRepobugList')),
    set::footPager(usePager())
);
