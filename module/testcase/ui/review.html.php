<?php
declare(strict_types=1);
/**
 * The review view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();

formPanel
(
    formGroup
    (
        set::width('1/3'),
        set::label($lang->testcase->reviewedDateAB),
        datePicker
        (
            set::name('reviewedDate'),
            set::value(helper::today())
        )
    ),
    formGroup
    (
        set::width('1/3'),
        set::label($lang->testcase->result),
        set::required(true),
        picker
        (
            set::name('result'),
            set::items($lang->testcase->reviewResultList)
        )
    ),
    formGroup
    (
        set::label($lang->testcase->reviewedByAB),
        picker
        (
            set::name('reviewedBy[]'),
            set::value($app->user->account),
            set::items($users),
            set::multiple(true)
        )
    ),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows(8)
        )
    ),
    set::actions(array('submit'))
);
hr();
history();

render('modalDialog');
