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

$canModify          = common::canModify('product', $product);
$canExport          = hasPriv('testcase', 'export');
$canExportTemplate  = hasPriv('testcase', 'exportTemplate');
$canExportXmind     = hasPriv('testcase', 'exportXmind');
$canImport          = hasPriv('testcase', 'import');
$canImportFromLib   = hasPriv('testcase', 'importFromLib');
$canImportXmind     = hasPriv('testcase', 'importXmind');
$canBrowseGroupCase = hasPriv('testcase', 'groupcase');
$canBrowseZeroCase  = hasPriv('testcase', 'zerocase');
$canBrowseUnits     = hasPriv('testtask', 'browseunits');

$lang->testcase->typeList[''] = $lang->testcase->allType;
if(!isset($param)) $param = 0;

$caseTypeItems = array();
foreach($lang->testcase->typeList as $type => $typeName)
{
    if($canBrowseUnits and $type == 'unit')
    {
        $url  = $this->createLink('testtask', 'browseUnits', "productID=$productID&browseType=newest&orderBy=id_desc&recTotal=0&recPerPage=20&pageID=1&projectID=$projectID");
        $text = $lang->testcase->browseUnits;
    }
    elseif(isset($groupBy))
    {
        $url  = $this->createLink('testcase', 'groupCase', "productID=$productID&branch=$branch&groupBy=story&projectID=$projectID&caseType=$type");
        $text = $typeName;
    }
    else
    {
        $url  = $this->createLink('testcase', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&param=$param&caseType=$type");
        $text = $typeName;
    }

    $caseTypeItems[] = array('text' => $text, 'url' => $url, 'active' => $type == $caseType);
}

$suiteItems = array();
if(empty($suiteList))
{
    if(empty($productID) or common::canModify('product', $product))
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

$otherItems = array();
$otherItems[] = array('text' => $lang->testcase->onlyScene);

$currentCaseType = zget($lang->testcase->typeList, $caseType, '');
$currentTypeName = empty($currentCaseType) ? $lang->testcase->allType : $currentCaseType;

$currentSuiteID   = isset($suiteID) ? (int)$suiteID : 0;
$currentSuite     = zget($suiteList, $currentSuiteID, '');
$currentSuiteName = empty($currentSuite) ? $lang->testsuite->common : $currentSuite->name;

$currentOtherName = $this->cookie->onlyScene ? $lang->testcase->onlyScene : $lang->other;

featureBar
(
    to::before
    (
        productMenu
        (
            set::title($currentTypeName),
            set::items($caseTypeItems)
        )
    ),
    set::linkParams($projectParam . "productID=$productID&branch=$branch&browseType={key}&param=0&caseType=$caseType"),
    $canBrowseZeroCase ? li
    (
        set::class('nav-item'),
        a
        (
            set::href($this->createLink('testcase', 'zeroCase', "productID=$productID&branch=$branch&orderBy=id_desc&projectID=" . ($isProjectApp ? $this->session->project : 0))),
            set('data-app', $app->tab),
            set('data-id', 'zerocaseTab'),
            $lang->testcase->zeroCase
        )
    ) : null,
    dropdown
    (
        btn
        (
            setClass('ghost'),
            $currentSuiteName
        ),
        set::items($suiteItems)
    ),
    dropdown
    (
        btn
        (
            setClass('ghost'),
            $currentOtherName
        ),
        set::items($otherItems)
    ),
    li(searchToggle(set::open($browseType == 'bysearch'))),
    li(btn(setClass('ghost'), set::icon('unfold-all'), $lang->sort))
);

$exportItems = array();
$importItems = array();
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
        $exportItems[] = array('text' => $lang->testcase->exportTemplate, 'url' => $link, 'data-toggle' => 'modal', 'data-app' => $app->tab, 'data-width' => '65%');
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

toolbar
(
    $exportItems ? dropdown
    (
        btn(
            setClass('btn btn-link ghost square'),
            set::icon('export')
        ),
        set::arrow(false),
        set::items($exportItems),
        set::placement('bottom-end'),
    ) : null,
    $importItems ? dropdown
    (
        btn(
            setClass('btn btn-link ghost square'),
            set::icon('import')
        ),
        set::arrow(false),
        set::items($importItems),
        set::placement('bottom-end'),
    ) : null,
    btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url(helper::createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule")),
            $lang->testcase->create
        ),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items
            (
                array
                (
                    array('text' => $lang->testcase->create,      'url' => helper::createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule")),
                    array('text' => $lang->testcase->batchCreate, 'url' => helper::createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$initModule")),
                    array('text' => $lang->testcase->newScene,    'url' => helper::createLink('testcase', 'createScene', "productID=$productID&branch=$branch&moduleID=$initModule"))
                )
            ),
            set::placement('bottom-end'),
        )
    )
);

$closeLink = $browseType == 'bymodule' ? createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=$browseType&param=0&caseType=&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("caseModule")';
sidebar
(
    moduleMenu(set(array
    (
        'modules'   => $moduleTree,
        'activeKey' => $moduleID,
        'closeLink' => $closeLink
    )))
);

$this->testcase->buildOperateMenu(null, 'browse');

foreach($cases as $case)
{
    $actions = array();
    foreach($this->config->testcase->dtable->fieldList['actions']['actionsMap'] as $actionCode => $actionMap)
    {
        $isClickable = $this->testcase->isClickable($case, $actionCode);

        $actions[] = $isClickable ? $actionCode : array('name' => $actionCode, 'disabled' => true);
    }
    $case->actions = $actions;
}

$footToolbar = array('items' => array
(
    array('type' => 'btn-group', 'items' => array
    (
        array('text' => $lang->testtask->runCase, 'className' => 'batch-btn', 'data-url' => helper::createLink('testtask', 'batchRun', "productID=$productID&orderBy=$orderBy")),
        array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => helper::createLink('bug', 'batchEdit', "productID={$product->id}&branch=$branch")),
        array('caret' => 'up', 'btnType' => 'primary', 'url' => '#navActions', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    )),
    array('caret' => 'up', 'text' => $lang->testcase->moduleAB, 'btnType' => 'primary', 'url' => '#navModule', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    array('text' => $lang->testcase->importToLib, 'btnType' => 'primary', 'data-toggle' => 'modal', 'data-url' => '#importToLib'),
    array('caret' => 'up', 'text' => $lang->testcase->scene, 'btnType' => 'primary', 'url' => '#navScene','data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
));

$typeItems = array();
foreach($lang->testcase->typeList as $key => $result) $typeItems[] = array('text' => $result, 'className' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchCaseTypeChange', "result=$key"));

/*
zui::menu
(
    set::id('navActions'),
    set::class('menu dropdown-menu'),
    set::items(array
    (
        array('text' => $lang->delete, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchDelete', "productID=$productID")),
        array('text' => $lang->testcase->type, 'class' => 'not-hide-menu', 'items' => $typeItems),
        array('text' => $lang->testcase->confirmStoryChange, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchConfirmStoryChange', "productID=$productID")),
    ))
);

$moduleItems = array();
foreach($modules as $moduleId => $module) $moduleItems[] = array('text' => $module, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchChangeModule', "moduleID=$moduleId"));

menu
(
    set::id('navModule'),
    set::class('dropdown-menu'),
    set::items($moduleItems)
);

$sceneItems = array();
foreach($iscenes as $sceneID => $scene) $sceneItems[] = array('text' => $scene, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchChangeScene', "sceneId=$sceneID"));

menu
(
    set::id('navScene'),
    set::class('dropdown-menu'),
    set::items($sceneItems)
);
 */

$config->testcase->dtable->fieldList['story']['map'] = $stories;

dtable
(
    set::userMap($users),
    set::cols(array_values($config->testcase->dtable->fieldList)),
    set::data(array_values($cases)),
    set::footPager(usePager()),
    set::checkable(true),
    set::footToolbar($footToolbar),
);

render();
