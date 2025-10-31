<?php
declare(strict_types=1);
/**
 * The header view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('scene', $lang->testcase->sceneb);
jsVar('automated', $lang->testcase->automated);
jsVar('children', $lang->childrenAB);
jsVar('checkedSummary', $lang->testcase->checkedSummary);
jsVar('noCase', $lang->scene->noCase);

$isOnlyScene    = strtolower($browseType) == 'onlyscene';
$isProjectApp   = $this->app->tab == 'project';
$isExecutionApp = $this->app->tab == 'execution';
$currentModule  = $isProjectApp ? 'project'  : 'testcase';
$currentMethod  = $isProjectApp ? 'testcase' : 'browse';
$projectParam   = $isProjectApp ? "projectID={$this->session->project}&" : '';
$moduleID       = isset($moduleID) ? (int)$moduleID : 0;
$rawMethod      = $app->rawMethod;
$load           = $rawMethod !== 'browse' ? null : 'table';
$product        = is_bool($product) ? new stdclass() : $product;

$canModify = common::canModify('product', $product);
if(!empty($project)) $canModify = $canModify && common::canModify('project', $project);

if(!isset($isFromDoc)) $isFromDoc = false;
if(!isset($isFromAI)) $isFromAI = false;
if(!isset($suffixParam)) $suffixParam = '';
if(!isset($from)) $from = '';

$canSwitchCaseType   = $this->app->tab == 'qa';
$canDisplaySuite     = $this->app->tab == 'qa' && $rawMethod != 'browseunits';
$canManageModule     = $canModify && hasPriv('tree', 'browse') && !empty($productID) && (!isset($project) || $project->hasProduct);
$canCreateSuite      = $canModify && hasPriv('testsuite', 'create');
$canBrowseUnits      = hasPriv('testtask', 'browseunits');
$canBrowseZeroCase   = hasPriv('testcase', 'zerocase') && $rawMethod != 'browseunits';
$canBrowseGroupCase  = hasPriv('testcase', 'groupcase');
$canBrowseScene      = hasPriv('testcase', 'browseScene') && ($rawMethod == 'browse' || $rawMethod == 'browsescene') && !$isFromDoc && !$isFromAI;
$canAutomation       = !$isExecutionApp && $canModify && hasPriv('testcase', 'automation') && !empty($productID) && $rawMethod != 'browseunits';
$canExport           = !$isExecutionApp && hasPriv('testcase', 'export');
$canExportTemplate   = !$isExecutionApp && hasPriv('testcase', 'exportTemplate');
$canExportFreeMind   = !$isExecutionApp && hasPriv('testcase', 'exportFreeMind');
$canExportXmind      = !$isExecutionApp && hasPriv('testcase', 'exportXmind');
$canImport           = !$isExecutionApp && $canModify && hasPriv('testcase', 'import');
$canImportFromLib    = !$isExecutionApp && $canModify && hasPriv('testcase', 'importFromLib');
$canImportXmind      = !$isExecutionApp && $canModify && hasPriv('testcase', 'importXmind');
$canCreateCase       = $canModify && hasPriv('testcase', 'create');
$canBatchCreateCase  = $canModify && $productID && hasPriv('testcase', 'batchCreate');
$canCreateScene      = $canModify && $productID && hasPriv('testcase', 'createScene');
$canImportUnitResult = $canModify && hasPriv('testtask', 'importUnitResult');
$canCreate           = $canCreateCase || $canBatchCreateCase || $canCreateScene;

$lang->testcase->typeList[''] = $lang->testcase->allType;
if(!isset($param)) $param = 0;

if($rawMethod == 'zerocase')    $caseType = '';
if($rawMethod == 'browseunits') $caseType = 'unit';

if($canSwitchCaseType)
{
    /* Process variables of case type menu. */
    $currentTypeName = zget($lang->testcase->typeList, $caseType, $lang->testcase->allType);
    $caseTypeItems   = array();
    foreach($lang->testcase->typeList as $type => $typeName)
    {
        if($rawMethod == 'groupcase' && $type == 'unit') continue;

        if($canBrowseUnits && $type == 'unit')
        {
            $url  = $this->createLink('testtask', 'browseUnits', "productID=$productID&browseType=newest&orderBy=id_desc&recTotal=0&recPerPage=20&pageID=1&projectID=$projectID");
            $text = $lang->testcase->browseUnits;
        }
        else
        {
            $url  = $rawMethod == 'groupcase' ? $this->createLink('testcase', 'groupcase', "productID=$productID&branch=$branch&groupBy=$groupBy&projectID=$projectID&caseType=$type") : $this->createLink('testcase', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&param=$param&caseType=$type");
            $text = $typeName;
        }

        $caseTypeItems[] = array('text' => $text, 'url' => $url, 'active' => $type == $caseType);
    }
}

