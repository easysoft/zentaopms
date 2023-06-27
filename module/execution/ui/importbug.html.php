<?php
declare(strict_types=1);
/**
 * The task import file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     execution
 * @link        http://www.zentao.net
 */

namespace zin;

featureBar
(
    set::current('all'),
    set::linkParams("executionID={$execution->id}"),
    li
    (
        searchToggle(set::module('bug'))
    )
);

formBase
(
    setID('importForm'),
    set::action(createLink('execution', 'importBug', "executionID={$execution->id}&browseType={$browseType}&param={$param}")),
    set::actions(array()),
    dtable
    (
        set::userMap($users),
        set::cols($config->execution->importBug->dtable->fieldList),
        set::data($bugs),
        set::checkable(true),
        set::showToolbarOnChecked(false),
        set::onRenderCell(jsRaw('window.handleImportBug')),
        set::onRenderRow(jsRaw('window.onRenderRow')),
        set::footToolbar(array(
            'items' => array(
                array(
                    'text'  => $lang->import,
                    'class' => 'btn secondary import-bug-btn size-sm',
                ),
                array(
                    'text'  => $lang->goback,
                    'class' => 'btn size-sm text-gray',
                    'url'   => createLink('execution', 'task', "executionID={$execution->id}")
                )
            )
        )),
        set::footPager(
            usePager(),
            set::recPerPage($recPerPage),
            set::recTotal($recTotal),
            set::linkCreator(helper::createLink('execution', 'importBug', "executionID={$execution->id}&browseType={$browseType}&param=$param&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}"))
        )
    )
);

jsVar('executionBegin', $execution->begin);
div
(
    setClass('hidden'),
    setID('priSelect'),
    select
    (
        setClass('select-pri w-12'),
        set::name('pri[]'),
        set::items($lang->task->priList),
        set::required(true),
    )
);

div
(
    setClass('hidden'),
    setID('userSelect'),
    select
    (
        setClass('select-user w-24'),
        set::name('assignedTo[]'),
        set::items($users)
    )
);

div
(
    setClass('hidden'),
    setID('numInput'),
    control
    (
        set::type('number'),
        set::min('0'),
        setClass('input-num w-12'),
        set::name('estimate[]'),
    )
);

div
(
    setClass('hidden'),
    setID('dateInput'),
    control
    (
        set::type('date'),
        setClass('input-date w-26'),
        set::name('deadline[]'),
    )
);

render();
