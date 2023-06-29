<?php
declare(strict_types=1);
/**
 * The browse view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

$topSceneCount = count(array_filter(array_map(function($scene){return $scene->isCase == 2 && $scene->grade == 1;}, $scenes)));
$topCaseCount  = count(array_filter(array_map(function($scene){return $scene->isCase == 1 && $scene->scene == 0;}, $scenes)));

jsVar('pageSummary', sprintf($lang->testcase->summary, $topSceneCount, $topCaseCount));
jsVar('checkedSummary', $lang->testcase->checkedSummary);

include 'header.html.php';

$canBatchRun                = hasPriv('testtask', 'batchRun') && !$isOnlyScene;
$canBatchEdit               = hasPriv('testcase', 'batchEdit') && !$isOnlyScene;
$canBatchReview             = hasPriv('testcase', 'batchReview') && !$isOnlyScene && ($config->testcase->needReview || !empty($config->testcase->forceReview));
$canBatchDelete             = hasPriv('testcase', 'batchDelete') && !$isOnlyScene;
$canBatchCaseTypeChange     = hasPriv('testcase', 'batchCaseTypeChange') && !$isOnlyScene;
$canBatchConfirmStoryChange = hasPriv('testcase', 'batchConfirmStoryChange') && !$isOnlyScene;
$canBatchChangeBranch       = hasPriv('testcase', 'batchChangeBranch') && !$isOnlyScene && $this->session->currentProductType && $this->session->currentProductType != 'normal';
$canBatchChangeModule       = hasPriv('testcase', 'batchChangeModule') && !empty($productID) && ($product->type == 'normal' || $branch !== 'all');
$canBatchChangeScene        = hasPriv('testcase', 'batchChangeScene') && !$isOnlyScene;
$canImportToLib             = hasPriv('testcase', 'importToLib') && !$isOnlyScene;
$canGroupBatch              = ($canBatchRun || $canBatchEdit || $canBatchReview || $canBatchDelete || $canBatchCaseTypeChange || $canBatchConfirmStoryChange);
$canBatchAction             = ($canGroupBatch || $canBatchChangeBranch || $canBatchChangeModule || $canBatchChangeScene || $canImportToLib);

$caseProductIds = array();
foreach($cases as $case) $caseProductIds[$case->product] = $case->product;
$caseProductID = count($caseProductIds) > 1 ? 0 : $productID;

$footToolbar = $canBatchAction ? array('items' => array
(
    $canGroupBatch ? array('type' => 'btn-group', 'items' => array
    (
        $canBatchRun ? array('text' => $lang->testtask->runCase, 'className' => 'batch-btn', 'data-url' => helper::createLink('testtask', 'batchRun', "productID=$productID&orderBy=$orderBy")) : null,
        $canBatchEdit ? array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => helper::createLink('testcase', 'batchEdit', "productID=$caseProductID&branch=$branch")) : null,
        ($canBatchReview || $canBatchDelete || $canBatchCaseTypeChange || $canBatchConfirmStoryChange) ? array('caret' => 'up', 'btnType' => 'secondary', 'url' => '#navActions', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start') : null,
    )) : null,
    $canBatchChangeBranch ? array('caret' => 'up', 'text' => $lang->product->branchName[$this->session->currentProductType], 'btnType' => 'secondary', 'url' => '#navBranch', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start') : null,
    $canBatchChangeModule ? array('caret' => 'up', 'text' => $lang->testcase->moduleAB, 'btnType' => 'secondary', 'url' => '#navModule', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start') : null,
    $canBatchChangeScene ? array('caret' => 'up', 'text' => $lang->testcase->scene, 'btnType' => 'secondary', 'url' => '#navScene','data-toggle' => 'dropdown', 'data-placement' => 'top-start') : null,
    $canImportToLib ? array('text' => $lang->testcase->importToLib, 'btnType' => 'secondary', 'data-toggle' => 'modal', 'data-target' => '#importToLib', 'data-size' => 'sm') : null,
)) : null;

if($canBatchReview)
{
    $reviewItems = array();
    foreach($lang->testcase->reviewResultList as $key => $result)
    {
        if($key == '') continue;
        $reviewItems[] = array('text' => $result, 'className' => 'batch-btn ajax-btn', 'data-url' => $this->createLink('testcase', 'batchReview', "result=$key"));
    }
}

if($canBatchCaseTypeChange)
{
    $typeItems = array();
    foreach($lang->testcase->typeList as $key => $result)
    {
        $typeItems[] = array('text' => $result, 'className' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchCaseTypeChange', "result=$key"));
    }
}

if($canBatchReview || $canBatchDelete || $canBatchCaseTypeChange || $canBatchConfirmStoryChange)
{
    menu
    (
        set::id('navActions'),
        set::class('menu dropdown-menu'),
        set::items(array
        (
            $canBatchReview ? array('text' => $lang->testcase->review, 'class' => 'not-hide-menu', 'items' => $reviewItems) : null,
            $canBatchDelete ? array('text' => $lang->delete, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchDelete', "productID=$productID")) : null,
            $canBatchCaseTypeChange ? array('text' => $lang->testcase->type, 'class' => 'not-hide-menu', 'items' => $typeItems) : null,
            $canBatchConfirmStoryChange ? array('text' => $lang->testcase->confirmStoryChange, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchConfirmStoryChange', "productID=$productID")) : null,
        ))
    );
}

if($canBatchChangeBranch)
{
    $branchItems = array();
    foreach($branchTagOption as $branchID => $branchName) $branchItems[] = array('text' => $module, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchChangeBranch', "branchID=$branchId"));

    menu
    (
        set::id('navBranch'),
        set::class('dropdown-menu'),
        set::items($branchItems)
    );
}

if($canBatchChangeModule)
{
    $moduleItems = array();
    foreach($modules as $moduleId => $module) $moduleItems[] = array('text' => $module, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchChangeModule', "moduleID=$moduleId"));

    menu
    (
        set::id('navModule'),
        set::class('dropdown-menu'),
        set::items($moduleItems)
    );
}

if($canBatchChangeScene)
{
    $sceneItems = array();
    foreach($iscenes as $sceneID => $scene) $sceneItems[] = array('text' => $scene, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchChangeScene', "sceneId=$sceneID"));

    menu
    (
        set::id('navScene'),
        set::class('dropdown-menu'),
        set::items($sceneItems)
    );
}

$cols = $isOnlyScene ? $this->config->scene->dtable->fieldList : $this->loadModel('datatable')->getSetting('testcase');
if(!empty($cols['actions']['list']))
{
    $executionID = ($app->tab == 'project' || $app->tab == 'execution') ? $this->session->{$app->tab} : '0';
    foreach($cols['actions']['list'] as $method => $methodParams)
    {
        if(!isset($methodParams['url'])) continue;

        $cols['actions']['list'][$method]['url'] = str_replace('%executionID%', (string)$executionID, $methodParams['url']);
    }
}

if(isset($cols['title'])) $cols['title']['nestedToggle'] = $topSceneCount > 0;
if(isset($cols['story'])) $cols['story']['map']          = $stories;

foreach($scenes as $scene)
{
    $actionType = $scene->isCase == 1 ? 'testcase' : 'scene';
    $cols['actions']['menu'] = $config->$actionType->menu;

    $scene->browseType = $browseType;
    initTableData(array($scene), $cols, $this->testcase);

    if($scene->isCase != 1) continue;

    $stages = array_filter(explode(',', $scene->stage));
    foreach($stages as $key => $stage) $stages[$key] = zget($lang->testcase->stageList, $stage);
    $scene->stage = implode($lang->comma, $stages);
}

dtable
(
    set::customCols(!$isOnlyScene),
    set::userMap($users),
    set::cols($cols),
    set::data(array_values($scenes)),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::checkable($canBatchAction),
    set::checkInfo(jsRaw('function(checks){return window.setStatistics(this, checks);}')),
    set::footToolbar($footToolbar),
    set::footPager(usePager())
);

modal
(
    on::click('button[type="submit"]', 'getCheckedCaseIdList'),
    set::id('importToLib'),
    set::modalProps(array('title' => $lang->testcase->importToLib)),
    form
    (
        set::url(createLink('testcase', 'importToLib')),
        set::actions(array('submit')),
        set::submitBtnText($lang->testcase->import),
        formRow
        (
            formGroup
            (
                set::label($lang->testcase->selectLibAB),
                set::name('lib'),
                set::items($libraries),
                set::value(''),
                set::required(true),
            ),
        ),
        formRow
        (
            setClass('hidden'),
            formGroup
            (
                set::name('caseIdList'),
                set::value(''),
            ),
        ),
    ),
);

render();
