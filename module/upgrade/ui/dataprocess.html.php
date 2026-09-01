<?php
declare(strict_types=1);
/**
 * The data process view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$isEn      = $app->getClientLang() == 'en';
$stepCount = count($config->upgrade->dataProcessSteps);

/* 左侧待办列表按配置渲染，标题取各步骤语言对象的 common。*/
/* The left step list is rendered by config, and the title comes from the language object of each step. */
$stepItems = array();
foreach($config->upgrade->dataProcessSteps as $step)
{
    $stepItems[] = row
    (
        setClass('step-item items-center gap-2'),
        setData(['step' => $step]),
        span
        (
            setClass('step-icon'),
            icon(setClass('text-gray-400 w-4 h-4'), 'clock')
        ),
        span
        (
            $lang->upgrade->{$step}->common
        )
    );
}

jsVar('dataProcessSteps', $config->upgrade->dataProcessSteps);
jsVar('dataProcessFinishedSteps', $finishedSteps);
jsVar('executeFailed', $lang->upgrade->executeFailed);

$continueLink = inlink('afterExec', "fromVersion={$fromVersion}&processed=no");

div
(
    setStyle(['padding' => '3rem 4rem', 'height' => '100vh', 'overflow' => 'hidden']),
    col
    (
        setClass('container rounded-md bg-white gap-2 px-8 py-6 h-full'),
        div
        (
            setClass('text-xl font-medium'),
            $lang->upgrade->dataProcess
        ),
        div
        (
            setClass('text-warning'),
            $lang->upgrade->dataProcessTip
        ),
        row
        (
            setClass('bg-gray-100 gap-2 p-2 flex-1 min-h-0'),
            setStyle(['max-height' => 'calc(100% - 6rem)']),
            col
            (
                setID('stepsBlock'),
                setClass('bg-white rounded-md justify-between gap-4 p-4 w-64 h-full'),
                col
                (
                    setClass('gap-2'),
                    span
                    (
                        setClass('text-lg font-medium'),
                        $lang->upgrade->dataProcessStepsTitle
                    ),
                    col
                    (
                        setID('stepsBox'),
                        setClass('gap-2 overflow-x-hidden overflow-y-auto h-full'),
                        $stepItems
                    )
                ),
                col
                (
                    setClass('gap-2'),
                    span
                    (
                        $lang->upgrade->progress
                    ),
                    row
                    (
                        setClass('justify-between items-center gap-2'),
                        progressbar
                        (
                            setID('stepsProgressBar'),
                            setClass('rounded-full'),
                            setStyle(['height' => '.75rem', 'width' => '100%']),
                            set::color('rgba(var(--color-primary-500-rgb), var(--tw-bg-opacity));'),
                            set::percent(0)
                        ),
                        span
                        (
                            setClass('text-right'),
                            setStyle(['min-width' => (strlen((string)$stepCount) + 1) . 'rem']),
                            span
                            (
                                setID('stepsProgressText'),
                                0
                            ),
                            ' / ' . $stepCount
                        )
                    )
                )
            ),
            col
            (
                setID('progressBlock'),
                setClass('rounded-md gap-4 bg-white px-6 py-4 w-full h-full overflow-y-auto')
            )
        ),
        div
        (
            setClass('center'),
            a
            (
                setID('continueBtn'),
                setClass('btn primary disabled ' . ($isEn ? 'w-28' : 'w-24')),
                set::href($continueLink),
                $lang->upgrade->continue
            )
        )
    )
);

render('pagebase');
