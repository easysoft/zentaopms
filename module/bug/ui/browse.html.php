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

$this->bug->setOperateActions($view = 'browse');

foreach($bugs as $bug)
{
    $bug->productName  = zget($products, $bug->product);
    $bug->storyName    = zget($stories, $bug->story);
    $bug->taskName     = zget($tasks, $bug->task);
    $bug->toTaskName   = zget($tasks, $bug->toTask);
    $bug->module       = zget($modulePairs, $bug->module);
    $bug->branch       = zget($branchTagOption, $bug->branch);
    $bug->project      = zget($projectPairs, $bug->project);
    $bug->execution    = zget($executions, $bug->execution);
    $bug->openedBy     = zget($users, $bug->openedBy); 
    $bug->assignedTo   = zget($users, $bug->assignedTo); 
    $bug->resolvedBy   = zget($users, $bug->resolvedBy); 
    $bug->mailto       = zget($users, $bug->mailto); 
    $bug->closedBy     = zget($users, $bug->closedBy); 
    $bug->lastEditedBy = zget($users, $bug->lastEditedBy); 
    $bug->type         = zget($lang->bug->typeList, $bug->type); 
    $bug->confirmed    = zget($lang->bug->confirmedList, $bug->confirmed); 
    $bug->resolution   = zget($lang->bug->resolutionList, $bug->resolution); 
    $bug->os           = zget($lang->bug->osList, $bug->os); 
    $bug->browser      = zget($lang->bug->browserList, $bug->browser); 

    $actions = array();
    foreach($this->config->bug->dtable->fieldList['actions']['actionsMap'] as $actionCode => $actionMap)
    {
        $isClickable = $this->bug->isClickable($bug, $actionCode);

        $actions[] = $isClickable ? $actionCode : array('name' => $actionCode, 'disabled' => true);
    }
    $bug->actions = $actions;
}

$cols = array_values($config->bug->dtable->fieldList);
$data = array_values($bugs);

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

featureBar
(
    set::current($browseType),
    set::linkParams("product=$product->id&branch=$branch&browseType={key}"),
    li(searchToggle())
);

if(!isonlybody())
{
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
        $canCreate && !$canBatchCreate ? item(set($createItem)) : null,
        $canBatchCreate && !$canCreate ? item(set($batchCreateItem)) : null,
    );
}

sidebar
(
    moduleMenu(set(array
    (
        'modules'   => $moduleTree,
        'activeKey' => $currentModuleID,
        'closeLink' => createLink('bug', 'browse', "productID=$product->id&branch=$branch")
    )))
);

dtable
(
    set::cols($cols),
    set::data($data),
    set::footPager(usePager()),
    set::footer(jsRaw('window.footerGenerator'))
);

render();
