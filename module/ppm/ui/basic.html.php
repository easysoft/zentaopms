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

$pipelines = array();
$pipeline1 = new stdclass();
$pipeline1->title  = 'a0001';
$pipeline1->status = 'success';
$pipelines[] = $pipeline1;

$checkPipeline = true;
$pipelineBox   = array();
foreach($pipelines as $pipeline)
{
    if($pipeline->status != 'success') $checkPipeline = false;
    $pipelineBox[] = section
    (
        div
        (
            setClass('border px-4 h-12 flex items-center'),
            span(setClass('font-bold'), "{$lang->ppm->pipeline}: {$pipeline->title}"),
            $pipeline->status == 'success' ? label(setClass('success ml-4'), $lang->ppm->checkStatusList['success']) : label(setClass('danger ml-4'), $lang->ppm->checkStatusList['fail'])
        ),
        div
        (
            setClass('border px-4 py-4'),
            setStyle(array('margin-top' => '-1px')),
            div
            (
                setClass('flex items-center py-1'),
                $pipeline->status == 'success' ? icon(setClass('text-success font-bold mr-1'), 'check') : icon(setClass('text-danger font-bold mr-1'), 'close'),
                span("{$lang->ppm->runResult}: ", $lang->ppm->pipelineStatus[$pipeline->status]),
                div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->ppm->request}: {$lang->ppm->pipelineStatus['success']})"))
            )
        )
    );
}

$checkAI     = true;
$checkScan   = $scanSevereIssue <= $config->ppm->scanSevereIssue && $scanOrdinaryIssue <= $config->ppm->scanOrdinaryIssue && $scanPassRate >= $config->ppm->scanPassRate;
$hasConflict = empty($checkResult->conflictFiles) ? 'no' : 'yes'; // 是否有代码冲突

$domBox = div
(
    $ppm->status == 'opened' ? div
    (
        $canMerge ? section
        (
            setClass('flex w-full checkMerge'),
            $defaultMergeType == 'fast' && $ppm->mergeBaseSHA != $ppm->mergeTargetSHA ? div(setClass('py-6 border-l-4 border-r-4 border-danger')) : div(setClass('py-6 border-l-4 border-r-4 border-success')),
            div
            (
                $defaultMergeType == 'fast' && $ppm->mergeBaseSHA != $ppm->mergeTargetSHA ? setClass('flex flex-auto items-center pl-4 bg-danger bg-opacity-5 items-center') :
                setClass('flex flex-auto items-center pl-4 bg-success bg-opacity-5 items-center success-box'),
                set::style(array('justify-content' => 'space-between')),
                $defaultMergeType == 'fast' && $ppm->mergeBaseSHA != $ppm->mergeTargetSHA ?
                div
                (
                    setClass('flex flex-auto items-center pl-4 bg-danger bg-opacity-5 items-center'),
                    span(setClass('text-danger font-bold'), $lang->ppm->notice->fastNotice)
                ) : div(span(setClass('text-success font-bold'), $lang->ppm->checkSuccess)),
                hasPriv('ppm', 'merge') && !empty($mergeBtnItems) && $ppm->status == 'opened' ? div(btnGroup
                (
                    setClass('merge-btn-group'),
                    btn
                    (
                        setClass('btn primary ajax-submit'),
                        set::disabled($defaultMergeType == 'fast' && $ppm->mergeBaseSHA != $ppm->mergeTargetSHA),
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
        ) : section
        (
            setClass('flex w-full mt-2 checkMerge'),
            div(setClass('py-6 border-l-4 border-r-4 border-danger')),
            div
            (
                setClass('flex flex-auto items-center pl-4 bg-danger bg-opacity-5 items-center'),
                span(setClass('text-danger font-bold'), $lang->ppm->checkFailed . ($checkMessage ? "({$checkMessage})" : '')),
            )
        ),
    ) : null,
    section
    (
        div
        (
            setClass('border px-4 h-12 flex items-center'),
            span(setClass('font-bold'), $lang->ppm->codeConflict),
            $hasConflict == 'yes' ? label(setClass('danger ml-4'), $lang->ppm->checkStatusList['fail']) : label(setClass('success ml-4'), $lang->ppm->checkStatusList['success']),
            div(setClass('flex flex-auto justify-end'), btn(setClass('ghost text-primary'), span(icon(setClass('mr-2'), 'about'), $lang->ppm->locateView)))
        ),
        div
        (
            setClass('border px-4 py-4'),
            setStyle(array('margin-top' => '-1px')),
            div
            (
                setClass('flex items-center'),
                $hasConflict == 'yes' ? icon(setClass('text-danger font-bold mr-1'), 'close') : icon(setClass('text-success font-bold mr-1'), 'check'),
                span("{$lang->ppm->hasConflict}: ", $lang->ppm->hasConflictList[$hasConflict]),
                div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->ppm->request}: {$lang->ppm->hasConflictList['no']})"))
            )
        )
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
            $reviewResult == 'rejected' ? label(setClass('danger ml-4'),  $lang->ppm->approvalStatusList[$reviewResult]) : null,
            $reviewResult == 'inProgress' ? label(setClass('secondary ml-4'),  $lang->ppm->approvalStatusList[$reviewResult]) : null
        ),
        div
        (
            setClass('border px-4 py-4'),
            setStyle(array('margin-top' => '-1px')),
            div
            (
                setClass('flex items-center py-1'),
                $reviewResult == 'approved' ? icon(setClass('text-success font-bold mr-1 reviewResultIcon'), 'check') : icon(setClass('text-danger font-bold mr-1 reviewResultIcon'), 'close'),
                span("{$lang->ppm->reviewStatus}: ", $lang->ppm->approvalStatusList[$reviewResult]),
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
    section
    (
        div
        (
            setClass('border px-4 h-12 flex items-center'),
            span(setClass('font-bold'), $lang->ppm->codeScan),
            $checkScan ? label(setClass('success ml-4'), $lang->ppm->checkStatusList['success']) : label(setClass('success ml-4'), $lang->ppm->checkStatusList['fail'])
        ),
        div
        (
            setClass('border px-4 py-4'),
            setStyle(array('margin-top' => '-1px')),
            div
            (
                setClass('flex items-center py-1'),
                $scanSevereIssue <= $config->ppm->scanSevereIssue ? icon(setClass('text-success font-bold mr-1'), 'check') : icon(setClass('text-danger font-bold mr-1'), 'close'),
                span("{$lang->ppm->scanSevereIssue}: ", $scanSevereIssue),
                div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->ppm->request}: ≤{$config->ppm->scanSevereIssue})"))
            ),
            div
            (
                setClass('flex items-center py-1'),
                $scanOrdinaryIssue <= $config->ppm->scanOrdinaryIssue ? icon(setClass('text-success font-bold mr-1'), 'check') : icon(setClass('text-warning font-bold mr-1'), 'about'),
                span("{$lang->ppm->scanOrdinaryIssue}: ", $scanOrdinaryIssue),
                div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->ppm->request}: ≤{$config->ppm->scanOrdinaryIssue})"))
            ),
            div
            (
                setClass('flex items-center py-1'),
                $scanPassRate >= $config->ppm->scanPassRate ? icon(setClass('text-success font-bold mr-1'), 'check') : icon(setClass('text-danger font-bold mr-1'), 'close'),
                span("{$lang->ppm->scanPassRate}: ", "{$scanPassRate} %"),
                div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->ppm->request}: {$config->ppm->scanPassRate}%)"))
            )
        )
    ),
    $pipelineBox
);
