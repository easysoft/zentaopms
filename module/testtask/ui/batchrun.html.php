<?php
declare(strict_types=1);
/**
 * The batch run view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

if(count($cases) != count($caseIdList))
{
    h::js("zui.Modal.alert('{$lang->testtask->skipChangedCases}');");
}

if(!empty($emptyCases))
{
    $emptyCasesTip = sprintf($lang->testtask->emptyCases, $emptyCases);
    pageJS("zui.Modal.alert({message: '{$emptyCasesTip}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'});\n");
}

unset($lang->testcase->resultList['n/a']);

$fileModals = array();
$caseItems  = array();
foreach($cases as $caseID => $case)
{
    if($case->status == 'wait') continue;

    $stepItems = array();
    if(!empty($steps[$case->id]))
    {
        $lastGradeId = array();
        $grades      = array();
        $preGrade    = 0;
        foreach($steps[$case->id] as $stepID => $step)
        {
            if(empty($step->parent))  $grades[$step->id] = 1;
            if(!empty($step->parent)) $grades[$step->id] = $grades[$step->parent] + 1;

            $grade = $grades[$step->id];
            if(!isset($lastGradeId[$grade])) $lastGradeId[$grade] = 0;
            $lastGradeId[$grade] ++;
            if($preGrade > $grade)
            {
                foreach($lastGradeId as $thisGrade => $thisId)
                {
                    if($thisGrade > $grade) unset($lastGradeId[$thisGrade]);
                }
            }

            $currentID  = $lastGradeId[1];
            if($grade > 1) $currentID .= '.' . $lastGradeId[2];
            if($grade > 2) $currentID .= '.' . $lastGradeId[3];
            $stepClass  = "step-{$step->type} pl-" . ($grade - 1) * 2;
            $stepResult = count($steps[$case->id]) == count($stepItems) + 1 ? 'fail' : 'pass';
            $preGrade   = $grade;

            $stepItems[] = h::tr
            (
                h::td
                (
                    setStyle('width', '30%'),
                    set::className('break-words'),
                    set::colspan($step->type == 'group' ? 2 : 1),
                    span
                    (
                        set::className($stepClass),
                        set::hint(true),
                        $currentID . '、' . htmlspecialchars_decode($step->desc)
                    )
                ),
                $step->type != 'group' ? h::td
                (
                    setStyle('width', '30%'),
                    set::className('break-words'),
                    span
                    (
                        set::hint(true),
                        $lang->testcase->stepExpect . ':' . htmlspecialchars_decode($step->expect)
                    )
                ) : null,
                $step->type != 'group' ? h::td
                (
                    setStyle('width', '90px'),
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
                    div
                    (
                        setClass('flex items-center'),
                        input
                        (
                            setClass('flex-1 min-w-0'),
                            set::name("reals[$caseID][$stepID]")
                        ),
                        btn
                        (
                            setClass('ml-2 text-primary flex-none'),
                            set::target("#fileModal{$stepID}"),
                            set('data-toggle', 'modal'),
                            set('title', $lang->testtask->files),
                            set::icon('paper-clip')
                        )
                    )
                ) : null
            );

            if($step->type != 'group')
            {
                $fileModals[] = modal
                (
                    set::id("fileModal{$stepID}"),
                    set::title($lang->testtask->files),
                    setData('position', 'center'),
                    fileSelector
                    (
                        set::name("files{$stepID}[]")
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
    }

    $caseItems[] = h::tr
    (
        h::td
        (
            $case->id,
            input
            (
                set::type('hidden'),
                set::name("version[$caseID]"),
                set::value($case->version)
            ),
            input
            (
                set::type('hidden'),
                set::name("caseID[$caseID]"),
                set::value($case->id)
            )
        ),
        $from == 'work' ? h::td(span(set::hint(true), $case->taskName)) : null,
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
            set::className(empty($steps[$case->id]) ? 'hidden reals' : 'stepsAndExpect'),
            !empty($steps[$case->id]) ? h::table
            (
                set::className('table bordered'),
                $stepItems
            ) : null,
            empty($steps[$case->id]) ? input
            (
                set::name("reals[$caseID][]")
            ) : null
        )
    );
}
formPanel
(
    set::title(($from == 'testtask' ? $lang->testtask->common . $lang->hyphen : '') . $lang->testtask->batchRun),
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
                    setStyle('width', '60px'),
                    $lang->idAB
                ),
                $from == 'work' ? h::th(setStyle('width', '80px'), $lang->testtask->common) : null,
                h::th
                (
                    setStyle('width', '100px'),
                    $lang->testcase->module
                ),
                h::th
                (
                    setStyle('width', '200px'),
                    $lang->testcase->title
                ),
                h::th
                (
                    setStyle('width', '100px'),
                    set::className('precondition'),
                    $lang->testcase->precondition
                ),
                h::th
                (
                    setStyle('width', '100px'),
                    $lang->testcase->resultAB
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
    ),
    $fileModals
);

render();
