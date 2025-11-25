<?php
declare(strict_types=1);
/**
 * The create view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('+projectID', $projectID);
jsVar('copyProjectID', 0);
jsVar('copyExecutionID', 0);
jsVar('isStage', false);
jsVar('window.weekend', $config->execution->weekend);

formPanel
(
    set::className('createPanel'),
    to::heading(div
    (
        setClass('panel-title text-lg'),
        $lang->execution->create
    )),
    to::headingActions
    (
        btn
        (
            setClass('primary-pale'),
            set::icon('copy'),
            set::url('#copyExecutionModal'),
            set('data-destroyOnHide', true),
            set('data-toggle', 'modal'),
            $lang->execution->copy
        )
    ),
    on::change('[name=begin]', 'computeWorkDays(NaN)'),
    on::change('[name=end]', 'computeWorkDays(NaN)'),
    $config->systemMode == 'ALM' ? formGroup
    (
        set::width('1/2'),
        set::label($lang->execution->projectName),
        set::required(true),
        picker(
            setID('project'),
            set::name('project'),
            set::items($allProjects),
            set::value($projectID),
            on::change('refreshPage')
        )
    ) : null,
    formGroup
    (
        set::width('1/2'),
        set::name('name'),
        set::label($lang->execution->name),
        set::value($execution->name)
    ),
    !empty($config->setCode) ? formGroup
    (
        set::width('1/2'),
        set::name('code'),
        set::label($lang->execution->code),
        set::value($execution->code)
    ) : null,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->execution->dateRange),
            set::required(true),
            inputGroup
            (
                datePicker
                (
                    set::name('begin'),
                    set('id', 'begin'),
                    set::value(date('Y-m-d')),
                    set::placeholder($lang->execution->begin)
                ),
                $lang->project->to,
                datePicker
                (
                    set::name('end'),
                    set('id', 'end'),
                    set::value(''),
                    set::placeholder($lang->execution->end)
                )
            )
        ),
        formGroup
        (
            radioList
            (
                set::name('delta'),
                set::inline(true),
                set::items($lang->execution->endList),
                on::change('computeEndDate')
            )
        )
    ),
    formGroup
    (
        set::label($lang->execution->days),
        set::width('1/2'),
        inputGroup
        (
            setClass('has-suffix'),
            input
            (
                set::name('days'),
                set::value('')
            ),
            div
            (
                setClass('input-control-suffix z-50'),
                $lang->execution->day
            )
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->execution->PM),
        picker
        (
            set::name('PM'),
            set::items($pmUsers),
            set::value(empty($copyExecution) ? '' : $copyExecution->PM)
        )
    ),
    formGroup
    (
        set::label($lang->execution->team),
        picker
        (
            set::name('teamMembers[]'),
            set::items($users),
            set::multiple(true)
        )
    ),
    formGroup
    (
        set::label($lang->execution->desc),
        set::name('desc'),
        set::control('editor')
    ),
    formRow
    (
        setID('aclList'),
        formGroup
        (
            set::width('1/2'),
            set::name('acl'),
            set::label($lang->execution->acl),
            set::control('radioList'),
            set::items($lang->execution->aclList),
            set::value($execution->acl),
            on::change('setWhite')
        )
    ),
    formGroup
    (
        set::label($lang->whitelist),
        set::id('whitelistBox'),
        picker
        (
            set::name('whitelist[]'),
            set::items($users),
            set::multiple(true)
        )
    ),
    formHidden('status', 'wait'),
    formHidden("products[]", key($allProducts)),
    formHidden('PO', ''),
    formHidden('QD', ''),
    formHidden('RD', ''),
    formHidden('type', 'kanban'),
    formHidden('vision', 'lite')
);

modalTrigger
(
    modal
    (
        set::id('copyExecutionModal'),
        set::footerClass('justify-center'),
        to::header
        (
            div
            (
                setClass('w-full'),
                div
                (
                    h4
                    (
                        set::className('copy-title'),
                        $lang->execution->copyTitle
                    )
                ),
                div
                (
                    setClass('flex items-center py-4 border-b border-b-1'),
                    span
                    (
                        setClass('mr-2'),
                        $lang->execution->selectProject
                    ),
                    picker
                    (
                        set::className('flex-1 w-full'),
                        set::name('project'),
                        set::items($copyProjects),
                        set::value($projectID),
                        set::required(true),
                        on::change('loadProjectExecutions')
                    )
                )
            )
        ),
        to::footer
        (
            setClass('mt-4'),
            btn
            (
                setClass('primary btn-wide hidden confirmBtn'),
                set::text($lang->execution->copyExec),
                on::click('setCopyExecution')
            )
        ),
        div
        (
            set::id('copyExecutions'),
            setClass('flex items-center flex-wrap gap-4')
        )
    )
);
