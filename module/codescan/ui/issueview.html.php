<?php
declare(strict_types=1);
/**
 * The issue view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('codescanLang', $lang->codescan);
$app->loadLang('bug');
if(!empty($repoID)) dropmenu(set::objectID($repoID), set::tab('repo'));
$canViewRule = hasPriv('codescan', 'view');
$canViewBug  = hasPriv('bug', 'view');

$setItems = array();
if(!empty($fileIssueList))
{
    foreach($fileIssueList as $fileIssue)
    {
        $serviceRepoKey = (int)$fileIssue->repoID;
        $issueRepoID    = isset($gitFoxRepos[$serviceRepoKey]) ? $serviceRepoKey : (int)zget($repoPair, $fileIssue->repoID, 0);
        if(!$issueRepoID && !empty($repoID)) $issueRepoID = (int)$repoID;

        $setItems[] = div
        (
            setClass('mx-2 flex-auto h-16 mb-2'),
            set::active($fileIssue->id == $issueID),
            col
            (
                setClass('setting-box cursor-pointer border border-hover rounded-md px-2 h-16 open-url'),
                $fileIssue->id == $issueID ? setClass('secondary-pale') : null,
                set('data-id', $fileIssue->id),
                set('data-url', inLink('issueview', "issueID={$fileIssue->id}&repoID={$issueRepoID}")),
                div
                (
                    p
                    (
                        label(setClass('ml-1 mr-2 size-sm rounded'), $fileIssue->id),
                        setClass('text-md my-2 line-clamp-2'),
                        set::title($fileIssue->content),
                        html($fileIssue->content)
                    )
                )
            )
        );
    }
}

$startCol = 0;
if(!empty($issue->payload->snippet))
{
    preg_match('/^\s*/', $issue->payload->snippet, $matches);
    $startCol = strlen($matches[0]) + 1;
}

$ruleContent = common::checkNotCN() ? zget($rule, 'contentEn') : zget($rule, 'content');

detailHeader
(
    to::prefix
    (
        backBtn
        (
            set::icon('back'),
            set::type('secondary'),
            set::back('codescan-issue,codescan-taskview'),
            $lang->goback
        ),
        label(setClass('flex-none'), $issue->id),
        label(setClass('flex-none'), basename($issue->path)),
        entityLabel
        (
            set::level(1),
            set::text($issue->content)
        ),
    )
);

