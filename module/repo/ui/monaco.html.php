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

$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, $app->tab == 'devops' ? 'ajaxGetDropMenu' : 'ajaxGetDropMenuData', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

if(isonlybody())
{
    to::header(false);
    to::main(false);
}

jsVar('isonlybody', isonlybody());
jsVar('entry', $entry);
jsVar('repoID', $repoID);
jsVar('repo', $repo);
jsVar('revision', $revision);
jsVar('branchID', $branchID);
jsVar('branchMenus', $dropMenus);
jsVar('file', $file);
jsVar('tree', $tree);
jsVar('appTab', $app->tab);
jsVar('openedFiles', array($entry));
jsVar('urlParams', "repoID=$repoID&objectID=$objectID&entry=%s&revision=$revision&showBug=$showBug&encoding=$encoding");
jsVar('currentLink', $this->createLink('repo', 'view', "repoID=$repoID&objectID=$objectID&entry=$file"));

\zin\featureBar();

$monacoDropMenus = array();
if(!isonlybody() && common::hasPriv('repo', 'blame'))    $monacoDropMenus[] = array('text' => $this->lang->repo->blame,    'icon' => 'blame',    'data-link' => $this->repo->createLink('blame', "repoID=$repoID&objectID=$objectID&entry={path}&revision=$revision&encoding=$encoding"), 'class' => 'repoDropDownMenu');
if(!isonlybody() && common::hasPriv('repo', 'download')) $monacoDropMenus[] = array('text' => $this->lang->repo->download, 'icon' => 'download', 'data-link' => $this->repo->createLink('download', "repoID=$repoID&path={path}&fromRevision=$revision"), 'class' => 'repoDropDownMenu');

$tabs = array(array('name' => 'branch', 'text' => $lang->repo->branch), array('name' => 'tag', 'text' => $lang->repo->tag));
$menuData = $repo->SCM == 'Subversion' ? array() : array('branch' => $dropMenus['branchMenus'], 'tag' => $dropMenus['tagMenus']);

div(
    set::id('fileTabs'),
    setStyle('position', 'relative'),
    tabs
    (
        set::id('monacoTabs'),
        set::className('relative'),
        div(setStyle(array('position' => 'absolute', 'width' => '100%', 'height' => '40px', 'background' => '#efefef', 'top' => '0px'))),
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
                    set::className('monaco-close')
                )
            ),
            div(set::id('tab-' . $file))
        ),
        empty($monacoDropMenus) ? null : dropdown
        (
            set::arrow(false),
            set::staticMenu(true),
            set::key('dropdown'),
            set::className('absolute top-0 right-0 z-10 monaco-dropmenu'),
            btn
            (
                setClass('ghost text-black pull-right'),
                set::icon('ellipsis-v rotate-90')
            ),
            set::items
            (
                $monacoDropMenus
            )
        ),
        div(set::className('absolute top-0 left-0 z-20 arrow-left btn-left'), icon('chevron-left')),
        div(set::className('absolute top-0 right-0 z-20 arrow-right btn-right'), icon('chevron-right'))
    )
);

helper::isAjaxRequest('modal') || isonlybody() ? null : sidebar
(
    set::side('left'),
    setClass('repo-sidebar canvas'),
    $repo->SCM == 'Subversion' ? null : div
    (
        setClass('surface'),
        dropmenu
        (
            setID('repoBranchDropMenu'),
            setClass('px-2'),
            on::click('window.changeBranch'),
            on::change('window.changeBranch'),
            set::objectID($dropMenus['selected']),
            set::text($dropMenus['selected']),
            set::data(array('data' => $menuData, 'tabs' => $tabs))
        )
    ),
    div(
        setClass('px-2'),
        treeEditor
        (
            set::id('monacoTree'),
            set::items($tree),
            set::canSplit(false),
            set::collapsedIcon('folder'),
            set::expandedIcon('folder-open'),
            set::normalIcon('file-text-alt'),
            set::activeKey($entry),
            set::onClickItem(jsRaw('window.treeClick'))
        )
    )
);

a(set::className('iframe'), setData('size', '1200px'), setData('toggle', 'modal'), set::id('linkObject'));
