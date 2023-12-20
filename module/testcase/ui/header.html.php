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

$isOnlyScene   = strtolower($browseType) == 'onlyscene';
$isProjectApp  = $this->app->tab == 'project';
$currentModule = $isProjectApp ? 'project'  : 'testcase';
$currentMethod = $isProjectApp ? 'testcase' : 'browse';
$projectParam  = $isProjectApp ? "projectID={$this->session->project}&" : '';
$moduleID      = isset($moduleID) ? (int)$moduleID : 0;
$rawMethod     = $app->rawMethod;
$load          = $rawMethod !== 'browse' ? null : 'table';
$product       = is_bool($product) ? new stdclass() : $product;

$canModify           = common::canModify('product', $product);
$canSwitchCaseType   = $this->app->tab == 'qa';
$canDisplaySuite     = $this->app->tab == 'qa' && $rawMethod != 'browseunits';
$canManageModule     = hasPriv('tree', 'browse') && !empty($productID);
$canCreateSuite      = hasPriv('testsuite', 'create');
$canBrowseUnits      = hasPriv('testtask', 'browseunits');
$canBrowseZeroCase   = hasPriv('testcase', 'zerocase') && $rawMethod != 'browseunits';
$canBrowseGroupCase  = hasPriv('testcase', 'groupcase');
$canAutomation       = hasPriv('testcase', 'automation') && !empty($productID) && $rawMethod != 'browseunits';
$canExport           = hasPriv('testcase', 'export');
$canExportTemplate   = hasPriv('testcase', 'exportTemplate');
$canExportXmind      = hasPriv('testcase', 'exportXmind');
$canImport           = hasPriv('testcase', 'import');
$canImportFromLib    = hasPriv('testcase', 'importFromLib');
$canImportXmind      = hasPriv('testcase', 'importXmind');
$canCreateCase       = hasPriv('testcase', 'create');
$canBatchCreateCase  = hasPriv('testcase', 'batchCreate');
$canCreateScene      = hasPriv('testcase', 'createScene');
$canImportUnitResult = hasPriv('testtask', 'importUnitResult');
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

$linkParams = $projectParam . "productID=$productID&branch=$branch&browseType={key}&param=0&caseType={$caseType}";
$browseLink = createLink('testcase', 'browse', $linkParams);
if($app->tab == 'project') $browseLink = createLink('project', 'testcase', $linkParams);
if($rawMethod == 'browseunits') $browseLink = createLink('testtask', 'browseUnits', "productID=$productID&browseType={key}");

featureBar
(
    set::linkParams($rawMethod == 'zerocase' || $rawMethod == 'browseunits' ? null : $linkParams),
    set::link($rawMethod == 'zerocase' || $rawMethod == 'browseunits' ? $browseLink : null),
    set::current($rawMethod == 'browse' ? $this->session->caseBrowseType : null),
    set::load($load),
    set::app($app->tab),
    $canSwitchCaseType ? to::leading
    (
        dropdown
        (
            to('trigger', btn($currentTypeName, setClass('ghost'))),
            set::items($caseTypeItems),
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
    $canBrowseZeroCase && $rawMethod != 'groupcase' ? li
    (
        set::className('nav-item'),
        a
        (
            set::href($this->createLink('testcase', 'zeroCase', "productID=$productID&branch=$branch&orderBy=id_desc&projectID=" . ($isProjectApp ? $this->session->project : 0))),
            set('data-app', $app->tab),
            set('data-id', 'zerocaseTab'),
            set('class', $rawMethod == 'zerocase' ? 'active' : ''),
            $lang->testcase->zeroCase,
            ($rawMethod == 'zerocase' && $pager->recTotal != '') ? span(setClass('label size-sm rounded-full white'), $pager->recTotal) : null,
        )
    ) : null,
    $rawMethod == 'browse' ? li
    (
        set::className('nav-item'),
        a
        (
            set::href(str_replace('{key}', 'onlyScene', $browseLink)),
            set('class', $isOnlyScene ? 'active' : ''),
            set('data-load', $load),
            $lang->testcase->onlyScene
        )
    ) : null,
    $rawMethod != 'browseunits' && $rawMethod != 'zerocase' && $rawMethod != 'groupcase' ? li
    (
        set::className('nav-item mr-2'),
        checkbox
        (
            setID('onlyAutoCase'),
            set::checked($this->cookie->onlyAutoCase),
            bind::change('window.toggleOnlyAutoCase(event)'),
            $lang->testcase->onlyAutomated
        )
    ) : null,
    !in_array($rawMethod, array('browseunits', 'groupcase', 'zerocase')) ? searchToggle(set::module($this->app->rawMethod == 'testcase' ? 'testcase' : $this->app->rawModule), set::open($browseType == 'bysearch')) : null
);

$viewItems   = array(array('text' => $lang->testcase->listView, 'url' => $app->tab == 'project' ? createLink('project', 'testcase', "projectID={$projectID}") : inlink('browse', "productID=$productID&branch=$branch&browseType=all"), 'active' => $rawMethod != 'groupcase' ? true : false));
$exportItems = array();
$importItems = array();
$createItems = array();
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
    $params = "productID={$productID}&branch={$branch}&moduleID={$moduleID}";
    if($app->tab == 'project') $params .= "&from=project&param={$projectID}";
    $createItems[] = array('text' => $lang->testcase->create, 'url' => createLink('testcase', 'create', $params), 'data-app' => $app->tab);
}

if($canBatchCreateCase)
{
    $link = createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID");
    $createItems[] = array('text' => $lang->testcase->batchCreate, 'url' => $link, 'data-app' => $app->tab);
}

if($canCreateScene)
{
    $link = createLink('testcase', 'createScene', "productID=$productID&branch=$branch&moduleID=$moduleID");
    $createItems[] = array('text' => $lang->testcase->newScene, 'url' => $link, 'data-app' => $app->tab);
}

$currentCreateItem = current($createItems);

toolbar
(
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
        set::icon('wrench'),
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
            set(array('data-app' => $app->tab)),
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

if($rawMethod != 'zerocase' && $rawMethod != 'browseunits' && $rawMethod != 'groupcase')
{
    $settingLink = $canManageModule ? createLink('tree', 'browse', "productID=$productID&view=case&currentModuleID=0&branch=0&from={$app->tab}") : '';
    $closeLink   = createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=$browseType&param=0&caseType=&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}");
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
