<?php
declare(strict_types=1);
/**
 * The batch run view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

unset($lang->testcase->resultList['n/a']);

$caseItems = array();
foreach($cases as $caseID => $case)
{
    if($case->status == 'wait') continue;

    $stepItems = array();
    if(!empty($steps[$caseID]))
    {
        $stepId = $childId = 0;
        foreach($steps[$caseID] as $stepID => $step)
        {
            if($step->type == 'group' || $step->type == 'step')
            {
                $stepId ++;
                $childId = 0;
            }
            $currentID  = $step->type == 'item' ? "{$stepId}.{$childId}" : $stepId;
            $stepClass  = $step->type == 'item' ? 'step-item pl-2' : 'step-group';
            $stepResult = count($steps[$caseID]) == count($stepItems) + 1 ? 'fail' : 'pass';

            $stepItems[] = h::tr
            (
                h::td
                (
                    set::width('30%'),
                    set::className('break-words'),
                    set::colspan($step->type == 'group' ? 2 : 1),
                    span
                    (
                        set::className($stepClass),
                        set::hint(true),
                        $currentID . '、' . $step->desc
                    )
                ),
                $step->type != 'group' ? h::td
                (
                    set::width('30%'),
                    set::className('break-words'),
                    span
                    (
                        set::hint(true),
                        $lang->testcase->stepExpect . ':' . $step->expect
                    )
                ) : null,
                $step->type != 'group' ? h::td
                (
                    set::width('90px'),
                    set::className("hidden steps"),
                    picker
                    (
                        set::name("steps[$caseID][$stepID]"),
                        set::items($lang->testcase->resultList),
                        set::required(true),
                        set::value($stepResult)
                    )
                ) : null,
                $step->type != 'group' ? h::td
                (
                    set::className("hidden reals"),
                    input
                    (
                        set::name("reals[$caseID][$stepID]")
                    )
                ) : null
            );
            $childId ++;
        }
    }

    $caseItems[] = h::tr
    (
        h::td
        (
            $caseID,
            input
            (
                set::type('hidden'),
                set::name("version[$caseID]"),
                set::value($case->version)
            )
        ),
        h::td
        (
            h::span
            (
                set::hint(true),
                $modules[$case->module]
            )
        ),
        h::td
        (
            set::className('break-words'),
            h::span
            (
                set::hint(true),
                $case->title
            )
        ),
        h::td
        (
            set::className('precondition break-words'),
            span
            (
                set::hint(true),
                $case->precondition
            )
        ),
        h::td
        (
            radioList
            (
                set::primary(true),
                set::name("results[$caseID]"),
                set::value('pass'),
                set::inline(false),
                set::items($lang->testcase->resultList)
            )
        ),
        h::td
        (
            set::className(empty($steps[$caseID]) ? 'hidden reals' : 'stepsAndExpect'),
            !empty($steps[$caseID]) ? h::table
            (
                set::className('table bordered'),
                $stepItems
            ) : null,
            empty($steps[$caseID]) ? input
            (
                set::name("reals[$caseID][]")
            ) : null
        )
    );
}
formPanel
(
    set::title(($from == 'testtask' ? $lang->testtask->common . $lang->colon : '') . $lang->testtask->batchRun),
    set::width('auto'),

    on::click('[name^=results]', 'toggleAction'),
    on::keyup('[name^=reals]', 'toggleStep'),

    h::table
    (
        set::className('table bordered'),
        h::thead
        (
            h::tr
            (
                h::th
                (
                    set::width('60px'),
                    $lang->idAB
                ),
                h::th
                (
                    set::width('100px'),
                    $lang->testcase->module
                ),
                h::th
                (
                    set::width('200px'),
                    $lang->testcase->title
                ),
                h::th
                (
                    set::width('100px'),
                    set::className('precondition'),
                    $lang->testcase->precondition
                ),
                h::th
                (
                    set::width('100px'),
                    $lang->testcase->result
                ),
                h::th
                (
                    $lang->testcase->stepDesc . $lang->slash . $lang->testcase->stepExpect
                ),
            )
        ),
        h::tbody
        (
            $caseItems
        )
    )
);

render();
