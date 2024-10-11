<?php
declare(strict_types=1);
/**
 * The trigger view file of job module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     job
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('repo', $repo);
jsVar('dirs', !empty($dirs) ? $dirs : '');
jsVar('triggerRepeat', $lang->job->triggerRepeat);
jsVar('triggerTypeList', $lang->job->triggerTypeList);

jsVar('tagField', formRow
(
    setClass('svn-fields linkage-fields hidden mt-4'),
    formGroup
    (
        set::name('svnDir[]'),
        set::width('1/2'),
        set::label($lang->job->svnDir),
        set::control('static'),
        div(setID('dirPicker%s'))
    )
)->render());
jsVar('commitField', formRow
(
    setClass('comment-fields linkage-fields hidden mt-4'),
    formGroup
    (
        set::name('comment'),
        set::label($lang->job->comment),
        set::width('1/2'),
        set::required(true)
    ),
    h::span
    (
        setClass('leading-8 ml-2'),
        html($lang->job->commitEx)
    )
)->render());
jsVar('scheduleField', div
(
    setClass('linkage-fields'),
    formRow
    (
        setClass('custom-fields hidden mt-2'),
        formGroup
        (
            set::label(''),
            set::name('atDay[]'),
            set::control('checkListInline'),
            set::items($lang->datepicker->dayNames)
        )
    ),
    formRow
    (
        setClass('custom-fields hidden'),
        formGroup
        (
            set::label(''),
            set::width('1/2'),
            inputGroup
            (
                $lang->job->atTime,
                div(setID('scheduleTime%s'))
            )
        )
    )
)->render());
jsVar('triggerField', div
(
    setClass('trigger-box border py-4 hidden bg-gray-100'),
    icon('trash pull-right mr-4 delete-trigger cursor-pointer'),
    formGroup
    (
        set::name("triggerType[%s]"),
        set::width('1/2'),
        set::required(true),
        set::label($lang->job->triggerType),
        set::control('static'),
        div(setID('triggerPicker%s'))
    )
)->render());

$triggers = array();
if(empty($job->triggerType)) $job->triggerType = key($lang->job->triggerTypeList);
foreach(explode(',', $job->triggerType) as $index => $trigger)
{
    $triggers[] = div
    (
        setClass('trigger-box border py-4 bg-gray-100'),
        icon
        (
            'trash pull-right mr-4 delete-trigger cursor-pointer',
            !strpos($job->triggerType, ',') ? setClass('hidden') : null
        ),
        formGroup
        (
            set::width('1/2'),
            set::name("triggerType[$index]"),
            set::required(true),
            set::label($lang->job->triggerType),
            set::items($lang->job->triggerTypeList),
            set::value($trigger)
        ),
        $trigger == 'tag' && $repo->SCM == 'Subversion' ? formRow
        (
            setClass('svn-fields mt-4 linkage-fields'),
            formGroup
            (
                set::name('svnDir[]'),
                set::width('1/2'),
                set::label($lang->job->svnDir),
                set::items(!empty($dirs) ? $dirs : array()),
                set::value($job->svnDir)
            )
        ) : null,
        $trigger == 'commit' ? formRow
        (
            setClass('comment-fields mt-4 linkage-fields'),
            formGroup
            (
                set::name('comment'),
                set::label($lang->job->comment),
                set::value($job->comment),
                set::width('1/2'),
                set::required(true)
            ),
            h::span
            (
                setClass('leading-8 ml-2'),
                html($lang->job->commitEx)
            )
        ) : null,
        $trigger == 'schedule' ? div
        (
            setClass('linkage-fields'),
            formRow
            (
                setClass('custom-fields mt-2'),
                formGroup
                (
                    set::label(''),
                    set::name('atDay[]'),
                    set::control('checkListInline'),
                    set::items($lang->datepicker->dayNames),
                    set::value($job->atDay)
                )
            ),
            formRow
            (
                setClass('custom-fields'),
                formGroup
                (
                    set::label(''),
                    set::width('1/2'),
                    inputGroup
                    (
                        $lang->job->atTime,
                        timePicker
                        (
                            set::name('atTime'),
                            set::value($job->atTime)
                        )
                    )
                )
            )
        ) : null,
    );
}

if($job->customParam)
{
    $i           = 1;
    $customParam = array();
    foreach(json_decode($job->customParam) as $paramName => $paramValue)
    {
        $isCustom      = zget($lang->job->paramValueList, $paramValue, '') ? false : true;
        $customParam[] = inputGroup
        (
            $lang->job->paramName,
            input
            (
                setStyle('width', '50%'),
                setClass('form-control'),
                set::id("paramName{$i}"),
                set::name('paramName[]'),
                set::value($paramName)
            ),
            $lang->job->paramValue,
            select
            (
                setStyle('width', '25%'),
                setClass('paramValue ' . ($isCustom ? 'hidden' : '')),
                set::id("paramValue{$i}"),
                set::name('paramValue[]'),
                !$isCustom ? set::value($paramValue) : null,
                set::items($lang->job->paramValueList),
                $isCustom ? set::disabled(true) : null
            ),
            input
            (
                setStyle('width', '25%'),
                setClass('form-control paramValue ' . (!$isCustom ? 'hidden' : '')),
                set::id("paramValueInput{$i}"),
                set::name('paramValue[]'),
                $isCustom ? set::value($paramValue) : null,
                !$isCustom ? set::disabled(true) : null
            ),
            span
            (
                setClass('input-group-addon'),
                checkbox
                (
                    setClass('custom'),
                    set::id("custom{$i}"),
                    set::name('custom'),
                    set::text($lang->job->custom),
                    set::checked($isCustom)
                )
            ),
            span
            (
                setClass('input-group-addon'),
                h::a
                (
                    setClass('add-param'),
                    set::href('javascript:void(0)'),
                    icon('plus')
                )
            ),
            span
            (
                setClass('input-group-addon'),
                a
                (
                    setClass('delete-param'),
                    set::href('javascript:void(0)'),
                    icon('close')
                )
            )
        );

        $i ++;
    }
}

formPanel
(
    setID('triggerForm'),
    set::formClass('gap-2'),
    set::title($lang->job->trigger),
    set::actionsClass('w-2/3'),
    on::click('.add-param', 'addItem'),
    on::click('.delete-param', 'deleteItem'),
    on::click('.custom', 'setValueInput'),
    on::click('select.paramValue', 'changeCustomField'),
    on::click('.delete-trigger', 'deleteTrigger'),
    on::change('input[name^=triggerType]', 'changeTriggerType'),
    set::headingActionsClass('flex-auto justify-start w-11/12'),
    to::headingActions
    (
        checkbox
        (
            setID('autoRun'),
            set::text($lang->job->autoRun),
            set::checked(!$job->autoRun),
            on::change('toggleAutoRun')
        )
    ),
    $triggers,
    formRow
    (
        setClass('add-trigger-btn', count($triggers) >= count($lang->job->triggerTypeList) ? 'hidden' : null),
        btn
        (
            set::icon('plus'),
            setClass('primary-pale bd-primary'),
            set::text($lang->job->addTrigger),
            on::click('addTrigger')
        )
    ),
    formRow
    (
        set::id('paramDiv'),
        formGroup
        (
            set::label($lang->job->customParam),
            !empty($job->customParam) ? $customParam : null,
            set::width('2/3'),
            inputGroup
            (
                $lang->job->paramName,
                input
                (
                    setStyle('width', '50%'),
                    setClass('form-control paramName'),
                    set::name('paramName[]')
                ),
                $lang->job->paramValue,
                select
                (
                    setStyle('width', '25%'),
                    setClass('paramValue'),
                    set::name('paramValue[]'),
                    set::items($lang->job->paramValueList)
                ),
                input
                (
                    setStyle('width', '25%'),
                    setClass('form-control hidden paramValue'),
                    set::name('paramValue[]'),
                    set::disabled(true)
                ),
                span
                (
                    setClass('input-group-addon'),
                    checkbox
                    (
                        setClass('custom'),
                        set::name('custom'),
                        set::text($lang->job->custom)
                    )
                ),
                span
                (
                    setClass('input-group-addon'),
                    h::a
                    (
                        setClass('add-param'),
                        set::href('javascript:void(0)'),
                        icon('plus')
                    )
                ),
                span
                (
                    setClass('input-group-addon'),
                    a
                    (
                        setClass('delete-param'),
                        set::href('javascript:void(0)'),
                        icon('close')
                    )
                )
            )
        )
    ),
    formHidden('autoRun', zget($job, 'autoRun', 1))
);
