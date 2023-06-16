<?php
declare(strict_types=1);
/**
 * The story view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */

namespace zin;

/* Show feature bar. */
featureBar
(
    set::current($type),
    set::link(createLink($app->rawModule, $app->rawMethod, "&executionID=$executionID&storyType=$storyType&orderBy=$orderBy&type={key}")),
    li(searchToggle(set::module('story')))
);

/* Build create story button. */
$fnBuildCreateStoryButton = function() use ($lang, $product, $storyType, $productID, $executionID)
{
    if(!common::canModify('product', $product)) return null;

    $createLink      = createLink('story', 'create', "product=$productID&branch=0&moduleID=0&storyID=0&objectID=$executionID&bugID=0&planID=0&todoID=0&extra=&storyType=$storyType");
    $batchCreateLink = createLink('story', 'batchCreate', "productID=$productID&branch=0&moduleID=0&storyID=0&executionID=$executionID&plan=0&storyType=$storyType");

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

    if(!empty($productID) && hasPriv($storyType, 'batchCreate') && hasPriv($storyType, 'create'))
    {
        $items = array();

        if(commonModel::isTutorialMode())
        {
            /* Tutorial create link. */
            $wizardParams = helper::safe64Encode("productID=$productID&branch=0&moduleID=0");
            $link = $this->createLink('tutorial', 'wizard', "module=story&method=create&params=$wizardParams");
            $items[] = array('text' => $lang->story->createCommon, 'url' => $link);
        }
        else
        {
            $items[] = array('text' => $lang->story->create, 'url' => $createLink);
        }

        $items[] = array('text' => $lang->story->batchCreate, 'url' => $batchCreateLink);

        return dropdown
        (
            icon('plus'),
            $createBtnTitle,
            span(setClass('caret')),
            setClass('btn secondary'),
            set::items($items),
        );
    }

    return item(set(array
    (
        'text'  => $createBtnTitle,
        'icon'  => 'plus',
        'type'  => 'dropdown',
        'class' => 'secondary',
        'url'   => $createBtnLink
    )));
};

/* Build link story button. */
$fnBuildLinkStoryButton = function() use($lang, $product, $productID, $executionID)
{
    if(!common::canModify('product', $product)) return null;

    /* Tutorial mode. */
    if(commonModel::isTutorialMode())
    {
        $wizardParams = helper::safe64Encode("project={$executionID}");

        return item(set(array
        (
            'text' => $lang->project->linkStory,
            'url'  => createLink('tutorial', 'wizard', "module=project&method=linkStory&params=$wizardParams")
        )));
    }

    $buttonLink  = '';
    $buttonTitle = '';
    $dataToggle  = '';
    if(common::hasPriv('projectstory', 'importPlanStories'))
    {
        $buttonLink  = empty($productID) ? '' : '#linkStoryByPlan';
        $buttonTitle = $lang->execution->linkStoryByPlan;
        $dataToggle  = 'data-toggle="modal"';
    }
    if(common::hasPriv('projectstory', 'linkStory'))
    {
        $buttonLink  = $this->createLink('projectstory', 'linkStory', "project=0");
        $buttonTitle = $lang->execution->linkStory;
        $dataToggle  = '';
    }

    if(empty($buttonLink)) return null;

    if(!empty($productID) && common::hasPriv('projectstory', 'linkStory') && common::hasPriv('projectstory', 'importPlanStories'))
    {
        $items = array();
        $items[] = array('text' => $lang->execution->linkStory,       'url' => createLink('projectstory', 'linkStory', "project=0"));
        $items[] = array('text' => $lang->execution->linkStoryByPlan, 'url' => '#linkStoryByPlan', 'data-toggle' => $dataToggle);

        return dropdown
        (
            icon('link'),
            $buttonTitle,
            span(setClass('caret')),
            setClass('btn primary'),
            set::items($items),
        );
    }

    return null;
};

/* Show tool bar. */
toolbar
(
    item(set(array
    (
        'text' => $lang->story->report->common,
        'icon' => 'common-report icon-bar-chart muted',
        'class' => 'ghost'
    ))),
    item(set(array
    (
        'text'  => $lang->story->export,
        'icon'  => 'export',
        'class' => 'ghost',
        'url'   => createLink('story', 'export', "productID=$productID&orderBy=$orderBy&executionID=$executionID&browseType=$type&storyType=$storyType"),
    ))),
    $fnBuildCreateStoryButton(),
    $fnBuildLinkStoryButton()
);


/* DataTable columns. */
$setting = $this->datatable->getSetting('story');
$cols    = array_values($setting);
foreach($cols as $key => $col)
{
    $col['name']  = $col['id'];
    if($col['id'] == 'title')
    {
        $col['link'] = sprintf($col['link'], createLink('execution', 'storyView', array('storyID' => '${row.id}', 'execution' => $executionID)));
    }

    $cols[$key] = $col;
}


/* DataTable data. */
$this->loadModel('story');

$data = array();
foreach($stories as $story)
{
    $story->taskCount = $storyTasks[$story->id];
    $story->actions   = $this->story->buildActionButtonList($story, 'browse');
    $story->plan      = isset($story->planTitle) ? $story->planTitle : $plans[$story->plan];

    $data[] = $story;

    if(!isset($story->children)) continue;

    /* Children. */
    foreach($story->children as $key => $child)
    {
        $child->taskCount = $storyTasks[$child->id];
        $child->actions   = $this->story->buildActionButtonList($child, 'browse');

        $data[] = $child;
    }
}

