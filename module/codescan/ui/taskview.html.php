<?php
declare(strict_types=1);
/**
 * The taskview file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
include 'task.header.html.php';
include 'chart.html.php';
h::css('#mainNavbar + #mainContainer .detail-body {height: 100%;}');

if(!empty($issueStatistics->issue) && !empty($distribution))
{
    unset($distribution['status']);
    $distributionDiv = array();
    foreach($distribution as $key => $value)
    {
        $pieTitle = $lang->codescan->{'issue' . ucfirst($key == 'priority' ? 'severity' : $key) . 'Distribution'};
        $pieClass = 'w-full canvas p-3 border';
        if($key != 'priority') $pieClass .= ' mr-2';
        $distributionDiv[] = div
        (
            setClass($pieClass),
            $pieChart($value, $pieTitle, $key)
        );
    }
}

panel
(
    div
    (
        setID('taskMenu'),
        setClass('flex justify-between'),
        $headers
    ),
    detailBody
    (
        empty($issueStatistics->issue) ? sectionList(div(setClass('w-full text-center pt-10'), $lang->noData)) : sectionList
        (
            div
            (
                setClass('flex'),
                panel
                (
                    setID('scanIssueOverview'),
                    setClass('w-full border canvas pb-2'),
                    div(setClass('text-xl font-semibold p-1 mb-3'), $lang->codescan->scanIssueOverview),
                    div
                    (
                        setClass('flex-auto flex justify-around text-center items-center'),
                        cell
                        (
                            div(setClass('text-3xl h-10'), zget($issueStatistics, 'issue', 0)),
                            div($lang->codescan->issueCount)
                        ),
                        cell
                        (
                            div(setClass('text-3xl h-10'), zget($issueStatistics, 'files', 0)),
                            div($lang->codescan->fileCount)
                        ),
                        cell
                        (
                            div(setClass('text-3xl h-10'), zget($issueStatistics, 'rules', 0)),
                            div($lang->codescan->hitRuleCount)
                        ),
                        cell
                        (
                            div(setClass('text-3xl h-10'), zget($issueStatistics, 'committers', 0)),
                            div($lang->codescan->injectorCount)
                        )
                    )
                )
            ),
            div
            (
                setClass('flex'),
                $distributionDiv
            ),
            div
            (
                setClass('flex justify-between'),
                div
                (
                    setClass('w-1/2 p-3 mr-1 border canvas'),
                    div($rankingChart($lang->codescan->fileRanking, $fileRanking))
                ),
                div
                (
                    setClass('w-1/2 p-3 ml-1 border canvas'),
                    div($rankingChart($lang->codescan->ruleRanking, $ruleRanking))
                )
            )
        ),
        detailSide
        (
            tabs
            (
                setID('basicInfo'),
                set::collapse(true),
                tabPane
                (
                    set::key('basicInfo'),
                    set::active(true),
                    set::title($lang->basicInfo),
                    tableData
                    (
                        item
                        (
                            set::name($lang->codescan->name),
                            $task->name
                        ),
                        item
                        (
                            set::name($lang->codescan->repo),
                            zget($repoList, $task->repoID)
                        ),
                        item
                        (
                            set::name($lang->codescan->branch),
                            $task->branch
                        ),
                        item
                        (
                            set::name($lang->codescan->runStatus),
                            zget($lang->codescan->latestExecStatusList, $task->status)
                        ),
                        item
                        (
                            set::name($lang->codescan->result),
                            zget($lang->codescan->latestScanResultList, $task->result)
                        ),
                        item
                        (
                            set::name($lang->codescan->plan),
                            empty($plan->name) ? '' : $plan->name
                        ),
                        item
                        (
                            set::name($lang->codescan->triggerName),
                            empty($trigger->name) ? '' : $trigger->name
                        ),
                        item
                        (
                            set::name($lang->codescan->triggerType),
                            zget($lang->codescan->triggerTypeList, $task->triggerType)
                        ),
                        item
                        (
                            set::name($lang->codescan->scope),
                            zget($lang->codescan->scopeList, $task->scanType)
                        ),
                        item
                        (
                            set::name($lang->codescan->startTime),
                            $task->startTime
                        ),
                        item
                        (
                            set::name($lang->codescan->endTime),
                            $task->endTime
                        ),
                        item
                        (
                            set::name($lang->codescan->runTime),
                            $task->runTime
                        )
                    )
                )
            )
        )
    )
);
