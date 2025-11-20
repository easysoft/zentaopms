<?php
declare(strict_types=1);
/**
 * The testtask view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('allTasks', $lang->testtask->allTasks);
jsVar('pageSummary', sprintf($lang->testtask->allSummary, count($tasks), $waitCount, $testingCount, $blockedCount, $doneCount));
jsVar('checkedAllSummary', $lang->testtask->checkedAllSummary);

$productItems[] = array('text' => $lang->testtask->all, 'url' => createLink('execution', 'testtask', array('executionID' => $executionID)));
foreach($products as $currentProductID => $productName)
{
    $productItems[] = array('text' => $productName, 'url' => createLink('execution', 'testtask', array('executionID' => $executionID, 'productID' => $currentProductID)));
}

$productDropdown = dropdown
(
    to('trigger', btn($productID ? zget($products, $productID) : $lang->testtask->all, setClass('ghost'))),
    set::items($productItems)
);

featureBar
(
    li
    (
        setClass('nav-item'),
        a
        (
            set('class', 'active'),
            $lang->testtask->browse
        )
    ),
    to::before($productDropdown)
);

$canCreate = $canBeChanged && hasPriv('testtask', 'create');

toolbar
(
    $canCreate ? btn
    (
        setClass('btn primary'),
        set::icon('plus'),
        set::url(createLink('testtask', 'create', "product=0&executionID=$executionID&build=0&projectID=$execution->project")),
        set('data-app', $app->tab),
        $lang->testtask->create
    ) : null
);

foreach($tasks as $id => $task) $tasks[$id]->taskID = $task->id;
$tasks = initTableData(array_values($tasks), $config->execution->testtask->dtable->fieldList, $this->testtask);

$footToolbar = array();
if($canBeChanged and common::hasPriv('testreport', 'browse'))
{
    $footToolbar = array('items' => array
        (
            array('text' => $lang->testreport->common, 'className' => 'batch-btn', 'data-url' => createLink('execution', 'testreport', "objectID=$executionID&objctType=execution"))
        ), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));
}

dtable
(
    set::id('taskTable'),
    set::userMap($users),
    set::cols($config->execution->testtask->dtable->fieldList),
    set::data($tasks),
    set::plugins(array('cellspan')),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::orderBy($orderBy),
    set::sortLink(createLink('execution', 'testtask', "executionID={$execution->id}&productID={$productID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&page={$pager->pageID}")),
    set::getCellSpan(jsRaw('window.getCellSpan')),
    set::footToolbar($footToolbar),
    set::footPager(usePager(array
    (
        'recPerPage'  => $pager->recPerPage,
        'recTotal'    => $pager->recTotal,
        'linkCreator' => helper::createLink('execution', 'testtask', "executionID={$execution->id}&productID={$productID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}")
    ))),
    set::checkInfo(jsRaw('function(checkedIDList){return window.setStatistics(this, checkedIDList);}')),
    set::emptyTip($lang->testtask->noTesttask),
    set::createTip($lang->testtask->create),
    set::createLink($canCreate ? createLink('testtask', 'create', "product=0&executionID=$executionID&build=0&projectID=$execution->project") : '')
);
