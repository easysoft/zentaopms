<?php
declare(strict_types=1);
/**
 * The diffeditor view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */

namespace zin;

$inModal      = isInModal() || !empty($fromModal);
$entry        = count($diffs) && empty($entry) ? $diffs[0]->fileName : $entry;
$currentEntry = $this->repo->encodePath($entry);
$fileInfo     = $entry ? pathinfo($entry) : array();
$showBug      = isset($showBug) ? $showBug : 0;
$objectID     = isset($objectID) ? $objectID : 0;
$tree         = $this->repo->getFileTree($repo, '', $diffs);
$oldRevision  = helper::safe64Encode($oldRevision);
$newRevision  = helper::safe64Encode($newRevision);
$diffLink     = $this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=" . $file . "&oldrevision={oldRevision}&newRevision={newRevision}");

jsVar('diffs', $diffs);
jsVar('tree', $tree);
jsVar('file', $file ? $file : $currentEntry);
jsVar('currentFile', $currentEntry);
jsVar('entry', $entry);
jsVar('diffLink', $diffLink);
jsVar('urlParams', "repoID=$repoID&objectID=$objectID&entry=%s&oldRevision=$oldRevision&newRevision=$newRevision&showBug=$showBug&encoding=$encoding");

\zin\featureBar();

$dropMenus = array();
if(!$inModal)
{
    if(common::hasPriv('repo', 'download')) $dropMenus[] = array('text' => $this->lang->repo->downloadDiff, 'icon' => 'download', 'data-link' => $this->repo->createLink('download', "repoID=$repoID&path={path}&fromRevision=$oldRevision&toRevision=$newRevision&type=path"), 'className' => 'repoDownload-code');

    $dropMenus[] = array('text' => $this->lang->repo->viewDiffList['inline'], 'icon' => 'snap-house', 'id' => 'inline', 'class' => 'inline-appose');
    $dropMenus[] = array('text' => $this->lang->repo->viewDiffList['appose'], 'icon' => 'col-archive', 'id' => 'appose', 'class' => 'inline-appose');
}

div(
    set::id('fileTabs'),
    tabs
    (
        set::id('monacoTabs'),
        set::className('relative'),
        on::click('.monaco-close')->call('closeTab', jsRaw('this')),
        on::click('.repoDownload-code')->call('downloadCode'),
        on::click('.inline-appose')->call('inlineAppose'),
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
                    set::className('monaco-close')
                )
            ),
            div(set::id('tab-' . $currentEntry))
        ),
        $inModal ? null : dropdown
        (
            set::arrow(false),
            set::staticMenu(true),
            btn
            (
                setClass('ghost text-black pull-right absolute top-0 right-0 z-10 monaco-dropmenu'),
                set::icon('ellipsis-v rotate-90')
            ),
            set::items
            (
                $dropMenus
            )
        ),
        div(set::className('absolute top-0 left-0 z-20 arrow-left btn-left'), icon('chevron-left')),
        div(set::className('absolute top-0 right-0 z-20 arrow-right btn-right'), icon('chevron-right'))
    )
);

$inModal ? null : sidebar
(
    set::side('left'),
    set::maxWidth(800),
    setClass('repo-sidebar canvas p-2'),
    treeEditor
    (
        set::id('monacoTree'),
        set::items($tree),
        set::canSplit(false),
        set::collapsedIcon('folder text-warning'),
        set::expandedIcon('folder-open text-warning'),
        set::normalIcon('file-text-alt'),
        set::selected($currentEntry),
        set::onClickItem(jsRaw('window.treeClick'))
    )
);

a(set::className('iframe'), setData('size', '1200px'), setData('toggle', 'modal'), set::id('linkObject'));
