<?php
declare(strict_types=1);
/**
 * The task team view of task of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

$teamForm    = array();
$hiddenArrow = (empty($task->mode) or $task->mode == 'linear') ? '' : 'hidden';
$i = 1;

if(!empty($task->team))
{
    foreach($task->team as $member)
    {
        $memberDisabled = false;
        $sortDisabled   = false;
        $memberStatus   = $member->status;
        if($memberStatus == 'done') $memberDisabled = true;
        if($memberStatus != 'wait' and $task->mode == 'linear') $sortDisabled = true;
        if($memberStatus == 'done' and $task->mode == 'multi')  $sortDisabled = true;
        if(strpos('|closed|cancel|pause|', $task->status) !== false and $app->rawMethod != 'activate')
        {
            $memberStatus   = $task->status;
            $memberDisabled = true;
            $sortDisabled   = true;
        }

        $hourDisabled = $memberDisabled;

        $teamForm[] = h::tr
        (
            set
            (
                array
                (
                    'class' => "member member-$memberStatus",
                    'data-estimate' => (float)$member->estimate,
                    'data-consumed' => (float)$member->consumed,
                    'data-left' => (float)$member->left
                )
            ),
            h::td
            (
                setClass('team-index'),
                span
                (
                    setClass("team-number"),
                    $i
                ),
                icon("angle-down $hiddenArrow")
            ),
            h::td
            (
                set::width('240px'),
                picker
                (
                    set::name("team[]"),
                    set::items($members),
                    set::value($member->account),
                    $memberDisabled ? set::disabled(true) : null
                )
            ),
            h::td
            (
                set::width('135px'),
                inputControl
                (
                    input
                    (
                        set::name("teamEstimate[]"),
                        set::value((float)$member->estimate),
                        $hourDisabled ? set::readonly(true) : null,
                        set::placeholder($lang->task->estimateAB)
                    ),
                    to::suffix($lang->task->suffixHour),
                    set::suffixWidth(20)
                )
            ),
            h::td
            (
                set::width('135px'),
                inputControl
                (
                    input
                    (
                        set::name("teamConsumed[]"),
                        set::value((float)$member->consumed),
                        set::readonly(true),
                        set::placeholder($lang->task->consumedAB)
                    ),
                    to::suffix($lang->task->suffixHour),
                    set::suffixWidth(20)
                )
            ),
            h::td
            (
                setClass('required'),
                set::width('135px'),
                inputControl
                (
                    input
                    (
                        set::name("teamLeft[]"),
                        set::value((float)$member->left),
                        $hourDisabled ? set::readonly(true) : null,
                        set::placeholder($lang->task->leftAB)
                    ),
                    to::suffix($lang->task->suffixHour),
                    set::suffixWidth(20)
                )
            ),
            h::td
            (
                set::width('100px'),
                setClass('center'),
                btnGroup
                (
                    set::items(array(
                        array('icon' => 'plus',  'class' => 'btn ghost btn-add'),
                        array('icon' => 'trash', 'class' => 'btn ghost btn-delete')
                    ))
                )
            )
        );

        $i ++;
    }
}

$count = $i + 3;
for($i; $i < $count; $i ++)
{
    $teamForm[] = h::tr
    (
        h::td
        (
            setClass('team-index'),
            span
            (
                setClass("team-number"),
                $i
            ),
            icon("angle-down $hiddenArrow")
        ),
        h::td
        (
            set::width('240px'),
            picker
            (
                set::name("team[]"),
                set::items($members)
            )
        ),
        h::td
        (
            set::width('135px'),
            inputControl
            (
                input
                (
                    set::name("teamEstimate[]"),
                    set::placeholder($lang->task->estimateAB)
                ),
                to::suffix($lang->task->suffixHour),
                set::suffixWidth(20)
            )
        ),
        !empty($task->team) ? h::td
        (
            set::width('135px'),
            inputControl
            (
                input
                (
                    set::name("teamConsumed[]"),
                    set::value(0),
                    set::readonly(true),
                    set::placeholder($lang->task->consumedAB)
                ),
                to::suffix($lang->task->suffixHour),
                set::suffixWidth(20)
            )
        ) : null,
        !empty($task->team) ? h::td
        (
            set::width('135px'),
            inputControl
            (
                input
                (
                    set::name("teamLeft[]"),
                    set::placeholder($lang->task->leftAB)
                ),
                to::suffix($lang->task->suffixHour),
                set::suffixWidth(20)
            )
        ) : null,
        h::td
        (
            set::width('100px'),
            setClass('center'),
            btnGroup
            (
                set::items(array(
                    array('icon' => 'plus',  'class' => 'btn ghost btn-add'),
                    array('icon' => 'trash', 'class' => 'btn ghost btn-delete')
                ))
            )
        )
    );
}
