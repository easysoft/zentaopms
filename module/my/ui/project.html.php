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

featurebar
(
    set::current($status),
    set::linkParams("status={key}"),
);

$projects = initTableData($projects, $config->my->project->dtable->fieldList, $this->my);

$waitCount      = 0;
$doingCount     = 0;
$suspendedCount = 0;
$closedCount    = 0;
foreach($projects as $project)
{
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

    $project->end = $project->end == LONG_TIME ? $lang->project->longTime : $project->end;

    $project->actions = $this->loadModel('project')->buildActionList($project);
}

$projects = array_values($projects);

$footerHtml = sprintf($lang->project->summary, count($projects));
if($status == 'openedbyme') $footerHtml = sprintf($lang->project->allSummary, count($projects), $waitCount, $doingCount, $suspendedCount, $closedCount);

dtable
(
    set::cols($config->my->project->dtable->fieldList),
    set::data($projects),
    set::onRenderCell(jsRaw('window.onRenderProjectNameCell')),
    set::footer(array(array('html' => $footerHtml), 'flex', 'pager')),
    set::footPager(usePager()),
);

render();
