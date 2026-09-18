<?php
declare(strict_types=1);
/**
 * The finish view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

$fields = useFields('task.finish');

if(!$canRecordEffort)
{
    modalHeader();
    div
    (
        set::className('alert with-icon'),
        icon('exclamation-sign icon-3x'),
        div
        (
            set::className('content'),
            p
            (
                set::className('font-bold'),
                $deniedNotice
            )
        )
    );
}
else
{
    jsVar('consumed', empty($task->team) ? $task->consumed : (float)$task->myConsumed);
    jsVar('task', $task);
    jsVar('consumedEmpty', $lang->task->error->consumedEmptyAB);

    modalHeader
    (
        to::suffix
        (
            span
            (
                setClass('flex gap-x-2 mr-3 nowrap'),
                !empty($task->team) ? $lang->task->common . $lang->task->consumed : $lang->task->hasConsumed,
                span
                (
                    setClass('label secondary-pale'),
                    $task->consumed . $lang->task->suffixHour
                )
            ),
            span
            (
                setClass('flex gap-x-2 nowrap'),
                $lang->task->consumed,
                span
                (
                    setClass('label warning-pale'),
                    span
                    (
                        setID('totalConsumed'),
                        $task->consumed
                    ),
                    $lang->task->suffixHour
                )
            )
        )
    );

    formPanel
    (
        setID('finishForm'),
        set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
        set::fields($fields)
    );
    hr();
    history();
}

render();
