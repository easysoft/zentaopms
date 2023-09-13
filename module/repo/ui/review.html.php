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

dropmenu(set::tab('repo'));
jsVar('orderBy', $orderBy);
jsVar('sortLink', createLink('repo', 'review', "repoID=$repoID&browseType=$browseType&orderBy={orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"));

foreach($bugs as $bug)
{
    $bug->revisionA = $repo->SCM != 'Subversion' ? strtr($bug->v2, '*', '-') : $bug->v2;

    $lines = explode(',', trim($bug->lines, ','));
    if(empty($bug->v1))
    {
        $bug->v2   = $repo->SCM != 'Subversion' ? strtr($bug->v2, '*', '-') : $bug->v2;
        $revision  = $repo->SCM != 'Subversion' ? $this->repo->getGitRevisionName($bug->v2, zget($historys, $bug->v2)) : $bug->v2;
        $bug->link = $this->repo->createLink('view', "repoID=$repoID&objectID=0&entry={$bug->entry}&revision={$bug->v2}") . "#L$lines[0]";
    }
    else
    {
        $revision  = $repo->SCM != 'Subversion' ? substr($bug->v1, 0, 10) : $bug->v1;
        $revision .= ' : ';
        $revision .= $repo->SCM != 'Subversion' ? substr($bug->v2, 0, 10) : $bug->v2;
        if($repo->SCM != 'Subversion') $revision .= ' (' . zget($historys, $bug->v1) . ' : ' . zget($historys, $bug->v2) . ')';
        $bug->link = $this->repo->createLink('diff', "repoID=$repoID&objectID=0&entry={$bug->entry}&oldRevision={$bug->v1}&newRevision={$bug->v2}") . "#L$lines[0]";
    }

    $bug->entry = $repo->name . '/' . $this->repo->decodePath($bug->entry);
}
$bugs = initTableData($bugs, $config->repo->reviewDtable->fieldList);

\zin\featureBar
(
    set::linkParams("repoID=$repoID&browseType={key}"),
);

dtable
(
    set::userMap($users),
    set::cols($config->repo->reviewDtable->fieldList),
    set::data($bugs),
    set::sortLink(jsRaw('createSortLink')),
    set::onRenderCell(jsRaw('window.renderRepobugList')),
    set::footPager(usePager()),
);

render();