if($canDisplaySuite)
{
    /* Process variables of sutie menu. */
    $currentSuiteID   = isset($suiteID) ? (int)$suiteID : 0;
    $currentSuite     = zget($suiteList, $currentSuiteID, '');
    $currentSuiteName = empty($currentSuite) ? $lang->testsuite->common : $currentSuite->name;
    $suiteItems       = array();
    if(empty($suiteList))
    {
        if($canCreateSuite && (empty($productID) || $canModify))
        {
            $suiteItems[] = array('text' => $lang->testsuite->create, 'url' => $this->createLink('testsuite', 'create', "productID=$productID"));
        }
    }
    else
    {
        foreach($suiteList as $suiteID => $suite)
        {
            $suiteItems[] = array('text' => $suite->name, 'url' => $this->createLink('testcase', 'browse', "productID=$productID&branch=$branch&browseType=bySuite&param=$suiteID"), 'active' => $suiteID == (int)$currentSuiteID);
        }
    }
}

if($isFromDoc || $isFromAI)
{
    $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
    $productChangeLink = createLink($app->rawModule, $app->rawMethod, "productID={productID}&branch=$branch&browseType=$browseType&param=$param&caseType=$caseType&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&projectID=$projectID&from=$from&blockID=$blockID");

    formPanel
    (
        setID('zentaolist'),
        setClass('mb-4-important'),
        set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList['productCase'])),
        set::actions(array()),
        set::showExtra(false),
        to::titleSuffix
        (
            span
            (
                setClass('text-muted text-sm text-gray-600 font-light'),
                span
                (
                    setClass('text-warning mr-1'),
                    icon('help'),
                ),
                $lang->doc->previewTip
            )
        ),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('product'),
                set::label($lang->doc->product),
                set::control(array('required' => false)),
                set::items($products),
                set::value($productID),
                set::required(),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                ),
                on::change('[name="product"]')->do("loadModal('$productChangeLink'.replace('{productID}', $(this).val()))")
            )
        )
    );
}

$linkParams = $rawMethod == 'groupcase' ? "productID=$productID&branch=$branch&groupBy=$groupBy&objectID=0&caseType=$caseType&browseType={key}" : $projectParam . "productID=$productID&branch=$branch&browseType={key}&param=0" . $suffixParam;
$browseLink = createLink('testcase', 'browse', $linkParams);
if($app->tab == 'project') $browseLink = createLink('project', 'testcase', $linkParams);
if($app->tab == 'execution' && $from != 'doc' && $from != 'ai') $browseLink = createLink('execution', 'testcase', "executionID={$executionID}&productID=$productID&branch=$branch&browseType={key}");
if($rawMethod == 'browseunits') $browseLink = createLink('testtask', 'browseUnits', "productID=$productID&browseType={key}");

