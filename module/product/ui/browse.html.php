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

data('activeMenuID', $storyType);
jsVar('URChanged', $this->lang->story->URChanged);

$storyCommon       = $storyType == 'requirement' ? $lang->URCommon : $lang->SRCommon;
$isProjectStory    = $this->app->rawModule == 'projectstory';
$projectHasProduct = $isProjectStory && !empty($project->hasProduct);
$projectIDParam    = $isProjectStory ? "projectID=$projectID&" : '';
$storyBrowseType   = $this->session->storyBrowseType;
$branchType        = $showBranch ? $product->type : '';
$storyProductIds   = array();

foreach($stories as $story) $storyProductIds[$story->product] = $story->product;
$storyProductID = count($storyProductIds) > 1 ? 0 : $productID;

/* Generate sidebar to display module tree menu. */
$fnGenerateSideBar = function() use ($moduleTree, $moduleID, $productID, $branchID, $projectHasProduct, $param, $isProjectStory)
{
    global $app;
    $params = $app->rawParams;
    if(isset($params['browseType'])) $params['browseType'] = 'byModule';
    if(isset($params['param']))      $params['param']      = '';
    if(isset($params['recTotal']))   $params['recTotal']   = 0;
    if(isset($params['pageID']))     $params['pageID']     = 1;
    if($isProjectStory && $params['productID']) $params['productID'] = 0;

    sidebar
    (
        moduleMenu
        (
            set::modules($moduleTree),
            set::activeKey($isProjectStory && empty($param) && !empty($productID) ? $productID : $moduleID),
            set::closeLink(helper::createLink($app->rawModule, $app->rawMethod, http_build_query($params))),
            $productID ? set::settingLink(helper::createLink('tree', 'browse', "rootID=$productID&view=story&currentModuleID=0&branch=$branchID")) : null,
            $projectHasProduct ? set::settingApp('product') : null
        )
    );
};

