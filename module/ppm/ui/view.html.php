<?php
declare(strict_types=1);
/**
 * The view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@chandao.com>
 * @package     ppm
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

$app->loadLang('reporeviewflow');
$entry        = count($diffs) ? $diffs[0]->fileName : '';
$currentEntry = $this->repo->encodePath($entry);
$fileInfo     = $entry ? pathinfo($entry) : array();
$showBug      = isset($showBug) ? $showBug : 0;
$objectID     = isset($objectID) ? $objectID : 0;
$tree         = $this->repo->getFileTree($repo, '', $diffs);
$oldRevision  = helper::safe64Encode($oldRevision);
$newRevision  = helper::safe64Encode($newRevision);
$diffLink     = $this->repo->createLink('diff', "repoID={$ppm->repoID}&objectID={$objectID}&entry=&oldrevision={oldRevision}&newRevision={newRevision}");

jsVar('diffs', $diffs);
jsVar('mrID', $ppm->id);
jsVar('tree', $tree);
jsVar('file', $currentEntry);
jsVar('entry', $entry);
jsVar('diffLink', $diffLink);
jsVar('urlParams', "repoID={$ppm->repoID}&objectID=$objectID&entry=%s&oldRevision=$oldRevision&newRevision=$newRevision&showBug=$showBug&encoding=$encoding");
jsVar('sseURL', "http://localhost:{$config->devops->gitfoxPort}/api/v2/spaces/{$repo->space}/events?App=zentao&Operator={$app->user->account}&Authorization={$gitfoxServer->token}");

h:css("#monacoTree .text-clip {overflow: visible;}");

$dropMenus = array();
if(common::hasPriv('repo', 'download')) $dropMenus[] = array('text' => $this->lang->repo->downloadDiff, 'icon' => 'download', 'url' => $this->repo->createLink('download', "repoID={$ppm->repoID}&path=$currentEntry&fromRevision=$oldRevision&toRevision=$newRevision&type=path"), 'target' => '_self');

$dropMenus[] = array('text' => $this->lang->repo->viewDiffList['inline'], 'icon' => 'snap-house', 'id' => 'inline', 'class' => 'inline-appose');
$dropMenus[] = array('text' => $this->lang->repo->viewDiffList['appose'], 'icon' => 'col-archive', 'id' => 'appose', 'class' => 'inline-appose');

$encoding      = empty($encoding) ? '' : $encoding;
$hasConflict   = empty($mergeCheckMessage->conflictFiles) ? 'no' : 'yes'; // 是否有代码冲突
$checkMessage  = zget($mergeCheckMessage, 'message', '');
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

$config->ppm->AICodeScore       = 6;   // 合格的AI评审代码分数
$config->ppm->AISevereIssue     = 0;   // 合格的AI评审高危问题
$config->ppm->AIOrdinaryIssue   = 5;   // 合格的AI评审一般问题
$config->ppm->approvalReviewer  = 2;   // 合格的人工评审审批人数
$config->ppm->doneReviewer      = 2;   // 合格的人工评审审批通过人数
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

$checkAI       = true;
$checkApproval = $approvalStatus == 'approved' && $approvalReviewer >= $config->ppm->approvalReviewer && $doneReviewer >= $config->ppm->doneReviewer;
$checkScan     = $scanSevereIssue <= $config->ppm->scanSevereIssue && $scanOrdinaryIssue <= $config->ppm->scanOrdinaryIssue && $scanPassRate >= $config->ppm->scanPassRate;

$basicItems = array();
$basicItems[] = item(set::name($lang->ppm->author),       zget($users, $ppm->createdBy));
$basicItems[] = item(set::name($lang->ppm->createdDate),  $ppm->createdDate);
$basicItems[] = item(set::name($lang->ppm->targetBranch), $ppm->targetBranch);
$basicItems[] = item(set::name($lang->ppm->sourceBranch), $ppm->sourceBranch);
$basicItems[] = item(set::name($lang->ppm->description),  !empty($ppm->desc) ? strip_tags($ppm->desc) : $lang->noData);

$mergeTypeList    = empty($flow) ? array('merge', 'squash', 'rebase', 'fast') : $flow->definition->reviewFlow->merge->options;
$defaultMergeType = empty($defaultMergeType) ? $mergeTypeList[0] : $defaultMergeType;
$mergeBtnItems    = array();
foreach($mergeTypeList as $mergeType)
{
    $mergeBtnItems[] = array
    (
        'id'        => $mergeType,
        'icon'      => $mergeType == $defaultMergeType ? 'check text-black' : 'docspace scale-50',
        'content'   => array('html' => "<div><span><strong>{$lang->reporeviewflow->mergeOptionList[$mergeType]}</strong></span><div style=' white-space: normal; text-overflow: clip; overflow: visible;'><p>{$lang->ppm->mergeTypeInfoList[$mergeType]}<p></div></div>"),
        'data-on'   => 'click',
        'data-call' => "loadMergeBtn('{$mergeType}')"
    );
}
if(!hasPriv('repo', 'diff')) unset($config->ppm->commitLogs->dtable->fieldList['id']['link']);

if(!in_array($app->user->account, array_keys($reviewers)) || $ppm->status != 'opened')
{
    $config->ppm->actions->view['mainActions'] = array_diff($config->ppm->actions->view['mainActions'], array('review'));
}

$actions = $this->loadModel('common')->buildOperateMenu($ppm);

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
            (   setID('mr-detail'),
                div
                (
                    setClass('py-1 title-header'),
                    span(setClass('text-lg text-clip font-bold'), "#{$ppm->id} {$ppm->title}"),
                    label(setClass('primary ml-4'), zget($lang->ppm->statusList, $ppm->status))
                ),
                div
                (
                    setClass('my-2 detail-header'),
                    span(html(sprintf($lang->ppm->MRHistory, zget($users, $ppm->createdBy), $ppm->createdDate, $ppm->sourceBranch, $commitPager->recTotal, $ppm->targetBranch)))
                ),
                div
                (
                    tabs
                    (
                        set::headerClass('border-b mr-menu'),
                        tabPane
                        (
                            set::key('basic'),
                            set::title($lang->ppm->mergeInfo),
                            set::active($type == 'basic'),
                            div
                            (
                                $ppm->status == 'opened' ? div
                                (
                                    $hasConflict == 'no' && $reviewResult == 'approved' && !$checkMessage ? section
                                    (
                                        setClass('flex w-full checkMerge'),
                                        div(setClass('py-6 border-l-4 border-r-4 border-success')),
                                        div
                                        (
                                            setClass('flex flex-auto items-center pl-4 bg-success bg-opacity-5 items-center success-box'),
                                            set::style(array('justify-content' => 'space-between')),
                                            div(span(setClass('text-success font-bold'), $lang->ppm->checkSuccess)),
                                            hasPriv('ppm', 'merge') && !empty($mergeBtnItems) && $ppm->status == 'opened' ? div(btnGroup
                                            (
                                                setClass('merge-btn-group'),
                                                btn
                                                (
                                                    setClass('btn primary ajax-submit'),
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
                                section
                                (
                                    div
                                    (
                                        setClass('border px-4 h-12 flex items-center'),
                                        span(setClass('font-bold'), $lang->ppm->AIReview),
                                        $checkAI ? label(setClass('success ml-4'), $lang->ppm->checkStatusList['success']) : label(setClass('warning ml-4'), $lang->ppm->checkStatusList['wait'])
                                    ),
                                    div
                                    (
                                        setClass('border px-4 py-4'),
                                        setStyle(array('margin-top' => '-1px')),
                                        div
                                        (
                                            setClass('flex items-center py-1'),
                                            $AICodeScore < $config->ppm->AICodeScore ? icon(setClass('text-warning font-bold mr-1'), 'about') : icon(setClass('text-success font-bold mr-1'), 'check'),
                                            span("{$lang->ppm->AICodeScore}: ", $AICodeScore),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->ppm->request}: ≥{$config->ppm->AICodeScore})"))
                                        ),
                                        div
                                        (
                                            setClass('flex items-center py-1'),
                                            $AISevereIssue > $config->ppm->AISevereIssue ? icon(setClass('text-warning font-bold mr-1'), 'about') : icon(setClass('text-success font-bold mr-1'), 'check'),
                                            span("{$lang->ppm->AISevereIssue}: ", $AISevereIssue),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->ppm->request}: ≤{$config->ppm->AISevereIssue})"))
                                        ),
                                        div
                                        (
                                            setClass('flex items-center py-1'),
                                            $AIOrdinaryIssue > $config->ppm->AIOrdinaryIssue ? icon(setClass('text-warning font-bold mr-1'), 'about') : icon(setClass('text-success font-bold mr-1'), 'check'),
                                            span("{$lang->ppm->AIOrdinaryIssue}: ", "$AIOrdinaryIssue"),
                                            div(setClass('flex flex-auto justify-end'), span(setClass('mr-2'), "({$lang->ppm->request}: ≤{$config->ppm->AIOrdinaryIssue})"))
                                        )
                                    )
                                ),
                                section
                                (
                                    div
                                    (
                                        setClass('border px-4 h-12 flex items-center'),
                                        span(setClass('font-bold'), $lang->ppm->manualReview),
                                        $reviewResult == 'approved' ? label(setClass('success ml-4'), $lang->ppm->approvalStatusList[$reviewResult]) : null,
                                        $reviewResult == 'rejected' ? label(setClass('danger ml-4'),  $lang->ppm->approvalStatusList[$reviewResult]) : null,
                                        $reviewResult == 'inProgress' ? label(setClass('secondary ml-4'),  $lang->ppm->approvalStatusList[$reviewResult]) : null,
                                        div(setClass('flex flex-auto justify-end'), btn(setClass('ghost text-primary'), span(icon(setClass('mr-2'), 'about'), $lang->ppm->locateView)))
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
                                            count($reviewers) >= $minReviewers ? icon(setClass('text-success font-bold mr-1 reviewerCountIcon'), 'check') : icon(setClass('text-success font-bold mr-1 reviewerCountIcon'), 'check'),
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
                            )
                        ),
                        tabPane
                        (
                            set::key('bug'),
                            set::title($lang->ppm->issueList . " ({$bugPager->recTotal})"),
                            set::active($type == 'bug'),
                            dtable
                            (
                                set::id('bugs'),
                                set::cols($config->ppm->bug->dtable->fieldList),
                                set::data(array_values($bugs)),
                                set::loadPartial(true),
                                set::footPager(usePager('bugPager'))
                            )
                        ),
                        tabPane
                        (
                            set::key('commit'),
                            set::title($lang->ppm->commitLogs . " ({$commitPager->recTotal})"),
                            set::active($type == 'commit'),
                            dtable
                            (
                                set::id('commitLogs'),
                                set::cols($config->ppm->commitLogs->dtable->fieldList),
                                set::data(array_values($commitLogs)),
                                set::loadPartial(true),
                                set::footPager(usePager('commitPager'))
                            )
                        ),
                        tabPane
                        (
                            set::key('files'),
                            set::title($lang->ppm->changeFiles . ' (' . count($diffs) . ')'),
                            set::active($type == 'files'),
                            empty($diffs) ? p(setClass('detail-content'), $lang->ppm->noChanges) : div(
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
                            set::title($lang->ppm->linkedObject . " ({$objectPager->recTotal})"),
                            set::active($type == 'object'),
                            dtable
                            (
                                set::id('linkObjects'),
                                set::cols($config->ppm->createCheck->linkObject->dtable->fieldList),
                                set::data(array_values($linkObjects)),
                                set::loadPartial(true),
                                set::onRenderCell(jsRaw('window.renderObjectCell')),
                                set::footPager(usePager('objectPager'))
                            )
                        )
                    )
                )
            )
        ),
        center
        (
            setClass('pt-6 sticky bottom-0 mr-toolbar'),
            floatToolbar
            (
                set::prefix(array(array('icon' => 'back', 'text' => $lang->goback, 'hint' => $lang->goback, 'data-back' => 'ppm-browse', 'class' => 'open-url'))),
                set::main($actions['mainActions']),
                set::object($ppm)
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
        tabs(setID('basic'), setClass('canvas rounded shadow py-2 px-4'), tabPane(set::title($lang->ppm->basicInfo), tableData($basicItems))),
        div
        (
            setID('reviewer'),
            h::js('loadTarget("' . createLink('ppm', 'ajaxGetReviewers', "ppmID={$ppm->id}") . '", "#reviewer")'),
        ),
        div
        (
            setID('mr-history'),
            history(setClass('mt-2 border-0 canvas shadow-sm mr-history'), setStyle(array('box-shadow' => 'var(--shadow-none)')))
        )
    )
);