$queryMenuLink = createLink('testcase', 'browse', $projectParam . "productID=$productID&branch=$branch&browseType=bySearch&param={queryID}" . $suffixParam);
$objectID = 0;
if($app->tab == 'project')   $objectID = $projectID;
if($app->tab == 'execution' && $from != 'doc' && $from != 'ai') $objectID = $executionID;
featureBar
(
    set::isModal($isFromDoc || $isFromAI),
    set::module('testcase'),
    set::method('browse'),
    set::linkParams($rawMethod == 'zerocase' || $rawMethod == 'browseunits' || $rawMethod == 'browsescene' ? null : $linkParams),
    set::link($rawMethod == 'zerocase' || $rawMethod == 'browseunits' || $rawMethod == 'browsescene' ? $browseLink : null),
    set::queryMenuLinkCallback(array(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink))),
    set::current(in_array($methodName, array('groupcase', 'browse')) ? $this->session->caseBrowseType : null),
    set::load($load),
    set::app($app->tab),
    $canSwitchCaseType ? to::leading
    (
        dropdown
        (
            btn($currentTypeName, setClass('ghost')),
            set::items($caseTypeItems),
            set::trigger('click')
        )
    ) : null,
    $canDisplaySuite && $rawMethod != 'groupcase' ? li
    (
        set::className('nav-item'),
        dropdown
        (
            a
            (
                setClass('ghost' . (($rawMethod == 'browse' && $this->session->caseBrowseType == 'bysuite') ? ' active' : '')),
                $currentSuiteName,
                ($rawMethod == 'browse' && $this->session->caseBrowseType == 'bysuite' && $pager->recTotal != '') ? span(setClass('label size-sm rounded-full white'), $pager->recTotal) : null,
                span(setClass('caret'))
            ),
            set::items($suiteItems)
        )
    ) : null,
    $zeroCaseTab,
    $canBrowseScene ? li
    (
        set::className('nav-item'),
        a
        (
            set::href(inlink('browseScene', "productID=$productID&branch=$branch&moduleID=$moduleID")),
            set('class', $isOnlyScene ? 'active' : ''),
            set('data-load', $load),
            $lang->testcase->onlyScene
        )
    ) : null,
    $showAutoCaseCheckbox ? li
    (
        set::className('nav-item mr-2'),
        checkbox
        (
            setID('onlyAutoCase'),
            set::checked($this->cookie->onlyAutoCase),
            on::change('toggleOnlyAutoCase'),
            $lang->testcase->onlyAutomated
        )
    ) : null,
    !in_array($rawMethod, array('browseunits', 'groupcase', 'zerocase')) ? searchToggle
    (
        set::simple($isFromDoc || $isFromAI),
        ($isFromDoc || $isFromAI) ? set::target('#docSearchForm') : null,
        set::module($this->app->rawMethod == 'testcase' ? 'testcase' : $this->app->rawModule),
        set::open($browseType == 'bysearch')
    ) : null
);

if($isFromDoc || $isFromAI)
{
    div(setID('docSearchForm'));
}

$viewItemUrl = (($isProjectApp || $isExecutionApp) && $from != 'doc' && $from != 'ai') ? createLink($isProjectApp ? 'project' : 'execution', 'testcase', $isProjectApp ? "projectID={$projectID}" : "executionID=$executionID") : inlink('browse', "productID=$productID&branch=$branch&browseType=all");
$viewItems   = array(array('text' => $lang->testcase->listView, 'url' => $viewItemUrl, 'active' => $rawMethod != 'groupcase' ? true : false));
$exportItems = array();
$importItems = array();
if($canBrowseGroupCase)
{
    $link = inlink('groupCase', "productID=$productID&branch=$branch&groupBy=story&projectID=$projectID&caseType=$caseType");
    $viewItems[] = array('text' => $lang->testcase->groupView, 'url' => $link, 'data-app' => $app->tab, 'active' => $rawMethod == 'groupcase' ? true : false);
}

if(!empty($productID))
{
    if($canExport)
    {
        $link = $this->createLink('testcase', 'export', "productID=$productID&orderBy=$orderBy&taskID=0&browseType=$browseType");
        $exportItems[] = array('text' => $lang->testcase->export, 'url' => $link, 'data-toggle' => 'modal', 'data-app' => $app->tab);
    }
    if($canExportTemplate)
    {
        $link = $this->createLink('testcase', 'exportTemplate', "productID=$productID");
        $exportItems[] = array('text' => $lang->testcase->exportTemplate, 'url' => $link, 'data-toggle' => 'modal', 'data-app' => $app->tab, 'data-size' => 'sm');
    }
    if($canExportXmind)
    {
        $link = $this->createLink('testcase', 'exportXmind', "productID=$productID&moduleID=$moduleID&branch=$branch");
        $exportItems[] = array('text' => $lang->testcase->xmindExport, 'url' => $link, 'data-toggle' => 'modal', 'data-app' => $app->tab);
    }

    if($canExportFreeMind)
    {
        $link = $this->createLink('testcase', 'exportFreeMind', "productID=$productID&moduleID=$moduleID&branch=$branch");
        $exportItems[] = array('text' => $lang->testcase->exportFreeMind, 'url' => $link, 'data-toggle' => 'modal', 'data-app' => $app->tab);
    }

    if($canModify)
    {
        if($canImport)
        {
            $link = $this->createlink('testcase', 'import', "productID=$productID&branch=$branch");
            $importItems[] = array('text' => $lang->testcase->fileImport, 'url' => $link, 'data-toggle' => 'modal', 'data-app' => $app->tab);
        }

        if($canImportFromLib)
        {
            $link  = $this->createLink('testcase', 'importFromLib', "productID=$productID&branch=$branch&libID=0&orderBy=id_desc&browseType=&queryID=10&recTotal=0&recPerPage=20&pageID=1&projectID=$projectID");
            $importItems[] = array('url' => $link, 'text' => $lang->testcase->importFromLib, 'data-app' => $app->tab);
        }

        if($canImportXmind)
        {
            $link = $this->createLink('testcase', 'importXmind', "productID=$productID&branch=$branch");
            $importItems[] = array('url' => $link, 'text' => $lang->testcase->xmindImport, 'data-toggle' => 'modal', 'data-app' => $app->tab);
        }
    }
}

