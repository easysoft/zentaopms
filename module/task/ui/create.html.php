<?php
declare(strict_types=1);
/**
 * The create view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tian Shujie<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

/* ====== Preparing and processing page data ====== */

/* zin: Set variables to define picker options for form. */
jsVar('lifetime', $execution->lifetime);
jsVar('attribute', $execution->attribute);
jsVar('showFields', $showFields);
jsVar('toTaskList', !empty($task->id));
jsVar('blockID', $blockID);
jsVar('executionID', $execution->id);
jsVar('ditto', $lang->task->ditto);
jsVar('teamMemberError', $lang->task->error->teamMember);
jsVar('vision', $config->vision);
jsVar('requiredFields', $config->task->create->requiredFields);
jsVar('estimateNotEmpty', sprintf($lang->error->gt, $lang->task->estimate, '0'));

/* ====== Define the page structure with zin widgets ====== */

/* zin: Define the form in main content. */
formPanel
(
    set::title($lang->task->create), // The form title is diffrent from the page title,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('execution'),
            set::label($lang->task->execution),
            set::value($execution->id),
            set::items($executions)
        ),
        formGroup
        (
            set::width('1/2'),
            set::name('module'),
            set::label($lang->task->module),
            set::value($task->module),
            set::items($moduleOptionMenu)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::name('type'),
            set::label($lang->task->type),
            set::control('select'),
            set::items($lang->task->typeList),
            set::value($task->type),
            on::change('typeChange')
        ),
        formGroup
        (
            set::width('1/4'),
            set::id('selectTestStoryBox'),
            set::class('hidden'),
            checkbox(
                set::name('selectTestStory'),
                set::text($lang->task->selectTestStory),
                set::rootClass('ml-4'),
                on::change('toggleSelectTestStory'),
            )
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::name('assignedTo[]'),
            set::label($lang->task->assignTo),
            set::value($task->assignedTo),
            set::items($members),
        ),
        formGroup
        (
            set::width('1/10'),
            set::id('multipleBox'),
            checkbox(
                set::name('multiple'),
                set::text($lang->task->multiple),
                set::rootClass('ml-4'),
                on::change('showTeamBox')
            )
        ),
        formGroup
        (
            set::width('1/4'),
            set::class('modeBox hidden'),
            radioList(
                set::name('mode'),
                set::value(!empty($task->mode) ? $task->mode : 'linear'),
                set::class('ml-4'),
                set::items($config->task->modeOptions),
                set::inline(true)
            )
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('story'),
        set::label($lang->task->story),
        set::value($task->story),
        set::items($stories),
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->task->name),
            set::width('3/4'),
            set::name('name'),
            set::value($task->name),
        ),
        formGroup
        (
            set::width('1/4'),
            inputGroup
            (
                $lang->task->pri,
                select
                (
                    set::name('pri'),
                    set::items($lang->task->priList),
                ),
                $lang->task->estimate,
                input
                (
                    set::name('estimate'),
                ),
            ),
        )
    ),
    formGroup
    (
        set::label($lang->task->desc),
        set::name('desc'),
        set::control('editor'),
    ),
    formGroup
    (
        set::name('files[]'),
        set::label($lang->story->files),
        set::control('file')
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->task->datePlan),
        inputGroup
        (
            input
            (
                set::type('date'),
                set::name('estStarted'),
                set::value($task->estStarted),
                set::placeholder($lang->task->estStarted),
            ),
            $lang->task->to,
            input
            (
                set::type('date'),
                set::name('deadline'),
                set::value($task->deadline),
                set::placeholder($lang->task->deadline),
            ),
        )
    ),
    formGroup
    (
        set::label($lang->product->mailto),
        set::name('mailto[]'),
        set::items($users),
    ),
    formGroup
    (
        set::width('3/4'),
        set::label($lang->task->afterSubmit),
        radioList
        (
            set::name('after'),
            set::value(!empty($task->id) ? 'toTaskList' : 'continueAdding'),
            set::items($config->task->afterOptions),
            set::inline(true)
        ),
    ),
);


/* ====== Render page ====== */

render();
