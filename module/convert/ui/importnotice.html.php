<?php
declare(strict_types=1);
/**
 * The import notice view file of convert module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     convert
 * @link        https://www.zentao.net
 */
namespace zin;

$labelWidth = $method == 'db' ? '80px' : '20px';

formPanel
(
    set::title($method == 'db' ? $lang->convert->jira->importFromDB : $lang->convert->jira->importFromFile),
    set::headingClass('justify-start'),
    set::bodyClass('px-0'),
    set::submitBtnText($lang->convert->jira->next),
    set::backUrl(inlink('convertJira')),
    to::heading
    (
        span
        (
            setClass('flex items-center text-gray'),
            icon('exclamation text-warning mr-1'),
            span($lang->convert->jira->importNotice)
        )
    ),
    formGroup
    (
        setStyle(array('align-items' => 'center')),
        set::label('1.'),
        set::labelWidth($labelWidth),
        $lang->convert->jira->importSteps[$method][1]
    ),
    formGroup
    (
        setStyle(array('align-items' => 'center')),
        set::label('2.'),
        set::labelWidth($labelWidth),
        $lang->convert->jira->importSteps[$method][2]
    ),
    formGroup
    (
        setStyle(array('align-items' => 'center')),
        set::label('3.'),
        set::labelWidth($labelWidth),
        $method == 'db' ? $lang->convert->jira->importSteps[$method][3] : html(sprintf($lang->convert->jira->importSteps[$method][3], $app->getTmpRoot() . 'jirafile')),
    ),
    formGroup
    (
        setStyle(array('align-items' => 'center')),
        set::label('4.'),
        set::labelWidth($labelWidth),
        html(sprintf($lang->convert->jira->importSteps[$method][4], $app->getTmpRoot())),
    ),
    formGroup
    (
        setStyle(array('align-items' => 'center')),
        set::label('5.'),
        set::labelWidth($labelWidth),
        $lang->convert->jira->importSteps[$method][5]
    ),
    $method == 'db' ? formGroup
    (
        set::label($lang->convert->jira->database),
        set::labelWidth($labelWidth),
        set::required(true),
        input
        (
            setClass('w-72'),
            set::name('dbName'),
            set::placeholder($lang->convert->jira->dbNameNotice)
        )
    ) : null
);

render();
