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
    set::linkParams("toExecution={$execution->id}"),
    li
    (
        searchToggle(set::module('bug'))
    )
);

jsVar('statusList', $lang->bug->statusList);
formBatchPanel
(
    set::mode('edit'),
    setID('importForm'),
    set::data(array_values($bugs)),
    set::onRenderRowCol(jsRaw('window.handleImportBug')),
    formBatchItem
    (
        set::name('id'),
        set::label(''),
        set::control('checkbox'),
        set::width('20px'),
    ),
    formBatchItem
    (
        set::name('id'),
        set::label('ID'),
        set::control('index'),
        set::width('60px'),
    ),
    formBatchItem
    (
        set::name('severity'),
        set::label($lang->bug->abbr->severity),
        set::control('static'),
        set::width('48px'),
    ),
    formBatchItem
    (
        set::name('title'),
        set::label($lang->bug->title),
        set::control('static'),
    ),
    formBatchItem
    (
        set::name('status'),
        set::label($lang->bug->status),
        set::control('static'),
        set::width('80px')
    ),
    formBatchItem
    (
        set::name('pri'),
        set::label($lang->execution->pri),
        set::control('select'),
        set::required(in_array('pri', $requiredFields)),
        set::width('68px'),
        set::value('3'),
        set::items($lang->bug->priList),
    ),
    formBatchItem
    (
        set::name('assignedTo'),
        set::label($lang->task->assignedTo),
        set::control('picker'),
        set::required(in_array('assignedTo', $requiredFields)),
        set::width('108px'),
        set::items($users),
    ),
    formBatchItem
    (
        set::name('estimate'),
        set::label($lang->task->estimate),
        set::control('number'),
        set::required(in_array('estimate', $requiredFields)),
        set::width('64px'),
    ),
    formBatchItem
    (
        set::name('estStarted'),
        set::label($lang->task->estStarted),
        set::control('date'),
        set::required(in_array('estStarted', $requiredFields)),
        set::width('110px'),
    ),
    formBatchItem
    (
        set::name('deadline'),
        set::label($lang->task->deadline),
        set::control('date'),
        set::required(in_array('deadline', $requiredFields)),
        set::width('110px'),
    ),
    set::actions(array()),
    set::footerActions(array(
        array(
            'text'    => $lang->import,
            'icon'    => 'import',
            'class'   => 'btn secondary toolbar-item batch-btn size-sm',
            'onClick' => 'window.importBug()'
        ),
        array(
            'text'  => $lang->goback,
            'class' => 'btn toolbar-item size-sm text-gray ml-2',
            'url'   => createLink('execution', 'task', "executionID={$execution->id}")
        )
    )),
);

render();
