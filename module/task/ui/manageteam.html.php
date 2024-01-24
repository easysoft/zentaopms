<?php
declare(strict_types=1);
/**
 * The manageteam view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;
if(!isset($task->members[$app->user->account]))
{
    div
    (
        setClass('alert with-icon my-8'),
        icon('exclamation-sign text-gray text-4xl'),
        div
        (
            setClass('content'),
            html(sprintf($lang->task->deniedNotice, '<strong>' . $lang->task->teamMember . '</strong>', $lang->task->transfer))
        )
    );
}
else
{
    include './taskteam.html.php';
    jsVar('teamMemberError', $lang->task->error->teamMember);
    jsVar('totalLeftError', sprintf($this->lang->task->error->leftEmptyAB, $this->lang->task->statusList[$task->status]));
    jsVar('estimateNotEmpty', sprintf($lang->error->gt, $lang->task->estimate, '0'));
    jsVar('leftNotEmpty', sprintf($lang->error->gt, $lang->task->left, '0'));

    to::header
    (
        entityLabel
        (
            set::level(1),
            setClass('clip w-full'),
            set::text($lang->task->team . ' > ' . $task->name)
        )
    );

    formPanel
    (
        setID('teamForm'),
        set::action(inlink('manageTeam', "executionID=$task->execution&taskID=$task->id")),
        set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
        h::table
        (
            setID('teamTable'),
            setClass('table table-form'),
            formHidden('mode', $task->mode),
            formHidden('status', $task->status),
            $teamForm
        )
    );
}

render();
