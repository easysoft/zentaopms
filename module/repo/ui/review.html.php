<?php
declare(strict_types=1);
/**
 * The link view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

$module = $app->tab == 'devops' ? 'repo' : $app->tab;
if(!$allRepo) dropmenu
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
    $bug->revisionA = strtr($v2, '*', '-');

    $lines     = trim($bug->lines, ',');
    $fileEntry = $this->repo->encodePath("{$bug->entry}#{$bug->lines}");
    if(empty($v1))
    {
        $v2   = strtr($v2, '*', '-');
        $revision  = $this->repo->getGitRevisionName($v2, (int)zget($historys, $v2));
        $bug->link = $this->repo->createLink('view', "repoID={$bug->repo}&objectID={$objectID}&entry={$fileEntry}&revision={$v2}");
    }
    else
    {
        $revision  = substr($v1, 0, 10);
        $revision .= ' : ';
        $revision .= substr($v2, 0, 10);
        $revision .= ' (' . zget($historys, $v1) . ' : ' . zget($historys, $v2) . ')';
        if(strpos($v1, $v2) === 0 || strpos($v2, $v1) === 0) $v1 = $v2;
        $bug->link = $this->repo->createLink('diff', "repoID={$bug->repo}&objectID={$objectID}&entry={$fileEntry}&oldRevision={$v1}&newRevision={$v2}");
    }
}
if(hasPriv('bug', 'batchAssignTo'))
{
    $pinyinItems     = common::convert2Pinyin($users);
    $assignedToItems = array();
    foreach($users as $key => $value)
    {
        if($key == 'closed') continue;
        $key = base64_encode((string)$key); // 编码用户名中的特殊字符
        $assignedToItems[] = array('text' => $value, 'keys' => zget($pinyinItems, $value, ''), 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('bug', 'batchAssignTo', "assignedTo=$key&productID=0&type=product"));
    }
    $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->bug->assignedTo, 'type' => 'dropdown', 'data-placement' => 'top-start', 'items' => $assignedToItems, 'data-menu' => array('searchBox' => true));
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
    hasPriv('bug', 'batchAssignTo') ? set::footToolbar($footToolbar) : null,
    set::footPager(usePager())
);
