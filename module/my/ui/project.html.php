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

featurebar
(
    set::current($status),
    set::linkParams("status={key}"),
);

$projects = initTableData($projects, $config->my->project->dtable->fieldList, $this->my);

foreach($projects as $project)
{
    if(!empty($project->PM))
    {
        $project->PMAccount = $project->PM;
        $project->PMAvatar  = $usersAvatar[$project->PM];
        $project->PM        = \zget($users, $project->PM);
    }

    if(!$project->budget) $project->budget = $lang->project->future;
    if($project->budget)
    {
        $projectBudget = in_array($this->app->getClientLang(), array('zh-cn','zh-tw')) ? round((float)$project->budget / 10000, 2) . $lang->project->tenThousand : round((float)$project->budget, 2);

        $project->budget = zget($lang->project->currencySymbol, $project->budgetUnit) . ' ' . $projectBudget;
    }

    $project->end = $project->end == LONG_TIME ? $lang->project->longTime : $project->end;

    $project->actions = $this->loadModel('project')->buildActionList($project);
}

$cols     = array_values($config->my->project->dtable->fieldList);
$projects = array_values($projects);

dtable
(
    set::cols($cols),
    set::data($projects),
    set::footPager
    (
        usePager(),
        set::page($pager->pageID),
        set::recPerPage($pager->recPerPage),
        set::recTotal($pager->recTotal),
        set::linkCreator(helper::createLink('my', 'project', "status={$status}&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}&orderBy=$orderBy"))
    ),
);

render();
