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
jsVar('showGrade', $showGrade);
jsVar('oldShowGrades', $showGrades);
jsVar('storyType', $storyType);
jsVar('tab', $app->tab);
jsVar('vision', $config->vision);

$storyCommon       = $storyType == 'requirement' ? $lang->URCommon : $lang->SRCommon;
$isProjectStory    = $this->app->rawModule == 'projectstory';
$projectHasProduct = $isProjectStory && !empty($project->hasProduct);
$projectIDParam    = $isProjectStory ? "projectID=$projectID&" : '';
$storyBrowseType   = $this->session->storyBrowseType;
$storyProductIds   = array();

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
            set::activeKey($isProjectStory && empty($param) && !empty($productID) && count($projectProducts) > 1 ? $productID : $moduleID),
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

    global $app;
    $createLink      = createLink('story', 'create', "product=" . (empty($productID) ? current(array_keys($projectProducts)) : $productID) . "&branch=$branch&moduleID=$moduleID&storyID=0&projectID=$projectID&bugID=0&planID=0&todoID=0&extra=&storyType=$storyType") . ($isProjectStory ? '#app=project' : '');
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

        if($isProjectStory)
        {
            $items[] = array('text' => $lang->story->batchCreate . $lang->SRCommon, 'url' => $batchCreateLink);
            if(str_contains($project->storyType, 'requirement'))
            {
                if(common::hasPriv('requirement', 'create'))      $items[] = array('text' => $lang->requirement->create, 'url' => createLink('requirement', 'create', "product=$productID&branch=$branch&moduleID=$moduleID&requirementID=0&projectID=$projectID") . '#app=project');
                if(common::hasPriv('requirement', 'batchCreate')) $items[] = array('text' => $lang->requirement->batchCreate . $lang->URCommon, 'url' => createLink('requirement', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID&requirementID=0&project=$projectID") . '#app=project');
            }

            if(str_contains($project->storyType, 'epic'))
            {
                if(common::hasPriv('epic', 'create'))      $items[] = array('text' => $lang->epic->create, 'url' => createLink('epic', 'create', "product=$productID&branch=$branch&moduleID=$moduleID&epicID=0&projectID=$projectID") . '#app=project');
                if(common::hasPriv('epic', 'batchCreate')) $items[] = array('text' => $lang->epic->batchCreate . $lang->ERCommon, 'url' => createLink('epic', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID&epicID=0&project=$projectID") . '#app=project');
            }
        }
        else
        {
            $items[] = array('text' => $lang->story->batchCreate, 'url' => $batchCreateLink);
        }

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

    if($storyType == 'requirement') $lang->execution->linkStory = str_replace($lang->SRCommon, $lang->URCommon, $lang->execution->linkStory);

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

$setting = $this->loadModel('datatable')->getSetting('product', 'browse', false, $storyType);
<<<<<<< HEAD
if($storyType != 'story') unset($setting['taskCount'], $setting['bugCount'], $setting['caseCount']);
=======
if($storyType == 'requirement') unset($setting['plan'], $setting['stage'], $setting['taskCount'], $setting['bugCount'], $setting['caseCount']);
if($storyType == 'story' && $config->edition == 'ipd') unset($setting['roadmap']);
>>>>>>> master
$cols = array_values($setting);

/* DataTable data. */
$this->loadModel('story');

$data    = array();
$options = array('storyTasks' => $storyTasks, 'storyBugs' => $storyBugs, 'storyCases' => $storyCases, 'modules' => $modules, 'plans' => (isset($plans) ? $plans : array()), 'users' => $users, 'execution' => $project, 'roadmaps' => $roadmaps);
foreach($stories as $story)
{
    $story->rawModule    = $story->module;
    $story->from         = $app->tab;
    $options['branches'] = zget($branchOptions, $story->product, array());
<<<<<<< HEAD
    $data[] = $this->story->formatStoryForList($story, $options, $storyType, $maxGradeGroup);
}

/* Generate toolbar of DataTable footer. */
$fnGenerateFootToolbar = function() use ($lang, $product, $productID, $project, $storyType, $browseType, $isProjectStory, $projectHasProduct, $storyProductID, $projectID, $branch, $users, $branchTagOption, $modules, $plans, $gradePairs)
=======
    $data[] = $this->story->formatStoryForList($story, $options, $storyType);
    if(!isset($story->children)) continue;

    /* Children. */
    foreach($story->children as $key => $child)
    {
        if($app->rawModule == 'projectstory' && $child->project != $story->project) continue;
        $child->rawModule = $child->module;
        $data[] = $this->story->formatStoryForList($child, $options, $storyType);
    }
}

/* Generate toolbar of DataTable footer. */
$fnGenerateFootToolbar = function() use ($lang, $product, $productID, $project, $storyType, $browseType, $isProjectStory, $projectHasProduct, $storyProductID, $projectID, $branch, $users, $branchTagOption, $modules, $plans, $branchID)
>>>>>>> master
{
    /* Flag variables of permissions. */
    $canBeChanged         = common::canModify('product', $product);
    $canBatchEdit         = $canBeChanged && hasPriv($storyType, 'batchEdit');
    $canBatchClose        = hasPriv($storyType, 'batchClose') && strtolower($browseType) != 'closedbyme' && strtolower($browseType) != 'closedstory';
    $canBatchReview       = $canBeChanged && hasPriv($storyType, 'batchReview');
    $canBatchChangeGrade  = $canBeChanged && hasPriv($storyType, 'batchChangeGrade');
    $canBatchChangeStage  = $canBeChanged && hasPriv('story', 'batchChangeStage') && $storyType == 'story';
    $canBatchChangeBranch = $canBeChanged && hasPriv($storyType, 'batchChangeBranch') && $product && $product->type != 'normal' && $productID;
<<<<<<< HEAD
    $canBatchChangeModule = $canBeChanged && hasPriv($storyType, 'batchChangeModule') && $productID && $product && $product->type == 'normal';
    $canBatchChangePlan   = $canBeChanged && hasPriv($storyType, 'batchChangePlan') && (!$isProjectStory || $projectHasProduct || ($isProjectStory && isset($project->model) && $project->model == 'scrum')) && $productID && $product && $product->type == 'normal';
    $canBatchChangeParent = $canBeChanged && hasPriv($storyType, 'batchChangeParent');
=======
    $canBatchChangeModule = $canBeChanged && hasPriv($storyType, 'batchChangeModule') && $productID && (($product->type != 'normal' && $branchID != 'all') || $product->type == 'normal') && !$isProjectStory;
    $canBatchChangePlan   = $canBeChanged && hasPriv('story', 'batchChangePlan') && $storyType == 'story' && (!$isProjectStory || $projectHasProduct || ($isProjectStory && isset($project->model) && $project->model == 'scrum')) && $productID && $product && (($product->type != 'normal' && $branchID != 'all') || $product->type == 'normal');
>>>>>>> master
    $canBatchAssignTo     = $canBeChanged && hasPriv($storyType, 'batchAssignTo');
    $canBatchUnlink       = $canBeChanged && $projectHasProduct && hasPriv('projectstory', 'batchUnlinkStory');
    $canBatchImportToLib  = $canBeChanged && $isProjectStory && in_array($this->config->edition, array('max', 'ipd')) && hasPriv('story', 'batchImportToLib') && helper::hasFeature('storylib');
    $canBatchAction       = $canBatchEdit || $canBatchClose || $canBatchReview || $canBatchChangeGrade || $canBatchChangeStage || $canBatchChangeModule || $canBatchChangePlan || $canBatchChangeParent || $canBatchAssignTo || $canBatchUnlink || $canBatchImportToLib || $canBatchChangeBranch;

    /* Remove empty data from data list. */
    unset($lang->story->reviewResultList[''], $lang->story->reviewResultList['revert']);
    unset($lang->story->reasonList[''], $lang->story->reasonList['subdivided'], $lang->story->reasonList['duplicate']);
    unset($plans[''], $lang->story->stageList[''], $users['']);

    /* Generate dropdown menu items for the DataTable footer toolbar.*/
    $planItems  = $planItems ?? array();
    $gradeItems = array();
    foreach($lang->story->reviewResultList as $key => $result) $reviewResultItems[$key] = array('text' => $result,     'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchReview', "result=$key"));
    foreach($gradePairs as $key => $result)                    $gradeItems[]            = array('text' => $result,     'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchChangeGrade', "result=$key"));
    foreach($lang->story->reasonList as $key => $reason)       $reviewRejectItems[]     = array('text' => $reason,     'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchReview', "result=reject&reason=$key"));
    foreach($branchTagOption as $branchID => $branchName)      $branchItems[]           = array('text' => $branchName, 'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchChangeBranch', "branchID=$branchID"));
    foreach($modules as $moduleID => $moduleName)              $moduleItems[]           = array('text' => $moduleName, 'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchChangeModule', "moduleID=$moduleID"));
    foreach($plans as $planID => $planName)                    $planItems[]             = array('text' => $planName,   'class' => 'batch-btn', 'data-formaction' => $this->createLink('story', 'batchChangePlan', "planID=$planID"));
    foreach($lang->story->stageList as $key => $stageName)
    {
        if(!str_contains('|tested|verified|rejected|pending|released|closed|', "|$key|")) continue;
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
                'className' => 'secondary batch-btn',
                'disabled'  => ($canBatchEdit ? '': 'disabled'),
                'data-page' => 'batch',
                'data-formaction' => $this->createLink($storyType, 'batchEdit', "productID=$storyProductID&projectID=$projectID&branch=$branch&type=$storyType")
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
        array('caret' => 'up', 'text' => $lang->story->planAB, 'className' => $canBatchChangePlan ? 'secondary batchChangePlanBtn' : 'hidden', 'items' => $planItems, 'type' => 'dropdown', 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true)),
        /* Change branch button. */
        ($canBatchChangeBranch && $product->type != 'normal') ? array('caret' => 'up', 'text' => $lang->product->branchName[$product->type], 'className' => 'batchChangeBranchBtn', 'items' => $branchItems, 'type' => 'dropdown', 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true)) : null,
        /* AssignedTo button. */
        array('caret' => 'up', 'text' => $lang->story->assignedTo, 'className' => ($canBatchAssignTo ? 'secondary batchAssignToBtn' : 'hidden'), 'items' => $assignItems, 'type' => 'dropdown', 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true)),
        /* Change parent button. */
        array('text' => $lang->story->changeParent, 'className' => $canBatchChangeParent ? 'secondary batchChangeParentBtn' : 'hidden', 'data-toggle' => 'modal', 'url' => createLink($storyType, 'batchChangeParent', "productID=$productID&storyType=$storyType")),
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