sidebar
(
    moduleMenu(set(array(
        'modules'     => $moduleTree,
        'activeKey'   => $param,
        'closeLink'   => $this->createLink('execution', 'story')
    )))
);

$canBatchEdit        = common::hasPriv('story', 'batchEdit');
$canBatchClose       = common::hasPriv('story', 'batchClose') && $storyType != 'requirement';
$canBatchChangeStage = common::hasPriv('story', 'batchChangeStage') && $storyType != 'requirement';
$canBatchUnlink      = common::hasPriv('execution', 'batchUnlinkStory');
$canBatchToTask      = common::hasPriv('story', 'batchToTask', $checkObject) && $storyType != 'requirement';
$canBatchAssignTo    = common::hasPriv($storyType, 'batchAssignTo');
$canBatchAction      = $canBeChanged && in_array(true, array($canBatchEdit, $canBatchClose, $canBatchChangeStage, $canBatchUnlink, $canBatchToTask, $canBatchAssignTo));

$footToolbar = array();
if($canBatchAction)
{
    if($canBatchToTask)
    {
        menu
        (
            set::id('batchToTask'),
            set::class('dropdown-menu'),
            $canBatchToTask ? item(set(array(
                'text'  => $lang->story->batchToTask,
                'class' => 'batch-btn ajax-btn',
                'url'   => '#batchToTask'
            ))) : null,
        );
    }

    if($canBatchToTask || $canBatchEdit)
    {
        $editClass = $canBatchEdit ? 'batch-btn' : 'disabled';
        $footToolbar['items'][] = array(
            'type'  => 'btn-group',
            'items' => array(
                array('text' => $lang->edit, 'class' => "btn secondary size-sm {$editClass}", 'btnType' => 'primary', 'data-url' => createLink('story', 'batchEdit', "productID=0&executionID={$execution->id}&branch=0&storyType={$storyType}")),
                array('caret' => 'up', 'class' => 'btn btn-caret size-sm secondary', 'url' => '#batchToTask', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
            )
        );
    }

    if($canBatchAssignTo)
    {
        $assignedToItems = array();
        foreach ($users as $account => $name)
        {
            $assignedToItems[] = array(
                'text'     => $name,
                'class'    => 'batch-btn ajax-btn',
                'data-url' => createLink('story', 'batchAssignTo', "toryType={$storyType}&assignedTo={$account}")
            );
        }

        menu
        (
            set::id('navAssignedTo'),
            set::class('dropdown-menu'),
            set::items($assignedToItems)
        );
    }

    if($canBatchAssignTo)
    {
        $footToolbar['items'][] = array(
            'caret'       => 'up',
            'text'        => $lang->story->assignedTo,
            'class'       => 'btn btn-caret size-sm secondary',
            'url'         => '#navAssignedTo',
            'data-toggle' => 'dropdown'
        );
    }

    if($canBatchClose)
    {
        $footToolbar['items'][] = array(
            'text'  => $lang->close,
            'class' => 'btn btn-caret size-sm secondary',
            'url'   => $this->createLink('story', 'batchClose', "productID=0&executionID={$execution->id}")
        );
    }

    if($canBatchChangeStage)
    {
        $stageItems = array();
        foreach($lang->story->stageList as $stageID => $stage)
        {
            $stageItems[] = array(
                'text'     => $stage,
                'class'    => 'batch-btn ajax-btn',
                'data-url' => createLink('story', 'batchChangeStage', "stageID=$stageID")
            );
        }

        menu
        (
            set::id('navStage'),
            set::class('dropdown-menu'),
            set::items($stageItems)
        );
    }

    if($canBatchChangeStage)
    {
        $footToolbar['items'][] = array(
            'caret'          => 'up',
            'text'           => $lang->story->stageAB,
            'class'          => 'btn btn-caret size-sm secondary',
            'url'            => '#navStage',
            'data-toggle'    => 'dropdown',
            'data-placement' => 'top-start'
        );
    }

    if($canBatchUnlink)
    {
        $footToolbar['items'][] = array(
            'text'  => $lang->execution->unlinkStoryAB,
            'class' => 'btn btn-caret size-sm secondary',
            'url'   => $this->createLink('execution', 'batchUnlinkStory', "executionID={$execution->id}")
        );
    }
}

jsVar('cases', $storyCases);
jsVar('summary', $summary);
jsVar('checkedSummary', str_replace('%storyCommon%', $lang->SRCommon, $lang->product->checkedSummary));
dtable
(
    set::userMap($users),
    set::customCols(true),
    set::groupDivider(true),
    set::cols($cols),
    set::data($data),
    set::className('shadow rounded'),
    set::footToolbar($footToolbar),
    set::footPager(
        usePager(),
        set::recPerPage($pager->recPerPage),
        set::recTotal($pager->recTotal),
        set::linkCreator(helper::createLink('execution', 'story', "executionID={$execution->id}&storyType={$storyType}&orderBy=$orderBy&type={$type}&param={$param}&recTotal={$recTotal}&recPerPage={recPerPage}&page={page}"))
    ),
    set::checkInfo(jsRaw('function(checkedIDList){return window.setStatistics(this, checkedIDList);}')),
);

render();
