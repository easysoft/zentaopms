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
$this->testtask->buildOperateMenu(null, 'browse');
foreach($tasks as $task)
{
    $actions = array();
    foreach($this->config->testtask->dtable->fieldList['actions']['actionsMap'] as $actionCode => $actionMap)
    {
        $isClickable = $this->testtask->isClickable($task, $actionCode);
        $actions[]   = $isClickable ? $actionCode : array('name' => $actionCode, 'disabled' => true);
    }
    $task->actions = $actions;
}

featureBar
(
    set::current('all'),
    set::linkParams("projectID={$projectID}")
);

$cols = array_values($config->testtask->dtable->fieldList);
$data = array_values($tasks);
toolbar
(
    btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url(helper::createLink('testtask', 'create', "product=$productID")),
            $lang->testtask->create
        )
    )
);

$footerHTML = sprintf($lang->testtask->allSummary, count($tasks), $waitCount, $testingCount, $blockedCount, $doneCount);
dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::fixedLeftWidth('20%'),
    set::footer(array(array('html' => $footerHTML), 'flex', 'pager')),
    set::footPager(usePager()),
);

render();
