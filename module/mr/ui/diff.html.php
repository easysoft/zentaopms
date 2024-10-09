<?php
declare(strict_types=1);
/**
 * The diff file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

$app->loadLang('productplan');
$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($MR->id),
            set::level(1),
            set::text($MR->title)
        ),
        $MR->deleted ? h::span
        (
            setClass('label danger'),
            $lang->product->deleted
        ) : null
    )
);

include 'header.html.php';
$entry        = count($diffs) ? $diffs[0]->fileName : '';
$currentEntry = $this->repo->encodePath($entry);
$fileInfo     = $entry ? pathinfo($entry) : array();
$showBug      = isset($showBug) ? $showBug : 0;
$objectID     = isset($objectID) ? $objectID : 0;
$tree         = $this->repo->getFileTree($repo, '', $diffs);
$diffLink     = $this->repo->createLink('diff', "repoID={$MR->repoID}&objectID=$objectID&entry=&oldrevision={oldRevision}&newRevision={newRevision}");

jsVar('diffs', $diffs);
jsVar('tree', $tree);
jsVar('file', $currentEntry);
jsVar('entry', $entry);
jsVar('diffLink', $diffLink);
jsVar('urlParams', "repoID={$MR->repoID}&objectID=$objectID&entry=%s&oldRevision=$oldRevision&newRevision=$newRevision&showBug=$showBug&encoding=$encoding");

$dropMenus = array();
if(common::hasPriv('repo', 'download')) $dropMenus[] = array('text' => $this->lang->repo->downloadDiff, 'icon' => 'download', 'url' => $this->repo->createLink('download', "repoID={$MR->repoID}&path=$currentEntry&fromRevision=$oldRevision&toRevision=$newRevision&type=path"), 'target' => '_self');

$dropMenus[] = array('text' => $this->lang->repo->viewDiffList['inline'], 'icon' => 'snap-house', 'id' => 'inline', 'class' => 'inline-appose');
$dropMenus[] = array('text' => $this->lang->repo->viewDiffList['appose'], 'icon' => 'col-archive', 'id' => 'appose', 'class' => 'inline-appose');

panel
(
    setClass('relative'),
    div
    (
        set::id('mrMenu'),
        $headers
    ),
    empty($diffs) ? p(setClass('detail-content'), $lang->mr->noChanges) : div(
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
                dropdown
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
        ),

        sidebar
        (
            set::side('left'),
            treeEditor
            (
                set::id('monacoTree'),
                set::items($tree),
                set::canSplit(false),
                set::collapsedIcon('folder'),
                set::expandedIcon('folder-open'),
                set::normalIcon('file-text-alt'),
                set::selected($currentEntry),
                set::onClickItem(jsRaw('window.treeClick'))
            )
        ),
        on::click('.inline-appose')->call('inlineAppose'),
        on::click('#monacoTabs .monaco-close')->call('closeTab', jsRaw('this')),
        on::click('#monacoTabs .menu-item a')->call('changeDiffType', jsRaw('this')),
        a(set::className('iframe'), setData('size', '1200px'), setData('toggle', 'modal'), set::id('linkObject'))
    )
);

include 'actions.html.php';
