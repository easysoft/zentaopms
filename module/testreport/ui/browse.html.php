<?php
declare(strict_types=1);
/**
 * The browse view file of testreport module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testreport
 * @link        https://www.zentao.net
 */
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

foreach($reports as $report)
{
    $taskName = '';
    foreach(explode(',', $report->tasks) as $taskID) $taskName .= '#' . $taskID . $tasks[$taskID] . ' ';
    $report->tasks = $taskName;
}

$config->testreport->dtable->fieldList['execution']['map'] = $executions;

$tableData = initTableData($reports, $config->testreport->dtable->fieldList, $this->testreport);

$cols = array_values($config->testreport->dtable->fieldList);
$data = array_values($tableData);

featureBar
(
    set::module('testreport'),
    set::method('browse'),
    set::current('all'),
    set::linkParams("objectID={$objectID}&objectType={$objectType}&extra={$extra}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")
);

toolbar
(
    $app->rawModule != 'project' && $app->rawModule != 'execution' ? btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set('data-app', $app->tab),
            set::url(helper::createLink('testreport', 'create', "objectID=0&objectType=testtask&productID={$objectID}")),
            $lang->testreport->create
        )
    ) : null
);

$linkParams = '';
foreach($app->rawParams as $key => $value) $linkParams = $key != 'orderBy' ? "{$linkParams}&{$key}={$value}" : "{$linkParams}&orderBy={name}_{sortType}";

dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::orderBy($orderBy),
    set::sortLink(createLink($app->rawModule, $app->rawMethod, $linkParams)),
    set::fixedLeftWidth('0.33'),
    set::footPager(usePager()),
    set::emptyTip($lang->testreport->noReport)
);

render();
