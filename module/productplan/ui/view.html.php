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

include($this->app->getModuleRoot() . 'ai/ui/promptmenu.html.php');

$unlinkURL['story'] = helper::createLink('productplan', 'unlinkStory', "storyID=%s&planID={$plan->id}");
$unlinkURL['bug']   = helper::createLink('productplan', 'unlinkBug',   "bugID=%s&planID={$plan->id}");

$confirmLang['story']    = $lang->productplan->confirmUnlinkStory;
$confirmLang['bug']      = $lang->productplan->confirmUnlinkBug;
$confirmLang['start']    = $lang->productplan->confirmStart;
$confirmLang['finish']   = $lang->productplan->confirmFinish;
$confirmLang['activate'] = $lang->productplan->confirmActivate;
$confirmLang['delete']   = $lang->productplan->confirmDelete;

$decodeParam = helper::safe64Decode($param);
$isInModal   = isInModal();

jsVar('initLink',        $link);
jsVar('type',            $type);
jsVar('linkParams',      $decodeParam);
jsVar('orderBy',         $orderBy);
jsVar('planID',          $plan->id);
jsVar('confirmLang',     $confirmLang);
jsVar('unlinkURL',       $unlinkURL);
jsVar('childrenAB',      $lang->story->childrenAB);
jsVar('cases',           $storyCases);
jsVar('summary',         $summary);
jsVar('checkedSummary',  $lang->product->checkedSRSummary);
jsVar('storyPageID',     $storyPager->pageID);
jsVar('storyRecPerPage', $storyPager->recPerPage);
jsVar('gradeGroup',      $gradeGroup);

$bugCols   = array();
$storyCols = array();
foreach($config->productplan->defaultFields['story'] as $field)
{
    if($field == 'branch' && $product->type == 'normal') continue;
    $storyCols[$field] = zget($config->story->dtable->fieldList, $field, array());
    if($field == 'id' && common::hasPriv('execution', 'storySort'))
    {
        $storyCols['sort']['title'] = $lang->productplan->updateOrder;
        $storyCols['sort']['fixed'] = 'left';
        $storyCols['sort']['align'] = 'center';
        $storyCols['sort']['group'] = 1;
        $storyCols['sort']['width'] = 60;
    }
}
if(isset($storyCols['branch'])) $storyCols['branch']['map'] = $branchOption;
foreach($config->productplan->defaultFields['bug'] as $field)   $bugCols[$field]   = zget($config->bug->dtable->fieldList, $field, array());

$storyCols['title']['link']         = $this->createLink('story', 'storyView', "storyID={id}");
$storyCols['title']['title']        = $lang->productplan->storyTitle;
$storyCols['assignedTo']['type']    = 'user';
$storyCols['module']['type']        = 'text';
$storyCols['module']['map']         = $modulePairs;
$storyCols['module']['sortType']    = true;
$storyCols['actions']['list']       = $config->productplan->actionList;
$storyCols['actions']['menu']       = array('unlinkStory');
$storyCols['actions']['minWidth']   = 60;
$bugCols['assignedTo']['type']      = 'user';
$bugCols['actions']['list']         = $config->productplan->actionList;
$bugCols['actions']['menu']         = array('unlinkBug');
$bugCols['actions']['minWidth']     = 60;

$canBeChanged              = common::canBeChanged('plan', $plan);
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

$canBatchActionStory = ($canBeChanged && ($canBatchUnlinkStory || $canBatchCloseStory || $canBatchEditStory || $canBatchReviewStory || $canBatchChangeBranchStory || $canBatchChangeModuleStory || $canBatchChangePlanStory || $canBatchChangeStageStory || $canBatchAssignToStory));
$canBatchActionBug   = ($canBeChanged && ($canBatchUnlinkBug || $canBatchEditBug || $canBatchChangePlanBug));

unset($lang->story->reviewResultList[''], $lang->story->reviewResultList['revert']);
unset($lang->story->reasonList[''], $lang->story->reasonList['subdivided'], $lang->story->reasonList['duplicate']);
unset($plans[''], $lang->story->stageList[''], $users['']);

