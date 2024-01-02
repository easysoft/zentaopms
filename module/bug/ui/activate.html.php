<?php
declare(strict_types=1);
/**
 * The activate view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();

formPanel
(
    formGroup
    (
        set::width('1/3'),
        set::label($lang->bug->assignedTo),
        picker
        (
            set::name('assignedTo'),
            set::value($bug->resolvedBy),
            set::items($users)
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->bug->openedBuild),
        set::required(true),
        picker
        (
            set::name('openedBuild[]'),
            set::value($bug->openedBuild),
            set::items($builds),
            set::multiple(true)
        ),
        input
        (
            set::name('status'),
            set::value('active'),
            set::className('hidden')
        )
    ),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows(6)
        )
    ),
    formGroup
    (
        set::label($lang->bug->files),
        upload()
    )
);
hr();
history();

render();
