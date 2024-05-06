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
    $objectID      = $app->tab == 'execution' ? $bug->execution : 0;

    $bug->entry     = $this->repo->decodePath($bug->entry);
    $bug->revisionA = substr($repo->SCM != 'Subversion' ? strtr($bug->v2, '*', '-') : $bug->v2, 0, 10);

    $lines     = trim($bug->lines, ',');
    $fileEntry = $this->repo->encodePath("{$bug->entry}#{$bug->lines}");
    if(empty($bug->v1))
    {
        $bug->v2   = $repo->SCM != 'Subversion' ? strtr($bug->v2, '*', '-') : $bug->v2;
        $revision  = $repo->SCM != 'Subversion' ? $this->repo->getGitRevisionName($bug->v2, (int)zget($historys, $bug->v2)) : $bug->v2;
        $bug->link = $this->repo->createLink('view', "repoID={$bug->repo}&objectID={$objectID}&entry={$fileEntry}&revision={$bug->v2}");
    }
    else
    {
        $revision  = $repo->SCM != 'Subversion' ? substr($bug->v1, 0, 10) : $bug->v1;
        $revision .= ' : ';
        $revision .= $repo->SCM != 'Subversion' ? substr($bug->v2, 0, 10) : $bug->v2;
        if($repo->SCM != 'Subversion') $revision .= ' (' . zget($historys, $bug->v1) . ' : ' . zget($historys, $bug->v2) . ')';
        $bug->link = $this->repo->createLink('diff', "repoID={$bug->repo}&objectID={$objectID}&entry={$fileEntry}&oldRevision={$bug->v1}&newRevision={$bug->v2}");
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
