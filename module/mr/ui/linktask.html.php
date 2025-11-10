<?php
declare(strict_types=1);
/**
 * The linktask file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

$app->loadModuleConfig('repo');

$moduleName = $app->rawModule;
jsVar('orderBy',  $orderBy);
jsVar('sortLink', createLink($moduleName, 'linkTask', "MRID=$MRID&repoID=$repoID&browseType=$browseType&param=$param&orderBy={orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"));

$footToolbar = array(
    'items' => array
    (
        array(
            'text'      => $lang->repo->linkTask,
            'className' => 'batch-btn ajax-btn',
            'data-app'  => $app->tab,
            'data-url'  => createLink($moduleName, 'linkTask', "MRID=$MRID&repoID=$repoID&browseType=$browseType&param=$param&orderBy=$orderBy")
        )
    ),
    'btnProps' => array('size' => 'sm', 'btnType' => 'secondary', 'data-type' => 'tasks'));

searchForm
(
    set::module('mrTask'),
    set::simple(true),
    set::show(true),
    set::extraHeight('+144'),
    set::onSearch(jsRaw("window.onSearchLinks.bind(null, 'mr-task')"))
);

div
(
    set('class', 'mr-linkstory-title'),
    icon('unlink'),
    span
    (
        set('class', 'font-semibold ml-2'),
        $lang->repo->unlinkedTasks . "({$pager->recTotal})"
    )
);
$config->repo->taskDtable->fieldList['assignedTo']['currentUser'] = $app->user->account;
$allTasks = initTableData($allTasks, $config->repo->taskDtable->fieldList);
$data = array_values($allTasks);
dtable
(
    set::userMap($users),
    set::data($data),
    set::cols($config->repo->taskDtable->fieldList),
    set::checkable(true),
    set::loadPartial(true),
    set::footToolbar($footToolbar),
    set::sortLink(jsRaw('createSortLink')),
    set::footer(array('checkbox', 'toolbar', array('html' => html::a(inlink('link', "MRID=$MRID&type=task"), $lang->goback, '', "class='btn size-sm'")), 'flex', 'pager')),
    set::footPager(usePager())
);
