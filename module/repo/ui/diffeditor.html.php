<?php
declare(strict_types=1);
/**
 * The diffeditor view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     repo
 * @link        http://www.zentao.net
 */

namespace zin;

$entry        = count($diffs) ? $diffs[0]->fileName : '';
$currentEntry = $this->repo->encodePath($entry);
$fileInfo     = $entry ? pathinfo($entry) : array();
$showBug      = isset($showBug) ? $showBug : true;
$objectID     = isset($objectID) ? $objectID : 0;
$tree         = $this->repo->getFileTree($repo, '', $diffs);
$diffLink     = $this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=" . $file . "&oldrevision={oldRevision}&newRevision={newRevision}");

jsVar('diffs', $diffs);
jsVar('tree', $tree);
jsVar('file', $currentEntry);
jsVar('entry', $entry);
jsVar('diffLink', $diffLink);
jsVar('urlParams', "repoID=$repoID&objectID=$objectID&entry=%s&oldRevision=$oldRevision&newRevision=$newRevision&showBug=$showBug&encoding=$encoding");

\zin\featureBar();

$dropMenus = array();
if(!isonlybody())
{
    if(common::hasPriv('repo', 'download')) $dropMenus[] = array('text' => $this->lang->repo->downloadDiff, 'icon' => 'download', 'data-link' => $this->repo->createLink('download', "repoID=$repoID&path={path}&fromRevision=$oldRevision&toRevision=$newRevision&type=path"), 'id' => 'repoDownloadCode');

    $dropMenus[] = array('text' => $this->lang->repo->viewDiffList['inline'], 'icon' => 'snap-house', 'id' => 'inline', 'class' => 'inline-appose');
    $dropMenus[] = array('text' => $this->lang->repo->viewDiffList['appose'], 'icon' => 'col-archive', 'id' => 'appose', 'class' => 'inline-appose');
}
div(
    set::id('fileTabs'),
    tabs
    (
        set::id('monacoTabs'),
        set::className('relative'),
        div(setStyle(array('position' => 'absolute', 'width' => '100%', 'height' => '35px', 'background' => '#efefef', 'top' => '0px'))),
        tabPane
        (
            set::title($fileInfo['basename']),
            set::active(true),
            set::key('tab-' . str_replace('=', '-', $currentEntry)),
            to::suffix
            (
                icon
                (
                    'close',
                    set::className('monaco-close'),
                )
            ),
            div(set::id('tab-' . $currentEntry)),
        ),
        dropdown
        (
            set::arrow(false),
            set::staticMenu(true),
            on::click('#repoDownloadCode', 'downloadCode'),
            set::className('absolute top-0 right-0 z-10 monaco-dropmenu'),
            btn
            (
                setClass('ghost text-black pull-right'),
                set::icon('ellipsis-v rotate-90'),
            ),
            set::items
            (
                $dropMenus
            ),
        ),
        div(set::className('absolute top-0 left-0 z-20 arrow-left btn-left'), icon('chevron-left')),
        div(set::className('absolute top-0 right-0 z-20 arrow-right btn-right'), icon('chevron-right')),
    )
);

isonlybody() ? null : sidebar
(
    set::side('left'),
    setClass('repo-sidebar canvas p-2'),
    treeEditor
    (
        set::id('monacoTree'),
        set::items($tree),
        set::canSplit(false),
        set::collapsedIcon('folder'),
        set::expandedIcon('folder-open'),
        set::normalIcon('file-text-alt'),
        set::activeKey($entry),
        set::onClickItem(jsRaw('window.treeClick')),
    )
);

a(set::className('iframe'), setData('size', '1200px'), setData('toggle', 'modal'), set::id('linkObject'));
