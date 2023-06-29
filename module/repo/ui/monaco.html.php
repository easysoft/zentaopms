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
jsVar('file', $file);
jsVar('tree', $tree);
jsVar('openedFiles', array($entry));
jsVar('urlParams', "repoID=$repoID&objectID=$objectID&entry=%s&revision=$revision&showBug=$showBug&encoding=$encoding");

featureBar();

$dropMenus = array();
if(common::hasPriv('repo', 'blame'))    $dropMenus[] = array('text' => $this->lang->repo->blame,    'icon' => 'blame',    'url' => $this->repo->createLink('blame', "repoID=$repoID&objectID=$objectID&entry=$file&revision=$revision&encoding=$encoding"));
if(common::hasPriv('repo', 'download')) $dropMenus[] = array('text' => $this->lang->repo->download, 'icon' => 'download', 'url' => $this->repo->createLink('download', "repoID=$repoID&path=$file&fromRevision=$revision"), 'target' => '_blank');
div(
    set::id('fileTabs'),
    tabs
    (
        set::id('monacoTabs'),
        set::class('relative'),
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
