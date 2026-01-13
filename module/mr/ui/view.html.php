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
$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

$entry        = count($diffs) ? $diffs[0]->fileName : '';
$currentEntry = $this->repo->encodePath($entry);
$fileInfo     = $entry ? pathinfo($entry) : array();
$showBug      = isset($showBug) ? $showBug : 0;
$objectID     = isset($objectID) ? $objectID : 0;
$tree         = $this->repo->getFileTree($repo, '', $diffs);
$oldRevision  = helper::safe64Encode($oldRevision);
$newRevision  = helper::safe64Encode($newRevision);
$diffLink     = $this->repo->createLink('diff', "repoID={$mr->repoID}&objectID={$objectID}&entry=&oldrevision={oldRevision}&newRevision={newRevision}");

jsVar('diffs', $diffs);
jsVar('mrID', $mr->id);
jsVar('tree', $tree);
jsVar('file', $currentEntry);
jsVar('entry', $entry);
jsVar('diffLink', $diffLink);
jsVar('urlParams', "repoID={$mr->repoID}&objectID=$objectID&entry=%s&oldRevision=$oldRevision&newRevision=$newRevision&showBug=$showBug&encoding=$encoding");

h:css("#monacoTree .text-clip {overflow: visible;}");

$dropMenus = array();
if(common::hasPriv('repo', 'download')) $dropMenus[] = array('text' => $this->lang->repo->downloadDiff, 'icon' => 'download', 'url' => $this->repo->createLink('download', "repoID={$mr->repoID}&path=$currentEntry&fromRevision=$oldRevision&toRevision=$newRevision&type=path"), 'target' => '_self');

$dropMenus[] = array('text' => $this->lang->repo->viewDiffList['inline'], 'icon' => 'snap-house', 'id' => 'inline', 'class' => 'inline-appose');
$dropMenus[] = array('text' => $this->lang->repo->viewDiffList['appose'], 'icon' => 'col-archive', 'id' => 'appose', 'class' => 'inline-appose');

$encoding      = empty($encoding) ? '' : $encoding;
$hasConflict   = empty($mergeCheckMessage->conflictFiles) ? 'no' : 'yes'; // 是否有代码冲突
$conflictFiles = zget($mergeCheckMessage, 'conflictFiles', array());
$minReviewers  = empty($flow) ? 0 : $flow->definition->reviewFlow->approvals->minReviewers;

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

$checkAI       = true;
$checkApproval = $approvalStatus == 'approved' && $approvalReviewer >= $config->mr->approvalReviewer && $doneReviewer >= $config->mr->doneReviewer;
$checkScan     = $scanSevereIssue <= $config->mr->scanSevereIssue && $scanOrdinaryIssue <= $config->mr->scanOrdinaryIssue && $scanPassRate >= $config->mr->scanPassRate;

$basicItems = array();
$basicItems[] = item(set::name($lang->mr->author),       zget($users, $mr->createdBy));
$basicItems[] = item(set::name($lang->mr->createdDate),  $mr->createdDate);
$basicItems[] = item(set::name($lang->mr->targetBranch), $mr->targetBranch);
$basicItems[] = item(set::name($lang->mr->sourceBranch), $mr->sourceBranch);
$basicItems[] = item(set::name($lang->mr->description),  !empty($mr->desc) ? strip_tags($mr->desc) : $lang->noData);

$actions = $this->loadModel('common')->buildOperateMenu($mr);

