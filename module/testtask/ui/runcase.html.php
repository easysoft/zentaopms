<?php
declare(strict_types=1);
/**
 * The runCase view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('caseResultSave', $lang->save);
jsVar('tab', $app->tab);
jsVar('confirm', $confirm);
jsVar('resultsLink', createLink('testtask', 'results', "runID={$runID}&caseID={$caseID}&version={$version}&status=all"));

modalHeader
(
    set::title($lang->testtask->lblRunCase),
    set::entityText($run->case->title),
    set::entityID($caseID)
);

$stepTrs    = array();
$fileModals = array();
if($confirm != 'yes')
{
    $steps = $run->case->steps;
    if(empty($steps))
    {
        $step = new stdclass();
        $step->id     = 0;
        $step->parent = 0;
        $step->case   = $run->case->id;
        $step->type   = 'step';
        $step->desc   = '';
        $step->expect = '';
        $steps[] = $step;
    }
    foreach($steps as $key => $step)
    {
        $stepClass = "step-{$step->type}";
        $stepTrs[] = h::tr
        (
            setClass("step {$stepClass}"),
            h::td
            (
                setClass('text-left border'),
                div
                (
                    setClass('inputGroup'),
                    h::span
                    (
                        setClass('step-item-id mr-2'),
                        setClass('ml-' . (($step->grade- 1) * 2)),
                        $step->name
                    ),
                    nl2br(zget($step, 'desc', ''))
                )
            ),
            h::td
            (
                setClass('text-left border'),
                nl2br(zget($step, 'expect', ''))
            ),
            h::td
            (
                setClass('result-td'),
                setClass('text-center'),
                picker
                (
                    on::change('checkStepValue'),
                    set::name("result[{$step->id}]"),
                    set::items($lang->testcase->resultList),
                    set::value($step->type != 'group' ? 'pass' : ''),
                    set::required($step->type != 'group'),
                    set::disabled($step->type == 'group'),
                    $step->type == 'group' ? set('disabled', 'disabled') : ''
                )
            ),
            h::td
            (
                setClass('real-td'),
                h::table
                (
                    setClass('w-full'),
                    h::tr
                    (
                        h::td
                        (
                            setClass('p-0 bd-0'),
                            textarea
                            (
                                on::keyup('realChange'),
                                setClass('leading-4 w-60' ),
                                set('rows', '1'),
                                set::name("real[{$step->id}]"),
                                nl2br(zget($step, 'real', '')),
                                $step->type == 'group' ? set('disabled', 'disabled') : ''
                            )
                        ),
                        h::td
                        (
                            setClass('p-0 bd-0'),
                            width('40px'),
                            btn
                            (
                                setClass('ml-2 text-primary'),
                                $step->type != 'group' ? set::target("#fileModal{$step->id}") : '',
                                $step->type != 'group' ? set('data-toggle', 'modal') : '',
                                set('title', $lang->testtask->files),
                                set::icon('paper-clip'),
                                set::disabled($step->type == 'group')
                            )
                        )
                    )
                )
            )
        );

        $fileModals[] = modal
        (
            set::id("fileModal{$step->id}"),
            set::title($lang->testtask->files),
            setData('position', 'center'),
            upload
            (
                set::name("files{$step->id}[]")
            ),
            div
            (
                setClass('text-center'),
                btn
                (
                    setClass('btn-wide primary'),
                    set('data-dismiss', 'modal'),
                    $lang->save
                )
            )
        );
    }
}

!empty($run->case->precondition) ? h::table
(
    setClass('mb-6'),
    h::tr
    (
        h::td
        (
            setClass('case-precondition w-16 align-top'),
            $lang->testcase->precondition
        ),
        h::td
        (
            nl2br(zget($run->case, 'precondition', '')),
        )
    )
) : '';


form
(
    set::id('caseStepForm'),
    set::actions(array()),
    set::grid(false),
    h::table
    (
        setClass('table'),
        h::thead
        (
            $confirm != 'yes' ? h::tr
            (
                h::td
                (
                    setClass('w-96'),
                    $lang->testcase->stepDesc
                ),
                h::td
                (
                    width('cal(760px - 24rem)'),
                    $lang->testcase->stepExpect
                ),
                h::td
                (
                    setClass('result-td'),
                    width('96px'),
                    $lang->testcase->result
                ),
                h::td
                (
                    width('280px'),
                    $lang->testcase->real
                )
            ) : ''
        ),
        h::tbody
        (
            $stepTrs,
            h::tr
            (
                h::td
                (
                    set('colspan', '4'),
                    setClass('action-td p-0'),
                    div
                    (
                        setClass('text-center'),
                        $preLink ? a
                        (
                            setClass('btn btn-wide w-24 m-3'),
                            set::id('pre'),
                            set::href($preLink),
                            set('data-load', 'modal'),
                            $lang->testtask->pre
                        ) : '',
                        $run->case->status != 'wait' && $confirm != 'yes' && !empty($run->case->steps) ? btn
                        (
                            setClass('primary btn-wide w-24 m-3'),
                            set::btnType('submit'),
                            $lang->save
                        ) : '',
                        $nextLink ? a
                        (
                            setClass('btn btn-wide w-24 m-3'),
                            set::id('next'),
                            set::href($nextLink),
                            set('data-load', 'modal'),
                            $lang->testtask->next
                        ) : '',
                        input
                        (
                            setClass('hidden'),
                            set::name('case'),
                            set::value($run->case->id)
                        ),
                        input
                        (
                            setClass('hidden'),
                            set::name('version'),
                            set::value($run->case->currentVersion)
                        )
                    )
                )
            )
        ),
        $fileModals
    )
);

div
(
    setClass('main border-t'),
    set::id('resultsContainer'),
    div
    (
        set::id('casesResults')
    )
);

set::id('runCaseModal');

render();

