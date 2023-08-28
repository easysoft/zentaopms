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

jsVar('copied', $lang->repo->copied);

dropmenu(set::module('repo'), set::tab('repo'));

/* Prepare repo select data. */
$branchMenus = array();
$tagMenus    = array();
$selected    = '';
foreach($branches as $branchName)
{
    $selected       = ($branchName == $branchID and $branchOrTag == 'branch') ? $branchName : $selected;
    $base64BranchID = helper::safe64Encode(base64_encode($branchName));
    $branchLink     = $this->createLink('repo', 'browse', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID");

    $branchMenus[] = array('text' => $branchName, 'id' => $branchName, 'keys' => zget(common::convert2Pinyin(array($branchName), $branchName), ''), 'url' => $branchLink);
}
foreach($tags as $tagName)
{
    $selected    = ($tagName == $branchID and $branchOrTag == 'tag') ? $tagName : $selected;
    $base64TagID = helper::safe64Encode(base64_encode($tagName));
    $tagLink     = $this->createLink('repo', 'browse', "repoID=$repoID&branchID=$base64TagID&objectID=$objectID&path=&revision=HEAD&refresh=0&branchOrTag=tag");

    $tagMenus[] = array('text' => $tagName, 'id' => $tagName, 'keys' => zget(common::convert2Pinyin(array($tagName), $tagName), ''), 'url' => $tagLink);
}

$tabs = array(array('name' => 'branch', 'text' => $lang->repo->branch), array('name' => 'tag', 'text' => $lang->repo->tag));
$menuData = array('branch' => $branchMenus, 'tag' => $tagMenus);

/* Prepare breadcrumb navigation data. */
$base64BranchID    = helper::safe64Encode(base64_encode($branchID));
$breadcrumbItems   = array();
$breadcrumbItems[] = h::a
(
    set::href($this->repo->createLink('browse', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID")),
    $repo->name,
);
$breadcrumbItems[] = h::span('/', setStyle('margin', '0 5px'));

$paths    = explode('/', $path);
$fileName = array_pop($paths);
$postPath = '';
foreach($paths as $index => $pathName)
{
    $postPath .= $pathName . '/';
    $breadcrumbItems[] = h::a
    (
        set::href($this->repo->createLink('browse', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID&path=" . $this->repo->encodePath($postPath))),
        trim($pathName, '/'),
    );
    $breadcrumbItems[] = h::span('/', setStyle('margin', '0 5px'));
}
if($fileName) $breadcrumbItems[] = h::span($fileName);

/* zin: Define the set::module('repo') feature bar on main menu. */
featureBar(
    formGroup
    (
        set::class('repo-select'),
        set::required(true),
        dropmenu
        (
            setID('repoBranchDropMenu'),
            set::objectID($selected),
            set::text($selected),
            set::data(array('data' => $menuData, 'tabs' => $tabs)),
        ),
    ),
    ...$breadcrumbItems
);

/* zin: Define the toolbar on main menu. */
$refreshLink   = $this->createLink('repo', 'browse', "repoID=$repoID&branchID=" . $base64BranchID . "&objectID=$objectID&path=" . $this->repo->encodePath($path) . "&revision=$revision&refresh=1");
$refreshItem   = array('text' => $lang->refresh, 'url' => $refreshLink, 'class' => 'primary', 'icon' => 'refresh');

$createItem = array('text' => $lang->repo->createAction, 'url' => createLink('repo', 'create', "objectID={$objectID}"));

$tableData = initTableData($infos, $config->repo->repoDtable->fieldList, $this->repo);

$downloadWg = div
(
    set::id('modal-downloadCode'),
    set::title($lang->repo->downloadCode),
    on('click', '', array('capture' => true, 'prevent' => true, 'stop' => true)),
    !empty($cloneUrl->svn) ? div
    (
        p(set::class('repo-downloadCode'), $lang->repo->cloneUrl),
        formRow
        (
            formGroup
            (
                set::width('450px'),
                input
                (
                    set::type('text'),
                    set::name('svnUrl'),
                    set::value($cloneUrl->svn),
                    set::readOnly(true),
                ),
            ),
            formGroup
            (
                set::width('50px'),
                btn
                (
                    set::icon('copy'),
                )
            )
        ),
    ) : null,

    !empty($cloneUrl->ssh) ? div
    (
        p(set::class('repo-downloadCode'), $lang->repo->sshClone),
        formRow
        (
            formGroup
            (
                set::width('450px'),
                input
                (
                    set::type('text'),
                    set::name('sshUrl'),
                    set::value($cloneUrl->ssh),
                    set::readOnly(true),
                ),
            ),
            formGroup
            (
                set::width('50px'),
                btn
                (
                    set::class('copy-btn'),
                    set::icon('copy'),
                )
            )
        ),
    ) : null,

    !empty($cloneUrl->http) ? div
    (
        p(set::class('repo-downloadCode'), $lang->repo->httpClone),
        formRow
        (
            formGroup
            (
                set::width('450px'),
                input
                (
                    set::type('text'),
                    set::name('httpUrl'),
                    set::value($cloneUrl->http),
                    set::readOnly(true),
                ),
            ),
            formGroup
            (
                set::width('50px'),
                btn
                (
                    set::class('copy-btn'),
                    set::icon('copy'),
                )
            )
        ),
    ) : null,

    div
    (
        setStyle(array('margin-top' => '20px')),
        btn
        (
            set::icon('down-circle'),
            set::class('downloadZip-btn'),
            set::text($lang->repo->downloadZip),
        )
    ),
);

toolbar
(
    span(
        set::class('last-sync-time'),
        $lang->repo->notice->lastSyncTime . $cacheTime
    ),
    item(set($refreshItem)),
    dropdown
    (
        set::staticMenu(true),
        btn
        (
            setClass('primary download-btn'),
            set::icon('download'),
            $lang->repo->download
        ),
        to::items
        (
            array($downloadWg)
        ),
    ),
    hasPriv('repo', 'create') && $this->app->tab == 'project' ? item
    (
        set($createItem + array
        (
            'icon'  => 'plus',
            'class' => 'btn primary',
        )),
        set('data-app', $this->app->tab),
    ) : null,
);

dtable
(
    set::cols($config->repo->repoDtable->fieldList),
    set::data($tableData),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::canRowCheckable(jsRaw('function(rowID){return false;}')),
    set::footPager(),
);

/* zin: Define the sidebar in main content. */
$encodePath  = $this->repo->encodePath($path);
$diffLink    = $this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=" . $encodePath . "&oldrevision={oldRevision}&newRevision={newRevision}");

jsVar('repoID',   $repoID);
jsVar('branch',   $branchID);
jsVar('diffLink', $diffLink);
jsVar('sortLink', helper::createLink('repo', 'browse', "repoID={$repoID}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"));

/* Disbale check all checkbox of table header */
$config->repo->commentDtable->fieldList['id']['checkbox'] = jsRaw('(rowID) => rowID !== \'HEADER\'');

$commentsTableData = initTableData($revisions, $config->repo->commentDtable->fieldList, $this->repo);

$readAllLink = $this->repo->createLink('log', "repoID=$repoID&objectID=$objectID&entry=" . $encodePath . "&revision=HEAD&type=$logType");

$footToolbar['items'][] = array('text' => $lang->repo->diff, 'className' => "btn primary size-sm btn-diff", 'btnType' => 'primary', 'onClick' => jsRaw('window.diffClick'));
$footToolbar['items'][] = array('text' => $lang->repo->allLog, 'url' => $readAllLink);

sidebar
(
    set::side('right'),
    dtable
    (
        set::id('repo-comments-table'),
        set::cols($config->repo->commentDtable->fieldList),
        set::data($commentsTableData),
        set::onRenderCell(jsRaw('window.renderCommentCell')),
        set::onCheckChange(jsRaw('window.checkedChange')),
        set::canRowCheckable(jsRaw('function(rowID){return canRowCheckable(rowID);}')),
        set::footToolbar($footToolbar),
        set::footer(array('toolbar', 'flex', 'pager')),
        set::footPager(usePager()),
        set::showToolbarOnChecked(false),
    ),
);

render();
