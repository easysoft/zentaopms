<?php
declare(strict_types=1);
/**
 * The task view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     execution
 * @link        http://www.zentao.net
 */

namespace zin;

/* zin: Define the set::module('task') feature bar on main menu. */
featureBar
(
    setClass('nav-item'),
    btn
    (
        set::text($lang->execution->importTask),
        set::active(true),
        set::url('###')
    ),
    set::current($browseType),
    set::linkParams("executionID={$execution->id}&status={key}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
);

$config->task->dtable->importTask->fieldList['execution']['map'] = $executions;
if($execution->lifetime == 'ops' || in_array($execution->attribute, array('request', 'review'))) unset($config->task->dtable->importTask->fieldList['story']);

jsVar('orderBy',  $orderBy);
jsVar('sortLink', helper::createLink('execution', 'importTask', "executionID={$execution->id}&fromExecution={$fromExecution}&orderBy={orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"));
dtable
(
    set::userMap($memberPairs),
    set::cols(array_values($config->task->dtable->importTask->fieldList)),
    set::data($tasks2Imported),
    set::showToolbarOnChecked(false),
    set::sortLink(jsRaw('createSortLink')),
    set::footToolbar(array(
        'items' => array(
            array(
                'text'  => $lang->execution->importTask,
                'class' => 'btn secondary toolbar-item batch-btn size-sm',
                'data-url' => createLink('execution', 'importtask', "executionID={$execution->id}&fromExecution={$fromExecution}"),
            ),
            array(
                'text'  => $lang->goback,
                'class' => 'btn toolbar-item size-sm text-gray',
                'url'   => 'javascript:window.history.go(-1);',
            )
        )
    )),
    set::footPager(
        usePager(),
        set::recPerPage($pager->recPerPage),
        set::recTotal($pager->recTotal),
        set::linkCreator(helper::createLink('execution', 'importTask', "executionID={$execution->id}&fromExecution={$fromExecution}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}"))
    )
);

render();
