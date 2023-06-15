<?php
declare(strict_types=1);
/**
 * The browse view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     repo
 * @link        http://www.zentao.net
 */

namespace zin;

/* Prepare repo select data. */
$menus    = array();
$selected = '';
foreach($branches as $branchName)
{
    $selected       = ($branchName == $branchID and $branchOrTag == 'branch') ? $branchName : $selected;
    $base64BranchID = helper::safe64Encode(base64_encode($branchName));
    $branchLink     = $this->createLink('repo', 'browse', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID");

    $menus[] = array('text' => $branchName, 'value' => $branchName, 'url' => $branchLink);
}
foreach($tags as $tagName)
{
    $selected    = ($tagName == $branchID and $branchOrTag == 'tag') ? $tagName : $selected;
    $base64TagID = helper::safe64Encode(base64_encode($tagName));
    $tagLink     = $this->createLink('repo', 'browse', "repoID=$repoID&branchID=$base64TagID&objectID=$objectID&path=&revision=HEAD&refresh=0&branchOrTag=tag");

    $menus[] = array('text' => $tagName, 'value' => $tagName, 'url' => $tagLink);
}

/* Prepare breadcrumb navigation data. */
$base64BranchID    = helper::safe64Encode(base64_encode($branchID));
$breadcrumbItems   = array();
$breadcrumbItems[] = h::a
(
    setClass('form-title'),
    set::href($this->repo->createLink('browse', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID")),
    $repo->name,
);
$breadcrumbItems[] = h::span('/');

$paths    = explode('/', $path);
$fileName = array_pop($paths);
$postPath = '';
foreach($paths as $index => $pathName)
{
    $postPath .= $pathName . '/';
    $breadcrumbItems[] = h::a
    (
        setClass('form-title'),
        set::href($this->repo->createLink('browse', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID&path=" . $this->repo->encodePath($postPath))),
        trim($pathName, '/'),
    );
    $breadcrumbItems[] = h::span('/');
}
if($fileName) $breadcrumbItems[] = h::span($fileName);

/* zin: Define the set::module('repo') feature bar on main menu. */
featureBar(
    formGroup
    (
        set::width('200px'),
        set::class('repo-select'),
        set::required(true),
        select
        (
            set::id('repo-select'),
            set::items($menus),
            set::value($selected)
        )
    ),
    ...$breadcrumbItems
);

/* zin: Define the toolbar on main menu. */
$refreshLink   = $this->createLink('repo', 'browse', "repoID=$repoID&branchID=" . $base64BranchID . "&objectID=$objectID&path=" . $this->repo->encodePath($path) . "&revision=$revision&refresh=1");
$refreshItem   = array('text' => $lang->refresh, 'url' => $refreshLink, 'class' => 'primary', 'icon' => 'refresh');
$downloadItem  = array('text' => $lang->repo->downloadCode, 'url' => '#modalDownloadCode', 'class' => 'primary download-btn', 'icon' => 'download', 'data-toggle' => 'modal');

$tableData = initTableData($infos, $config->repo->repoDtable->fieldList, $this->repo);

toolbar
(
    span(
        set::class('last-sync-time'),
        $lang->repo->notice->lastSyncTime . $cacheTime
    ),
    item(set($refreshItem)),
    item(set($downloadItem)),
);

modalTrigger
(
    modal
    (
        set::id('modalDownloadCode'),
        set::title($lang->repo->downloadCode),
        $cloneUrl->svn ? div
        (
            p(set::class('repo-downloadCode'), $lang->repo->cloneUrl),
            formRow
            (
                formGroup
                (
                    set::width('2/3'),
                    input
                    (
                        set::type('text'),
                        set::value($cloneUrl->svn),
                        set::readOnly(true),
                    ),
                ),
                formGroup
                (
                    set::width('1/6'),
                    btn
                    (
                        set::icon('copy'),
                    )
                )
            ),
        ) : null,

        $cloneUrl->ssh ? div
        (
            p(set::class('repo-downloadCode'), $lang->repo->sshClone),
            formRow
            (
                formGroup
                (
                    set::width('2/3'),
                    input
                    (
                        set::type('text'),
                        set::value($cloneUrl->ssh),
                        set::disabled(true),
                    ),
                ),
                formGroup
                (
                    set::width('1/6'),
                    btn
                    (
                        set::icon('copy'),
                    )
                )
            ),
        ) : null,

        $cloneUrl->http ? div
        (
            p(set::class('repo-downloadCode'), $lang->repo->httpClone),
            formRow
            (
                formGroup
                (
                    set::width('2/3'),
                    input
                    (
                        set::type('text'),
                        set::value($cloneUrl->http),
                        set::disabled(true),
                    ),
                ),
                formGroup
                (
                    set::width('1/6'),
                    btn
                    (
                        set::icon('copy'),
                    )
                )
            ),
        ) : null,

        to::footer
        (
            set::footerClass('flex-center'),
            btn
            (
                set::class('downloadZip-btn'),
                set::text($lang->repo->downloadZip),
            )
        ),
    )
);

dtable
(
    set::cols($config->repo->repoDtable->fieldList),
    set::data($tableData),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(),
);

/* zin: Define the sidebar in main content. */
$encodePath  = $this->repo->encodePath($path);
$diffLink    = $this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=" . $encodePath . "&oldrevision={oldRevision}&newRevision={newRevision}");
$revisionMap = array();
foreach($revisions as $revision) $revisionMap[$revision->id] = $revision->revision;

jsVar('repoID',      $repoID);
jsVar('branch',      $branchID);
jsVar('menus',       $menus);
jsVar('revisionMap', $revisionMap);
jsVar('diffLink',    $diffLink);
jsVar('sortLink', helper::createLink('repo', 'browse', "repoID={$repoID}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"));


$commentsTableData = initTableData($revisions, $config->repo->commentDtable->fieldList, $this->repo);

$readAllLink = $this->repo->createLink('log', "repoID=$repoID&objectID=$objectID&entry=" . $encodePath . "&revision=HEAD&type=$logType");

$footToolbar['items'][] = array('text' => $lang->repo->diff, 'class' => "btn secondary size-sm btn-diff", 'btnType' => 'primary', 'onClick' => jsRaw('window.diffClick'));
$footToolbar['items'][] = array('text' => $lang->repo->allLog, 'url' => $readAllLink);

sidebar
(
    set::side('right'),
    cell(set::class('sidebar-comments')),
    dtable
    (
        set::id('repo-comments-table'),
        set::cols($config->repo->commentDtable->fieldList),
        set::data($commentsTableData),
        set::onRenderCell(jsRaw('window.renderCommentCell')),
        set::onCheckChange(jsRaw('window.checkedChange')),
        set::checkInfo(jsRaw('function(){return {html:\'\'}}')),
        set::footToolbar($footToolbar),
        set::footPager(usePager()),
        set::showToolbarOnChecked(false),
    ),
);

render();
