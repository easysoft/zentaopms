<?php
declare(strict_types=1);
/**
 * The files view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;

$domBox = empty($diffs) ? p(setClass('detail-content'), $lang->ppm->noChanges) : div(
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
        set::maxWidth(800),
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
);
