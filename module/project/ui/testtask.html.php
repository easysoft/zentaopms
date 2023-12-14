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

jsVar('allTasks', $lang->testtask->allTasks);
jsVar('pageSummary', sprintf($lang->testtask->allSummary, count($tasks), $waitCount, $testingCount, $blockedCount, $doneCount));
jsVar('checkedAllSummary', $lang->testtask->checkedAllSummary);

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
    )
);

$canCreate = common::canModify('project', $project) && common::hasPriv('testtask', 'create');
toolbar
(
    $canCreate ? btn
    (
        setClass('btn primary'),
        set::icon('plus'),
        set::url(helper::createLink('testtask', 'create', "product=0&executionID=0&build=0&projectID={$project->id}")),
        setData('app', 'project'),
        $lang->testtask->create
    ) : null
);

$config->project->dtable->testtask->fieldList['actions']['list']['report']['url']['params'] = "objectID={project}&objectType=project&extra={id}";
$tasks   = initTableData($tasks, $config->project->dtable->testtask->fieldList);
$summary = sprintf($lang->testtask->allSummary, count($tasks), $waitCount, $testingCount, $blockedCount, $doneCount);

$footToolbar = array();
if(common::canModify('project', $project) and common::hasPriv('project', 'testreport'))
{
    $footToolbar = array('items' => array
        (
            array('text' => $lang->testreport->common, 'className' => 'batch-btn', 'data-url' => createLink('project', 'testreport', "objectID={$project->id}&objctType=project"))
        ), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));
}

dtable
(
    set::id('taskTable'),
    set::cols($config->project->dtable->testtask->fieldList),
    set::data($tasks),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::userMap($users),
    set::orderBy($orderBy),
    set::sortLink(createLink('project', 'testtask', "projectID={$project->id}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::plugins(array('cellspan')),
    set::getCellSpan(jsRaw('window.getCellSpan')),
    set::fixedLeftWidth('20%'),
    set::footToolbar($footToolbar),
    set::footPager(usePager(array
    (
        'recPerPage'  => $pager->recPerPage,
        'recTotal'    => $pager->recTotal,
        'linkCreator' => helper::createLink('project', 'testtask', "projectID={$project->id}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}")
    ))),
    set::checkInfo(jsRaw('function(checkedIDList){return window.setStatistics(this, checkedIDList);}')),
    set::emptyTip($lang->testtask->noTesttask),
    set::createTip($lang->testtask->create),
    set::createLink($canCreate ? createLink('testtask', 'create', "product=0&executionID=0&build=0&projectID={$project->id}") : '')
);

render();
