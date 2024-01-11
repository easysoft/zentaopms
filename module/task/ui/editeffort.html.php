<?php
declare(strict_types=1);
/**
 * The edit effort view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

$readonly = !$effort->isLast || (!empty($task->team) && $effort->left == 0);

modalHeader(set::title($lang->task->editEffort));
formPanel
(
    set::shadow(!isAjaxRequest('modal')),
    formGroup
    (
        set::width('1/3'),
        set::label($lang->task->date),
        set::name('date'),
        set::control('date'),
        set::value($effort->date)
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::label($lang->task->currentConsumed),
            inputControl
            (
                input
                (
                    set::name('consumed'),
                    set::value($effort->consumed)
                ),
                to::suffix($lang->task->suffixHour),
                set::suffixWidth(20)
            )
        ),
        formGroup
        (
            set::width('1/4'),
            set::label($lang->task->left),
            inputControl
            (
                input
                (
                    set::name('left'),
                    set::value($effort->left),
                    set::readonly($readonly)
                ),
                to::suffix($lang->task->suffixHour),
                set::suffixWidth(20)
            )
        )
    ),
    formGroup
    (
        set::label($lang->task->work),
        set::control('textarea'),
        set::name('work'),
        set::value(strip_tags($effort->work)),
        set::rows('1')
    )
);

render();
