<?php
declare(strict_types=1);
/**
 * The restart view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = useFields('task.restart');

jsVar('confirmFinish', $lang->task->confirmFinish);
jsVar('confirmTeamFinish', $lang->task->confirmTeamFinish);
jsVar('consumedSmallError', $lang->task->error->consumedSmall);
jsVar('recordedConsumed', !empty($currentTeam) ? (float)$currentTeam->consumed : (float)$task->consumed);
jsVar('currentTeam', $currentTeam);

modalHeader();
if(!$canRecordEffort)
{
    if($task->assignedTo != $app->user->account && $task->mode == 'linear')
    {
        $deniedNotice = sprintf($lang->task->deniedNotice, $task->assignedToRealName, $lang->task->start);
    }
    else
    {
        $deniedNotice = sprintf($lang->task->deniedNotice, $lang->task->teamMember, $lang->task->start);
    }

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
    formPanel
    (
        setID('restartForm'),
        set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
        set::fields($fields)
    );
    hr();
    history();
}