<?php
declare(strict_types=1);
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chen.tao<chentao@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('projectID', $projectID);

$isProjectStory    = $this->app->rawModule == 'projectstory';
$projectHasProduct = $isProjectStory && !empty($project->hasProduct);
$projectIDParam    = $isProjectStory ? "projectID=$projectID&" : '';
$storyBrowseType   = $this->session->storyBrowseType;
$branchType        = $showBranch ? $product->type : '';
$storyProductIds   = array();

foreach($stories as $story) $storyProductIds[$story->product] = $story->product;
$storyProductID = count($storyProductIds) > 1 ? 0 : $productID;

/* Generate sidebar to display module tree menu. */
$fnGenerateSideBar = function() use ($moduleTree, $moduleID, $productID, $branchID)
{
    global $app;
    $params = $app->rawParams;
    if(isset($params['browseType'])) $params['browseType'] = 'byModule';
    if(isset($params['param']))      $params['param']      = '';
    if(isset($params['recTotal']))   $params['recTotal']   = 0;
    if(isset($params['pageID']))     $params['pageID']     = 1;

    sidebar
    (
        moduleMenu(set(array
        (
            'modules'     => $moduleTree,
            'activeKey'   => $moduleID,
            'settingLink' => helper::createLink('tree', 'browse', "rootID=$productID&view=story&currentModuleID=0&branch=$branchID"),
            'closeLink'   => helper::createLink($app->rawModule, $app->rawMethod, http_build_query($params)),
        )))
    );
};