$createItems = array();
if($canCreateCase)
{
    $createCaseLink = createLink('testcase', 'create', "productID={$productID}&branch={$branch}&moduleID={$moduleID}" . ($app->tab == 'project' ? "&from=project&param={$projectID}" : ''));
    $createItems[] = array('text' => $lang->testcase->create, 'url' => $createCaseLink, 'data-app' => $app->tab);
}

if($canBatchCreateCase)
{
    $batchCreateCaseLink = createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID");
    $createItems[] = array('text' => $lang->testcase->batchCreate, 'url' => $batchCreateCaseLink, 'data-app' => $app->tab);
}

if($canCreateScene)
{
    $createSceneLink = createLink('testcase', 'createScene', "productID=$productID&branch=$branch&moduleID=$moduleID");
    $createItems[] = array('text' => $lang->testcase->newScene, 'url' => $createSceneLink, 'data-app' => $app->tab);
}

$currentCreateItem = current($createItems);

toolbar
(
    setClass(array('hidden' => $isFromDoc || $isFromAI)),
    $viewItems ? dropdown
    (
        btn
        (
            setClass('btn ghost square'),
            set::icon('kanban')
        ),
        set::items($viewItems),
        set::placement('bottom-end')
    ) : null,
    $canAutomation ? btn
    (
        setClass('ghost square'),
        set::icon('backend'),
        set::hint($lang->testcase->automation),
        set::url(inlink('automation', "productID={$productID}")),
        set('data-toggle', 'modal'),
        set('data-width', '50%')
    ) : null,
    $exportItems ? dropdown
    (
        btn
        (
            setClass('btn ghost square'),
            set::icon('export'),
        ),
        set::items($exportItems),
        set::placement('bottom-end')
    ) : null,
    $importItems && $rawMethod != 'browseunits'? dropdown
    (
        btn
        (
            setClass('btn ghost square'),
            set::icon('import')
        ),
        set::items($importItems),
        set::placement('bottom-end')
    ) : null,
    $canCreate && $rawMethod != 'browseunits' ? btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url($currentCreateItem['url']),
            set(array('data-app' => $app->tab, 'class' => 'createBtn')),
            $currentCreateItem['text']
        ),
        count($createItems) > 1 ?  dropdown
        (
            btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items($createItems),
            set::placement('bottom-end')
        ) : null
    ) : null,
    $rawMethod == 'browseunits' && (empty($productID) || $canModify) && $canImportUnitResult ? btn
    (
        set::className('btn primary'),
        set::icon('import'),
        set::url(createLink('testtask', 'importUnitResult', "product=$productID")),
        $lang->testtask->importUnitResult
    ) : null
);

if($showSidebar)
{
    $settingLink = $canManageModule ? createLink('tree', 'browse', "productID=$productID&view=case&currentModuleID=0&branch=0&from={$app->tab}") : '';
    $closeLink   = $isOnlyScene ? createLink('testcase', 'browseScene', "productID=$productID&branch=$branch&moduleID=0&orderBy=$orderBy") : createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=$browseType&param=0&caseType=&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}");
    sidebar
    (
        moduleMenu(set(array
        (
            'modules'     => $moduleTree,
            'activeKey'   => $moduleID,
            'settingLink' => $settingLink,
            'closeLink'   => $closeLink
        )))
    );
}