$queryMenuLink = createLink($app->rawModule, $app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType=bySearch&param={queryID}");
featureBar
(
    ($showGrade || $storyType != 'story' || $isProjectStory) ? to::leading
    (
        picker
        (
            set::name('showGrades'),
            set::items($gradeMenu),
            set::search(false),
            set::multiple(true),
            set::width('145px'),
            setStyle('justify-content', 'center'),
            set::display($lang->story->viewAllGrades),
            set::menu(array('checkbox' => true)),
            set::value($showGrades),
            set::onPopHidden(jsRaw('setShowGrades'))
        )
    ) : null,
    set::current($storyBrowseType),
    set::link(createLink($app->rawModule, $app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType={key}&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&projectID=$projectID")),
    set::queryMenuLinkCallback(array(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink))),
    li(searchToggle(set::open($browseType == 'bysearch' || $storyBrowseType == 'bysearch'), set::module($config->product->search['module'])))
);

toolbar
(
<<<<<<< HEAD
    (!hasPriv($storyType, 'report') || !$productID) ? null : item(set(array('id' => 'reportBtn', 'text' => $lang->story->report->common, 'icon' => 'bar-chart', 'class' => 'ghost', 'url' => helper::createLink($storyType, 'report', "productID=$productID&branchID=$branch&storyType=$storyType&browseType=$browseType&moduleID=$moduleID&chartType=pie&projectID=$projectID") . ($app->tab == 'project' ? '#app=project' : '')))),
    !hasPriv($storyType, 'export') ? null : item(set(array('id' => 'exportBtn', 'text' => $lang->export, 'icon' => 'export', 'class' => 'ghost', 'url' => helper::createLink($storyType, 'export', "productID=$productID&orderBy=$orderBy&executionID=$projectID&browseType=$browseType"), 'data-toggle' => 'modal'))),
=======
    (!hasPriv('story', 'report') || !$productID) ? null : item(set(array('id' => 'reportBtn', 'icon' => 'bar-chart', 'class' => 'ghost', 'url' => helper::createLink('story', 'report', "productID=$productID&branchID=$branch&storyType=$storyType&browseType=$browseType&moduleID=$moduleID&chartType=pie&projectID=$projectID") . ($app->tab == 'project' ? '#app=project' : '')))),
    !hasPriv('story', 'export') ? null : item(set(array('id' => 'exportBtn', 'icon' => 'export', 'class' => 'ghost', 'url' => helper::createLink('story', 'export', "productID=$productID&orderBy=$orderBy&executionID=$projectID&browseType=$browseType&storyType=$storyType"), 'data-toggle' => 'modal'))),
>>>>>>> master
    $fnBuildCreateStoryButton(),
    $fnBuildLinkStoryButton()
);

$fnGenerateSideBar();

$footToolbar = $fnGenerateFootToolbar();
$sortLink    = createLink('product', 'browse', "productID={$productID}&branch={$branch}&browseType={$browseType}&param={$param}&storyType={$storyType}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&projectID=$projectID");
if($this->app->rawModule == 'projectstory') $sortLink = createLink('projectstory', 'story', "projectID={$projectID}&productID={$productID}&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");

$emptyTip = $lang->story->noStory;
if($storyType == 'requirement') $emptyTip = $lang->story->noRequirement;
if($storyType == 'epic')        $emptyTip = $lang->story->noEpic;
dtable
(
    set::id('stories'),
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
    set::emptyTip($emptyTip),
    set::createTip($lang->story->create),
    set::createLink(hasPriv($storyType, 'create') ? createLink($storyType, 'create', "product=$productID&branch=$branch&moduleID=$moduleID&storyID=0&projectID=$projectID&bugID=0&planID=0&todoID=0&extra=&storyType=$storyType") . ($isProjectStory ? '#app=project' : '') : '')
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