$planItems = $moduleItems = $branchItems = $reviewRejectItems = $stageItems = $assignItems = $reviewResultItems = array();
foreach($lang->story->reviewResultList as $key => $result) $reviewResultItems[$key] = array('text' => $result,     'class' => 'batch-btn', 'data-type' => 'story', 'data-url' => $this->createLink('story', 'batchReview', "result=$key"));
foreach($lang->story->reasonList as $key => $reason)       $reviewRejectItems[]     = array('text' => $reason,     'class' => 'batch-btn', 'data-type' => 'story', 'data-url' => $this->createLink('story', 'batchReview', "result=reject&reason=$key"));
foreach($branchTagOption as $branchID => $branchName)      $branchItems[]           = array('text' => $branchName, 'class' => 'batch-btn', 'data-type' => 'story', 'data-url' => $this->createLink('story', 'batchChangeBranch', "branchID=$branchID"));
foreach($modules as $moduleID => $moduleName)              $moduleItems[]           = array('text' => $moduleName, 'class' => 'batch-btn', 'data-type' => 'story', 'data-url' => $this->createLink('story', 'batchChangeModule', "moduleID=$moduleID"));
foreach($plans as $planID => $planName)                    $planItems[]             = array('text' => $planName,   'class' => 'batch-btn', 'data-type' => 'story', 'data-url' => $this->createLink('story', 'batchChangePlan', "planID=$planID&oldPlanID={$plan->id}"));
foreach($lang->story->stageList as $key => $stageName)     $stageItems[]            = array('text' => $stageName,  'class' => 'batch-btn', 'data-type' => 'story', 'data-url' => $this->createLink('story', 'batchChangeStage', "stage=$key"));

$pinyinItems = common::convert2Pinyin($users);
foreach($users as $account => $realname)
{
    if($account == 'closed') continue;
    $assignItems[] = array('text' => $realname, 'keys' => zget($pinyinItems, $realname, ''), 'class' => 'batch-btn', 'data-type' => 'story', 'data-url' => $this->createLink('story', 'batchAssignTo', "productID=$plan->product"), 'data-account' => $account);
}

if(isset($reviewResultItems['reject'])) $reviewResultItems['reject'] = array('class' => 'not-hide-menu', 'text' => $lang->story->reviewResultList['reject'], 'items' => $reviewRejectItems);
$reviewResultItems = array_values($reviewResultItems);

$navStoryActionItems = array();
if($canBatchCloseStory)  $navStoryActionItems[] = array('text' => $lang->close, 'class' => 'batch-btn', 'data-type' => 'story', 'data-page' => 'batch', 'data-url' => helper::createLink('story', 'batchClose', "productID={$plan->product}"));
if($canBatchEditStory)   $navStoryActionItems[] = array('text' => $lang->edit, 'class' => 'batch-btn',  'data-type' => 'story', 'data-page' => 'batch', 'data-url' => helper::createLink('story', 'batchEdit', "productID=$plan->product&projectID=$projectID&branch=$branch"));
if($canBatchReviewStory) $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->review, 'items' => $reviewResultItems);
if($canBatchChangeBranchStory && $product->type != 'normal') $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->product->branchName[$product->type], 'items' => $branchItems);
if($canBatchChangeModuleStory) $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->moduleAB, 'items' => $moduleItems);
if($canBatchChangePlanStory)   $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->planAB, 'items' => $planItems, 'listProps' => array('searchBox' => true, 'maxHeight' => 400));
if($canBatchChangeStageStory)  $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->stageAB, 'items' => $stageItems);
if($canBatchAssignToStory)     $navStoryActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->assignedTo, 'items' => $assignItems, 'listProps' => array('searchBox' => true, 'maxHeight' => 400));