/* Build create story button. */
$fnBuildCreateStoryButton = function() use ($lang, $product, $isProjectStory, $storyType, $productID, $branch, $moduleID, $projectID)
{
    if(!common::canModify('product', $product)) return null;

    global $app;
    $createLink      = createLink('story', 'create', "product=$productID&branch=$branch&moduleID=$moduleID&storyID=0&projectID=$projectID&bugID=0&planID=0&todoID=0&extra=&storyType=$storyType");
    $batchCreateLink = createLink('story', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID&storyID=0&project=$projectID&plan=0&storyType=$storyType");

    $createBtnLink  = '';
    $createBtnTitle = '';
    if(hasPriv($storyType, 'create'))
    {
        $createBtnLink  = $createLink;
        $createBtnTitle = $lang->story->create;
    }
    elseif(hasPriv($storyType, 'batchCreate'))
    {
        $createBtnLink  = empty($productID) ? '' : $batchCreateLink;
        $createBtnTitle = $lang->story->batchCreate;
    }

    /* Without privilege, don't render create button. */
    if(empty($createBtnLink)) return null;

    /* With batch create privileges then render the dropdown menu. */
    if(!empty($productID) && hasPriv($storyType, 'batchCreate') && hasPriv($storyType, 'create'))
    {
        $items = array();

        if(commonModel::isTutorialMode())
        {
            /* Tutorial create link. */
            $wizardParams = helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID");
            if($isProjectStory) $wizardParams = helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID&storyID=&projectID=$projectID");
            $link = $this->createLink('tutorial', 'wizard', "module=story&method=create&params=$wizardParams");
            $items[] = array('text' => $lang->story->createCommon, 'icon' => 'plus', 'url' => $link);
        }
        else
        {
            $items[] = array('text' => $lang->story->create, 'icon' => 'plus', 'url' => $createLink);
        }

        $items[] = array('text' => $lang->story->batchCreate, 'icon' => 'plus', 'url' => $batchCreateLink);

        return btnGroup
        (
            btn
            (
                setClass($app->tab != 'product' ? 'secondary' : 'primary'),
                set::icon('plus'),
                set::text($createBtnTitle),
                set::url($createBtnLink)
            ),
            dropdown
            (
                span(setClass('caret')),
                setClass('btn'),
                setClass($app->tab != 'product' ? 'secondary' : 'primary'),
                setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0')),
                set::placement('bottom-end'),
                set::items($items),
            )
        );
    }

    return item(set(array
    (
        'text'  => $createBtnTitle,
        'icon'  => 'plus',
        'class' => $app->tab != 'product' ? 'secondary' : 'primary',
        'url'   => $createBtnLink
    )));
};

/* Build link story button. */
$fnBuildLinkStoryButton = function() use($lang, $product, $projectHasProduct, $project, $projectID)
{
    if(!common::canModify('product', $product)) return null;

    if(!$projectHasProduct) return null;

    /* Tutorial mode. */
    if(commonModel::isTutorialMode())
    {
        $wizardParams = helper::safe64Encode("project=$project->id");

        return item(set(array
        (
            'text' => $lang->project->linkStory,
            'url'  => createLink('tutorial', 'wizard', "module=project&method=linkStory&params=$wizardParams")
        )));
    }

    $buttonLink  = '';
    $buttonTitle = '';
    if(common::hasPriv('projectstory', 'linkStory'))
    {
        $buttonLink  = $this->createLink('projectstory', 'linkStory', "project=$projectID");
        $buttonTitle = $lang->execution->linkStory;
    }
    if(empty($buttonLink)) return null;

    return item(set(array
    (
        'text'  => $buttonTitle,
        'icon'  => 'link',
        'class' => 'primary',
        'url'   => $buttonLink
    )));
};

/* DataTable columns. */
$setting = $this->datatable->getSetting('product');
if($storyType == 'requirement') unset($setting['plan'], $setting['stage'], $setting['taskCount'], $setting['bugCount'], $setting['caseCount']);
$cols = array_values($setting);

/* DataTable data. */
$this->loadModel('story');

$data    = array();
$options = array('storyTasks' => $storyTasks, 'storyBugs' => $storyBugs, 'storyCases' => $storyCases, 'modules' => $modules, 'plans' => (isset($plans) ? $plans : array()), 'users' => $users, 'execution' => $project);
foreach($stories as $story)
{
    $options['branches'] = zget($branchOptions, $story->product, array());
    $data[] = $this->story->formatStoryForList($story, $options);
    if(!isset($story->children)) continue;

    /* Children. */
    foreach($story->children as $key => $child) $data[] = $this->story->formatStoryForList($child, $options);
}

data('storyBrowseType', $storyBrowseType);

$canBeChanged         = common::canModify('product', $product);
$canBatchEdit         = ($canBeChanged and common::hasPriv($storyType, 'batchEdit'));
$canBatchClose        = (common::hasPriv($storyType, 'batchClose') and strtolower($browseType) != 'closedbyme' and strtolower($browseType) != 'closedstory');
$canBatchReview       = ($canBeChanged and common::hasPriv($storyType, 'batchReview'));
$canBatchChangeStage  = ($canBeChanged and common::hasPriv('story', 'batchChangeStage') and $storyType == 'story');
$canBatchChangeBranch = ($canBeChanged and common::hasPriv($storyType, 'batchChangeBranch') and $this->session->currentProductType and $this->session->currentProductType != 'normal' and $productID);
$canBatchChangeModule = ($canBeChanged and common::hasPriv($storyType, 'batchChangeModule'));
$canBatchChangePlan   = ($canBeChanged and common::hasPriv('story', 'batchChangePlan') and $storyType == 'story' and (!$isProjectStory or $projectHasProduct or ($isProjectStory and isset($project->model) and $project->model == 'scrum')));
$canBatchAssignTo     = ($canBeChanged and common::hasPriv($storyType, 'batchAssignTo'));
$canBatchUnlink       = ($canBeChanged and $projectHasProduct and common::hasPriv('projectstory', 'batchUnlinkStory'));
$canBatchImportToLib  = ($canBeChanged and $isProjectStory and isset($this->config->maxVersion) and common::hasPriv('story', 'batchImportToLib') and helper::hasFeature('storylib'));
$canBatchAction       = ($canBatchEdit or $canBatchClose or $canBatchReview or $canBatchChangeStage or $canBatchChangeModule or $canBatchChangePlan or $canBatchAssignTo or $canBatchUnlink or $canBatchImportToLib or $canBatchChangeBranch);
$footToolbar = $canBatchAction ? array('items' => array
(
    array('type' => 'btn-group', 'items' => array
    (
        array('text' => $lang->edit, 'className' => 'secondary batch-btn', 'disabled' => ($canBatchEdit ? '': 'disabled'), 'data-page' => 'batch', 'data-formaction' => $this->createLink('story', 'batchEdit', "productID=$storyProductID&projectID=$projectID&branch=$branch&storyType=$storyType")),
        array('caret' => 'up', 'className' => 'size-sm secondary', 'url' => '#navActions', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    )),
    !$canBatchUnlink ? null : array('text' => $lang->story->unlink, 'className' => 'secondary', 'id' => 'batchUnlinkStory'),
    array('caret' => 'up', 'text' => $lang->story->moduleAB, 'className' => $canBatchChangeModule ? '' : 'hidden', 'url' => '#navModule', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    array('caret' => 'up', 'text' => $lang->story->planAB, 'className' => $canBatchChangePlan ? '' : 'hidden', 'url' => '#navPlan', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    array('caret' => 'up', 'text' => $lang->story->assignedTo, 'className' => ($canBatchAssignTo ? '' : 'hidden'), 'url' => '#navAssignedTo', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    !$canBatchImportToLib ? null : array('text' => $lang->story->importToLib, 'className' => 'btn secondary', 'id' => 'importToLib', 'data-toggle' => 'modal', 'url' => '#batchImportToLib'),
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary')) : null;

unset($lang->story->reviewResultList[''], $lang->story->reviewResultList['revert']);
unset($lang->story->reasonList[''], $lang->story->reasonList['subdivided'], $lang->story->reasonList['duplicate']);
unset($plans[''], $lang->story->stageList[''], $users['']);

foreach($lang->story->reviewResultList as $key => $result) $reviewResultItems[$key] = array('text' => $result,     'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchReview', "result=$key"));
foreach($lang->story->reasonList as $key => $reason)       $reviewRejectItems[]     = array('text' => $reason,     'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchReview', "result=reject&reason=$key"));
foreach($branchTagOption as $branchID => $branchName)      $branchItems[]           = array('text' => $branchName, 'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchChangeBranch', "branchID=$branchID"));
foreach($modules as $moduleID => $moduleName)              $moduleItems[]           = array('text' => $moduleName, 'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchChangeModule', "moduleID=$moduleID"));
foreach($plans as $planID => $planName)                    $planItems[]             = array('text' => $planName,   'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchChangePlan', "planID=$planID"));
foreach($lang->story->stageList as $key => $stageName)
{
    if(!str_contains('|tested|verified|released|closed|', "|$key|")) continue;
    $stageItems[] = array('text' => $stageName,  'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchChangeStage', "stage=$key"));
}
foreach($users as $account => $realname)
{
    if($account == 'closed') continue;
    $assignItems[] = array('text' => $realname, 'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchAssignTo', "productID={$product->id}"), 'data-account' => $account);
}

