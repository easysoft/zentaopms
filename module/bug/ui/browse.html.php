<?php
declare(strict_types=1);
/**
 * The browse view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('productID', $product->id);
jsVar('branch',    $branch);

$queryMenuLink = createLink('bug', 'browse', "productID={$product->id}&branch={$branch}&browseType=bySearch&param={queryID}");
featureBar
(
    set::current($browseType == 'bysearch' ? $param : $browseType),
    set::linkParams("product=$product->id&branch=$branch&browseType={key}"),
    set::queryMenuLinkCallback(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink)),
    li(searchToggle())
);

if(!isonlybody())
{
    $canCreate      = false;
    $canBatchCreate = false;
    if(common::canModify('product', $product))
    {
        $canCreate      = hasPriv('bug', 'create');
        $canBatchCreate = hasPriv('bug', 'batchCreate');

        $selectedBranch  = $branch != 'all' ? $branch : 0;
        $createLink      = $this->createLink('bug', 'create', "productID={$product->id}&branch=$selectedBranch&extra=moduleID=$currentModuleID");
        $batchCreateLink = $this->createLink('bug', 'batchCreate', "productID={$product->id}&branch=$branch&executionID=0&moduleID=$currentModuleID");
        if(commonModel::isTutorialMode())
        {
            $wizardParams = helper::safe64Encode("productID={$product->id}&branch=$branch&extra=moduleID=$currentModuleID");
            $createLink   = $this->createLink('tutorial', 'wizard', "module=bug&method=create&params=$wizardParams");
        }

        $createItem      = array('text' => $lang->bug->create,      'url' => $createLink);
        $batchCreateItem = array('text' => $lang->bug->batchCreate, 'url' => $batchCreateLink);
    }

    toolbar
    (
        hasPriv('bug', 'report') ? item(set(array
        (
            'icon'  => 'bar-chart',
            'text'  => $lang->bug->report->common,
            'class' => 'ghost',
            'url'   => createLink('bug', 'report', "productID=$product->id&browseType=$browseType&branch=$branch&module=$currentModuleID")
        ))) : null,
        hasPriv('bug', 'export') ? item(set(array
        (
            'text'  => $lang->bug->export,
            'icon'  => 'export',
            'class' => 'ghost',
            'url'   => createLink('bug', 'export', "productID={$product->id}&orderBy=$orderBy&browseType=$browseType"),
            'data-toggle' => 'modal'
        ))) : null,
        $canCreate && $canBatchCreate ? btngroup
        (
            btn(setClass('btn primary'), set::icon('plus'), set::url($createLink), $lang->bug->create),
            dropdown
            (
                btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
                set::items(array($createItem, $batchCreateItem)),
                set::placement('bottom-end'),
            )
        ) : null,
        $canCreate && !$canBatchCreate ? item(set($createItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null,
        $canBatchCreate && !$canCreate ? item(set($batchCreateItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null,
    );
}

$closeLink = $browseType == 'bymodule' ? createLink('bug', 'browse', "productID={$product->id}&branch=$branch&browseType=$browseType&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("bugModule")';
sidebar
(
    moduleMenu(set(array
    (
        'modules'   => $moduleTree,
        'activeKey' => $currentModuleID,
        'closeLink' => $closeLink
    )))
);

$config->bug->dtable->fieldList['module']['map']    = $modulePairs;
$config->bug->dtable->fieldList['product']['map']   = $products;
$config->bug->dtable->fieldList['story']['map']     = $stories;
$config->bug->dtable->fieldList['task']['map']      = $tasks;
$config->bug->dtable->fieldList['toTask']['map']    = $tasks;
$config->bug->dtable->fieldList['branch']['map']    = $branchTagOption;
$config->bug->dtable->fieldList['project']['map']   = $projectPairs;
$config->bug->dtable->fieldList['execution']['map'] = $executions;

$bugs = initTableData($bugs, $config->bug->dtable->fieldList, $this->bug);

$cols = array_values($config->bug->dtable->fieldList);
$data = array_values($bugs);

$footToolbar = array('items' => array
(
    array('type' => 'btn-group', 'items' => array
    (
        array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => createLink('bug', 'batchEdit', "productID={$product->id}&branch=$branch")),
        array('caret' => 'up', 'btnType' => 'primary', 'url' => '#navActions', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    )),
    array('caret' => 'up', 'text' => $lang->product->branchName[$this->session->currentProductType], 'className' => $this->session->currentProductType == 'normal' ? 'hidden' : '' , 'btnType' => 'primary', 'url' => '#navBranch', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    array('caret' => 'up', 'text' => $lang->bug->abbr->module, 'btnType' => 'primary', 'url' => '#navModule', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    array('caret' => 'up', 'text' => $lang->bug->assignedTo, 'btnType' => 'primary', 'url' => '#navAssignedTo','data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
));

$resolveItems = array();
foreach($lang->bug->resolutionList as $key => $resolution)
{
    if($key == 'duplicate' || $key == 'tostory') continue;
    if($key == 'fixed')
    {
        $buildItems = array();
        foreach($builds as $key => $build) $buildItems[] = array('text' => $build, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('bug', 'batchResolve', "resolution=fixed&resolvedBuild=$key"));

        $resolveItems[] = array('text' => $resolution, 'class' => 'not-hide-menu', 'items' => $buildItems);
    }
    else
    {
        $resolveItems[] = array('text' => $resolution, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('bug', 'batchResolve', "resolution=$key"));
    }
}

menu
(
    set::id('navActions'),
    set::class('menu dropdown-menu'),
    set::items(array
    (
        array('text' => $lang->bug->confirm, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('bug', 'batchConfirm')),
        array('text' => $lang->bug->close, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('bug', 'batchClose')),
        array('text' => $lang->bug->activate, 'class' => 'batch-btn', 'data-url' => helper::createLink('bug', 'batchActivate', "productID=$product->id&branch=$branch")),
        array('text' => $lang->bug->resolve, 'class' => 'not-hide-menu', 'items' => $resolveItems),
    ))
);

$branchItems = array();
foreach($branchTagOption as $branchID => $branchName)
{
    $branchItems[] = array('text' => $branchName, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('bug', 'batchChangeBranch', "branchID=$branchID"));
}

menu
(
    set::id('navBranch'),
    set::class('dropdown-menu'),
    set::items($branchItems)
);

$moduleItems = array();
foreach($modules as $moduleID => $module)
{
    $moduleItems[] = array('text' => $module, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('bug', 'batchChangeModule', "moduleID=$moduleID"));
}

menu
(
    set::id('navModule'),
    set::class('dropdown-menu'),
    set::items($moduleItems)
);

$assignedToItems = array();
foreach ($memberPairs as $key => $value)
{
    $assignedToItems[] = array('text' => $value, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('bug', 'batchAssignTo', "assignedTo=$key&productID={$product->id}&type=product"));
}

menu
(
    set::id('navAssignedTo'),
    set::class('dropdown-menu'),
    set::items($assignedToItems)
);

dtable
(
    set::userMap($users),
    set::cols($cols),
    set::data($data),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::footPager
    (
        usePager(),
        set::page($pager->pageID),
        set::recPerPage($pager->recPerPage),
        set::recTotal($pager->recTotal),
        set::linkCreator(helper::createLink('bug', 'browse', "productID={$product->id}&branch={$branch}&browseType={$browseType}&param={$param}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}"))
    ),
);

render();
