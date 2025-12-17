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

data('storyType', $storyType);
data('activeMenuID', $storyType);
jsVar('URChanged', $this->lang->story->URChanged);
jsVar('gradeGroup', $gradeGroup);
jsVar('oldShowGrades', $showGrades);
jsVar('storyType', $storyType);
jsVar('tab', $app->tab);
jsVar('vision', $config->vision);
jsVar('window.globalSearchType', $storyType);
jsVar('storyViewPriv', hasPriv('story', 'view'));
jsVar('requirementViewPriv', hasPriv('requirement', 'view'));
jsVar('epicViewPriv', hasPriv('epic', 'view'));

$viewType          = $this->cookie->storyViewType ? $this->cookie->storyViewType : 'tree';
$storyCommon       = $storyType == 'requirement' ? $lang->URCommon : $lang->SRCommon;
$isProjectStory    = $this->app->rawModule == 'projectstory';
$projectHasProduct = $isProjectStory && !empty($project->hasProduct);
$projectIDParam    = $isProjectStory ? "projectID=$projectID&" : '';
$storyBrowseType   = $this->session->storyBrowseType;
$storyProductIds   = array();

$isFromDoc = $from === 'doc';
$isFromAI  = $from === 'ai';

$tabCondition   = $app->tab == 'product' || $isFromDoc || $isFromAI;
$storyCondition = $storyType == 'story' && count($gradeGroup['story']) <= 2;
$hideGrade      = ($tabCondition && $storyCondition) || $config->vision != 'rnd';

jsVar('projectHasProduct', $projectHasProduct);

foreach($stories as $story) $storyProductIds[$story->product] = $story->product;
$storyProductID = count($storyProductIds) > 1 ? 0 : $productID;

/* Generate sidebar to display module tree menu. */
$fnGenerateSideBar = function() use ($moduleTree, $moduleID, $productID, $branchID, $projectHasProduct, $param, $isProjectStory, $projectProducts)
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
            set::activeKey($isProjectStory && empty($param) && !empty($productID) && count($projectProducts) > 1 ? "p_" . $productID : $moduleID),
            set::closeLink(helper::createLink($app->rawModule, $app->rawMethod, http_build_query($params))),
            $productID ? set::settingLink(helper::createLink('tree', 'browse', "rootID=$productID&view=story&currentModuleID=0&branch=$branchID")) : null,
            set::settingApp($isProjectStory && !$projectHasProduct ? 'project' : 'product')
        )
    );
};

