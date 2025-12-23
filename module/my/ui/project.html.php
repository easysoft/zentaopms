<?php
declare(strict_types=1);
/**
 * The project view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('delayInfo', $lang->project->delayInfo);
jsVar('confirmDeleteTip', $lang->project->confirmDelete);

featurebar
(
    set::current($status),
    set::linkParams("status={key}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")
);

$this->loadModel('project');
$config->project->dtable->fieldList['id']['type']     = 'id';
$config->project->dtable->fieldList['id']['checkbox'] = false;

$config->project->dtable->fieldList['status']['show']    = false;
$config->project->dtable->fieldList['budget']['show']    = false;
$config->project->dtable->fieldList['hasProduct']['map'] = $lang->project->projectTypeList;

if($config->edition != 'open') $config->project->dtable->fieldList['workflowGroup']['map'] = $this->loadModel('workflowGroup')->getPairs('project', 'all');

$settings = $this->loadModel('datatable')->getSetting('my');
$projects = initTableData($projects, $settings, $this->project);

$waitCount      = 0;
$doingCount     = 0;
$suspendedCount = 0;
$closedCount    = 0;
foreach($projects as $project)
{
    $this->project->formatDataForList($project, array());

    if($project->status == 'wait')      $waitCount ++;
    if($project->status == 'doing')     $doingCount ++;
    if($project->status == 'suspended') $suspendedCount ++;
    if($project->status == 'closed')    $closedCount ++;

    if(!empty($project->PM))
    {
        $project->PMAccount = $project->PM;
        $project->PMAvatar  = $usersAvatar[$project->PM];
        $project->PM        = \zget($users, $project->PM);
    }

    if($project->budget)
    {
        $projectBudget = in_array($this->app->getClientLang(), array('zh-cn','zh-tw')) ? round((float)$project->budget / 10000, 2) . $lang->project->tenThousand : round((float)$project->budget, 2);

        $project->budget = zget($lang->project->currencySymbol, $project->budgetUnit) . ' ' . $projectBudget;
    }
    else
    {
        $project->budget = $lang->project->future;
    }

    $project->end      = $project->end == LONG_TIME ? $lang->project->longTime : $project->end;
    $project->estimate = helper::formatHours($project->estimate);
    $project->consume  = helper::formatHours($project->consume);
}

$projects = array_values($projects);

$footerHtml = sprintf($lang->project->summary, count($projects));
if($status == 'openedbyme') $footerHtml = sprintf($lang->project->allSummary, count($projects), $waitCount, $doingCount, $suspendedCount, $closedCount);

dtable
(
    set::cols($settings),
    set::data($projects),
    set::customCols(true),
    set::onRenderCell(jsRaw('window.onRenderProjectNameCell')),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', 'project', "status={$status}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footer(array(array('html' => $footerHtml), 'flex', 'pager')),
    set::footPager(usePager()),
    set::emptyTip($lang->project->empty)
);

render();
