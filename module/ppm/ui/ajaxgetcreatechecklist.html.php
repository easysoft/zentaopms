<?php
declare(strict_types=1);
namespace zin;
global $app;
$entry        = count($diffs) ? $diffs[0]->fileName : '';
$fileInfo     = $entry ? pathinfo($entry) : array();
$currentEntry = $this->repo->encodePath($entry);
$tree         = $this->repo->getFileTree($repo, '', $diffs);
$oldRevision  = helper::safe64Encode($sourceBranch);
$newRevision  = helper::safe64Encode($targetBranch);
jsVar('repoID', $repoID);
jsVar('diffs', $diffs);
jsVar('tree', $tree);
jsVar('currentFile', $currentEntry);
jsVar('file', $currentEntry);
jsVar('entry', $entry);
jsVar('urlParams', "repoID=$repoID&objectID=0&entry=%s&oldRevision=$oldRevision&newRevision=$newRevision&showBug=0&encode=&showLinkObject=0");
h:css("#monacoTree .text-clip {overflow: visible;}");

if(!hasPriv('repo', 'diff')) unset($config->ppm->createCheck->commit->dtable->fieldList['id']['link']);
unset($config->ppm->createCheck->linkObject->dtable->fieldList['assignedTo']);

tabs
(
    tabPane
    (
        set::key('commit'),
        set::title($lang->ppm->commitLogs . ' (' . $commitPager->recTotal . ')'),
        set::active(true),
        dtable
        (
            set::cols($config->ppm->createCheck->commit->dtable->fieldList),
            set::data($commits),
            set::userMap($users),
            set::loadPartial(true),
            set::footPager(usePager('commitPager'))
        )
    ),
    tabPane
    (
        set::key('diff'),
        set::title($lang->ppm->changeFiles . ' (' . count($diffs) . ')'),
        empty($diffs) ? p(setClass('detail-content'), $lang->ppm->noChanges) : div
        (
            setID('diff-sidebar-left'),
            div
            (
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
                                set::className('monaco-close')
                            )
                        ),
                        div(set::id('tab-' . $currentEntry))
                    ),
                    div(set::className('absolute top-0 left-0 z-20 arrow-left btn-left'), icon('chevron-left')),
                    div(set::className('absolute top-0 right-0 z-20 arrow-right btn-right'), icon('chevron-right'))
                )
            ),
            sidebar
            (
                set::maxWidth(800),
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
            ),
            on::click('.inline-appose')->call('inlineAppose'),
            on::click('#monacoTabs .monaco-close')->call('closeTab', jsRaw('this')),
            on::click('#monacoTabs .menu-item a')->call('changeDiffType', jsRaw('this')),
        )
    ),
    tabPane
    (
        set::key('object'),
        set::title($lang->ppm->linkedObject . ' (' . $objectPager->recTotal . ')'),
        dtable
        (
            set::cols($config->ppm->createCheck->linkObject->dtable->fieldList),
            set::data($objects),
            set::userMap($users),
            set::loadPartial(true),
            set::onRenderCell(jsRaw('window.renderObjectCell')),
            set::footPager(usePager('objectPager'))
        )
    ),
);
