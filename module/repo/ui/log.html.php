<?php
declare(strict_types=1);
/**
 * The log view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, $app->tab == 'devops' ? 'ajaxGetDropMenu' : 'ajaxGetDropMenuData', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

$diffLink = $this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=" . $this->repo->encodePath($entry) . "&oldrevision={oldRevision}&newRevision={newRevision}");

jsVar('diffLink', $diffLink);

/* Prepare breadcrumb navigation data. */
$base64BranchID    = helper::safe64Encode(base64_encode($branchID));
$breadcrumbItems   = array();
$breadcrumbItems[] = h::a
(
    set::href($this->repo->createLink('log', "repoID=$repoID&objectID=$objectID")),
    $repo->name,
);
$breadcrumbItems[] = h::span('/', setStyle('margin', '0 5px'));

$paths    = explode('/', $entry);
$fileName = array_pop($paths);
$postPath = '';
foreach($paths as $pathName)
{
    $postPath .= $pathName . '/';
    $breadcrumbItems[] = h::a
    (
        set::href($this->repo->createLink('log', "repoID=$repoID&objectID=$objectID&entry=" . $this->repo->encodePath($postPath))),
        trim($pathName, '/'),
    );
    $breadcrumbItems[] = h::span('/', setStyle('margin', '0 5px'));
}
if($fileName) $breadcrumbItems[] = h::span($fileName);

foreach($logs as $log)
{
    $log->revision = substr($log->revision, 0, 10);
}

/* Disbale check all checkbox of table header */
$config->repo->logDtable->fieldList['revision']['checkbox'] = jsRaw('(rowID) => rowID !== \'HEADER\'');

$config->repo->logDtable->fieldList['revision']['link'] = array('module' => 'repo', 'method' => 'revision', 'params' => "repoID={$repoID}&objectID={$objectID}&revision={revision}");

$logs = initTableData($logs, $config->repo->logDtable->fieldList);

$footToolbar['items'][] = array('text' => $lang->repo->diff, 'className' => "btn primary size-sm btn-diff", 'btnType' => 'primary', 'onClick' => jsRaw('window.diffClick'));

\zin\featureBar(
    backBtn
    (
        setClass('mr-5'),
        set::icon('back'),
        set::type('secondary'),
        set::back('repo-browse'),
        $lang->goback
    ),
    ...$breadcrumbItems
);

dtable
(
    set::id('repo-logs-table'),
    set::cols($config->repo->logDtable->fieldList),
    set::data($logs),
    set::onCheckChange(jsRaw('window.checkedChange')),
    set::canRowCheckable(jsRaw('function(rowID){return canRowCheckable(rowID);}')),
    set::footToolbar($footToolbar),
    set::footer(array('toolbar', 'flex', 'pager')),
    set::footPager(usePager('pager', 'noTotalCount')),
    set::showToolbarOnChecked(false),
);

render();