/* Build create story button. */
$fnBuildCreateStoryButton = function() use ($lang, $product, $isProjectStory, $storyType, $productID, $branch, $moduleID, $projectID)
{
    if(!common::canModify('product', $product)) return null;

    global $app;
    $createLink      = createLink('story', 'create', "product=$productID&branch=$branch&moduleID=$moduleID&storyID=0&projectID=$projectID&bugID=0&planID=0&todoID=0&extra=&storyType=$storyType") . ($isProjectStory ? '#app=project' : '');
    $batchCreateLink = createLink('story', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID&storyID=0&project=$projectID&plan=0&storyType=$storyType"). ($isProjectStory ? '#app=project' : '');

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
            $items[] = array('text' => $lang->story->createCommon, 'url' => $link);
        }
        else
        {
            $items[] = array('text' => $lang->story->create, 'url' => $createLink);
        }

        $items[] = array('text' => $lang->story->batchCreate, 'url' => $batchCreateLink);

        return btnGroup
        (
            btn
            (
                setClass(($app->tab != 'product' ? 'secondary' : 'primary') . ' create-story-btn'),
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
                set::items($items)
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
$fnBuildLinkStoryButton = function() use($lang, $app, $product, $projectHasProduct, $project)
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

    $canLinkStory     = common::hasPriv('projectstory', 'linkStory');
    $canlinkPlanStory = !empty($product) && common::hasPriv('projectstory', 'importPlanStories');
    $linkStoryUrl     = $this->createLink('projectstory', 'linkStory', "project=$project->id");
    $linkItem         = array('text' => $lang->execution->linkStory, 'url' => $linkStoryUrl);
    $linkPlanItem     = array('text' => $lang->execution->linkStoryByPlan, 'url' => '#linkStoryByPlan', 'data-toggle' => 'modal', 'data-size' => 'sm');
    if($canLinkStory && $canlinkPlanStory)
    {
        return btngroup
        (
            btn(
                setClass('btn primary'),
                set::icon('link'),
                set::url($linkStoryUrl),
                setData('app', $app->tab),
                $lang->execution->linkStory
            ),
            dropdown
            (
                btn(setClass('btn primary dropdown-toggle'),
                setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
                set::items(array_filter(array($linkItem, $linkPlanItem))),
                set::placement('bottom-end')
            )
        );

    }
    if($canLinkStory && !$canlinkPlanStory) return item(set($linkItem + array('class' => 'btn primary link-story-btn', 'icon' => 'plus')));
    if($canlinkPlanStory && !$canLinkStory) return item(set($linkPlanItem + array('class' => 'btn primary', 'icon' => 'plus')));
};

/* DataTable columns. */
$config->story->dtable->fieldList['title']['title'] = $lang->story->title;
if($app->rawModule == 'projectstory') $config->story->dtable->fieldList['title']['link'] = array('url' => helper::createLink('projectstory', 'view', 'storyID={id}&projectID={project}'), 'style' => array('color' => 'var(--color-link)'));

$setting = $this->loadModel('datatable')->getSetting('product', 'browse', false, $storyType);
if($storyType == 'requirement') unset($setting['plan'], $setting['stage'], $setting['taskCount'], $setting['bugCount'], $setting['caseCount']);
$cols = array_values($setting);

/* DataTable data. */
$this->loadModel('story');

$data    = array();
$options = array('storyTasks' => $storyTasks, 'storyBugs' => $storyBugs, 'storyCases' => $storyCases, 'modules' => $modules, 'plans' => (isset($plans) ? $plans : array()), 'users' => $users, 'execution' => $project);
foreach($stories as $story)
{
    $story->rawModule    = $story->module;
    $options['branches'] = zget($branchOptions, $story->product, array());
    $data[] = $this->story->formatStoryForList($story, $options, $storyType);
    if(!isset($story->children)) continue;

    /* Children. */
    foreach($story->children as $key => $child)
    {
        $child->rawModule = $child->module;
        $data[] = $this->story->formatStoryForList($child, $options, $storyType);
    }
}

/* Generate toolbar of DataTable footer. */
$fnGenerateFootToolbar = function() use ($lang, $product, $productID, $project, $storyType, $browseType, $isProjectStory, $projectHasProduct, $storyProductID, $projectID, $branch, $users, $branchTagOption, $modules, $plans)
{
    /* Flag variables of permissions. */
    $canBeChanged         = common::canModify('product', $product);
    $canBatchEdit         = $canBeChanged && hasPriv($storyType, 'batchEdit');
    $canBatchClose        = hasPriv($storyType, 'batchClose') && strtolower($browseType) != 'closedbyme' && strtolower($browseType) != 'closedstory';
    $canBatchReview       = $canBeChanged && hasPriv($storyType, 'batchReview');
    $canBatchChangeStage  = $canBeChanged && hasPriv('story', 'batchChangeStage') && $storyType == 'story';
    $canBatchChangeBranch = $canBeChanged && hasPriv($storyType, 'batchChangeBranch') && $product && $product->type != 'normal' && $productID;
    $canBatchChangeModule = $canBeChanged && hasPriv($storyType, 'batchChangeModule') && $productID && $product && $product->type == 'normal';
    $canBatchChangePlan   = $canBeChanged && hasPriv('story', 'batchChangePlan') && $storyType == 'story' && (!$isProjectStory || $projectHasProduct || ($isProjectStory && isset($project->model) && $project->model == 'scrum')) && $productID && $product && $product->type == 'normal';
    $canBatchAssignTo     = $canBeChanged && hasPriv($storyType, 'batchAssignTo');
    $canBatchUnlink       = $canBeChanged && $projectHasProduct && hasPriv('projectstory', 'batchUnlinkStory');
    $canBatchImportToLib  = $canBeChanged && $isProjectStory && isset($this->config->maxVersion) && hasPriv('story', 'batchImportToLib') && helper::hasFeature('storylib');
    $canBatchAction       = $canBatchEdit || $canBatchClose || $canBatchReview || $canBatchChangeStage || $canBatchChangeModule || $canBatchChangePlan || $canBatchAssignTo || $canBatchUnlink || $canBatchImportToLib || $canBatchChangeBranch;

    /* Remove empty data from data list. */
    unset($lang->story->reviewResultList[''], $lang->story->reviewResultList['revert']);
    unset($lang->story->reasonList[''], $lang->story->reasonList['subdivided'], $lang->story->reasonList['duplicate']);
    unset($plans[''], $lang->story->stageList[''], $users['']);

    /* Generate dropdown menu items for the DataTable footer toolbar.*/
    $planItems = $planItems ?? array();
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
        $assignItems[] = array('text' => $realname, 'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchAssignTo', "productID={$productID}"), 'data-account' => $account);
    }

    if(isset($reviewResultItems['reject'])) $reviewResultItems['reject'] = array('class' => 'not-hide-menu', 'text' => $lang->story->reviewResultList['reject'], 'items' => $reviewRejectItems);
    $reviewResultItems = array_values($reviewResultItems);

    $navActionItems = array();
    if($canBatchClose)  $navActionItems[] = array('class' => 'batch-btn batchClostBtn', 'text' => $lang->close, 'data-page' => 'batch', 'data-formaction' => helper::createLink('story', 'batchClose', "productID={$productID}"));
    if($canBatchReview) $navActionItems[] = array('class' => 'not-hide-menu batchReviewBtn', 'text' => $lang->story->review, 'items' => $reviewResultItems);
    if($canBatchChangeStage)  $navActionItems[] = array('class' => 'not-hide-menu batchChangeStageBtn', 'text' => $lang->story->stageAB, 'items' => $stageItems);

    if(!$canBatchAction) return array();
    $items = array
    (
        /* Edit button group. */
        array('type' => 'btn-group', 'items' => array
        (
            /* Edit button. */
            array
            (
                'text'      => $lang->edit,
                'className' => 'secondary batch-btn',
                'disabled'  => ($canBatchEdit ? '': 'disabled'),
                'data-page' => 'batch',
                'data-formaction' => $this->createLink('story', 'batchEdit', "productID=$storyProductID&projectID=$projectID&branch=$branch&storyType=$storyType")
            ),
            /* Popup menu trigger icon. */
            array('caret' => 'up', 'className' => 'size-sm secondary', 'items' => $navActionItems, 'data-toggle' => 'dropdown', 'data-placement' => 'top-start')
        )),
        /* Unlink stories button. */
        !$canBatchUnlink ? null : array
        (
            'text' => $lang->story->unlink,
            'className' => 'secondary batchUnlinkStory'
        ),
        /* Module button. */
        array('caret' => 'up', 'text' => $lang->story->moduleAB, 'className' => $canBatchChangeModule ? 'secondary batchChangeModuleBtn' : 'hidden', 'items' => $moduleItems, 'type' => 'dropdown', 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true)),
        /* Plan button. */
        array('caret' => 'up', 'text' => $lang->story->planAB, 'className' => $canBatchChangePlan ? 'secondary batchCnangePlanBtn' : 'hidden', 'items' => $planItems, 'type' => 'dropdown', 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true)),
        /* Change branch button. */
        ($canBatchChangeBranch && $product->type != 'normal') ? array('caret' => 'up', 'text' => $lang->product->branchName[$product->type], 'className' => 'batchChangeBranchBtn', 'items' => $branchItems, 'type' => 'dropdown', 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true)) : null,
        /* AssignedTo button. */
        array('caret' => 'up', 'text' => $lang->story->assignedTo, 'className' => ($canBatchAssignTo ? 'secondary batchAssignToBtn' : 'hidden'), 'items' => $assignItems, 'type' => 'dropdown', 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true)),
        /* Batch import to lib button .*/
        !$canBatchImportToLib ? null : array('text' => $lang->story->importToLib, 'className' => 'btn secondary batchImportToLibBtn', 'id' => 'importToLib', 'data-toggle' => 'modal', 'url' => '#batchImportToLib')
    );

    return array
    (
        'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'),
        'items' => array_values(array_filter($items))
    );
};

