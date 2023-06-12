<?php
declare(strict_types=1);
/**
 * The browse view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     project
 * @link        http://www.zentao.net
 */

namespace zin;

/* zin: Define the feature bar on main menu. */
featureBar
(
    set::current($browseType),
    set::linkParams("programID={$programID}&status={key}"),
    item(set(array
    (
        'type' => "checkbox",
        'name' => 'involved',
        'text' => $lang->project->mine
    ))),
    li(searchToggle())
);

/* zin: Define the toolbar on main menu. */
toolbar
(
    item(set(array
    (
        'type'  => "btnGroup",
        'items' => array(array
        (
            'icon'  => "list",
            'text'  => "",
            'class' => "btn-icon primary"
        ), array
        (
            'icon'  => "cards-view",
            'text'  => "",
            'class' => "btn-icon"
        ))
    ))),
    item(set(array
    (
        'icon'  => 'export',
        'text'  => $lang->project->export,
        'class' => "ghost export"
    ))),
    item(set(array
    (
        'icon'       => 'plus',
        'text'       => $lang->project->create,
        'class'      => "primary create-project-btn",
        'url'        => $this->createLink('project', 'createGuide', '', '', true),
        'data-modal' => 'modal'
    )))
);

/* zin: Define the sidebar in main content. */
sidebar
(
    moduleMenu(set(array(
        'modules'   => $programTree,
        'activeKey' => $programID,
        'closeLink' => $this->createLink('project', 'browse')
    )))
);

$canBatchEdit = common::hasPriv('project', 'batchEdit');
$footToolbar  = array();
if($canBatchEdit)
{
    $footToolbar['items'][] = array(
        'type'  => 'btn-group',
        'items' => array(
            array('text' => $lang->edit, 'class' => 'btn secondary size-sm batch-btn', 'btnType' => 'primary', 'data-url' => createLink('project', 'batchEdit')),
        )
    );
}

$settings = $this->loadModel('datatable')->getSetting('project');
foreach($settings as $key => $value)
{
    if($value->id == 'status' && strpos(',all,bysearch,undone,', ",$browseType,") === false) $value->show = false;
    if(commonModel::isTutorialMode() && ($value->id == 'PM' || $value->id == 'budget' || $value->id == 'teamCount')) $value->show = false;

    if(!$value->show) unset($settings[$key]);
}

/* zin: Define the dtable in main content. */
dtable
(
    set::groupDivider(true),
    set::cols($settings),
    set::data($projectStats),
    set::checkable($canBatchEdit),
    set::footToolbar($footToolbar),
    set::sortLink(helper::createLink('project', 'browse', "programID=$programID&browseType=$browseType&param=$param&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager()),
    set::customCols(true)
);

render();
