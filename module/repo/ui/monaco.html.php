<?php
declare(strict_types=1);
/**
 * The monaco view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     repo
 * @link        http://www.zentao.net
 */

namespace zin;

$tree = $this->repo->getFileTree($repo);

jsVar('isonlybody', isonlybody());
jsVar('entry', $entry);
jsVar('repoID', $repoID);
jsVar('repo', $repo);
jsVar('revision', $revision);
jsVar('branchID', $branchID);
jsVar('branchMenus', $branchMenus);
jsVar('file', $file);
jsVar('tree', $tree);
jsVar('openedFiles', array($entry));
jsVar('urlParams', "repoID=$repoID&objectID=$objectID&entry=%s&revision=$revision&showBug=$showBug&encoding=$encoding");
jsVar('currentLink', $this->createLink('repo', 'view', "repoID=$repoID&objectID=$objectID&entry=$file"));

featureBar();

$dropMenus = array();
if(common::hasPriv('repo', 'blame'))    $dropMenus[] = array('text' => $this->lang->repo->blame,    'icon' => 'blame',    'url' => $this->repo->createLink('blame', "repoID=$repoID&objectID=$objectID&entry=$file&revision=$revision&encoding=$encoding"));
if(common::hasPriv('repo', 'download')) $dropMenus[] = array('text' => $this->lang->repo->download, 'icon' => 'download', 'url' => $this->repo->createLink('download', "repoID=$repoID&path=$file&fromRevision=$revision"), 'target' => '_blank');
div(
    set::id('fileTabs'),
    setStyle('position', 'relative'),
    tabs
    (
        set::id('monacoTabs'),
        set::class('relative'),
        div(setStyle(array('position' => 'absolute', 'width' => '100%', 'height' => '35px', 'background' => '#efefef', 'top' => '0px'))),
        tabPane
        (
            set::title($pathInfo['basename']),
            set::active(true),
            set::key('tab-' . str_replace('=', '-', $file)),
            to::suffix
            (
                icon
                (
                    'close',
                    set::class('monaco-close'),
                )
            ),
            div(set::id('tab-' . $file)),
        ),
        dropdown
        (
            set::arrow(false),
            set::staticMenu(true),
            set::key('dropdown'),
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
        div(set::class('absolute top-0 left-0 z-20 arrow-left btn-left'), icon('chevron-left')),
        div(set::class('absolute top-0 right-0 z-20 arrow-right btn-right'), icon('chevron-right')),
    )
);

sidebar
(
    set::side('left'),
    select
    (
        set::id('sourceSwapper'),
        set::items($branchMenus),
        set::value($branchID),
        on::change('window.changeBranch')
    ),
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

a(set::class('iframe'), setData('size', '1000px'), setData('toggle', 'modal'), set::id('linkObject'));
