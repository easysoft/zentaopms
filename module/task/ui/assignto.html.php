<?php
declare(strict_types=1);
/**
 * The edit view of task of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunguangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

$fields = useFields('task.assignTo');

/* zin: Define the form in main content */
if(!empty($task->members) && strpos('wait,doing,pause', $task->status) !== false && (!isset($task->members[$app->user->account]) || $task->mode == 'linear'))
{
    $notice = '';
    if($task->mode == 'linear')
    {
        $notice = $lang->task->transferNotice;
    }
    else
    {
        $notice = html(sprintf($lang->task->deniedNotice, '<strong>' . $lang->task->teamMember . '</strong>', $lang->task->transfer));
    }

    div
    (
        setClass('alert with-icon my-8'),
        icon('exclamation-sign text-gray text-4xl'),
        div
        (
            setClass('content'),
            $notice
        )
    );
}
else
{
modalHeader
(
    set::title($lang->task->assignAction),
);

formPanel
(
    set::submitBtnText($lang->task->assignedTo),
    set::fields($fields)
);
hr();
history
(
    set::objectID($task->id)
);
}

render();
