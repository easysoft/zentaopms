<?php
declare(strict_types=1);
/**
 * The task file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
$isInPlanView = $type == 'task';
jsVar('planList', $planList);
if($repoID && !$isInPlanView) dropmenu(set::objectID($repoID), set::tab('repo'));
if($isInPlanView) include 'plan.header.html.php';

$isInPlanView ? null : featureBar
(
    div(searchToggle(set::module('codeScanTask'), set::open($type == 'bySearch')))
);
$config->codescan->task->dtable->fieldList['repo']['map']   = $repoList;
if($repoID) unset($config->codescan->task->dtable->fieldList['repoID']);
if($isInPlanView)
{
    unset($config->codescan->task->dtable->fieldList['actions']);
    unset($config->codescan->task->dtable->fieldList['plan']);
}
if(!hasPriv('codescan', 'issue')) unset($config->codescan->task->actionList['issue']);
$cols = $this->loadModel('datatable')->getSetting('codescan', 'task');
if(hasPriv('codescan', 'taskview'))
{
    $cols['name']['link']['params'] = "serviceRepoID={repoID}&taskID={id}&repoID={$repoID}&type=issue";
    if(isset($cols['actions']['list']['issue'])) $cols['actions']['list']['issue']['url']['params'] = "serviceRepoID={repoID}&taskID={id}&repoID={$repoID}&type=issue";
}
if(hasPriv('codescan', 'planview') && isset($cols['planID']))
{
    $cols['planID']['name'] = 'planName';
    $cols['planID']['link'] = array('module' => 'codescan', 'method' => 'planView', 'params' => "serviceRepoID={repoID}&planID={planID}&repoID={$repoID}&type=view");
}

if(isset($cols['issueCount'])) $cols['issueCount']['link']['params'] = "repoID={repoID}&taskID={id}&serviceRepoID={repoID}&type=all";
$urlParams = array
(
    'repoID'        => $repoID,
    'serviceRepoID' => $serviceRepoID,
    'planID'        => $planID,
    'type'          => $type,
    'queryID'       => $queryID,
    'orderBy'       => '{name}_{sortType}',
    'recPerPage'    => $pager->recPerPage,
    'pageID'        => $pager->pageID
);

if($isInPlanView)
{
    $urlParams = array
    (
        'serviceRepoID' => $serviceRepoID,
        'planID'        => $planID,
        'repoID'        => $repoID,
        'type'          => $type,
        'orderBy'       => '{name}_{sortType}',
        'recPerPage'    => $pager->recPerPage,
        'pageID'        => $pager->pageID
    );
}

$taskList = initTableData($tasks, $cols);
$table = dtable
(
    set::customCols(!$isInPlanView),
    set::cols($cols),
    set::data($tasks),
    set::sortLink(createLink('codescan', $isInPlanView ? 'planview' : 'task', $urlParams)),
    set::orderBy($orderBy),
    set::actionItemCreator(jsRaw('window.actionItemCreator')),
    set::onRenderCell(jsRaw('window.renderTaskCell')),
    set::footPager(usePager())
);


$isInPlanView ? panel
(
    div
    (
        setID('planMenu'),
        setClass('flex justify-between'),
        $headers
    ),
    div
    (
        set::bodyClass('flex'),
        div
        (
            setClass('w-full flex-auto'),
            $table
        )
    )
) : null;

$methodName = 'ajaxGetTaskLog';
$logParams  = "repoID=%logID%&pipelineName=%pipelineName%&executionID=%executionID%";
//include  'logs.html.php';
