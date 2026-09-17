<?php
namespace zin;

global $lang;

$fields   = defineFieldList('testtask.runCase');
$confirm  = data('confirm');
$run      = data('run');
$deployID = data('deployID');

$buildStepsBox = function() use($run, $confirm, $deployID, $lang)
{
    $stepTrs    = array();
    $fileModals = array();
    if($confirm != 'yes' && !empty($run->case))
    {
        $steps = $run->case->steps;
        if(empty($steps))
        {
            $stepTrs[] = h::tr
            (
                setClass("step"),
                h::td
                (
                    setClass('text-left border'),
                    div
                    (
                        setClass('inputGroup row', 'pl-0'),
                        h::span(setClass('step-item-id mr-2')),
                        div(html())
                    )
                ),
                h::td(setClass('text-left border'), html()),
                h::td
                (
                    setClass('result-td'),
                    picker
                    (
                        on::change('checkStepValue'),
                        set::name("result[0]"),
                        set::items($lang->testcase->resultList),
                        set::value('pass'),
                        set::required()
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
                            h::td(setClass('p-0 bd-0'), textarea(on::keyup('realChange'), setClass('leading-4 w-60'), set('rows', '1'), set::name("real[0]"))),
                            h::td(setClass('p-0 bd-0'), width('40px'), btn(setClass('ml-2 text-primary'), set::target("#fileModal0"), set('data-toggle', 'modal'), on::click('setFileModalHeight'), set('title', $lang->testtask->files), set::icon('paper-clip')))
                        )
                    )
                )
            );
            $fileModals[] = modal
            (
                set::id("fileModal0"),
                set::title($lang->testtask->files),
                setData('position', 'center'),
                fileSelector(set::name("files0[]")),
                div(setClass('text-center'), btn(setClass('btn-wide primary'), set('data-dismiss', 'modal'), $lang->save))
            );
        }

        foreach($steps as $step)
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
                        setClass('inputGroup row', 'pl-' . (($step->grade - 1) * 2)),
                        h::span(setClass('step-item-id mr-2'), $step->name),
                        div(setClass('wrap break-all whitespace-pre-line'), html_entity_decode(str_replace(' ', '&nbsp;', $step->desc)))
                    )
                ),
                h::td(setClass('text-left border break-all whitespace-pre-line'), html_entity_decode(str_replace(' ', '&nbsp;', $step->expect))),
                h::td
                (
                    setClass('result-td'),
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
                            h::td(setClass('p-0 bd-0'), textarea(on::keyup('realChange'), setClass('leading-4 w-60'), set('rows', '1'), set::name("real[{$step->id}]"), nl2br(zget($step, 'real', '')), $step->type == 'group' ? set('disabled', 'disabled') : '')),
                            h::td(setClass('p-0 bd-0'), width('40px'), btn(setClass('ml-2 text-primary'), $step->type != 'group' ? set::target("#fileModal{$step->id}") : '', $step->type != 'group' ? set('data-toggle', 'modal') : '', on::click('setFileModalHeight'), set('title', $lang->testtask->files), set::icon('paper-clip'), set::disabled($step->type == 'group')))
                        )
                    )
                )
            );

            $fileModals[] = modal
            (
                set::id("fileModal{$step->id}"),
                set::title($lang->testtask->files),
                setData('position', 'center'),
                fileSelector(set::name("files{$step->id}[]")),
                div(setClass('text-center'), btn(setClass('btn-wide primary'), set('data-dismiss', 'modal'), $lang->save))
            );
        }
    }

    $table = h::table
    (
        setClass('table'),
        h::thead
        (
            $confirm != 'yes' ? h::tr
            (
                h::td(setClass('w-96'), $lang->testcase->stepDesc),
                h::td(width('cal(760px - 24rem)'), $lang->testcase->stepExpect),
                h::td(setClass('result-td'), width('96px'), $lang->testcase->resultAB),
                h::td(width('280px'), $lang->testcase->real)
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
                        input(setClass('hidden'), set::name('case'), set::value($run->case->id)),
                        input(setClass('hidden'), set::name('version'), set::value($run->case->currentVersion))
                    )
                )
            )
        )
    );

    $precondition = !empty($run->case->precondition) ? h::table
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
                html(nl2br(zget($run->case, 'precondition', ''))),
            )
        )
    ) : '';

    return div
    (
        setClass('step-table-wrapper'),
        $precondition,
        $table,
        $fileModals,
        !is_null($deployID) ? formHidden('deploy', $deployID) : null
    );
};

$fields->field('stepTable')->width('full')->label(false)->control($buildStepsBox);
