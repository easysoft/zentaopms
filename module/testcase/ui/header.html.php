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

$isProjectApp  = $this->app->tab == 'project';
$currentModule = $isProjectApp ? 'project'  : 'testcase';
$currentMethod = $isProjectApp ? 'testcase' : 'browse';
$projectParam  = $isProjectApp ? "projectID={$this->session->project}&" : '';
$initModule    = isset($moduleID) ? (int)$moduleID : 0;
$rawMethod     = $app->rawMethod;

$canModify          = common::canModify('product', $product);
$canSwitchCaseType  = $this->app->tab == 'qa';
$canDisplaySuite    = $this->app->tab == 'qa';
$canManageModule    = hasPriv('tree', 'browse') && !empty($productID);
$canCreateSuite     = hasPriv('testsuite', 'create');
$canBrowseUnits     = hasPriv('testtask', 'browseunits');
$canBrowseZeroCase  = hasPriv('testcase', 'zerocase');
$canBrowseGroupCase = hasPriv('testcase', 'groupcase');
$canAutomation      = hasPriv('testcase', 'automation') && !empty($productID);
$canExport          = hasPriv('testcase', 'export');
$canExportTemplate  = hasPriv('testcase', 'exportTemplate');
$canExportXmind     = hasPriv('testcase', 'exportXmind');
$canImport          = hasPriv('testcase', 'import');
$canImportFromLib   = hasPriv('testcase', 'importFromLib');
$canImportXmind     = hasPriv('testcase', 'importXmind');
$canCreateCase      = hasPriv('testcase', 'create');
$canBatchCreateCase = hasPriv('testcase', 'batchCreate');
$canCreateScene     = hasPriv('testcase', 'createScene');
$canCreate          = $canCreateCase || $canBatchCreateCase || $canCreateScene;

$lang->testcase->typeList[''] = $lang->testcase->allType;
if(!isset($param)) $param = 0;

if($rawMethod == 'zerocase') $caseType = '';

if($canSwitchCaseType)
{
    /* Process variables of case type menu. */
    $currentCaseType = zget($lang->testcase->typeList, $caseType, '');
    $currentTypeName = empty($currentCaseType) ? $lang->testcase->allType : $currentCaseType;
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
            $url  = $this->createLink('testcase', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&param=$param&caseType=$type");
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
        if($canCreateSuite && (empty($productID) || common::canModify('product', $product)))
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
featureBar
(
    set::linkParams($rawMethod == 'zerocase' ? null : $linkParams),
    set::link($rawMethod == 'zerocase' ? $browseLink : null),
    $canSwitchCaseType ? to::before
    (
        productMenu
        (
            set::title($currentTypeName),
            set::items($caseTypeItems)
        )
    ) : null,
    $canBrowseZeroCase ? li
    (
        set::class('nav-item'),
        a
        (
            set::href($this->createLink('testcase', 'zeroCase', "productID=$productID&branch=$branch&orderBy=id_desc&projectID=" . ($isProjectApp ? $this->session->project : 0))),
            set('data-app', $app->tab),
            set('data-id', 'zerocaseTab'),
            set('class', $rawMethod == 'zerocase' ? 'active' : ''),
            $lang->testcase->zeroCase
        )
    ) : null,
    $canDisplaySuite ? dropdown
    (
        btn
        (
            setClass('ghost'),
            $currentSuiteName
        ),
        set::items($suiteItems)
    ) : null,
    li
    (
        set::class('nav-item'),
        a($lang->testcase->onlyAutomated)
    ),
    li
    (
        set::class('nav-item'),
        a($lang->testcase->onlyScene)
    ),
    li(searchToggle(set::open($browseType == 'bysearch'))),
    li(btn(setClass('ghost'), set::icon('unfold-all'), $lang->sort))
);

$viewItems   = array(array('text' => $lang->testcase->listView, 'active' => true));
$exportItems = array();
$importItems = array();
$createItems = array();
if($canBrowseGroupCase)
{
    $link = inlink('groupCase', "productID=$productID&branch=$branch&groupBy=story&projectID=$projectID&caseType=$caseType");
    $viewItems[] = array('text' => $lang->testcase->groupView, 'url' => $link, 'data-app' => $app->tab);
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
            $importItems[] = array('url' => $link, 'text' => $lang->testcase->importFromLib, 'data-toggle' => 'modal', 'data-app' => $app->tab);
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
    $link = createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule");
    $createItems[] = array('text' => $lang->testcase->create, 'url' => $link);
}

if($canBatchCreateCase)
{
    $link = createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$initModule");
    $createItems[] = array('text' => $lang->testcase->batchCreate, 'url' => $link);
}

if($canCreateScene)
{
    $link = createLink('testcase', 'createScene', "productID=$productID&branch=$branch&moduleID=$initModule");
    $createItems[] = array('text' => $lang->testcase->newScene, 'url' => $link);
}

$currentCreateItem = current($createItems);

toolbar
(
    $viewItems ? dropdown
    (
        btn
        (
            setClass('btn btn-link ghost square'),
            set::icon('kanban')
        ),
        set::items($viewItems),
        set::placement('bottom-end'),
    ) : null,
    $canAutomation ? btn
    (
        set
        (
            array('icon' => 'wrench', 'hint' => $lang->testcase->automation, 'url' => inlink('automation', "productID=$productID"), 'class' => 'btn btn-link ghost square', 'data-toggle' => 'modal', 'data-width' => '50%')
        )
    ) : null,
    $exportItems ? dropdown
    (
        btn
        (
            setClass('btn btn-link ghost square'),
            set::icon('export')
        ),
        set::items($exportItems),
        set::placement('bottom-end'),
    ) : null,
    $importItems ? dropdown
    (
        btn
        (
            setClass('btn btn-link ghost square'),
            set::icon('import')
        ),
        set::items($importItems),
        set::placement('bottom-end'),
    ) : null,
    $canCreate ? btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url($currentCreateItem['url']),
            $currentCreateItem['text']
        ),
        count($createItems) > 1 ?  dropdown
        (
            btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items($createItems),
            set::placement('bottom-end'),
        ) : null,
    ) : null,
);

if($rawMethod != 'zerocase')
{
    $settingLink = $canManageModule ? createLink('tree', 'browse', "productID=$productID&view=case&currentModuleID=0&branch=0&from={$app->tab}") : '';
    $closeLink   = $browseType == 'bymodule' ? createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=$browseType&param=0&caseType=&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("caseModule")';
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
