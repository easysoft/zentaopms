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

/* Project switch in second level nav. */
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

$diffLink = $this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=" . $this->repo->encodePath($entry) . "&oldrevision={oldRevision}&newRevision={newRevision}");

jsVar('diffLink', $diffLink);
jsVar('appTab', $app->tab);

/* Prepare breadcrumb navigation data. */
$breadcrumbItems   = array();
$path ? $breadcrumbItems[] = h::a
(
    set::href($this->repo->createLink('log', "repoID=$repoID&branchID=&objectID=$objectID")),
    set('data-app', $app->tab),
    h::span('/', setStyle('margin', '0 5px'))
) : null;

$paths    = explode('/', $entry);
$fileName = array_pop($paths);
$postPath = '';
foreach($paths as $pathName)
{
    $postPath .= $pathName . '/';
    $breadcrumbItems[] = h::a
    (
        set::href($this->repo->createLink('log', "repoID=$repoID&branchID=&objectID=$objectID&entry=" . $this->repo->encodePath($postPath))),
        set('data-app', $app->tab),
        trim($pathName, '/')
    );
    $breadcrumbItems[] = h::span('/', setStyle('margin', '0 5px'));
}
if($fileName) $breadcrumbItems[] = h::span($fileName);

foreach($logs as $log)
{
    $log->revision = substr($log->revision, 0, 10);
    $log->designName = '';
    foreach(array('stroies', 'designs', 'tasks', 'bugs') as $value)
    {
        if(!empty($log->relations[$value]))
        {
            $log->designName .= html::commonButton($lang->repo->{$value} , '', 'btn size-sm mx-2');
            foreach($log->relations[$value] as $item) $log->designName .= html::a($item->url, '#'.$item->id, '_blank');
        }
    }
}

/* Disbale check all checkbox of table header */
$config->repo->logDtable->fieldList['revision']['checkbox'] = jsRaw('(rowID) => rowID !== \'HEADER\'');

$config->repo->logDtable->fieldList['revision']['link'] = array('module' => 'repo', 'method' => 'revision', 'params' => "repoID={$repoID}&objectID={$objectID}&revision={revision}");

$logs = initTableData($logs, $config->repo->logDtable->fieldList);

$footToolbar['items'][] = array('text' => $lang->repo->diff, 'className' => "size-sm btn-diff", 'btnType' => 'primary disabled', 'onClick' => jsRaw('window.diffClick'));

/* Prepare repo select data. */
$branchMenus = array();
$tagMenus    = array();
$selected    = '';

foreach($branches as $branchName)
{
    $selected       = $branchName == $branchID ? $branchName : $selected;
    $base64BranchID = helper::safe64Encode(base64_encode($branchName));
    $branchLink     = $this->createLink('repo', 'log', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID");
    $branchMenus[] = array('text' => $branchName, 'id' => $branchName, 'keys' => zget(common::convert2Pinyin(array($branchName), $branchName), ''), 'url' => $branchLink, 'data-app' => $app->tab);
}

foreach($tags as $tagName)
{
    $selected    = $tagName == $branchID ? $tagName : $selected;
    $base64TagID = helper::safe64Encode(base64_encode($tagName));
    $tagLink     = $this->createLink('repo', 'log', "repoID=$repoID&branchID=$base64TagID&objectID=$objectID&path=");
    $tagMenus[] = array('text' => $tagName, 'id' => $tagName, 'keys' => zget(common::convert2Pinyin(array($tagName), $tagName), ''), 'url' => $tagLink, 'data-app' => $app->tab);
}
$menuData = array('branch' => $branchMenus, 'tag' => $tagMenus);
$tabs     = array(array('name' => 'branch', 'text' => $lang->repo->branch), array('name' => 'tag', 'text' => $lang->repo->tag));

\zin\featureBar(
    /* Set back button. */
    in_array($source, array('browse')) ? backBtn
    (
        setClass('mr-5'),
        set::icon('back'),
        set::type('secondary'),
        set::back('repo-browse'),
        $lang->goback
    ) : null,
    formGroup
    (
        set::className('repo-select'),
        set::required(true),

        /* Switch projects. */
        (in_array($app->tab, array('project', 'execution')) && count($repoPairs) > 1) ? dropmenu
        (
            set::id('logRepoDropmenu'),
            set::text($repo->name),
            set::objectID($repo->id),
            set::url(createLink('repo', 'ajaxGetDropMenu', "repoID={$repo->id}&module=repo&method=log&projectID={$objectID}"))
        ) : null,

        /* Switch branches and labels. */
        ($repo->SCM != 'Subversion' && ($branches || $tags)) ? dropmenu
        (
            setID('logRepoBranchDropMenu'),
            set::objectID($selected),
            set::text($selected),
            set::data(array('data' => $menuData, 'tabs' => $tabs))
        ) : null
    ),
    $breadcrumbItems,
    div
    (
        $repo->SCM != 'Subversion' ? setClass('ml-4') : null,
        searchToggle
        (
            set::module('repoCommits'),
            set::open($browseType == 'bysearch')
        )
    )
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
    set::showToolbarOnChecked(false)
);

render();
