<?php
declare(strict_types=1);
/**
 * The testcase view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';

jsVar('unexecuted', $lang->testcase->unexecuted);

featureBar
(
    set::current($type),
    set::linkParams("mode=testcase&type={key}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    li(searchToggle(set::module($this->app->rawMethod . 'Testcase')))
);

$canBatchEdit = common::hasPriv('testcase', 'batchEdit');
$footToolbar  = array('items' => array
(
    $canBatchEdit ? array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => helper::createLink('testcase', 'batchEdit', 'productID=0&branch=all&type=case&tab=my')) : null
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));

if($type == 'openedbyme')
{
    unset($config->my->testcase->dtable->fieldList['testtask']);
    unset($config->my->testcase->dtable->fieldList['openedBy']);
}

if($type == 'assigntome') $config->my->testcase->dtable->fieldList['title']['link']['params'] .= "&from=testtask&taskID={task}";
foreach($config->my->testcase->dtable->fieldList['actions']['list'] as &$action) $action['url']['params'] = str_replace('{caseID}', "{id}", $action['url']['params']);

$cases = initTableData($cases, $config->my->testcase->dtable->fieldList, $this->testcase);
$data  = array_values($cases);

$defaultSummary = sprintf($lang->testcase->failSummary, count($cases), $failCount);
dtable
(
    set::data($data),
    set::cols($config->my->testcase->dtable->fieldList),
    set::userMap($users),
    set::fixedLeftWidth('50%'),
    set::checkable(true),
    set::defaultSummary(array('html' => $defaultSummary)),
    set::checkedSummary($lang->testcase->failCheckedSummary),
    set::checkInfo(jsRaw('function(checkedIDList){return window.setStatistics(this, checkedIDList);}')),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&type={$type}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::emptyTip($lang->testcase->noCase)
);

render();
