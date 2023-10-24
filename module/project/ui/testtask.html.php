<?php
declare(strict_types=1);
/**
 * The testcase view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

/* Compute summary. */
$waitCount    = 0;
$testingCount = 0;
$blockedCount = 0;
$doneCount    = 0;
foreach($tasks as $task)
{
    if($task->status == 'wait')    $waitCount ++;
    if($task->status == 'doing')   $testingCount ++;
    if($task->status == 'blocked') $blockedCount ++;
    if($task->status == 'done')    $doneCount ++;
    if($task->build == 'trunk' || empty($task->buildName)) $task->buildName = $this->lang->trunk;
}

featureBar
(
    set::current('all'),
    set::linkParams("projectID={$project->id}")
);

toolbar
(
    common::canModify('project', $project) && common::hasPriv('testtask', 'create') ? btn
    (
        setClass('btn primary'),
        set::icon('plus'),
        set::url(helper::createLink('testtask', 'create', "product=0&executionID=0&build=0&projectID={$project->id}")),
        setData('app', 'project'),
        $lang->testtask->create
    ) : null
);

unset($config->testtask->dtable->fieldList['product']);
unset($config->testtask->dtable->fieldList['execution']);

$tasks      = initTableData($tasks, $config->testtask->dtable->fieldList, $this->testtask);
$cols       = array_values($config->testtask->dtable->fieldList);
$data       = array_values($tasks);
$footerHTML = sprintf($lang->testtask->allSummary, count($tasks), $waitCount, $testingCount, $blockedCount, $doneCount);
dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::fixedLeftWidth('20%'),
    set::orderBy($orderBy),
    set::sortLink(createLink('project', 'testtask', "projectID={$project->id}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footer(array(array('html' => $footerHTML), 'flex', 'pager')),
    set::footPager(usePager()),
);

render();
