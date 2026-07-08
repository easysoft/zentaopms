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
jsVar('sseURL', "{$config->devops->gitfoxURL}:{$config->devops->gitfoxPort}/api/v2/spaces/{$repo->spaceID}/events");

h:css("#monacoTree .text-clip {overflow: visible;}");

$dropMenus = array();
if(common::hasPriv('repo', 'download')) $dropMenus[] = array('text' => $this->lang->repo->downloadDiff, 'icon' => 'download', 'url' => $this->repo->createLink('download', "repoID={$ppm->repoID}&path=$currentEntry&fromRevision=$oldRevision&toRevision=$newRevision&type=path"), 'target' => '_self');

$dropMenus[] = array('text' => $this->lang->repo->viewDiffList['inline'], 'icon' => 'snap-house', 'id' => 'inline', 'class' => 'inline-appose');
$dropMenus[] = array('text' => $this->lang->repo->viewDiffList['appose'], 'icon' => 'col-archive', 'id' => 'appose', 'class' => 'inline-appose');

$encoding      = empty($encoding) ? '' : $encoding;
$checkMessage  = zget($checkResult, 'message', '');
$conflictFiles = zget($checkResult, 'conflictFiles', array());
$minReviewers  = empty($flow) ? 0 : $flow->definition->reviewFlow->approvals->minReviewers;

$basicItems = array();
$basicItems[] = item(set::name($lang->ppm->author),       zget($users, $ppm->createdBy));
$basicItems[] = item(set::name($lang->ppm->createdDate),  $ppm->createdDate);
$basicItems[] = item(set::name($lang->ppm->targetBranch), $ppm->targetBranch);
$basicItems[] = item(set::name($lang->ppm->sourceBranch), $ppm->sourceBranch);
$basicItems[] = item(set::name($lang->ppm->description),  !empty($ppm->desc) ? strip_tags($ppm->desc) : $lang->noData);

$canMerge = zget($checkResult, 'canMerge', false);

$mergeTypeList    = empty($flow) ? array('merge', 'squash', 'rebase', 'fast') : $flow->definition->reviewFlow->merge->options;
$defaultMergeType = in_array($defaultMergeType, $mergeTypeList) ? $defaultMergeType : $mergeTypeList[0];

$mergeBtnItems = array();
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

if(!empty($reviewers)) $ppm->reviewers = implode(',', array_keys($reviewers));
$actions = $this->loadModel('common')->buildOperateMenu($ppm);

include "{$type}.html.php";

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
                    setClass('py-1 title-header text-clip'),
                    span(setClass('text-lg text-clip font-bold entity-title-text'), set::title($ppm->title), "#{$ppm->id} {$ppm->title}"),
                    $ppm->status == 'opened' ? label(setClass('primary ml-4'), zget($lang->ppm->statusList, $ppm->status)) : null,
                    $ppm->status == 'merged' ? label(setClass('success ml-4'), zget($lang->ppm->statusList, $ppm->status)) : null,
                    $ppm->status == 'closed' ? label(setClass('gray ml-4'), zget($lang->ppm->statusList, $ppm->status)) : null
                ),
                div
                (
                    setClass('my-2 detail-header flex'),
                    set::style(array('justify-content' => 'space-between')),
                    div(setClass('mr-2'), span(html(sprintf($lang->ppm->MRHistory, zget($users, $ppm->createdBy), $ppm->createdDate, $ppm->sourceBranch, $commitPager->recTotal, $ppm->targetBranch)))),
                    $ppm->status == 'opened' && $canMerge && !$checkMessage && $defaultMergeType ? div(img(set::src($config->ppm->mergeImages[$defaultMergeType]))) : null
                ),
                div
                (
                    setID('mrMenu'),
                    nav
                    (
                        li
                        (
                            setClass('nav-item'),
                            a
                            (
                                $lang->ppm->mergeInfo,
                                setClass('font-medium font-bold text-md'),
                                set::href(createLink('ppm', 'view', "id={$ppm->id}&type=basic")),
                                set('data-app', $app->tab),
                                $type == 'basic' ? setClass('active') : null
                            )
                        ),
                        li
                        (
                            setClass('nav-item'),
                            a
                            (
                                $lang->ppm->issueList . " ({$bugPager->recTotal})",
                                setClass('font-medium font-bold text-md'),
                                set::href(createLink('ppm', 'view', "id={$ppm->id}&type=bug")),
                                set('data-app', $app->tab),
                                $type == 'bug' ? setClass('active') : null
                            )
                        ),
                        li
                        (
                            setClass('nav-item'),
                            a
                            (
                                $lang->ppm->commitLogs . " ({$commitPager->recTotal})",
                                setClass('font-medium font-bold text-md'),
                                set::href(createLink('ppm', 'view', "id={$ppm->id}&type=commit")),
                                set('data-app', $app->tab),
                                $type == 'commit' ? setClass('active') : null
                            )
                        ),
                        li
                        (
                            setClass('nav-item'),
                            a
                            (
                                $lang->ppm->changeFiles . ' (' . count($diffs) . ')',
                                setClass('font-medium font-bold text-md'),
                                set::href(createLink('ppm', 'view', "id={$ppm->id}&type=files")),
                                set('data-app', $app->tab),
                                $type == 'files' ? setClass('active') : null
                            )
                        ),
                        li
                        (
                            setClass('nav-item'),
                            a
                            (
                                $lang->pipeline->common,
                                setClass('font-medium font-bold text-md'),
                                set('data-app', $app->tab),
                                set::href(createLink('ppm', 'view', "id={$ppm->id}&type=pipeline")),
                                $type == 'pipeline' ? setClass('active') : null
                            )
                        ),
                        li
                        (
                            setClass('nav-item'),
                            a
                            (
                                $lang->ppm->linkedObject . " ({$objectPager->recTotal})",
                                setClass('font-medium font-bold text-md'),
                                set('data-app', $app->tab),
                                set::href(createLink('ppm', 'view', "id={$ppm->id}&type=object")),
                                $type == 'object' ? setClass('active') : null
                            )
                        )
                    )
                ),
                div(setClass('tab-content'), $domBox),
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
            h::js('loadTarget("' . createLink('ppm', 'ajaxGetReviewers', "ppmID={$ppm->id}&type={$type}") . '", "#reviewer")'),
        ),
        div
        (
            setID('mr-history'),
            history(setClass('mt-2 border-0 canvas shadow-sm mr-history'), setStyle(array('box-shadow' => 'var(--shadow-none)')))
        )
    )
);