if(isset($reviewResultItems['reject'])) $reviewResultItems['reject'] = array('class' => 'not-hide-menu', 'text' => $lang->story->reviewResultList['reject'], 'items' => $reviewRejectItems);
$reviewResultItems = array_values($reviewResultItems);

$navActionItems = array();
if($canBatchClose)  $navActionItems[] = array('text' => $lang->close, 'class' => 'batch-btn', 'data-page' => 'batch', 'data-formaction' => helper::createLink('story', 'batchClose', "productID={$product->id}"));
if($canBatchReview) $navActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->review, 'items' => $reviewResultItems);
if($canBatchChangeBranch && $product->type != 'normal') $navActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->product->branchName[$product->type], 'items' => $branchItems);
if($canBatchChangeStage)  $navActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->stageAB, 'items' => $stageItems);

featureBar
(
    set::current($storyBrowseType),
    set::link(createLink($app->rawModule, $app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType={key}&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&projectID=$projectID")),
    li(searchToggle(set::open($browseType == 'bysearch'), set::module('story'))),
    div(
        zui::menu
        (
            set::id('navActions'),
            set::class('menu dropdown-menu'),
            set::items($navActionItems),
        ),
        zui::menu
        (
            set::id('navModule'),
            set::class('dropdown-menu'),
            set::items($moduleItems)
        ),
        zui::menu
        (
            set::id('navPlan'),
            set::class('dropdown-menu'),
            set::items($planItems)
        ),
        zui::menu
        (
            set::id('navAssignedTo'),
            set::class('dropdown-menu'),
            set::items($assignItems)
        ),
    ),
);

toolbar
(
    !common::hasPriv('story', 'report') ? null : item(set(array('text' => $lang->project->report, 'icon' => 'bar-chart', 'class' => 'ghost', 'url' => helper::createLink('story', 'report', "productID=$productID&branchID=$branch&storyType=$storyType&browseType=$browseType&moduleID=$moduleID&chartType=pie&projectID=$projectID")))),
    !common::hasPriv('story', 'export') ? null : item(set(array('text' => $lang->export, 'icon' => 'export', 'class' => 'ghost', 'url' => helper::createLink('story', 'export', "productID=$productID&orderBy=$orderBy&executionID=$projectID&browseType=$browseType&storyType=$storyType"), 'data-toggle' => 'modal'))),
    $fnBuildCreateStoryButton(),
    $fnBuildLinkStoryButton(),
);

/* Layout. */
$fnGenerateSideBar();

dtable
(
    set::id('stories'),
    set::userMap($users),
    set::customCols(true),
    set::checkable($canBatchAction),
    set::cols($cols),
    set::data($data),
    set::footPager(usePager()),
    set::footToolbar($footToolbar),
    set::footer(array('checkbox', 'toolbar', array('html' => $summary, 'className' => "text-dark"), 'flex', 'pager')),
);

modal(set::id('#batchUnlinkStoryBox'));

render();