div
(
    setID('mr-view'),
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
                    setClass('py-1 title-header'),
                    span(setClass('text-lg text-clip font-bold'), "#{$mr->id} {$mr->title}"),
                    label(setClass('primary ml-4'), zget($lang->mr->statusList, $mr->status))
                ),
                div
                (
                    setClass('my-2 detail-header'),
                    span(html(sprintf($lang->mr->MRHistory, zget($users, $mr->createdBy), $mr->createdDate, $mr->sourceBranch, $commitPager->recTotal, $mr->targetBranch)))
                ),
                div
                (
                    tabs
                    (
                        set::headerClass('border-b mr-menu'),
                        tabPane
                        (
                            set::key('basic'),
                            set::title($lang->mr->mergeInfo),
                            set::active($type == 'basic'),
                            div
                            (
                                $hasConflict == 'no'? section
                                (
                                    setClass('flex w-full checkMerge'),
                                    div(setClass('py-6 border-l-4 border-r-4 border-success')),
                                    div
                                    (
                                        setClass('flex flex-auto items-center pl-4 bg-success bg-opacity-5 items-center'),
                                        span(setClass('text-success font-bold'), $lang->mr->checkSuccess)
                                    )
                                ) : section
                                (
                                    setClass('flex w-full mt-2 checkMerge'),
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
                                        label(setID('approvalLabel'), setClass('success ml-4'), $lang->mr->checkStatusList['success']),
                                        div(setClass('flex flex-auto justify-end'), btn(setClass('ghost text-primary'), span(icon(setClass('mr-2'), 'about'), $lang->mr->locateView)))
                                    ),
                                    div
                                    (
                                        setClass('border px-4 py-4'),
                                        setStyle(array('margin-top' => '-1px')),
                                        div
                                        (
                                            setClass('flex items-center py-1'),
                                            icon(setClass('text-success font-bold mr-1 reviewResultIcon'), 'check'),
                                            span(setClass('reviewResult')),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->mr->request}: {$lang->mr->approvalStatusList['approved']})"))
                                        ),
                                        div
                                        (
                                            setClass('flex items-center py-1' . ($minReviewers == 0 ? ' hidden' : '')),
                                            icon(setClass('text-success font-bold mr-1 reviewerCountIcon'), 'check'),
                                            span(setClass('reviewerCount')),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->mr->request}: ≥{$config->mr->approvalReviewer})"))
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
                            set::title($lang->mr->issueList . " ({$bugPager->recTotal})"),
                            set::active($type == 'bug'),
                            dtable
                            (
                                set::id('bugs'),
                                set::cols($config->mr->bug->dtable->fieldList),
                                set::data(array_values($bugs)),
                                set::loadPartial(true),
                                set::footPager(usePager('bugPager'))
                            )
                        ),
                        tabPane
                        (
                            set::key('commit'),
                            set::title($lang->mr->commitLogs . " ({$commitPager->recTotal})"),
                            set::active($type == 'commit'),
                            dtable
                            (
                                set::id('commitLogs'),
                                set::cols($config->mr->commitLogs->dtable->fieldList),
                                set::data(array_values($commitLogs)),
                                set::loadPartial(true),
                                set::footPager(usePager('commitPager'))
                            )
                        ),
                        tabPane
                        (
                            set::key('files'),
                            set::title($lang->mr->changeFiles . ' (' . count($diffs) . ')'),
                            set::active($type == 'files'),
                            empty($diffs) ? p(setClass('detail-content'), $lang->mr->noChanges) : div(
                                setID('diff-sidebar-left'),
                                div
                                (
                                    set::id('fileTabs'),
                                    tabs
                                    (
                                        set::id('monacoTabs'),
                                        set::className('relative'),
                                        div(setStyle(array('position' => 'absolute', 'width' => '100%', 'height' => '35px', 'background' => '#efefef', 'top' => '0px'))),
                                        tabPane
                                        (
                                            set::title($fileInfo['basename']),
                                            set::active(true),
                                            set::key('tab-' . str_replace('=', '-', $currentEntry)),
                                            to::suffix
                                            (
                                                icon
                                                (
                                                    'close',
                                                    set::className('monaco-close')
                                                )
                                            ),
                                            div(set::id('tab-' . $currentEntry))
                                        ),
                                        dropdown
                                        (
                                            set::arrow(false),
                                            set::staticMenu(true),
                                            btn
                                            (
                                                setClass('ghost text-black pull-right absolute top-0 right-0 z-10 monaco-dropmenu'),
                                                set::icon('ellipsis-v rotate-90')
                                            ),
                                            set::items
                                            (
                                                $dropMenus
                                            )
                                        ),
                                        div(set::className('absolute top-0 left-0 z-20 arrow-left btn-left'), icon('chevron-left')),
                                        div(set::className('absolute top-0 right-0 z-20 arrow-right btn-right'), icon('chevron-right'))
                                    )
                                ),
                                sidebar
                                (
                                    set::maxWidth(800),
                                    treeEditor
                                    (
                                        set::id('monacoTree'),
                                        set::items($tree),
                                        set::canSplit(false),
                                        set::collapsedIcon('folder'),
                                        set::expandedIcon('folder-open'),
                                        set::normalIcon('file-text-alt'),
                                        set::selected($currentEntry),
                                        set::onClickItem(jsRaw('window.treeClick'))
                                    )
                                ),
                                on::click('.inline-appose')->call('inlineAppose'),
                                on::click('#monacoTabs .monaco-close')->call('closeTab', jsRaw('this')),
                                on::click('#monacoTabs .menu-item a')->call('changeDiffType', jsRaw('this')),
                                a(set::className('iframe'), setData('size', '1200px'), setData('toggle', 'modal'), set::id('linkObject'))
                            )
                        ),
                        tabPane
                        (
                            set::key('pipeline'),
                            set::title($lang->pipeline->common),
                            set::active($type == 'pipeline'),
                            sectionList()
                        ),
                        tabPane
                        (
                            set::key('related'),
                            set::title($lang->mr->linkedObject . " ({$objectPager->recTotal})"),
                            set::active($type == 'object'),
                            dtable
                            (
                                set::id('linkObjects'),
                                set::cols($config->mr->createCheck->linkObject->dtable->fieldList),
                                set::data(array_values($linkObjects)),
                                set::loadPartial(true),
                                set::footPager(usePager('objectPager'))
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
        tabs(setID('basic'), setClass('canvas rounded shadow py-2 px-4'), tabPane(set::title($lang->mr->basicInfo), tableData($basicItems))),
        div(setID('reviewer')),
        history(setClass('mt-2 border-0 canvas shadow-sm'), setStyle(array('box-shadow' => 'var(--shadow-none)')))
    )
);
