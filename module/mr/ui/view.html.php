<?php
declare(strict_types=1);
/**
 * The view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@chandao.com>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

$hasConflict = 'yes'; // 是否有代码冲突

$AICodeScore     = 3;     // AI评审代码分数
$AISevereIssue   = 0;     // AI评审高危问题
$AIOrdinaryIssue = 3;     // AI评审一般问题

$approvalStatus   = 'approved'; // 审批流审批结果
$approvalReviewer = 3;          // 审批人数
$doneReviewer     = 3;          // 审批通过人数

$scanSevereIssue   = 0;   // 代码扫描高危问题
$scanOrdinaryIssue = 1;   // 代码扫描一般问题
$scanPassRate      = 100; // 代码扫描安全门禁通过率

$config->mr->AICodeScore       = 6;   // 合格的AI评审代码分数
$config->mr->AISevereIssue     = 0;   // 合格的AI评审高危问题
$config->mr->AIOrdinaryIssue   = 5;   // 合格的AI评审一般问题
$config->mr->approvalReviewer  = 2;   // 合格的人工评审审批人数
$config->mr->doneReviewer      = 2;   // 合格的人工评审审批通过人数
$config->mr->scanSevereIssue   = 0;   // 合格的代码扫描高危问题
$config->mr->scanOrdinaryIssue = 3;   // 合格的代码扫描一般问题
$config->mr->scanPassRate      = 100; // 合格的代码扫描安全门禁通过率

$pipelines = array();
$pipeline1 = new stdclass();
$pipeline1->title  = 'a0001';
$pipeline1->status = 'success';
$pipelines[] = $pipeline1;

$pipeline2 = new stdclass();
$pipeline2->title  = 'hello world';
$pipeline2->status = 'failed';
$pipelines[] = $pipeline2;

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
            span(setClass('font-bold'), "{$lang->mr->pipeline}: {$pipeline->title}"),
            $pipeline->status == 'success' ? label(setClass('success ml-4'), $lang->mr->checkStatusList['success']) : label(setClass('danger ml-4'), $lang->mr->checkStatusList['fail'])
        ),
        div
        (
            setClass('border px-4 py-4'),
            setStyle(array('margin-top' => '-1px')),
            div
            (
                setClass('flex items-center py-1'),
                $pipeline->status == 'success' ? icon(setClass('text-success font-bold mr-1'), 'check') : icon(setClass('text-danger font-bold mr-1'), 'close'),
                span("{$lang->mr->runResult}: ", $lang->mr->pipelineStatus[$pipeline->status]),
                div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->mr->request}: {$lang->mr->pipelineStatus['success']})"))
            )
        )
    );
}

$checkAI       = $AICodeScore >= $config->mr->AICodeScore && $AISevereIssue <= $config->mr->AISevereIssue && $AIOrdinaryIssue <= $config->mr->AIOrdinaryIssue;
$checkApproval = $approvalStatus == 'approved' && $approvalReviewer >= $config->mr->approvalReviewer && $doneReviewer >= $config->mr->doneReviewer;
$checkScan     = $scanSevereIssue <= $config->mr->scanSevereIssue && $scanOrdinaryIssue <= $config->mr->scanOrdinaryIssue && $scanPassRate >= $config->mr->scanPassRate;

$basicItems = array();
$basicItems[] = item(set::name($lang->mr->author),       zget($users, $mr->createdBy));
$basicItems[] = item(set::name($lang->mr->createdDate),  $mr->createdDate);
$basicItems[] = item(set::name($lang->mr->targetBranch), $mr->targetBranch);
$basicItems[] = item(set::name($lang->mr->sourceBranch), $mr->sourceBranch);
$basicItems[] = item(set::name($lang->mr->description),  !empty($mr->description) ? $mr->description : $lang->noData);

$reviewers = array('admin', 'admin', 'admin');
$reviewItems = array();
foreach($reviewers as $reviewer)
{
    $reviewItems[] = div
    (
        setClass('bg-gray-100 my-1 py-2 px-4'),
        div
        (
            setClass('flex items-center'),
            icon(setClass('text-lg'), 'account'),
            span(setClass('ml-2 text-lg'), zget($users, $reviewer)),
            label(setClass('success ml-4 size-sm'), $lang->mr->approvalStatusList['approved']),
            div(setClass('flex flex-auto justify-end'), btn(setClass('ghost size-sm'), icon(setClass('text-primary'), 'trash')))
        ),
        div
        (
            setClass('mt-2 pl-6'),
            span("评审意见: ", '这里是评审意见')
        )
    );
}

$actions = $this->loadModel('common')->buildOperateMenu($mr);

div
(
    setClass('detail-body rounded flex gap-1'),
    div
    (
        setClass('col gap-1 grow min-w-0'),
        sectionList
        (
            div
            (
                div
                (
                    setClass('py-1'),
                    span(setClass('text-lg text-clip font-bold'), "#{$mr->id} dev(branch):{$mr->title}"),
                    label(setClass('primary ml-4'), '开启中')
                ),
                div
                (
                    setClass('my-2'),
                    span(html(sprintf($lang->mr->MRHistory, zget($users, $mr->createdBy), $mr->createdDate, $mr->sourceBranch, '3', $mr->targetBranch)))
                ),
                div
                (
                    tabs
                    (
                        set::headerClass('border-b'),
                        tabPane
                        (
                            set::key('basic'),
                            set::title($lang->mr->mergeInfo),
                            set::active($type == 'basic'),
                            div
                            (
                                $hasConflict == 'no' && $checkAI && $checkApproval && $checkScan && $checkPipeline ? section
                                (
                                    setClass('flex w-full'),
                                    div(setClass('py-6 border-l-4 border-r-4 border-success')),
                                    div
                                    (
                                        setClass('flex flex-auto items-center pl-4 bg-success bg-opacity-5 items-center'),
                                        span(setClass('text-success font-bold'), $lang->mr->checkSuccess)
                                    )
                                ) : section
                                (
                                    setClass('flex w-full mt-2'),
                                    div(setClass('py-6 border-l-4 border-r-4 border-danger')),
                                    div
                                    (
                                        setClass('flex flex-auto items-center pl-4 bg-danger bg-opacity-5 items-center'),
                                        span(setClass('text-danger font-bold'), $lang->mr->checkFailed)
                                    )
                                ),
                                section
                                (
                                    div
                                    (
                                        setClass('border px-4 h-12 flex items-center'),
                                        span(setClass('font-bold'), $lang->mr->codeConflict),
                                        $hasConflict == 'yes' ? label(setClass('danger ml-4'), $lang->mr->checkStatusList['fail']) : label(setClass('success ml-4'), $lang->mr->checkStatusList['success']),
                                        div(setClass('flex flex-auto justify-end'), btn(setClass('ghost text-primary'), span(icon(setClass('mr-2'), 'about'), $lang->mr->locateView)))
                                    ),
                                    div
                                    (
                                        setClass('border px-4 py-4'),
                                        setStyle(array('margin-top' => '-1px')),
                                        div
                                        (
                                            setClass('flex items-center'),
                                            $hasConflict == 'yes' ? icon(setClass('text-danger font-bold mr-1'), 'close') : icon(setClass('text-success font-bold mr-1'), 'check'),
                                            span("{$lang->mr->hasConflict}: ", $lang->mr->hasConflictList[$hasConflict]),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->mr->request}: {$lang->mr->hasConflictList['no']})"))
                                        )
                                    )
                                ),
                                section
                                (
                                    div
                                    (
                                        setClass('border px-4 h-12 flex items-center'),
                                        span(setClass('font-bold'), $lang->mr->AIReview),
                                        $checkAI ? label(setClass('success ml-4'), $lang->mr->checkStatusList['success']) : label(setClass('warning ml-4'), $lang->mr->checkStatusList['wait'])
                                    ),
                                    div
                                    (
                                        setClass('border px-4 py-4'),
                                        setStyle(array('margin-top' => '-1px')),
                                        div
                                        (
                                            setClass('flex items-center py-1'),
                                            $AICodeScore < $config->mr->AICodeScore ? icon(setClass('text-warning font-bold mr-1'), 'about') : icon(setClass('text-success font-bold mr-1'), 'check'),
                                            span("{$lang->mr->AICodeScore}: ", $AICodeScore),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->mr->request}: ≥{$config->mr->AICodeScore})"))
                                        ),
                                        div
                                        (
                                            setClass('flex items-center py-1'),
                                            $AISevereIssue > $config->mr->AISevereIssue ? icon(setClass('text-warning font-bold mr-1'), 'about') : icon(setClass('text-success font-bold mr-1'), 'check'),
                                            span("{$lang->mr->AISevereIssue}: ", $AISevereIssue),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->mr->request}: ≤{$config->mr->AISevereIssue})"))
                                        ),
                                        div
                                        (
                                            setClass('flex items-center py-1'),
                                            $AIOrdinaryIssue > $config->mr->AIOrdinaryIssue ? icon(setClass('text-warning font-bold mr-1'), 'about') : icon(setClass('text-success font-bold mr-1'), 'check'),
                                            span("{$lang->mr->AIOrdinaryIssue}: ", "$AIOrdinaryIssue"),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->mr->request}: ≤{$config->mr->AIOrdinaryIssue})"))
                                        )
                                    )
                                ),
                                section
                                (
                                    div
                                    (
                                        setClass('border px-4 h-12 flex items-center'),
                                        span(setClass('font-bold'), $lang->mr->review),
                                        $checkApproval ? label(setClass('success ml-4'), $lang->mr->checkStatusList['success']) : label(setClass('danger ml-4'), $lang->mr->checkStatusList['fail']),
                                        div(setClass('flex flex-auto justify-end'), btn(setClass('ghost text-primary'), span(icon(setClass('mr-2'), 'about'), $lang->mr->locateView)))
                                    ),
                                    div
                                    (
                                        setClass('border px-4 py-4'),
                                        setStyle(array('margin-top' => '-1px')),
                                        div
                                        (
                                            setClass('flex items-center py-1'),
                                            $approvalStatus == 'approved' ? icon(setClass('text-success font-bold mr-1'), 'check') : icon(setClass('text-danger font-bold mr-1'), 'close'),
                                            span("{$lang->mr->approvalStatus}: ", $lang->mr->approvalStatusList[$approvalStatus]),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->mr->request}: {$lang->mr->approvalStatusList['approved']})"))
                                        ),
                                        div
                                        (
                                            setClass('flex items-center py-1'),
                                            $approvalReviewer >= $config->mr->approvalReviewer ? icon(setClass('text-success font-bold mr-1'), 'check') : icon(setClass('text-danger font-bold mr-1'), 'close'),
                                            span("{$lang->mr->approvalReviewer}: ", $approvalReviewer),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->mr->request}: ≥{$config->mr->approvalReviewer})"))
                                        ),
                                        div
                                        (
                                            setClass('flex items-center py-1'),
                                            $doneReviewer >= $config->mr->doneReviewer ? icon(setClass('text-success font-bold mr-1'), 'check') : icon(setClass('text-danger font-bold mr-1'), 'close'),
                                            span("{$lang->mr->doneReviewer}: ", $doneReviewer),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->mr->request}: ≥{$config->mr->doneReviewer})"))
                                        )
                                    )
                                ),
                                section
                                (
                                    div
                                    (
                                        setClass('border px-4 h-12 flex items-center'),
                                        span(setClass('font-bold'), $lang->mr->codeScan),
                                        $checkScan ? label(setClass('success ml-4'), $lang->mr->checkStatusList['success']) : label(setClass('success ml-4'), $lang->mr->checkStatusList['fail'])
                                    ),
                                    div
                                    (
                                        setClass('border px-4 py-4'),
                                        setStyle(array('margin-top' => '-1px')),
                                        div
                                        (
                                            setClass('flex items-center py-1'),
                                            $scanSevereIssue <= $config->mr->scanSevereIssue ? icon(setClass('text-success font-bold mr-1'), 'check') : icon(setClass('text-danger font-bold mr-1'), 'close'),
                                            span("{$lang->mr->scanSevereIssue}: ", $scanSevereIssue),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->mr->request}: ≤{$config->mr->scanSevereIssue})"))
                                        ),
                                        div
                                        (
                                            setClass('flex items-center py-1'),
                                            $scanOrdinaryIssue <= $config->mr->scanOrdinaryIssue ? icon(setClass('text-success font-bold mr-1'), 'check') : icon(setClass('text-warning font-bold mr-1'), 'about'),
                                            span("{$lang->mr->scanOrdinaryIssue}: ", $scanOrdinaryIssue),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->mr->request}: ≤{$config->mr->scanOrdinaryIssue})"))
                                        ),
                                        div
                                        (
                                            setClass('flex items-center py-1'),
                                            $scanPassRate >= $config->mr->scanPassRate ? icon(setClass('text-success font-bold mr-1'), 'check') : icon(setClass('text-danger font-bold mr-1'), 'close'),
                                            span("{$lang->mr->scanPassRate}: ", "{$scanPassRate} %"),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->mr->request}: {$config->mr->scanPassRate}%)"))
                                        )
                                    )
                                ),
                                $pipelineBox
                            )
                        ),
                        tabPane
                        (
                            set::key('bug'),
                            set::title("问题清单 ({$bugPager->recTotal})"),
                            set::active($type == 'bug'),
                            dtable
                            (
                                set::id('bugs'),
                                set::cols($config->mr->bug->dtable->fieldList),
                                set::data(array_values($bugs)),
                                set::footPager(usePager('bugPager', '', array
                                (
                                    'recPerPage'  => $bugPager->recPerPage,
                                    'recTotal'    => $bugPager->recTotal,
                                    'linkCreator' => createLink('mr', 'view', "MRID={$mr->id}&type=bug&recTotal={$bugPager->recTotal}&recPerPage={recPerPage}&page={page}")
                                )))
                            )
                        ),
                        tabPane
                        (
                            set::key('commit'),
                            set::title("提交记录 ({$commitPager->recTotal})"),
                            set::active($type == 'commit'),
                            dtable
                            (
                                set::id('commitLogs'),
                                set::cols($config->mr->commitLogs->dtable->fieldList),
                                set::data(array_values($commitLogs)),
                                set::footPager(usePager('commitPager', '', array
                                (
                                    'recPerPage'  => $commitPager->recPerPage,
                                    'recTotal'    => $commitPager->recTotal,
                                    'linkCreator' => createLink('mr', 'view', "MRID={$mr->id}&type=commit&recTotal={$commitPager->recTotal}&recPerPage={recPerPage}&page={page}")
                                )))
                            )
                        ),
                        tabPane
                        (
                            set::key('files'),
                            set::title('变更的文件 (2)'),
                            set::active($type == 'files'),
                            sectionList
                            (
                                section('asd')
                            )
                        ),
                        tabPane
                        (
                            set::key('pipeline'),
                            set::title('流水线'),
                            set::active($type == 'pipeline'),
                            sectionList
                            (
                                section('asd')
                            )
                        ),
                        tabPane
                        (
                            set::key('related'),
                            set::title('关联项'),
                            sectionList
                            (
                                section('asd')
                            )
                        )
                    )
                )
            )
        ),
        center
        (
            setClass('pt-6 sticky bottom-0'),
            floatToolbar
            (
                set::prefix(array(array('icon' => 'back', 'text' => $lang->goback, 'hint' => $lang->goback, 'data-back' => 'mr-browse', 'class' => 'open-url'))),
                set::main($actions['mainActions']),
                set::object($mr)
            )
        ),
    ),
    div
    (
        setClass('w-2'),
        setStyle('background', 'var(--zt-page-bg)')
    ),
    div
    (
        setStyle(array('width' => '370px')),
        setClass('detail-side flex-none relative'),
        tabs(setID('basic'),    setClass('canvas rounded shadow py-2 px-4'),      tabPane(set::title($lang->mr->basicInfo), tableData($basicItems))),
        tabs(setID('reviewer'), set::headerBtn(array('title' => '添加', 'icon' => 'plus', 'class' => 'ghost text-primary', 'url' => createLink('repo', 'createBranch', 'objectID=0&repoID=1'))), setClass('canvas rounded shadow py-2 px-4 mt-2'), tabPane(set::title($lang->mr->reviewer),  tableData($reviewItems))),
        history(setClass('mt-2 border-0 canvas shadow-sm'), setStyle(array('box-shadow' => 'var(--shadow-none)')))
    )
);
