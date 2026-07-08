<?php
declare(strict_types=1);
/**
 * The overview view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
if($repoID) dropmenu(set::objectID($repoID), set::tab('repo'));

if(empty($overview) || empty($overview->issue) || empty($overview->issue->total))
{
    div
    (
        setClass('w-full dtable-empty-tip text-center bg-white'),
        div
        (
            setClass('text-gray'),
            $lang->codescan->notice->noScanData,
            hasPriv('codescan', 'createplan') ?
            btn
            (
                set(array('text' => $lang->codescan->createPlan, 'url' => inLink('createplan', "repoID={$repoID}"), 'class' => 'ml-2 primary-pale border-primary', 'icon'  => 'plus'))
            ) : null
        ),
    );
    return;
}

featureBar
(
    empty($lastExecuteTime) ? null : li
    (
        setClass('nav-item'),
        div
        (
          icon('help text-warning mr-1'),
          html(sprintf($lang->codescan->notice->refreshTime, $lastExecuteTime))
        )
    )
);

div
(
    setClass('flex mb-3'),
    panel
    (
        setID('issueOverview'),
        setClass('w-1/2 mr-1 border canvas pb-2'),
        div(setClass('text-xl font-semibold p-1 mb-3'), $lang->codescan->issueOverview),
        div
        (
            setClass('flex-auto flex justify-around text-center items-center'),
            cell
            (
                set::title(zget($overview->issue, 'total', 0)),
                div
                (
                    setClass('text-3xl h-10'),
                    $repoID && hasPriv('codescan', 'issue') ? a(set::href(inLink('issue', "repoID={$repoID}&taskID=0&serviceRepoID={$serviceRepoID}&type=all")), zget($issueStatistics, 'total', 0)) :
                    zget($issueStatistics, 'total', 0),
                ),
                div($lang->codescan->issueCount)
            ),
            cell
            (
                set::title(zget($overview->issue, 'wait', 0)),
                div
                (
                    setClass('text-3xl h-10'),
                    $repoID && hasPriv('codescan', 'issue') ? a(set::href(inLink('issue', "repoID={$repoID}&taskID=0&serviceRepoID={$serviceRepoID}&type=wait")), zget($issueStatistics, 'wait', 0)) :
                    zget($issueStatistics, 'wait', 0)
                ),
                div($lang->codescan->waitIssueCount)
            ),
            cell
            (
                set::title(zget($overview->issue, 'fixed', 0)),
                div(setClass('text-3xl h-10'), zget($issueStatistics, 'fixed', 0)),
                div
                (
                    $lang->codescan->fixedIssueCount,
                    icon('help ml-1', set::title($lang->codescan->notice->fixedIssueTip))
                )
            ),
            cell
            (
                set::title(zget($overview->issue, 'ignored', 0)),
                div(setClass('text-3xl h-10'), zget($issueStatistics, 'ignored', 0)),
                div($lang->codescan->ignoreIssueCount)
            )
        )
    ),
    panel
    (
        setID('scanOverview'),
        setClass('w-1/2 mr-1 border canvas pb-2'),
        div(setClass('text-xl font-semibold p-1 mb-3'), $lang->codescan->scanOverview),
        $repoID ? div
        (
            setClass('flex-auto flex justify-around text-center items-center'),
            cell
            (
                set::title(zget($overview->scan, 'files', 0)),
                div(setClass('text-3xl h-10'), zget($scanStatistics, 'files', 0)),
                div($lang->codescan->fileCount)
            ),
            cell
            (
                set::title(zget($overview->scan, 'rules', 0)),
                div(setClass('text-3xl h-10'), zget($scanStatistics, 'rules', 0)),
                div($lang->codescan->hitRuleCount)
            ),
            cell
            (
                set::title(zget($overview->scan, 'committers', 0)),
                div(setClass('text-3xl h-10'), zget($scanStatistics, 'committers', 0)),
                div($lang->codescan->injectorCount)
            )
        ) : div
        (
            setClass('flex-auto flex justify-around text-center items-center'),
            cell
            (
                set::title(zget($overview->scan, 'repos', 0)),
                div(setClass('text-3xl h-10'), zget($scanStatistics, 'repos', 0)),
                div($lang->codescan->scanRepoCount)
            ),
            cell
            (
                set::title(zget($overview->scan, 'tasks', 0)),
                div(setClass('text-3xl h-10'), zget($scanStatistics, 'tasks', 0)),
                div($lang->codescan->executionCount)
            ),
            cell
            (
                set::title(zget($overview->scan, 'files', 0)),
                div(setClass('text-3xl h-10'), zget($scanStatistics, 'files', 0)),
                div($lang->codescan->fileCount)
            )
        )
    )
);

include 'chart.html.php';
if(!empty($distribution))
{
    $distributionDiv = array();
    foreach($distribution as $key => $value)
    {
        $pieTitle = $lang->codescan->{'issue' . ucfirst($key == 'priority' ? 'severity' : $key) . 'Distribution'};
        $distributionDiv[] = div
        (
            setClass('w-full canvas p-3 border', $key != 'priority' ? 'mr-2' : ''),
            $pieChart($value, $pieTitle, $key)
        );
    }
}
div
(
    setClass('flex mb-3'),
    $distributionDiv
);

if($repoID)
{
    div
    (
        setClass('canvas mb-3 pt-4 pl-4 border'),
        setID('issueTrends'),
        on::init()->call('loadTarget', inLink('ajaxGetIssueTrends', "repoID={$repoID}"), '#issueTrends'),
    );

    div
    (
        setClass('flex justify-between mb-3'),
        on::init()->call('loadTarget', inLink('ajaxGetIssueResolvedByTop', "repoID={$repoID}"), '#issueResolvedByTop'),
        div
        (
            setClass('w-1/2 p-3 mr-1 border canvas'),
            div(setClass('flex'), $rankingChart($lang->codescan->issueInjectionTop, $injectionList))
        ),
        div(setID('issueResolvedByTop'), setClass('w-1/2 p-3 ml-1 border canvas'))
    );

    div
    (
        setClass('flex justify-between mb-3'),
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
    );
}
else
{
    $items = array();
    foreach($repoMetrics as $repo)
    {
        $items[] = array
        (
            'id'   => $repo->realID,
            'text' => $repo->name,
            'url'  => inLink('issue', "repoID={$repo->realID}")
        );
    }

    $currentRepo = empty($repoMetrics) ? 0 : current($repoMetrics)->realID;
    div
    (
        setClass('canvas mb-3'),
        setID('issueStatistics'),
        statisticBlock
        (
            on::click('#issueStatistics .block-statistic-nav-item')->call('loadStatistic', jsRaw('event')),
            set::headingClass('text-xl'),
            set::longBlock(true),
            set::active($currentRepo),
            set::title($lang->codescan->repoStatistic),
            set::items($items),
            div
            (
                setID('repoStatisticChart'),
                setClass('flex items-center justify-center h-full'),
                on::init()->call('loadTarget', inLink('ajaxGetRepoIssueStatistics', "repoID={$currentRepo}"), '#repoStatisticChart')
            )
        )
    );

    div
    (
        setClass('flex justify-between mb-3'),
        div
        (
            setID('topIssueRepoIssueRanking'),
            setClass('w-1/2 p-3 mr-1 border canvas'),
            $stackedChart($lang->codescan->issueRanking, $issueTotalRanking)
        ),
        div
        (
            setID('unresolvedIssueRanking'),
            setClass('w-1/2 p-3 ml-1 border canvas'),
            $stackedChart($lang->codescan->unSolvedRanking, $issueUnresolvedRanking)
        )
    );
}
