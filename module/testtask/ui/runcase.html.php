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
    set::entityID($caseID),
);

$stepTrs    = array();
$fileModals = array();
if($confirm != 'yes')
{
    if(empty($run->case->steps))
    {
        $step = new stdclass();
        $step->id     = 0;
        $step->parent = 0;
        $step->case   = $run->case->id;
        $step->type   = 'step';
        $step->desc   = '';
        $step->expect = '';
        $run->case->steps[] = $step;
    }
    $stepID = $childID = 0;
    foreach($run->case->steps as $key => $step)
    {
        $stepClass = "step-{$step->type}";
        if($step->type == 'group' or $step->type == 'step')
        {
            $stepID ++;
            $childID = 0;
        }

        $itemTds = array();
        if($step->type != 'group')
        {
            $itemTds[] = h::td
            (
                setClass('text-left'),
                nl2br(zget($step, 'expect', '')),
            );
            $itemTds[] = h::td
            (
                setClass("text-center"),
                select
                (
                    on::change('checkStepValue'),
                    set::name("steps[{$step->id}]"),
                    set::items($lang->testcase->resultList),
                    set::value('pass'),
                ),
            );
            $itemTds[] = h::td
            (
                h::table
                (
                    setClass('w-full'),
                    h::tr
                    (
                        h::td
                        (
                            setClass('p-0 bd-0'),
                            h::textarea
                            (
                                on::keyup('realChange'),
                                setClass('leading-4 w-full'),
                                set('rows', '1'),
                                set::name("reals[{$step->id}]"),
                                nl2br(zget($step, 'real', '')),
                            )
                        ),
                        h::td
                        (
                            setClass('p-0 bd-0 text-right'),
                            width('50px'),
                            btn
                            (
                                setClass('ml-4'),
                                set::url("#fileModal{$step->id}"),
                                set('data-toggle', 'modal'),
                                set('title', $lang->testtask->files),
                                set::icon('paper-clip'),
                            ),
                        ),
                    ),
                ),
            );
        }

        $stepTrs[] = h::tr
        (
            setClass("step {$stepClass}"),
            h::th
            (
                setClass('step-id'),
                $stepID,
            ),
            h::td
            (
                setClass('text-left'),
                $step->type == 'group' ? set('colspan', '4') : '',
                div
                (
                    setClass('inputGroup'),
                    $step->type == 'item' ? h::span
                    (
                        setClass('step-item-id mr-2'),
                        "{$stepID}.{$childID}",
                    ) : '',
                    nl2br(zget($step, 'desc', '')),
                ),
            ),
            $itemTds,
        );

        $childId ++;

        $fileModals[] = modal
        (
            set::id("fileModal{$step->id}"),
            set::title($lang->testtask->files),
            upload
            (
                set::name("files{$step->id}[]"),
            ),
            div
            (
                setClass('text-center'),
                btn
                (
                    setClass('btn-wide primary'),
                    set('data-dismiss', 'modal'),
                    $lang->save,
                ),
            ),
        );
    }
}


form
(
    set::actions(array()),
    h::table
    (
        setClass('table bordered'),
        h::thead
        (
            h::tr
            (
                h::td
                (
                    set('colspan', '5'),
                    h::strong($lang->testcase->precondition),
                    h::br(),
                    nl2br(zget($run->case, 'precondition', '')),
                ),
            ),
            $confirm != 'yes' ? h::tr
            (
                h::td
                (
                    width('50px'),
                    $lang->testcase->stepID,
                ),
                h::td
                (
                    width('cal(30%)'),
                    $lang->testcase->stepDesc,
                ),
                h::td
                (
                    width('calc(30%)'),
                    $lang->testcase->stepExpect,
                ),
                h::td
                (
                    width('100px'),
                    $lang->testcase->result,
                ),
                h::td
                (
                    $lang->testcase->real,
                ),
            ) : '',
        ),
        h::tbody
        (
            $stepTrs,
            h::tr
            (
                h::td
                (
                    set('colspan', '5'),
                    div
                    (
                        setClass('text-center'),
                        $preCase ? h::a
                        (
                            setClass('btn btn-wide w-24'),
                            set::id('pre'),
                            set::href(createLink('testtask', 'runCase', "runID={$preCase['runID']}&caseID={$preCase['caseID']}&version={$preCase['version']}")),
                            $lang->testtask->pre,
                        ) : '',
                        $run->case->status != 'wait' && $confirm != 'yes' ? btn
                        (
                            setClass('primary btn-wide w-24 mx-6'),
                            set::btnType('submit'),
                            $lang->save,
                        ) : '',
                        $nextCase ? h::a
                        (
                            setClass('btn btn-wide w-24'),
                            set::id('next'),
                            set::href(createLink('testtask', 'runCase', "runID={$nextCase['runID']}&caseID={$nextCase['caseID']}&version={$nextCase['version']}")),
                            $lang->testtask->next,
                        ) : '',
                        input
                        (
                            setClass('hidden'),
                            set::name('case'),
                            set::value($run->case->id),
                        ),
                        input
                        (
                            setClass('hidden'),
                            set::name('version'),
                            set::value($run->case->currentVersion),
                        ),
                    ),
                ),
            ),
        ),
    ),
    $fileModals,
);

div
(
    setClass('main'),
    set::id('resultsContainer'),
    div
    (
        set::id('casesResults'),
    ),
);

set::id('runCaseModal');

render();

