<?php
declare(strict_types=1);
/**
 * The issue view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

$isInModal = isInModal();
if($repoID && !$taskID && !$isInModal) dropmenu(set::objectID($repoID), set::tab('repo'));

jsVar('taskID', $taskID);
jsVar('taskURL', inLink('issue', "repoID={$repoID}&taskID={$taskID}&serviceRepoID={$serviceRepoID}&type=%s&queryID=0&severity={$severity}&extras=%s"));
if($taskID && !$isInModal)
{
    include 'task.header.html.php';
    $app->rawMethod = 'issue';
}

if(!$isInModal && empty($taskID))
{
    include 'issue.left.html.php';
}

$queryMenuLink = createLink('codescan', 'issue', "repoID={$repoID}&taskID={$taskID}&serviceRepoID={$serviceRepoID}&type=bysearch&queryID={queryID}");
$cols = $this->loadModel('datatable')->getSetting('codescan');
if(isset($cols['rulePlugin'])) $cols['rulePlugin']['map'] = $pluginList;
if(isset($cols['actions']['list']['bug'])) $cols['actions']['list']['bug']['url']['params'] = "product={$productID}&branch=0&extra=from=codescan,fromID={id},fromServerID={$realRepoID},repoID={$realRepoID}";
if(hasPriv('codescan', 'issueview')) $cols['content']['link'] = array('module' => 'codescan', 'method' => 'issueview', 'params' => "issueID={id}&repoID={repoID}");
if($isInModal)
{
    if($task) modalHeader(set::title($title), set::entityText($task->name), set::entityID($taskID));
    unset($cols['actions']);
}

$issueList = initTableData($issues, $cols);
$urlParams = array(
    'repoID'        => $repoID,
    'taskID'        => $taskID,
    'serviceRepoID' => $serviceRepoID,
    'type'          => $browseType,
    'queryID'       => $queryID,
    'severity'      => $severity,
    'extras'        => $extras,
    'orderBy'       => '{name}_{sortType}',
    'recPerPage'    => $pager->recPerPage,
    'pageID'        => $pager->pageID
);

jsVar('codescanLang', $lang->codescan);
jsVar('bugNotice',    $lang->codescan->notice->bugCreated);
jsVar('statusNotice', $lang->codescan->notice->statusDisable);
jsVar('statusList',   $lang->codescan->issueStatusList);
jsVar('pageUrl',      inLink('issue', "repoID={$repoID}&taskID={$taskID}&serviceRepoID={$serviceRepoID}&type=%s&queryID=0&severity=%s&extras={$extras}"));

unset($lang->codescan->severityList['all']);
$severityList = explode(',', $severity);
foreach($severityList as &$value)
{
    if($value) $value = $lang->codescan->severityList[$value];
}

$featureBar = $isInModal ? null : featureBar
(
    set::current($browseType),
    set::load('table'),
    empty($taskID) ? null : set::link("javascript:changeType('repoID={$repoID}&taskID={$taskID}&serviceRepoID={$serviceRepoID}&type={key}&queryID=0&severity={$severity}&extras={$extras}');"),
    set::linkParams("repoID={$repoID}&taskID={$taskID}&serviceRepoID={$serviceRepoID}&type={key}&queryID=0&severity={$severity}&extras={$extras}"),
    set::queryMenuLinkCallback(array(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink))),
    picker
    (
        setClass('ml-2 mr-2 mb-1'),
        set::name('severityFilter'),
        set::items($lang->codescan->severityList),
        set::search(false),
        set::multiple(true),
        set::width('120px'),
        set::display($severity && count($severityList) < 3 ? ($lang->codescan->severity . ': ' . implode(',', $severityList)) : $lang->codescan->allSeverity),
        set::menu(array('checkbox' => true)),
        set::value($severity),
        set::toolbar
        (
            array('text' => $lang->confirm, 'onClick' => jsRaw('(e,info) => {loadIssueList();info.relativeTarget.close();}')),
            array('text' => $lang->cancel, 'onClick' => jsRaw('(e,info) => info.relativeTarget.close()')),
        )
    ),
    $taskID ? div
    (
        btn
        (
            setID('searchForm'),
            set::className('search-form-toggle rounded-full gray-300-outline size-sm', $browseType == 'bySearch' ? 'active' : ''),
            set::icon('search'),
            set::text($lang->searchAB),
            on::click()->call('window.toggleSearchForm')
        )
    ) : div(searchToggle(set::module('codeScanIssue'), set::open($browseType == 'bySearch')))
);

if($taskID && !$isInModal)
{
    $search = div
    (
        setClass('search-form-block', $browseType == 'bySearch' ? '' : 'hidden'),
        searchForm
        (
            set::module('codeScanIssue'),
            set::show(true),
            set::simple(true),
            set::searchLoader(array('partial' => true, 'selector' => '.scan-issue-block'))
        )
    );
}

$table = dtable
(
    setID('issueTable'),
    set::customCols(!$isInModal),
    set::cols($cols),
    set::data($issueList),
    set::sortLink(createLink('codescan', 'issue', $urlParams)),
    set::orderBy($orderBy),
    set::loadPartial(true),
    $taskID ? set::extraHeight('+144') : null,
    set::onRenderCell(jsRaw('window.renderIssueCell')),
    set::footPager(usePager(array('linkCreator' => createLink('codescan', 'issue', "repoID={$repoID}&taskID={$taskID}&serviceRepoID={$serviceRepoID}&type={$browseType}&queryID={$queryID}&severity={$severity}&extras={$extras}&orderBy={$orderBy}&recPerPage={recPerPage}&page={page}")))),
);

$taskID && !$isInModal ? panel
(
    set::bodyClass('scan-issue-block'),
    div
    (
        setID('issueMenu'),
        setClass('flex justify-between'),
        $headers
    ),
    div
    (
        setClass('my-2'),
        $featureBar,
        $search
    ),
    $table
) : null;
