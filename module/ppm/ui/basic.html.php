<?php
declare(strict_types=1);
/**
 * The basic view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;
global $app;
$app->loadLang('pipeline');
$app->loadLang('codescan');

$AICodeScore     = 3;     // AI评审代码分数
$AISevereIssue   = 0;     // AI评审高危问题
$AIOrdinaryIssue = 3;     // AI评审一般问题

$scanSevereIssue   = 0;   // 代码扫描高危问题
$scanOrdinaryIssue = 1;   // 代码扫描一般问题
$scanPassRate      = 100; // 代码扫描安全门禁通过率

$config->ppm->AICodeScore       = 6;   // 合格的AI评审代码分数
$config->ppm->AISevereIssue     = 0;   // 合格的AI评审高危问题
$config->ppm->AIOrdinaryIssue   = 5;   // 合格的AI评审一般问题
$config->ppm->scanSevereIssue   = 0;   // 合格的代码扫描高危问题
$config->ppm->scanOrdinaryIssue = 3;   // 合格的代码扫描一般问题
$config->ppm->scanPassRate      = 100; // 合格的代码扫描安全门禁通过率

$canViewExecution = hasPriv('pipeline', 'execView');
$canViewTask      = hasPriv('codescan', 'taskView');
$checkPipeline    = true;
$pipelineBox      = array();
$codeScanBox      = array();

foreach($pipelines as $pipeline)
{
    if($pipeline->status != 'success') $checkPipeline = false;
    if(empty($pipeline->task))
    {
        $pipelineBox[] = section
        (
            div
            (
                setClass('border px-4 h-12 flex items-center'),
                span(setClass('font-bold'), "{$lang->ppm->pipeline}: {$pipeline->name}#{$pipeline->id}"),
                $pipeline->status == 'success' ? label(setClass('success ml-4'), $lang->pipeline->execStatusList['success']) : null,
                in_array($pipeline->status, array('failure', 'error', 'declined')) ? label(setClass('danger ml-4'), $lang->pipeline->execStatusList[$pipeline->status]) : null,
                in_array($pipeline->status, array('running', 'pending'))           ? label(setClass('secondary ml-4'), $lang->pipeline->execStatusList[$pipeline->status]) : null,
                in_array($pipeline->status, array('skipped', 'blocked'))           ? label(setClass('grey ml-4'), $lang->pipeline->execStatusList[$pipeline->status]) : null,
                $canViewExecution ? div(setClass('flex flex-auto justify-end'), btn(setClass('ghost text-primary'), span(icon(setClass('mr-2'), 'about'), $lang->ppm->locateView), set::url('pipeline', 'execView', "id={$pipeline->id}&spaceID=0&repoID={$repoID}&type=repo"), set::target('_blank'))) : null
            )
        );
    }
    else
    {
        if(empty($pipeline->task)) continue;
        $taskName = $pipeline->task->repoName . sprintf($lang->codescan->scanNo, $pipeline->task->repoNumber);
        $codeScanBox[] = section
        (
            div
            (
                setClass('border px-4 h-12 flex items-center'),
                span(setClass('font-bold'), "{$lang->ppm->codeScan}: {$taskName}"),
                $pipeline->task->result && $pipeline->task->result == 'pass' ? label(setClass('success ml-4'), $lang->codescan->latestScanResultList['pass']) : null,
                $pipeline->task->result && $pipeline->task->result != 'pass' ? label(setClass('danger ml-4'), $lang->codescan->latestScanResultList[$pipeline->task->result]) : null,
                !$pipeline->task->result ? label(setClass('secondary ml-4'), $lang->codescan->latestExecStatusList['in_progress']) : null,
                $canViewTask ? div(setClass('flex flex-auto justify-end'), btn(setClass('ghost text-primary'), span(icon(setClass('mr-2'), 'about'), $lang->ppm->locateView), set::url('codescan', 'taskView', "repoID={$repoID}&id={$pipeline->task->id}&serviceRepoID={$repoID}&type=issue"), set::target('_blank'))) : null
            )
        );
    }
}

$checkAI     = true;
$checkScan   = $scanSevereIssue <= $config->ppm->scanSevereIssue && $scanOrdinaryIssue <= $config->ppm->scanOrdinaryIssue && $scanPassRate >= $config->ppm->scanPassRate;
$hasConflict = !empty($checkResult->conflictFiles); // 是否有代码冲突
$checkResult->conflictFiles[] = '/app/public/test/sae2sd2/test2.php';

$conflictFiles = array();
if($hasConflict)
{
    foreach($checkResult->conflictFiles as $file)
    {
        $conflictFiles[] = div(setClass('flex items-center'), icon('file mr-1'), span($file));
    }
}

$domBox = div
(
    $ppm->status == 'opened' ? div
    (
        section
        (
            setClass('flex w-full checkMerge'),
            !$canMerge ? div(setClass('py-6 border-l-4 border-r-4 border-danger')) : div(setClass('py-6 border-l-4 border-r-4 border-success')),
            div
            (
                !$canMerge ? setClass('flex flex-auto items-center pl-4 bg-danger bg-opacity-5 items-center') :
                setClass('flex flex-auto items-center pl-4 bg-success bg-opacity-5 items-center success-box'),
                set::style(array('justify-content' => 'space-between')),
                !$canMerge ?
                div
                (
                    setClass('flex flex-auto items-center pl-4 bg-opacity-5 items-center'),
                    span(setClass('text-danger font-bold'), $lang->ppm->checkFailed . ($checkMessage ? "({$checkMessage})" : ''))
                ) : div(span(setClass('text-success font-bold'), $lang->ppm->checkSuccess)),
                hasPriv('ppm', 'merge') && !empty($mergeBtnItems) && $ppm->status == 'opened' ? div(btnGroup
                (
                    setClass('merge-btn-group'),
                    btn
                    (
                        $canMerge ? setClass('btn ajax-submit primary') : setClass('btn bg-gray-100 text-black'),
                        set::disabled(!$canMerge),
                        set::url(createLink('ppm', 'merge', "ppmID={$ppm->id}&type={$defaultMergeType}")),
                        $lang->reporeviewflow->mergeOptionList[$defaultMergeType]
                    ),
                    count($mergeBtnItems) > 1 ? dropDown
                    (
                        btn(setClass('btn primary dropdown-toggle'),
                        setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
                        set::placement('bottom-end'),
                        set::items($mergeBtnItems)
                    ) : null
                )) : null
            )
        )
    ) : null,
    section
    (
        div
        (
            setClass('border px-4 h-12 flex items-center'),
            span(setClass('font-bold'), $lang->ppm->codeConflict),
            $hasConflict ? label(setClass('danger ml-4'), $lang->ppm->checkStatusList['fail']) : label(setClass('success ml-4'), $lang->ppm->checkStatusList['success']),
            //div(setClass('flex flex-auto justify-end'), btn(setClass('ghost text-primary'), span(icon(setClass('mr-2'), 'about'), $lang->ppm->locateView)))
        ),
        $hasConflict ? div
        (
            setClass('border px-4 py-4'),
            setStyle(array('margin-top' => '-1px')),
            div
            (
                div
                (
                    setClass('flex items-center'),
                    icon(setClass('text-danger font-bold mr-1'), 'close'),
                    span("{$lang->ppm->conflictFiles}: "),
                    div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->ppm->request}: {$lang->ppm->hasConflictList['no']})"))
                ),
                $conflictFiles,
            )
        ) : null
    ),
    //section
    //(
    //    div
    //    (
    //        setClass('border px-4 h-12 flex items-center'),
    //        span(setClass('font-bold'), $lang->ppm->AIReview),
    //        $checkAI ? label(setClass('success ml-4'), $lang->ppm->checkStatusList['success']) : label(setClass('warning ml-4'), $lang->ppm->checkStatusList['wait'])
    //    ),
    //    div
    //    (
    //        setClass('border px-4 py-4'),
    //        setStyle(array('margin-top' => '-1px')),
    //        div
    //        (
    //            setClass('flex items-center py-1'),
    //            $AICodeScore < $config->ppm->AICodeScore ? icon(setClass('text-warning font-bold mr-1'), 'about') : icon(setClass('text-success font-bold mr-1'), 'check'),
    //            span("{$lang->ppm->AICodeScore}: ", $AICodeScore),
    //            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->ppm->request}: ≥{$config->ppm->AICodeScore})"))
    //        ),
    //        div
    //        (
    //            setClass('flex items-center py-1'),
    //            $AISevereIssue > $config->ppm->AISevereIssue ? icon(setClass('text-warning font-bold mr-1'), 'about') : icon(setClass('text-success font-bold mr-1'), 'check'),
    //            span("{$lang->ppm->AISevereIssue}: ", $AISevereIssue),
    //            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->ppm->request}: ≤{$config->ppm->AISevereIssue})"))
    //        ),
    //        div
    //        (
    //            setClass('flex items-center py-1'),
    //            $AIOrdinaryIssue > $config->ppm->AIOrdinaryIssue ? icon(setClass('text-warning font-bold mr-1'), 'about') : icon(setClass('text-success font-bold mr-1'), 'check'),
    //            span("{$lang->ppm->AIOrdinaryIssue}: ", "$AIOrdinaryIssue"),
    //            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->ppm->request}: ≤{$config->ppm->AIOrdinaryIssue})"))
    //        )
    //    )
    //),
    section
    (
        setID('manualReview'),
        div
        (
            setClass('border px-4 h-12 flex items-center'),
            span(setClass('font-bold'), $lang->ppm->manualReview),
            $reviewResult == 'approved' ? label(setClass('success ml-4'), $lang->ppm->approvalStatusList[$reviewResult]) : null,
            $reviewResult == 'rejected' && !empty($reviewers) ? label(setClass('danger ml-4'),  $lang->ppm->approvalStatusList[$reviewResult]) : null,
            $reviewResult == 'inProgress' ? label(setClass('secondary ml-4'),  $lang->ppm->approvalStatusList[$reviewResult]) : null,
            $reviewResult == 'rejected' && empty($reviewers) ? label(setClass('secondary ml-4'),  $lang->ppm->approvalStatusList['inProgress']) : null
        ),
        div
        (
            setClass('border px-4 py-4'),
            setStyle(array('margin-top' => '-1px')),
            div
            (
                setClass('flex items-center py-1'),
                $reviewResult == 'approved' ? icon(setClass('text-success font-bold mr-1 reviewResultIcon'), 'check') : icon(setClass('text-danger font-bold mr-1 reviewResultIcon'), 'close'),
                span("{$lang->ppm->reviewStatus}: ", $reviewResult == 'rejected' && empty($reviewers) ? $lang->ppm->notice->noHasReviewer : $lang->ppm->approvalStatusList[$reviewResult]),
                div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->ppm->request}: {$lang->ppm->approvalStatusList['approved']})"))
            ),
            div
            (
                setClass('flex items-center py-1' . ($minReviewers == 0 ? ' hidden' : '')),
                count($reviewers) >= $minReviewers ? icon(setClass('text-success font-bold mr-1 reviewerCountIcon'), 'check') : icon(setClass('text-danger font-bold mr-1 reviewerCountIcon'), 'close'),
                span("{$lang->ppm->approvalReviewer}: ", count($reviewers)),
                div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->ppm->request}: ≥{$minReviewers})"))
            )
        )
    ),
    $codeScanBox,
    $pipelineBox
);
