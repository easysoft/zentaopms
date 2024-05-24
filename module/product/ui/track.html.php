<?php
declare(strict_types=1);
/**
* The UI file of product module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     product
* @link        https://www.zentao.net
*/

namespace zin;

$viewByTypePairs['epic']        = $lang->story->viewByER;
$viewByTypePairs['requirement'] = $lang->story->viewByUR;
$viewByTypePairs['story']       = $lang->story->viewBySR;

$storyTypeLang = $lang->SRCommon;
if($storyType == 'requirement') $storyTypeLang = $lang->URCommon;
if($storyType == 'epic') $storyTypeLang = $lang->ERCommon;

if($storyType != 'story') unset($lang->story->trackOrderByList['stage']);

$paramTemplate = "productID={$productID}&branch={$branch}&projectID={$projectID}&browseType=allstory&param=0&storyType=%s&orderBy=%s";
$orderByItems  = array();
$orderByTitle  = '';
foreach($lang->story->trackOrderByList as $orderByType => $orderByName)
{
    $item = array();
    $item['text']     = $orderByName;
    $item['selected'] = strpos($orderBy, $orderByType) === 0;
    $item['items']    = array();
    $item['items'][]  = array('text' => $lang->story->trackSortList['asc'],  'selected' => $orderBy == "{$orderByType}_asc",  'url' => createLink('product', 'track', sprintf($paramTemplate, $storyType, "{$orderByType}_asc")));
    $item['items'][]  = array('text' => $lang->story->trackSortList['desc'], 'selected' => $orderBy == "{$orderByType}_desc", 'url' => createLink('product', 'track', sprintf($paramTemplate, $storyType, "{$orderByType}_desc")));
    $orderByItems[]   = $item;

    if($item['selected'])
    {
        $orderByTitle = $orderByName;
        if($orderBy == "{$orderByType}_asc")  $orderByTitle .= $lang->story->trackSortList['asc'];
        if($orderBy == "{$orderByType}_desc") $orderByTitle .= $lang->story->trackSortList['desc'];
    }
}

featureBar
(
    to::leading
    (
        dropdown
        (
            to('trigger', btn(setClass('switchBtn'), $viewByTypePairs[$storyType])),
            set::items(array(
                array('text' => $viewByTypePairs['epic'],        'selected' => $storyType == 'epic',        'url' => createLink('product', 'track', sprintf($paramTemplate, "epic",        strpos($orderBy, 'stage') === 0 ? 'id_desc' : $orderBy))),
                array('text' => $viewByTypePairs['requirement'], 'selected' => $storyType == 'requirement', 'url' => createLink('product', 'track', sprintf($paramTemplate, "requirement", strpos($orderBy, 'stage') === 0 ? 'id_desc' : $orderBy))),
                array('text' => $viewByTypePairs['story'],       'selected' => $storyType == 'story',       'url' => createLink('product', 'track', sprintf($paramTemplate, "story",       $orderBy)))
            )),
        )
    ),
    li(searchToggle(set::open($browseType == 'bysearch' || $storyBrowseType == 'bysearch'), set::module($config->product->search['module']), set::text($lang->searchAB . $storyTypeLang)))
);

toolbar
(
    formSettingBtn(set::text($lang->settings))
);

$privs['epic']        = commonModel::hasPriv('epic',        'view');
$privs['requirement'] = commonModel::hasPriv('requirement', 'view');
$privs['story']       = commonModel::hasPriv('story',       'view');
$privs['project']     = commonModel::hasPriv('project',     'view');
$privs['execution']   = commonModel::hasPriv('execution',   'task');
$privs['task']        = commonModel::hasPriv('task',        'view');
$privs['bug']         = commonModel::hasPriv('bug',         'view');
$privs['case']        = commonModel::hasPriv('case',        'view');
$privs['design']      = commonModel::hasPriv('design',      'view');
$privs['commit']      = commonModel::hasPriv('repo',        'revision');

$app->loadLang('project');
$app->loadLang('task');
$app->loadLang('bug');
$app->loadLang('testcase');
jsVar('langStoryPriList',      $lang->story->priList);
jsVar('langStoryStatusList',   $lang->story->statusList);
jsVar('langStoryStageList',    $lang->story->stageList);
jsVar('langProjectStatusList', $lang->project->statusList);
jsVar('langTaskPriList',       $lang->task->priList);
jsVar('langTaskStatusList',    $lang->task->statusList);
jsVar('langChildren',          $lang->task->childrenAB);
jsVar('langBugPriList',        $lang->bug->priList);
jsVar('langBugSeverityList',   $lang->bug->severityList);
jsVar('langCasePriList',       $lang->testcase->priList);
jsVar('langCaseResultList',    $lang->testcase->resultList);
jsVar('langUnexecuted',        $lang->testcase->unexecuted);

jsVar('orderByItems', $orderByItems);
jsVar('orderByTitle', $orderByTitle);
jsVar('storyType',    $storyType);
jsVar('users',        $users);
jsVar('privs',        $privs);

empty($tracks) ? div(setClass('dtable-empty-tip bg-white shadow'), span(setClass('text-gray'), $lang->noData)) : div
(
    set::id('track'),
    zui::kanbanList
    (
        set::key('kanban'),
        set::items(array(array('data' => $tracks, 'getCol' => jsRaw('window.getCol'), 'getItem' => jsRaw('window.getItem'), 'itemRender' => jsRaw('window.itemRender'), 'afterRender' => jsRaw('window.afterRender'), 'draggable' => false))),
        set::height('calc(100vh - 130px)')
    ),
    pager(setClass('justify-end'))
);
