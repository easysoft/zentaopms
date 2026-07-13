<?php
declare(strict_types=1);
/**
 * The testtask view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';

featureBar
(
    set::current($browseType),
    set::linkParams("mode=testtask&browseType={key}&param=&orderBy={$orderBy}"),
    li(searchToggle(set::module('myTesttask'),set::open($browseType == 'bySearch')))
);

foreach($config->my->testtask->dtable->fieldList['actions']['list'] as $actionKey => $action)
{
    if(!isset($action['data-toggle']) && !isset($action['data-confirm'])) $config->my->testtask->dtable->fieldList['actions']['list'][$actionKey]['data-app'] = 'qa';
}

$cols  = $this->loadModel('datatable')->getSetting('my', 'testtask');
$tasks = initTableData($tasks, $cols, $this->testtask);
$data  = array_values($tasks);

$footerHTML = $app->rawMethod == 'work' ? sprintf($lang->testtask->mySummary, count($tasks), $waitCount, $testingCount, $blockedCount) : sprintf($lang->testtask->pageSummary, count($tasks));
dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::customCols(true),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footer(array(array('html' => $footerHTML), 'flex', 'pager')),
    set::footPager(usePager()),
    set::emptyTip($lang->testtask->noTesttask)
);

render();
