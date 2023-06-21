<?php
declare(strict_types=1);
/**
 * The team view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<unguangming@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

modalTrigger
(
    modal
    (
        set::id('modalTeam'),
        set::title($lang->task->teamMember),
        formBatchPanel
        (
            to::headingActions
            (
                span($lang->task->mode), 
                formGroup
                (
                    set::name('mode'),
                    set::control('select'),
                    set::items($lang->task->modeList),
                    set::value('linear'),
                    set::width('100px'),
                ),
            ),
            set::maxRows(3),
            set::actions(array('submit')),
            set::shadow(false),
            set::title(''),
            set::actionsText(''),
            formBatchItem
            (
                set::name('id'),
                set::control('index'),
                set::width('10px'),
            ),
            formBatchItem
            (
                set::name('team'),
                set::control('select'),
                set::items($members),
                set::width('120px'),
                input
                (
                    set::name('teamSource'),
                    set::class('hidden'),
                )
            ),
            formBatchItem
            (
                set::name('teamEstimate'),
                set::placeholder($lang->task->estimateAB),
                set::width('50px'),
                set::control
                (
                    array(
                        'type' => 'inputControl',
                        'suffix' => $lang->task->suffixHour,
                        'suffixWidth' => 20
                    )
                )
            ),
        ),
    )
);