/* Layout. */
global $app;
if($app->rawModule == 'projectstory' && !empty($project)) dropmenu(set::text($project->name));

data('storyBrowseType', $storyBrowseType);

jsVar('childrenAB',     $lang->story->childrenAB);
jsVar('projectID',      $projectID);
jsVar('modulePairs',    $modulePairs);
jsVar('showBranch',     $showBranch);
jsVar('storyType',      $storyType);
jsVar('checkedSummary', $storyType == 'story' ? $lang->product->checkedSRSummary : $lang->product->checkedURSummary);

if($isProjectStory and $storyType == 'requirement')
{
    unset($lang->projectstory->featureBar['story']['linkedexecution']);
    unset($lang->projectstory->featureBar['story']['unlinkedexecution']);
}

featureBar
(
    set::current($storyBrowseType),
    set::link(createLink($app->rawModule, $app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType={key}&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&projectID=$projectID")),
    li(searchToggle(set::open($browseType == 'bysearch'), set::module('story')))
);

toolbar
(
    (!hasPriv('story', 'report') || !$productID) ? null : item(set(array('id' => 'reportBtn', 'text' => $lang->story->report->common, 'icon' => 'bar-chart', 'class' => 'ghost', 'url' => helper::createLink('story', 'report', "productID=$productID&branchID=$branch&storyType=$storyType&browseType=$browseType&moduleID=$moduleID&chartType=pie&projectID=$projectID" . ($app->tab == 'project' ? '#app=project' : ''))))),
    !hasPriv('story', 'export') ? null : item(set(array('id' => 'exportBtn', 'text' => $lang->export, 'icon' => 'export', 'class' => 'ghost', 'url' => helper::createLink('story', 'export', "productID=$productID&orderBy=$orderBy&executionID=$projectID&browseType=$browseType&storyType=$storyType"), 'data-toggle' => 'modal'))),
    $fnBuildCreateStoryButton(),
    $fnBuildLinkStoryButton()
);

$fnGenerateSideBar();

$footToolbar = $fnGenerateFootToolbar();
$sortLink    = createLink('product', 'browse', "productID={$productID}&branch={$branch}&browseType={$browseType}&param={$param}&storyType={$storyType}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&projectID=$projectID");
if($this->app->rawModule == 'projectstory') $sortLink = createLink('projectstory', 'story', "projectID={$projectID}&productID={$productID}&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");
dtable
(
    set::id('stories'),
    set::rowKey('uniqueID'),
    set::userMap($users),
    set::customCols(array('url' => createLink('datatable', 'ajaxcustom', "module={$app->moduleName}&method={$app->methodName}&extra={$storyType}"), 'globalUrl' => createLink('datatable', 'ajaxsaveglobal', "module={$app->moduleName}&method={$app->methodName}&extra={$storyType}"))),
    set::checkable(!empty($footToolbar)),  // The user can do batch action if this parameter is not false(true, null).
    set::cols($cols),
    set::data($data),
    set::sortLink($sortLink),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::checkInfo(jsRaw("function(checkedIdList){return window.setStatistics(this, checkedIdList, '{$summary}');}")),
    set::footPager(usePager()),
    set::footToolbar($footToolbar),
    set::emptyTip($storyType == 'story' ? $lang->story->noStory : $lang->story->noRequirement),
    set::createTip($lang->story->create),
    set::createLink(hasPriv($storyType, 'create') ? createLink('story', 'create', "product=$productID&branch=$branch&moduleID=$moduleID&storyID=0&projectID=$projectID&bugID=0&planID=0&todoID=0&extra=&storyType=$storyType") . ($isProjectStory ? '#app=project' : '') : '')
);

modal(set::id('#batchUnlinkStoryBox'));

$linkStoryByPlanTips = $lang->execution->linkNormalStoryByPlanTips;
if($product && $product->type != 'normal') $linkStoryByPlanTips = sprintf($lang->execution->linkBranchStoryByPlanTips, $lang->product->branchName[$product->type]);
if($isProjectStory) $linkStoryByPlanTips = str_replace($lang->execution->common, $lang->projectCommon, $linkStoryByPlanTips);
modal
(
    setID('linkStoryByPlan'),
    set::modalProps(array('title' => $lang->execution->linkStoryByPlan)),
    div
    (
        setClass('flex-auto'),
        icon('info-sign', setClass('warning-pale rounded-full mr-1')),
        $linkStoryByPlanTips
    ),
    form
    (
        setClass('text-center', 'py-4'),
        set::actions(array('submit')),
        set::submitBtnText($lang->execution->linkStory),
        formGroup
        (
            set::label($lang->execution->selectStoryPlan),
            set::required(true),
            setClass('text-left'),
            picker
            (
                set::name('plan'),
                set::required(true),
                set::items($plans)
            )
        )
    )
);

render();