/* Build create story button. */
$fnBuildCreateStoryButton = function() use ($lang, $product, $isProjectStory, $storyType, $productID, $branch, $moduleID, $projectID, $project, $projectProducts)
{
    if(!common::canModify('product', $product)) return null;
    if(!empty($project) && !common::canModify('project', $project)) return null;

    global $app, $config;
    $currentProductID = empty($productID) ? current(array_keys($projectProducts)) : $productID;
    $createLink       = createLink($storyType, 'create', "product=" . $currentProductID . "&branch=$branch&moduleID=$moduleID&storyID=0&projectID=$projectID&bugID=0&planID=0&todoID=0&extra=&storyType=$storyType") . ($isProjectStory ? '#app=project' : '');
    $batchCreateLink  = createLink($storyType, 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID&storyID=0&project=$projectID&plan=0&storyType=$storyType"). ($isProjectStory ? '#app=project' : '');

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
    if(hasPriv($storyType, 'batchCreate') && hasPriv($storyType, 'create'))
    {
        $items = array();

        if(commonModel::isTutorialMode())
        {
            /* Tutorial create link. */
            $wizardParams = helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID");
            if($isProjectStory) $wizardParams = helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID&storyID=&projectID=$projectID");
            $link = $this->createLink('tutorial', 'wizard', "module=story&method=create&params=$wizardParams");
            $items[] = array('text' => $lang->story->create, 'url' => $link);
        }
        elseif(!$isProjectStory)
        {
            $items[] = array('text' => $lang->story->create, 'url' => $createLink);
        }

        if($isProjectStory && $config->vision != 'lite')
        {
            if(!empty($productID)) $batchItems[] = array('text' => $lang->SRCommon, 'url' => $batchCreateLink);
            if(str_contains($project->storyType, 'requirement') && $this->config->URAndSR)
            {
                if(common::hasPriv('requirement', 'create')) $items[] = array('text' => $lang->requirement->create, 'url' => createLink('requirement', 'create', "product=$currentProductID&branch=$branch&moduleID=$moduleID&requirementID=0&projectID=$projectID") . '#app=project');
                if(common::hasPriv('requirement', 'batchCreate') && !empty($productID)) $batchItems[] = array('text' => $lang->URCommon, 'url' => createLink('requirement', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID&requirementID=0&project=$projectID") . '#app=project');
            }

            if(str_contains($project->storyType, 'epic') && $this->config->enableER)
            {
                if(common::hasPriv('epic', 'create')) $items[] = array('text' => $lang->epic->create, 'url' => createLink('epic', 'create', "product=$currentProductID&branch=$branch&moduleID=$moduleID&epicID=0&projectID=$projectID") . '#app=project');
                if(common::hasPriv('epic', 'batchCreate') && !empty($productID)) $batchItems[] = array('text' => $lang->ERCommon, 'url' => createLink('epic', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID&epicID=0&project=$projectID") . '#app=project');
            }

            if(!empty($productID)) $items[] = array('text' => $lang->story->batchCreate, 'items' => $batchItems);
        }
        else
        {
            $items[] = array('text' => $lang->story->batchCreate, 'url' => $batchCreateLink);
        }

        return btnGroup
        (
            $app->tab == 'project' ? setData('app', 'project') : null,
            btn
            (
                setClass(($app->tab != 'product' ? 'secondary' : 'primary') . ' create-story-btn'),
                set::icon('plus'),
                set::text($createBtnTitle),
                set::url($createBtnLink)
            ),
            empty($items) ? null : dropdown
            (
                btn(setClass('dropdown-toggle'), setClass($app->tab != 'product' ? 'secondary' : 'primary'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
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
$fnBuildLinkStoryButton = function() use($lang, $app, $product, $projectHasProduct, $project, $storyType)
{
    if(!common::canModify('product', $product)) return null;
    if(!empty($project) && !common::canModify('project', $project)) return null;

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
    $canlinkPlanStory = !empty($product) && common::hasPriv('projectstory', 'importPlanStories') && $storyType == 'story' && !$project->charter;
    $linkStoryUrl     = $this->createLink('projectstory', 'linkStory', "project=$project->id&browseType=&param=0&orderBy=id_desc&recPerPage=50&pageID=1&extra=&storyType=$storyType");
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
    if($canLinkStory && !$canlinkPlanStory) return item(set($linkItem + array('class' => 'btn primary link-story-btn', 'icon' => 'link')));
    if($canlinkPlanStory && !$canLinkStory) return item(set($linkPlanItem + array('class' => 'btn primary', 'icon' => 'link')));
};

/* DataTable columns. */
$config->story->dtable->fieldList['title']['title'] = $lang->story->title;
if($app->rawModule == 'projectstory') $config->story->dtable->fieldList['title']['link'] = array('url' => helper::createLink('projectstory', 'view', 'storyID={id}&projectID={project}'));

$config->$storyType->dtable->fieldList['assignedTo']['assignLink']['module'] = $storyType;
$setting = $this->loadModel('datatable')->getSetting('product', 'browse', false, $storyType);
if($storyType != 'story') unset($setting['taskCount'], $setting['bugCount'], $setting['caseCount']);
if($storyType == 'story' && $config->edition == 'ipd') unset($setting['roadmap']);
if($viewType == 'tiled') $setting['title']['nestedToggle'] = false;

if($isFromDoc || $isFromAI)
{
    if(isset($setting['actions'])) unset($setting['actions']);
    foreach($setting as $key => $col)
    {
        $setting[$key]['sortType'] = false;
        if(isset($col['link'])) unset($setting[$key]['link']);
        if($key == 'assignedTo') $setting[$key]['type'] = 'user';
        if($key == 'pri') $setting[$key]['priList'] = $lang->story->priList;
        if($key == 'title') $setting[$key]['link']  = array('url' => createLink('{type}', 'view', 'storyID={id}&version={version}'), 'data-toggle' => 'modal', 'data-size' => 'lg');
    }
}

$cols = array_values($setting);

/* DataTable data. */
$this->loadModel('story');

$data    = array();
$options = array('storyTasks' => $storyTasks, 'storyBugs' => $storyBugs, 'storyCases' => $storyCases, 'modules' => $modules, 'plans' => (isset($plans) ? $plans : array()), 'users' => $users, 'execution' => $project, 'roadmaps' => $roadmaps, 'reports' => $reports);
foreach($stories as $story)
{
    $story->rawModule    = $story->module;
    $story->from         = $app->tab;
    $options['branches'] = zget($branchOptions, $story->product, array());
    $data[] = $this->story->formatStoryForList($story, $options, $storyType, $maxGradeGroup);
}

/* Generate toolbar of DataTable footer. */
$fnGenerateFootToolbar = function() use ($lang, $app, $product, $productID, $project, $storyType, $browseType, $isProjectStory, $projectHasProduct, $storyProductID, $projectID, $branch, $users, $branchTagOption, $modules, $plans, $branchID, $gradePairs, $config,$noclosedRoadmaps, $gradeGroup)
{
    /* Flag variables of permissions. */
    $canBeChanged = common::canModify('product', $product);
    if(!empty($project)) $canBeChanged = $canBeChanged && common::canModify('project', $project);
    if($isProjectStory && $config->vision == 'rnd')
    {
        $canBatchClose      = $canBeChanged && hasPriv('projectstory', 'batchClose') && strtolower($browseType) != 'closedbyme';
        $canBatchEdit       = $canBeChanged && hasPriv('projectstory', 'batchEdit');
        $canBatchReview     = $canBeChanged && hasPriv('projectstory', 'batchReview');
        $canBatchAssignTo   = $canBeChanged && hasPriv('projectstory', 'batchAssignTo');
        $canBatchChangePlan = $canBeChanged && hasPriv('projectstory', 'batchChangePlan') && $productID && $product;
    }
    else
    {
        $canBatchEdit       = $canBeChanged && hasPriv($storyType, 'batchEdit');
        $canBatchClose      = hasPriv($storyType, 'batchClose') && strtolower($browseType) != 'closedbyme' && strtolower($browseType) != 'closedstory';
        $canBatchReview     = $canBeChanged && hasPriv($storyType, 'batchReview');
        $canBatchAssignTo   = $canBeChanged && hasPriv($storyType, 'batchAssignTo');
        $canBatchChangePlan = $canBeChanged && hasPriv($storyType, 'batchChangePlan') && $config->vision == 'rnd' && $productID && $product && (($product->type != 'normal' && $branchID != 'all') || $product->type == 'normal');
    }

    $canBatchChangeGrade   = $canBeChanged && hasPriv($storyType, 'batchChangeGrade') && count($gradePairs) > 1 && $config->{$storyType}->gradeRule == 'cross' && !$isProjectStory;
    $canBatchChangeStage   = $canBeChanged && hasPriv('story', 'batchChangeStage') && $storyType == 'story' && $config->vision != 'lite';
    $canBatchChangeBranch  = $canBeChanged && hasPriv($storyType, 'batchChangeBranch') && $product && $product->type != 'normal' && $productID;
    $canBatchChangeModule  = $canBeChanged && hasPriv($storyType, 'batchChangeModule') && $productID && (($product->type != 'normal' && $branchID != 'all') || $product->type == 'normal') && !$isProjectStory;
    $canBatchChangeParent  = $canBeChanged && hasPriv($storyType, 'batchChangeParent') && !($storyType == 'epic' && count($gradeGroup['epic']) < 2) && $app->tab == 'product';
    $canBatchUnlink        = $canBeChanged && $projectHasProduct && hasPriv('projectstory', 'batchUnlinkStory');
    $canBatchImportToLib   = $canBeChanged && $isProjectStory && in_array($this->config->edition, array('max', 'ipd')) && hasPriv('story', 'batchImportToLib') && helper::hasFeature('storylib');
    $canBatchChangeRoadmap = $canBeChanged && hasPriv($storyType, 'batchChangeRoadmap') && $config->vision == 'or' && ($storyType == 'requirement' || $storyType == 'epic');
    $canBatchAction        = $canBatchEdit || $canBatchClose || $canBatchReview || $canBatchChangeGrade || $canBatchChangeStage || $canBatchChangeModule || $canBatchChangePlan || $canBatchChangeParent || $canBatchAssignTo || $canBatchUnlink || $canBatchImportToLib || $canBatchChangeBranch || $canBatchChangeRoadmap;

    /* Remove empty data from data list. */
    unset($lang->story->reviewResultList[''], $lang->story->reviewResultList['revert']);
    unset($lang->story->reasonList[''], $lang->story->reasonList['subdivided'], $lang->story->reasonList['duplicate']);
    unset($lang->story->stageList[''], $users['']);

    if($storyType == 'story') $plans = \arrayUnion(array(0 => $lang->null), $plans);

    /* Generate dropdown menu items for the DataTable footer toolbar.*/
    $planItems    = $planItems ?? array();
    $gradeItems   = array();
    $roadmapItems = array();
    foreach($lang->story->reviewResultList as $key => $result) $reviewResultItems[$key] = array('text' => $result,     'class' => 'batch-btn', 'data-formaction' => $this->createLink($isProjectStory ? 'projectstory' : $storyType, 'batchReview', "result=$key"));
    foreach($gradePairs as $key => $result)                    $gradeItems[]            = array('text' => $result,     'class' => 'batch-btn', 'data-formaction' => $this->createLink($isProjectStory ? 'projectstory' : $storyType, 'batchChangeGrade', "result=$key&type=$storyType"));
    foreach($lang->story->reasonList as $key => $reason)       $reviewRejectItems[]     = array('text' => $reason,     'class' => 'batch-btn', 'data-formaction' => $this->createLink($isProjectStory ? 'projectstory' : $storyType, 'batchReview', "result=reject&reason=$key"));
    foreach($branchTagOption as $branchID => $branchName)      $branchItems[]           = array('text' => $branchName, 'class' => 'batch-btn', 'data-formaction' => $this->createLink($isProjectStory ? 'projectstory' : $storyType, 'batchChangeBranch', "branchID=$branchID"), 'attrs' => array('title' => $branchName));
    foreach($modules as $moduleID => $moduleName)              $moduleItems[]           = array('text' => $moduleName, 'class' => 'batch-btn', 'data-formaction' => $this->createLink($isProjectStory ? 'projectstory' : $storyType, 'batchChangeModule', "moduleID=$moduleID"));
    foreach($plans as $planID => $planName)                    $planItems[]             = array('text' => $planName,   'class' => 'batch-btn', 'data-formaction' => $this->createLink($isProjectStory ? 'projectstory' : $storyType, 'batchChangePlan', "planID=$planID"));
    foreach($noclosedRoadmaps as $roadmapID => $roadmapName)   $roadmapItems[]          = array('text' => empty($roadmapName) ? $lang->null : $roadmapName, 'class' => 'batch-btn', 'data-formaction' => $this->createLink($storyType, 'batchChangeRoadmap', "roadmapID=$roadmapID"));

    foreach($lang->story->stageList as $key => $stageName)
    {
        if(!str_contains('|tested|verified|rejected|released|closed|', "|$key|")) continue;
        $stageItems[] = array('text' => $stageName,  'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchChangeStage', "stage=$key"));
    }
    foreach($users as $account => $realname)
    {
        if($account == 'closed') continue;
        $assignItems[] = array('text' => $realname, 'class' => 'batch-btn', 'data-formaction' => $this->createLink($storyType, 'batchAssignTo', "productID={$productID}"), 'data-account' => $account);
    }

    if(isset($reviewResultItems['reject'])) $reviewResultItems['reject'] = array('class' => 'not-hide-menu', 'text' => $lang->story->reviewResultList['reject'], 'items' => $reviewRejectItems);
    $reviewResultItems = array_values($reviewResultItems);

    $navActionItems = array();
    if($canBatchClose)        $navActionItems[] = array('class' => 'batch-btn batchClostBtn', 'text' => $lang->close, 'data-page' => 'batch', 'data-formaction' => helper::createLink($storyType, 'batchClose', "productID={$productID}&executionID=0"));
    if($canBatchChangeGrade)  $navActionItems[] = array('class' => 'not-hide-menu batchGradeBtn', 'text' => $lang->story->grade, 'items' => $gradeItems);
    if($canBatchReview)       $navActionItems[] = array('class' => 'not-hide-menu batchReviewBtn', 'text' => $lang->story->review, 'items' => $reviewResultItems);
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
                'className' => 'secondary batch-btn' . (empty($navActionItems) && !$canBatchEdit ? ' hidden' : ''),
                'disabled'  => ($canBatchEdit ? '': 'disabled'),
                'data-page' => 'batch',
                'data-formaction' => $this->createLink($storyType, 'batchEdit', "productID=$storyProductID&projectID=$projectID&branch=$branch&type=$storyType")
            ),
            /* Popup menu trigger icon. */
            array('caret' => 'up', 'className' => 'size-sm secondary' . (empty($navActionItems) ? ' hidden' : ''), 'items' => $navActionItems, 'data-toggle' => 'dropdown', 'data-placement' => 'top-start')
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
        $canBatchChangeRoadmap ? array('caret' => 'up', 'text' => $lang->roadmap->common, 'className' => 'secondary', 'items' => $roadmapItems, 'type' => 'dropdown', 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true)) : null,
        /* Change branch button. */
        ($canBatchChangeBranch && $product->type != 'normal') ? array('caret' => 'up', 'text' => $lang->product->branchName[$product->type], 'className' => 'batchChangeBranchBtn', 'items' => $branchItems, 'type' => 'dropdown', 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true)) : null,
        /* AssignedTo button. */
        array('caret' => 'up', 'text' => $lang->story->assignedTo, 'className' => ($canBatchAssignTo ? 'secondary batchAssignToBtn' : 'hidden'), 'items' => $assignItems, 'type' => 'dropdown', 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true)),
        /* Change parent button. */
        array('text' => $lang->story->changeParent, 'className' => $canBatchChangeParent ? 'secondary batchChangeParentBtn' : 'hidden', 'data-toggle' => 'modal', 'url' => createLink($storyType, 'batchChangeParent', "productID=$productID&storyType=$storyType")),
        /* Batch import to lib button .*/
        $canBatchImportToLib && $storyType != 'requirement' ? array('text' => $lang->story->importToLib, 'className' => 'btn secondary batchImportToLibBtn', 'id' => 'importToLib', 'data-toggle' => 'modal', 'url' => '#batchImportToLib', 'data-on' => 'click', 'data-call' => 'importToLib') : null
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
$checkedSummary = $lang->product->checkedSRSummary;
if($storyType == 'requirement') $checkedSummary = $lang->product->checkedURSummary;
if($storyType == 'epic')        $checkedSummary = $lang->product->checkedERSummary;
if($isProjectStory)             $checkedSummary = $lang->product->checkedAllSummary;

data('storyBrowseType', $storyBrowseType);

jsVar('childrenAB',     $lang->story->childrenAB);
jsVar('projectID',      $projectID);
jsVar('modulePairs',    $modulePairs);
jsVar('storyType',      $storyType);
jsVar('checkedSummary', $checkedSummary);

jsVar('from',       $from);
jsVar('productID',  $productID);
jsVar('branch',     $branch);
jsVar('browseType', $browseType);
jsVar('param',      $param);
jsVar('orderBy',    $orderBy);
jsVar('recTotal',   $pager->recTotal);
jsVar('recPerPage', $pager->recPerPage);
jsVar('pageID',     $pager->pageID);

if($isFromDoc || $isFromAI)
{
    $this->app->loadLang('doc');
    $productChangeLink = createLink($app->rawModule, $app->rawMethod, $projectIDParam . "productID={productID}&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&projectID=$projectID&from=$from&blockID=$blockID");
    $insertListLink    = createLink($app->rawModule, $app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&projectID=$projectID&from=$from&blockID={blockID}");

    $blockType = 'productStory';
    if($storyType == 'epic')        $blockType = 'ER';
    if($storyType == 'requirement') $blockType = 'UR';
    formPanel
    (
        setID('zentaolist'),
        setClass('mb-4-important'),
        set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList[$blockType])),
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

$queryMenuLink = createLink($app->rawModule, $app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType=bySearch&param={queryID}&storyType=$storyType&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&projectID=$projectID&from=$from&blockID=$blockID");
featureBar
(
    $hideGrade ? null : to::leading
    (
        picker
        (
            set::tree(true),
            set::name('showGrades'),
            set::items($gradeMenu),
            set::search(false),
            set::multiple(true),
            set::width('150px'),
            setStyle('justify-content', 'center'),
            set::display($lang->story->viewAllGrades),
            set::menu(array('checkbox' => true, 'itemProps' => array('innerComponent' => 'a'))),
            set::value($showGrades),
            set::toolbar
            (
                array('text' => $lang->confirm, 'onClick' => jsRaw('(e,info) => {setShowGrades();info.relativeTarget.close();}')),
                array('text' => $lang->cancel, 'onClick' => jsRaw('(e,info) => info.relativeTarget.close()')),
            )
        )
    ),
    set::param($param),
    set::current($storyBrowseType),
    set::link(createLink($app->rawModule, $app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType={key}&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&projectID=$projectID&from=$from&blockID=$blockID")),
    set::queryMenuLinkCallback(array(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink))),
    set::isModal($isFromDoc || $isFromAI),
    set::modalTarget('#stories_table'),
    li(searchToggle
    (
        set::simple($isFromDoc || $isFromAI),
        set::open(!$isFromDoc && !$isFromAI && ($browseType == 'bysearch' || $storyBrowseType == 'bysearch')),
        set::module($config->product->search['module']),
        ($isFromDoc || $isFromAI) ? set::target('#docSearchForm') : null,
        ($isFromDoc || $isFromAI) ? set::onSearch(jsRaw('function(){$(this.element).closest(".modal").find("#featureBar .nav-item>.active").removeClass("active").find(".label").hide()}')) : null
    ))
);

if($isFromDoc || $isFromAI)
{
    div(setID('docSearchForm'));
}

$canExport = $isProjectStory ? hasPriv('projectstory', 'export') && $productID : hasPriv($storyType, 'export');
$canReport = $isProjectStory ? hasPriv('projectstory', 'report') : hasPriv($storyType, 'report');
$reportUrl = $isProjectStory ? helper::createLink('projectstory', 'report', "productID=$productID&branchID=$branch&storyType=$storyType&browseType=$browseType&moduleID=$moduleID&chartType=pie&projectID=$projectID") : helper::createLink($storyType, 'report', "productID=$productID&branchID=$branch&storyType=$storyType&browseType=$browseType&moduleID=$moduleID");
$exportUrl = $isProjectStory ? helper::createLink('projectstory', 'export', "productID=$productID&orderBy=$orderBy&executionID=$projectID&browseType=$browseType") : helper::createLink($storyType, 'export', "productID=$productID&orderBy=$orderBy&executionID=$projectID&browseType=$browseType");
toolbar
(
    setClass(array('hidden' => $isFromDoc || $isFromAI)),
    item(set(array
    (
        'type'  => 'btnGroup',
        'items' => array(array
        (
            'icon'      => 'list',
            'class'     => 'btn-icon switchButton' . ($viewType == 'tiled' ? ' text-primary' : ''),
            'data-type' => 'tiled',
            'hint'      => $lang->story->viewTypeList['tiled']
        ), array
        (
            'icon'      => 'treeview',
            'class'     => 'switchButton btn-icon' . ($viewType == 'tree' ? ' text-primary' : ''),
            'data-type' => 'tree',
            'hint'      => $lang->story->viewTypeList['tree']
        ))
    ))),
    (!$canReport || !$productID) ? null : item(set(array('id' => 'reportBtn', 'icon' => 'bar-chart', 'class' => 'ghost', 'url' => $reportUrl))),
    item(set(array('id' => 'exportBtn', 'icon' => 'export', 'class' => 'ghost' . ($canExport ? '' : ' hidden'), 'url' => $exportUrl, 'data-toggle' => 'modal'))),
    $fnBuildCreateStoryButton(),
    $fnBuildLinkStoryButton()
);

if(!$isFromDoc && !$isFromAI) $fnGenerateSideBar();

if($isFromDoc)                $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToDoc('#stories', '$blockType', $blockID, '$insertListLink')"));
if($isFromAI)                 $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToAI('#stories', 'story', '$storyType')"));
if(!$isFromDoc && !$isFromAI) $footToolbar = $fnGenerateFootToolbar();

$sortLink    = createLink('product', 'browse', "productID={$productID}&branch={$branch}&browseType={$browseType}&param={$param}&storyType={$storyType}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&projectID=$projectID");
if($this->app->rawModule == 'projectstory') $sortLink = createLink('projectstory', 'story', "projectID={$projectID}&productID={$productID}&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");

$emptyTip = $lang->story->noStory;
if($storyType == 'requirement') $emptyTip = $lang->story->noRequirement;
if($storyType == 'epic')        $emptyTip = $lang->story->noEpic;

$createStoryLink = createLink($storyType, 'create', 'product=' . (empty($productID) ? current(array_keys($projectProducts)) : $productID) . "&branch=$branch&moduleID=$moduleID&storyID=0&projectID=$projectID&bugID=0&planID=0&todoID=0&extra=&storyType=$storyType") . ($isProjectStory ? '#app=project' : '');
$createStoryLink = hasPriv($storyType, 'create') ?  $createStoryLink : '';
dtable
(
    set::id('stories'),
    set::userMap($users),
    set::checkable($isFromDoc || $isFromAI || !empty($footToolbar)),  // The user can do batch action if this parameter is not false(true, null).
    set::cols($cols),
    set::moduleName($storyType),
    set::data($data),
    set::noNestedCheck(),
    set::orderBy($orderBy),
    set::modules($modulePairs),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager()),
    set::emptyTip($emptyTip),
    set::footToolbar($footToolbar),
    !$isFromDoc ? null : set::afterRender(jsCallback()->call('toggleCheckRows', $idList)),
    (!$isFromDoc && !$isFromAI) ? null : set::onCheckChange(jsRaw('window.checkedChange')),
    (!$isFromDoc && !$isFromAI) ? null : set::height(400),
    ($isFromDoc || $isFromAI) ? null : set::customCols(array('url' => createLink('datatable', 'ajaxcustom', "module={$app->moduleName}&method={$app->methodName}&extra={$storyType}"), 'globalUrl' => createLink('datatable', 'ajaxsaveglobal', "module={$app->moduleName}&method={$app->methodName}&extra={$storyType}"), 'resetUrl' => createLink('datatable', 'ajaxreset', "module={$app->moduleName}&method={$app->methodName}&system=0&confirm=no&extra={$storyType}"), 'resetGlobalUrl' => createLink('datatable', 'ajaxreset', "module={$app->moduleName}&method={$app->methodName}&system=1&confirm=no&extra={$storyType}"))),
    ($isFromDoc || $isFromAI) ? null : set::sortLink($sortLink),
    ($isFromDoc || $isFromAI) ? null : set::checkInfo(jsRaw("function(checkedIdList){return window.setStatistics(this, checkedIdList, '{$summary}');}")),
    ($isFromDoc || $isFromAI) ? null : set::createTip($lang->story->create),
    ($isFromDoc || $isFromAI) ? null : set::createLink($createStoryLink)
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

if(isset($libs))
{
    modal
    (
        setID('batchImportToLib'),
        set::title($lang->story->importToLib),
        form
        (
            set::action($this->createLink('story', 'batchImportToLib')),
            formGroup
            (
                set::label($lang->story->lib),
                picker
                (
                    set::name('lib'),
                    set::items($libs),
                    set::required(true)
                ),
                input(set::className('hidden'), set::name('storyIdList'), set::id('storyIdList'))
            ),
            (!hasPriv('assetlib', 'approveStory') && !hasPriv('assetlib', 'batchApproveStory')) ? formGroup
            (
                set::label($lang->story->approver),
                picker
                (
                    set::name('assignedTo'),
                    set::items($approvers)
                )
            ) : null,
            set::submitBtnText($lang->import),
            set::actions(array('submit'))
        )
    );
}

render();