$storyFootToolbar = array();
if($canBatchActionStory)
{
    $storyFootToolbar = array('items' => array
    (
        array('type' => 'btn-group', 'items' => array
        (
            array('text' => $lang->productplan->unlinkStoryAB, 'className' => 'batch-btn size-sm', 'disabled' => ($canBatchUnlinkStory ? '' : 'disabled'), 'btnType' => 'secondary', 'data-type' => 'story', 'data-url' => helper::createLink('productplan', 'batchUnlinkStory', "planID=$plan->id&orderBy=$orderBy")),
            array('caret' => 'up', 'className' => 'size-sm', 'btnType' => 'secondary', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start', 'items' => $navStoryActionItems)
        ))
    ));
}

$planItems = array();
foreach($plans as $planID => $planName) $planItems[] = array('text' => $planName, 'class' => 'batch-btn', 'data-type' => 'bug', 'data-url' => $this->createLink('bug', 'batchChangePlan', "planID=$planID"));

$navBugActionItems = array();
if($canBatchEditBug)       $navBugActionItems[] = array('text' => $lang->edit, 'class' => 'batch-btn', 'data-type' => 'bug', 'data-page' => 'batch', 'data-url' => helper::createLink('bug', 'batchEdit', "productID=$plan->product&branch=$branch"));
if($canBatchChangePlanBug) $navBugActionItems[] = array('class' => 'not-hide-menu', 'text' => $lang->story->planAB, 'items' => $planItems, 'listProps' => array('searchBox' => true, 'maxHeight' => 400));

$bugFootToolbar = array();
if($canBatchActionBug)
{
    $bugFootToolbar = array('items' => array
    (
        array('type' => 'btn-group', 'items' => array
        (
            $canBatchUnlinkBug ? array('text' => $lang->productplan->unlinkAB, 'className' => 'batch-btn size-sm', 'btnType' => 'secondary', 'data-type' => 'bug', 'data-url' => helper::createLink('productplan', 'batchUnlinkBug', "planID=$plan->id&orderBy=$orderBy")) : null,
            array('caret' => 'up', 'className' => 'size-sm', 'btnType' => 'secondary', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start', 'items' => $navBugActionItems)
        ))
    ));
}

$planStories = initTableData($planStories, $storyCols, $this->productplan);
$planBugs    = initTableData($planBugs,    $bugCols,   $this->productplan);
foreach($planStories as $story) $story->estimate = helper::formatHours($story->estimate) . $config->hourUnit;

$createStoryLink            = common::hasPriv('story', 'create') ? $this->createLink('story', 'create', "productID=$plan->product&branch=$plan->branch&moduleID=0&storyID=0&projectID=$projectID&bugID=0&planID=$plan->id") : null;
$batchCreateStoryLink       = common::hasPriv('story', 'batchCreate') ? $this->createLink('story', 'batchCreate', "productID=$plan->product&branch=$plan->branch&moduleID=0&story=0&project=$projectID&plan={$plan->id}") : null;
$createRequirementLink      = common::hasPriv('requirement', 'create') ? $this->createLink('requirement', 'create', "productID=$plan->product&branch=$plan->branch&moduleID=0&storyID=0&projectID=$projectID&bugID=0&planID=$plan->id") : null;
$batchCreateRequirementLink = common::hasPriv('requirement', 'batchCreate') ? $this->createLink('requirement', 'batchCreate', "productID=$plan->product&branch=$plan->branch&moduleID=0&story=0&project=$projectID&plan={$plan->id}") : null;
$createEpicLink             = common::hasPriv('epic', 'create') ? $this->createLink('epic', 'create', "productID=$plan->product&branch=$plan->branch&moduleID=0&storyID=0&projectID=$projectID&bugID=0&planID=$plan->id") : null;
$batchCreateEpicLink        = common::hasPriv('epic', 'batchCreate') ? $this->createLink('epic', 'batchCreate', "productID=$plan->product&branch=$plan->branch&moduleID=0&story=0&project=$projectID&plan={$plan->id}") : null;

$branchNames = '';
if($product->type != 'normal')
{
    foreach(explode(',', (string)$plan->branch) as $branchID) $branchNames .= "{$branchOption[$branchID]},";
    $branchNames = trim($branchNames, ',');
}

$fnGetChildrenPlans = function($childrenPlans)
{
    $childrenPlanItems = array();
    foreach($childrenPlans as $childrenPlan)
    {
        $childrenPlanItems[] = a(set::href(inlink('view', "planID={$childrenPlan->id}")), "#{$childrenPlan->id} {$childrenPlan->title}");
        $childrenPlanItems[] = h::br();
    }

    array_pop($childrenPlanItems);
    return $childrenPlanItems;
};

$actions = $this->loadModel('common')->buildOperateMenu($plan);
foreach($actions as $actionType => $typeActions)
{
    foreach($typeActions as $key => $action)
    {
        $actions[$actionType][$key]['url']       = str_replace(array('{id}', '{product}', '{branch}'), array((string)$plan->id, $plan->product, $plan->branch), $action['url']);
        $actions[$actionType][$key]['className'] = isset($action['className']) ? $action['className'] . ' ghost' : 'ghost';
        $actions[$actionType][$key]['iconClass'] = isset($action['iconClass']) ? $action['iconClass'] . ' text-primary' : 'text-primary';
        if($actionType == 'suffixActions')
        {
            if($action['icon'] == 'edit')  $actions['suffixActions'][$key]['text'] = $lang->edit;
            if($action['icon'] == 'trash') $actions['suffixActions'][$key]['text'] = $lang->delete;
        }
    }
}
detailHeader
(
    to::prefix
    (
        $isInModal ? null : backBtn(set::icon('back'), set::type('secondary'), set::url($this->session->productPlanList), $lang->goback),
        entityLabel(set(array('entityID' => $plan->id, 'level' => 1, 'text' => $plan->title))),
        span(setClass('label circle primary'), ($plan->begin == FUTURE_TIME || $plan->end == FUTURE_TIME) ? $lang->productplan->future : $plan->begin . '~' . $plan->end),
        $plan->deleted ? span(setClass('label danger'), $lang->product->deleted) : null
    ),
    !$isInModal && !$plan->deleted && $actions ? to::suffix
    (
        btnGroup(set::items($actions['mainActions'])),
        !empty($actions['mainActions']) && !empty($actions['suffixActions']) ? div(setClass('divider mx-2')): null,
        btnGroup(set::items($actions['suffixActions']))
    ) : null
);

detailBody
(
    set::hasExtraMain(false),
    sectionList
    (
        tabs
        (
            setClass('w-full relative'),
            on::shown('.tab-pane')->call('$.apps.updateAppUrl', jsRaw('$this.data("url")')),
            tabPane
            (
                to::prefix(icon($lang->icons['story'], setClass('text-secondary'))),
                set::key('stories'),
                set::title($lang->productplan->linkedStories),
                set::active($type == 'story'),
                setData('url', sprintf($tabUrl, 'story')),
                $plan->parent >= 0 ? toolbar
                (
                    setClass('tab-actions absolute right-0 gap-2'),
                    setStyle('top', '-8px'),
                    $isInModal || (empty($createStoryLink) && empty($batchCreateStoryLink)) ? null : dropdown
                    (
                        btn
                        (
                            $app->tab == 'project' ? setData('app', 'project') : null,
                            set::text($lang->story->create),
                            setClass('open-url secondary' . (empty($createStoryLink) ? ' disabled' : '')),
                            set::icon('plus'),
                            set::caret(true),
                            set::url($createStoryLink)
                        ),
                        set::items(array(
                            $this->config->URAndSR  ? array('text' => $lang->requirement->create, 'url' => $createRequirementLink, 'class' => empty($createRequirementLink) ? 'disabled' : '', 'data-app' => $app->tab) : null,
                            $this->config->enableER ? array('text' => $lang->epic->create, 'url' => $createEpicLink, 'class' => empty($createEpicLink) ? 'disabled' : '', 'data-app' => $app->tab) : null,
                            array('text' => $lang->story->batchCreate, 'items' => array(
                                array('text' => $lang->SRCommon, 'url' => $batchCreateStoryLink, 'class' => empty($batchCreateStoryLink) ? 'disabled' : '', 'data-app' => $app->tab),
                                $this->config->URAndSR  ? array('text' => $lang->URCommon, 'url' => $batchCreateRequirementLink, 'class' => empty($batchCreateRequirementLink) ? 'disabled' : '', 'data-app' => $app->tab) : null,
                                $this->config->enableER ? array('text' => $lang->ERCommon, 'url' => $batchCreateEpicLink, 'class' => empty($batchCreateEpicLink) ? 'disabled' : '', 'data-app' => $app->tab) : null
                            ))
                        )),
                        set::trigger('hover'),
                        set::placement('bottom-end')
                    ),
                    !$isInModal && common::hasPriv('productplan', 'linkStory') ? btn
                    (
                        set::type('primary'),
                        set::icon('link'),
                        set::text($lang->productplan->linkStory),
                        set::className('linkStory-btn'),
                        bind::click('window.showLink', array('params' => array('story')))
                    ) : null
                ) : toolbar(setClass('tab-actions absolute right-0 gap-2'), setStyle('top', '-8px')),
                dtable
                (
                    setID('storyDTable'),
                    set::plugins(array('sortable')),
                    set::sortHandler('.move-plan'),
                    set::onSortEnd(jsRaw('window.onSortEnd')),
                    set::style(array('min-width' => '100%')),
                    set::userMap($users),
                    set::bordered(true),
                    set::cols($storyCols),
                    set::data(array_values($planStories)),
                    set::noNestedCheck(),
                    set::checkable($canBatchActionStory),
                    set::onRenderCell(jsRaw('window.renderStoryCell')),
                    set::footToolbar($storyFootToolbar),
                    set::sortLink(createLink('productplan', 'view', "planID={$plan->id}&type=story&orderBy={name}_{sortType}&link=false&param={$param}&recTotal={$storyPager->recTotal}&recPerPage={$storyPager->recPerPage}&page={$storyPager->pageID}")),
                    set::orderBy($orderBy),
                    set::extraHeight('+144'),
                    set::checkInfo(jsRaw("function(checkedIDList){return window.setStatistics(this, checkedIDList, '{$summary}');}")),
                    set::footPager
                    (
                        usePager('storyPager', '', array(
                            'recPerPage' => $storyPager->recPerPage,
                            'recTotal' => $storyPager->recTotal,
                            'linkCreator' => helper::createLink('productplan', 'view', "planID={$plan->id}&type=story&orderBy={$orderBy}&link=false&param={$param}&recTotal={$storyPager->recTotal}&recPerPage={recPerPage}&page={page}")
                        ))
                   )
                )
            ),
            tabPane
            (
                to::prefix(icon($lang->icons['bug'], setClass('text-danger'))),
                set::key('bugs'),
                set::title($lang->productplan->linkedBugs),
                set::active($type == 'bug'),
                setData('url', sprintf($tabUrl, 'bug')),
                !$isInModal && $plan->parent >= 0 && common::hasPriv('productplan', 'linkBug') ? toolbar
                (
                    setClass('tab-actions absolute right-0 gap-2'),
                    setStyle('top', '-8px'),
                    btn
                    (
                        set::type('primary'),
                        set::icon('link'),
                        set::text($lang->productplan->linkBug),
                        set::className('linkBug-btn'),
                        bind::click('window.showLink', array('params' => array('bug')))
                    )
                ) : null,
                dtable
                (
                    setID('bugDTable'),
                    set::style(array('min-width' => '100%')),
                    set::userMap($users),
                    set::bordered(true),
                    set::cols($bugCols),
                    set::data(array_values($planBugs)),
                    set::checkable($canBatchActionBug),
                    set::footToolbar($bugFootToolbar),
                    set::sortLink(createLink('productplan', 'view', "planID={$plan->id}&type=bug&orderBy={name}_{sortType}&link=false&param={$param}&recTotal={$bugPager->recTotal}&recPerPage={$bugPager->recPerPage}&page={$bugPager->pageID}")),
                    set::orderBy($orderBy),
                    set::extraHeight('+144'),
                    set::footer(array('checkbox', 'toolbar', array('html' => sprintf($lang->productplan->bugSummary, count($planBugs)), 'className' => "text-dark"), 'flex', 'pager')),
                    set::footPager
                    (
                        usePager('bugPager', '', array(
                            'recPerPage' => $bugPager->recPerPage,
                            'recTotal' => $bugPager->recTotal,
                            'linkCreator' => helper::createLink('productplan', 'view', "planID={$plan->id}&type=bug&orderBy={$orderBy}&link=false&param={$param}&recTotal={$bugPager->recTotal}&recPerPage={recPerPage}&page={page}")
                        ))
                    )
                )
            ),
            tabPane
            (
                to::prefix(icon('flag', setClass('text-special'))),
                set::key('planInfo'),
                set::title($lang->productplan->view),
                set::active($type == 'planInfo'),
                setData('url', sprintf($tabUrl, 'planInfo')),
                tableData
                (
                    set::title($lang->productplan->basicInfo),
                    item(set::name($lang->productplan->title), $plan->title),
                    $plan->parent > 0 ? item(set::name($lang->productplan->parent), a(set::href(inlink('view', "planID={$parentPlan->id}")), "#{$parentPlan->id} {$parentPlan->title}")) : null,
                    $product->type != 'normal' ? item(set::name($lang->product->branch), $branchNames) : null,
                    item(set::name($lang->productplan->begin), $plan->begin == FUTURE_TIME ? $lang->productplan->future : $plan->begin),
                    item(set::name($lang->productplan->end), $plan->end == FUTURE_TIME ? $lang->productplan->future : $plan->end),
                    $plan->parent == '-1' ? item(set::name($lang->productplan->children), $fnGetChildrenPlans($childrenPlans)) : null,
                    item(set::name($lang->productplan->status), $lang->productplan->statusList[$plan->status]),
                    item(set::name($lang->productplan->desc), empty($plan->desc) ? $lang->noData : html(($plan->desc)))
                ),
                html($this->printExtendFields($plan, 'html', 'position=all', false)),
                h::hr(setClass('mt-4')),
                history(set::objectID($plan->id), set::commentBtn(false), set::objectType('productplan'))
            )
        )
    )
);

render();