div
(
    sectionList
    (
        setID('basicInfo'),
        setClass('border-r'),
        set::title($lang->basicInfo),
        div
        (
            setClass('flex justify-between'),
            div
            (
                setClass('w-1/2'),
                h4
                (
                    setClass('mr-4 clip'),
                    set::title($rule->name),
                    btn
                    (
                        setClass('secondary-pale mr-2'),
                        set::hint($lang->codescan->view),
                        $canViewRule ? set::url(createLink('codescan', 'view', "ruleID={$issue->ruleID}")) : set::disabled(true),
                        $canViewRule ? setData('toggle', 'modal') : null,
                        $canViewRule ? setData('size', 'lg') : null,
                        $rule->lang . ':' . $rule->id
                    ),
                    $rule->name
                ),
                div
                (
                    setClass('m-1'),
                    label(setClass('gray-500-pale mr-2 size-lg'), set::title(zget($lang->codescan->typeList, $issue->ruleType)), zget($lang->codescan->typeList, $issue->ruleType)),
                    !empty($issue->priority) && $issue->priority == 'low'    ? label(setClass('gray-500-pale mr-2 size-lg'), set::title(zget($lang->codescan->severityList, $issue->priority, '')), zget($lang->codescan->severityList, $issue->priority, '')) : null,
                    !empty($issue->priority) && $issue->priority == 'medium' ? label(setClass('warning-pale mr-2 size-lg'),  set::title(zget($lang->codescan->severityList, $issue->priority, '')), zget($lang->codescan->severityList, $issue->priority, '')) : null,
                    !empty($issue->priority) && $issue->priority == 'high'   ? label(setClass('danger-pale mr-2 size-lg'),   set::title(zget($lang->codescan->severityList, $issue->priority, '')), zget($lang->codescan->severityList, $issue->priority, '')) : null,
                    label(setClass('gray-500-pale mr-2 size-lg'), set::title(zget($lang->codescan->issueStatusList, $issue->status)), zget($lang->codescan->issueStatusList, $issue->status)),
                    !empty($issue->bugID) ?
                    btn
                    (
                        icon('bug'),
                        setClass('danger-pale'),
                        set::size('sm'),
                        ' #' . $issue->bugID,
                        set::hint($lang->bug->view),
                        $canViewBug ? set::url(createLink('bug', 'view', "bugID={$issue->bugID}")) : set::disabled(true),
                        $canViewBug ? setData('toggle', 'modal') : null,
                        $canViewBug ? setData('size', 'lg') : null
                    ) : null
                )
            ),
            empty($issue->payload) && empty($issue->payload->location && empty($issue->payload->location->commit)) ? null : div
            (
                setClass('flex-none'),
                h::table
                (
                    setClass('table w-full max-w-full bordered text-center'),
                    h::thead
                    (
                        h::tr
                        (
                            h::th($lang->codescan->injector),
                            h::th(set::width('100px'), $lang->codescan->injectDate),
                            h::th(set::width('100px'), $lang->codescan->injectCommit)
                        )
                    ),
                    h::tr
                    (
                        h::td(zget($users, zget($issue->payload->location->commit, 'author', ''))),
                        h::td(empty($issue->payload->location->commit->committer_date) ? '' : date('Y-m-d', intval($issue->payload->location->commit->committer_date) / 1000)),
                        h::td
                        (
                            h::a
                            (
                                hasPriv('repo', 'revision') ? set::href(createLink('repo', 'revision', "repoID={$repoID}&objectID=0&revision=" . $issue->payload->location->commit->commit_short_id)) : null,
                                $issue->payload->location->commit->commit_short_id
                            )
                        )
                    )
                )
            )
        )
    ),
    sectionList
    (
        setClass('mt-4 issueFile'),
        setStyle(array('gap' => '0.5rem')),
        on::init()->call('initMonacoContainer'),
        div
        (
            setClass('issue-file flex items-center border rounded-md px-2'),
            set::title($issue->file),
            label(setClass('mr-1 gray-500-pale'), set::title($lang->codescan->branch), $task->branch),
            span($issue->file, setClass('clip')),
            btn
            (
                setClass('ml-1 ghost flex-none'),
                icon('copy'),
                on::click()->call('window.copyFile', jsRaw('$this'))
            ),
            input(setClass('hidden'), set::name('file'), set::value($issue->file)),
        ),
        tabs
        (
            setID('issueViewTabs'),
            empty($issue->snippetWithContext) || empty($issue->snippetStartLine) ? null : tabPane
            (
                set::title($lang->codescan->issueLocation),
                set::key('issueLocation'),
                div
                (
                    setClass('monaco-container'),
                    monaco
                    (
                        set::id('issueContainer'),
                        set::options
                        (
                            array
                            (
                                'value'                => $issue->snippetWithContext,
                                'language'             => strtolower($rule->lang),
                                'readOnly'             => true,
                                'autoIndent'           => true,
                                'contextmenu'          => null,
                                'automaticLayout'      => true,
                                'EditorMinimapOptions' => array('enabled' => false)
                            )
                        ),
                        set::action('create'),
                        set::lineMap(empty($issue->snippetStartLine) ? array() : range($issue->snippetStartLine, $issue->snippetEndLine)),
                        set::selectedLines(empty($issue->rangeStartLine) ? '' : "{$issue->rangeStartLine},{$issue->rangeEndLine},{$startCol},0"),
                        set::selectedClass('wave-decoration')
                    )
                )
            ),
            empty($rule->content) && empty($rule->description) ? null : tabPane
            (
                set::title($lang->codescan->issueReason),
                set::key('issueReason'),
                div(setClass('article'), html(empty($ruleContent) ? $rule->description : common::processMarkdown($ruleContent)))
            ),
            empty($task) ? null : tabPane
            (
                set::title($lang->codescan->taskInfo),
                set::key('taskInfo'),
                tableData
                (
                    item
                    (
                        set::name($lang->codescan->branch),
                        $task->branch
                    ),
                    item
                    (
                        set::name($lang->codescan->tool),
                        $rule->plugin
                    ),
                    item
                    (
                        set::name($lang->codescan->plan),
                        $task->planName
                    ),
                    item
                    (
                        set::name($lang->codescan->triggerName),
                        zget($task, 'triggerName', '')
                    ),
                    item
                    (
                        set::name($lang->codescan->createTime),
                        $task->createdDate
                    ),
                    item
                    (
                        set::name($lang->bug->resolution),
                        zget($lang->bug->resolutionList, zget($issue, 'resolution', ''), '')
                    ),
                    item
                    (
                        set::name($lang->bug->resolvedDate),
                        empty($issue->resolved) ? '' : $issue->resolved
                    )
                )
            ),
            empty($actions) ? null : tabPane
            (
                set::title($lang->history),
                set::key('history'),
                history
                (
                    set::objectType('codescanissue'),
                    set::objectID($issueID),
                    set::commentUrl(createLink('action', 'comment', array('objectType' => 'codescanissue', 'objectID' => $issueID)))
                )
            )
        )
    ),
);

$setItems ? sidebar
(
    set::side('right'),
    set::width(370),
    set::maxWidth(370),
    set::preserve(false),
    div
    (
        setClass('issue-view-list overflow-scroll px-2 pb-2 overflow-x-hidden canvas'),
        div
        (
            setClass('mx-2 h-12'),
            div
            (
                setClass('absolute font-bold issue-file-title text-md clip canvas w-full h-12 px-2 pt-4'),
                basename($issue->path) . $lang->codescan->fileIssueList,
                label(setClass('size-sm rounded-full ml-1'), count($setItems))
            ),
        ),
        div
        (
            setClass('flex flex-wrap solution-block'),
            $setItems
        )
    )
) : null;

$actionList = $this->loadModel('common')->buildOperateMenu($issue);
div
(
    setClass('detail-actions center sticky mt-4 bottom-4 z-10'),
    floatToolbar
    (
        set::object($issue),
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), set::back('codescan-issue,codescan-taskview'), $lang->goback)),
        empty($actionList['mainActions']) ? null : set::main($actionList['mainActions'])
    )
);
