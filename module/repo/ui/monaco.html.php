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
div(
    set::id('fileTabs'),
    tabs
    (
        set::id('monacoTabs'),
        tabPane
        (
            set::title($pathInfo['basename']),
            set::active(true),
            set::key('tab-' . str_replace('=', '-', $file)),
            set('data-prevent', false),
            to::suffix
            (
                icon
                (
                    'close',
                    set::class('monaco-close'),
                )
            ),
            tableData
            (
                div(set::id('tab-' . $file))
            )
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
