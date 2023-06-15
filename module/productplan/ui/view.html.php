<?php
declare(strict_types=1);
/**
 * The view view file of productplan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;

$unlinkURL['story'] = helper::createLink('productplan', 'unlinkStory', "storyID=%s&planID={$plan->id}&confirm=yes");
$unlinkURL['bug']   = helper::createLink('productplan', 'unlinkBug',   "bugID=%s&planID={$plan->id}&confirm=yes");
$locateURL['story'] = helper::createLink('productplan', 'view', "planID={$plan->id}&type=story&orderBy=$orderBy");
$locateURL['bug']   = helper::createLink('productplan', 'view', "planID={$plan->id}&type=bug&orderBy=$orderBy");

$confirmLang['story']    = $lang->productplan->confirmUnlinkStory;
$confirmLang['bug']      = $lang->productplan->confirmUnlinkBug;
$confirmLang['start']    = $lang->productplan->confirmStart;
$confirmLang['finish']   = $lang->productplan->confirmFinish;
$confirmLang['activate'] = $lang->productplan->confirmActivate;
$confirmLang['delete']   = $lang->productplan->confirmDelete;

jsVar('confirmLang', $confirmLang);
jsVar('unlinkURL',   $unlinkURL);
jsVar('locateURL',   $locateURL);

$menus = $this->productplan->buildOperateMenu($plan);
detailHeader
(
    to::title(entityLabel(set(array('entityID' => $plan->id, 'level' => 1, 'text' => $plan->title)))),
    (!$plan->deleted && !isonlybody() && $menus) ? to::suffix(btnGroup(set::items($menus))) : null
);

$bugCols  = array();
$storyCols = array();
foreach($config->productplan->defaultFields['story'] as $field) $storyCols[$field] = zget($config->story->dtable->fieldList, $field, array());
foreach($config->productplan->defaultFields['bug'] as $field)   $bugCols[$field]   = zget($config->bug->dtable->fieldList, $field, array());

$storyCols['title']['link']         = $this->createLink('story', 'view', "storyID={id}");
$storyCols['title']['nestedToggle'] = false;
$storyCols['assignedTo']['type']    = 'user';
$bugCols['assignedTo']['type']      = 'user';
$storyCols['module']['type']        = 'text';
$storyCols['module']['map']         = $modulePairs;
$storyCols['actions']['list']       = $config->productplan->actionList;
$bugCols['actions']['list']         = $config->productplan->actionList;
$storyCols['actions']['menu']       = array('unlinkStory');
$bugCols['actions']['menu']         = array('unlinkBug');
$storyCols['actions']['minWidth']   = 60;
$bugCols['actions']['minWidth']     = 60;

$canBatchUnlinkStory       = common::hasPriv('productPlan', 'batchUnlinkStory');
$canBatchCloseStory        = common::hasPriv('story', 'batchClose');
$canBatchEditStory         = common::hasPriv('story', 'batchEdit');
$canBatchReviewStory       = common::hasPriv('story', 'batchReview');
$canBatchChangeBranchStory = common::hasPriv('story', 'batchChangeBranch');
$canBatchChangeModuleStory = common::hasPriv('story', 'batchChangeModule');
$canBatchChangePlanStory   = common::hasPriv('story', 'batchChangePlan');
$canBatchChangeStageStory  = common::hasPriv('story', 'batchChangeStage');
$canBatchAssignToStory     = common::hasPriv('story', 'batchAssignTo');
$canBatchUnlinkBug         = common::hasPriv('productPlan', 'batchUnlinkBug');
$canBatchEditBug           = common::hasPriv('bug', 'batchEdit');
$canBatchChangePlanBug     = common::hasPriv('bug', 'batchChangePlan');

$canBatchActionStory = ($canBeChanged and ($canBatchUnlinkStory or $canBatchCloseStory or $canBatchEditStory or $canBatchReviewStory or $canBatchChangeBranchStory or $canBatchChangeModuleStory or $canBatchChangePlanStory or $canBatchChangeStageStory or $canBatchAssignToStory));
$canBatchActionBug   = ($canBeChanged and ($canBatchUnlinkBug or $canBatchEditBug or $canBatchChangePlanBug));

$bugFootToolbar   = array();
$storyFootToolbar = array();
if($canBatchActionStory)
{
    $storyFootToolbar = array('items' => array
    (
        array('type' => 'btn-group', 'items' => array
        (
            $canBatchUnlinkStory ? array('text' => $lang->productplan->unlinkStoryAB, 'className' => 'batch-btn size-sm primary', 'data-type' => 'story', 'data-formaction' => helper::createLink('productplan', 'batchUnlinkStory', "planID=$plan->id&orderBy=$orderBy")) : null,
            array('caret' => 'up', 'className' => 'size-sm primary', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start', 'url' => '#navStoryActions'),
        )),
    ));
}
if($canBatchActionBug)
{
    $bugFootToolbar = array('items' => array
    (
        array('type' => 'btn-group', 'items' => array
        (
            $canBatchUnlinkBug ? array('text' => $lang->productplan->unlinkAB, 'className' => 'batch-btn size-sm primary', 'data-type' => 'bug', 'data-formaction' => helper::createLink('productplan', 'batchUnlinkBug', "planID=$plan->id&orderBy=$orderBy")) : null,
            array('caret' => 'up', 'className' => 'size-sm primary', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start', 'url' => '#navBugActions'),
        )),
    ));
}

unset($lang->story->reviewResultList[''], $lang->story->reviewResultList['revert']);
unset($lang->story->reasonList[''], $lang->story->reasonList['subdivided'], $lang->story->reasonList['duplicate']);
unset($plans[''], $lang->story->stageList[''], $users['']);

foreach($lang->story->reviewResultList as $key => $result) $reviewResultItems[$key] = array('text' => $result,     'className' => 'batch-btn', 'data-type' => 'story', 'data-formaction' => $this->createLink('story', 'batchReview', "result=$key"));
foreach($lang->story->reasonList as $key => $reason)       $reviewRejectItems[]     = array('text' => $reason,     'className' => 'batch-btn', 'data-type' => 'story', 'data-formaction' => $this->createLink('story', 'batchReview', "result=reject&reason=$key"));
foreach($branchTagOption as $branchID => $branchName)      $branchItems[]           = array('text' => $branchName, 'className' => 'batch-btn', 'data-type' => 'story', 'data-formaction' => $this->createLink('story', 'batchChangeBranch', "branchID=$branchID"));
foreach($modules as $moduleID => $moduleName)              $moduleItems[]           = array('text' => $moduleName, 'className' => 'batch-btn', 'data-type' => 'story', 'data-formaction' => $this->createLink('story', 'batchChangeModule', "moduleID=$moduleID"));
foreach($plans as $planID => $planName)                    $planItems[]             = array('text' => $planName,   'className' => 'batch-btn', 'data-type' => 'story', 'data-formaction' => $this->createLink('story', 'batchChangePlan', "planID=$planID&oldPlanID={$plan->id}"));
foreach($lang->story->stageList as $key => $stageName)     $stageItems[]            = array('text' => $stageName,  'className' => 'batch-btn', 'data-type' => 'story', 'data-formaction' => $this->createLink('story', 'batchChangeStage', "stage=$key"));
foreach($users as $account => $realname)
{
    if($account == 'closed') continue;
    $assignItems[] = array('text' => $realname, 'className' => 'batch-btn', 'data-type' => 'story', 'data-formaction' => $this->createLink('story', 'batchAssignTo', "productID=$plan->product"), 'data-account' => $account);
}

if(isset($reviewResultItems['reject'])) $reviewResultItems['reject'] = array('class' => 'not-hide-menu', 'text' => $lang->story->reviewResultList['reject'], 'items' => $reviewRejectItems);
$reviewResultItems = array_values($reviewResultItems);

$navStoryActionItems = array();
if($canBatchCloseStory)  $navStoryActionItems[] = array('text' => $lang->close, 'className' => 'batch-btn', 'data-type' => 'story', 'data-page' => 'batch', 'data-formaction' => helper::createLink('story', 'batchClose', "productID={$plan->product}"));
if($canBatchEditStory)   $navStoryActionItems[] = array('text' => $lang->edit, 'className' => 'batch-btn',  'data-type' => 'story', 'data-page' => 'batch', 'data-formaction' => helper::createLink('story', 'batchEdit', "productID=$plan->product&projectID=$projectID&branch=$branch"));
if($canBatchReviewStory) $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->review, 'items' => $reviewResultItems);
if($canBatchChangeBranchStory && $product->type != 'normal') $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->product->branchName[$this->session->currentProductType], 'items' => $branchItems);
if($canBatchChangeModuleStory) $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->moduleAB, 'items' => $moduleItems);
if($canBatchChangePlanStory)   $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->planAB, 'items' => $planItems);
if($canBatchChangeStageStory)  $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->stageAB, 'items' => $stageItems);
if($canBatchAssignToStory)     $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->assignedTo, 'items' => $assignItems);

zui::menu
(
    set::id('navStoryActions'),
    set::class('menu dropdown-menu'),
    set::items($navStoryActionItems)
);


$planItems = array();
foreach($plans as $planID => $planName) $planItems[] = array('text' => $planName, 'className' => 'batch-btn', 'data-type' => 'bug', 'data-formaction' => $this->createLink('bug', 'batchChangePlan', "planID=$planID"));

$navBugActionItems = array();
if($canBatchEditBug)       $navBugActionItems[] = array('text' => $lang->edit, 'className' => 'batch-btn', 'data-type' => 'bug', 'data-page' => 'batch', 'data-formaction' => helper::createLink('bug', 'batchEdit', "productID=$plan->product&branch=$branch"));
if($canBatchChangePlanBug) $navBugActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->planAB, 'items' => $planItems);
zui::menu
(
    set::id('navBugActions'),
    set::class('menu dropdown-menu'),
    set::items($navBugActionItems)
);

$planStories = initTableData($planStories, $storyCols, $this->productplan);
$planBugs    = initTableData($planBugs,    $bugCols,   $this->productplan);

detailBody
(
    sectionList
    (
        tabs
        (
            setClass('w-full'),
            tabPane
            (
                to::prefix(icon(setClass('text-primary'), $lang->icons['story'])),
                set::key('stories'),
                set::title($lang->productplan->linkedStories),
                set::active($type == 'story'),
                dtable
                (
                    set::id('storyDTable'),
                    set::userMap($users),
                    set::bordered(true),
                    set::cols($storyCols),
                    set::data(array_values($planStories)),
                    set::checkable($canBatchActionStory),
                    set::footToolbar($storyFootToolbar),
                    set::footer(array('checkbox', 'toolbar', array('html' => $summary, 'className' => "text-dark"), 'flex', 'pager')),
                    set::footPager
                    (
                        usePager(null, 'storyPager'),
                        set::recPerPage($storyPager->recPerPage),
                        set::recTotal($storyPager->recTotal),
                        set::linkCreator(helper::createLink('build', 'view', "buildID={$build->id}&type=story&orderBy={$orderBy}&link={$link}&param={$param}&recTotal={$storyPager->recTotal}&recPerPage={recPerPage}&page={page}"))
                    ),
                )
            ),
            tabPane
            (
                to::prefix(icon(setClass('text-red'), $lang->icons['bug'])),
                set::key('bugs'),
                set::title($lang->productplan->linkedBugs),
                set::active($type == 'bug'),
                dtable
                (
                    set::id('bugDTable'),
                    set::userMap($users),
                    set::bordered(true),
                    set::cols($bugCols),
                    set::data(array_values($planBugs)),
                    set::checkable($canBatchActionBug),
                    set::footToolbar($bugFootToolbar),
                    set::footer(array('checkbox', 'toolbar', array('html' => sprintf($lang->productplan->bugSummary, count($planBugs)), 'className' => "text-dark"), 'flex', 'pager')),
                    set::footPager
                    (
                        usePager(null, 'bugPager'),
                        set::recPerPage($bugPager->recPerPage),
                        set::recTotal($bugPager->recTotal),
                        set::linkCreator(helper::createLink('build', 'view', "buildID={$build->id}&type=bug&orderBy={$orderBy}&link={$link}&param={$param}&recTotal={$bugPager->recTotal}&recPerPage={recPerPage}&page={page}"))
                    ),
                )
            ),
            tabPane
            (
                to::prefix(icon(setClass('text-info'), $lang->icons['info'])),
                set::key('planInfo'),
                set::title($lang->productplan->view),
                set::active($type == 'planInfo'),
            ),
        )
    )
);

render();

