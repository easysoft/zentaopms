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

featureBar();

$dropMenus = array();
if(common::hasPriv('repo', 'download')) $dropMenus[] = array('text' => $this->lang->repo->downloadDiff, 'icon' => 'download', 'url' => $this->repo->createLink('download', "repoID=$repoID&path=$currentEntry&fromRevision=$oldRevision&toRevision=$newRevision&type=path"), 'target' => '_blank');

$dropMenus[] = array('text' => $this->lang->repo->viewDiffList['inline'], 'icon' => 'snap-house', 'id' => 'inline', 'class' => 'inline-appose');
$dropMenus[] = array('text' => $this->lang->repo->viewDiffList['appose'], 'icon' => 'col-archive', 'id' => 'appose', 'class' => 'inline-appose');
div(
    set::id('fileTabs'),
    tabs
    (
        set::id('monacoTabs'),
        set::class('relative'),
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
                    set::class('monaco-close'),
                )
            ),
            div(set::id('tab-' . $currentEntry)),
        ),
        dropdown
        (
            set::arrow(false),
            set::staticMenu(true),
            set::class('absolute top-0 right-0 z-10 monaco-dropmenu'),
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
    )
);

sidebar
(
    set::side('left'),
    tree
    (
        set::id('monacoTree'),
        set::items($tree),
        set::collapsedIcon('folder'),
        set::expandedIcon('folder-open'),
        set::normalIcon('file-text-alt'),
        set::activeKey($entry),
        set::onClickItem(jsRaw('window.treeClick')),
    )
);

a(set::class('iframe'), setData('width', '90%'), setData('toggle', 'modal'), set::id('linkObject'));
