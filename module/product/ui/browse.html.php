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

$isProjectStory    = $this->app->rawModule == 'projectstory';
$projectHasProduct = $isProjectStory && !empty($project->hasProduct);
$projectIDParam    = $isProjectStory ? "projectID=$projectID&" : '';
$storyBrowseType   = $this->session->storyBrowseType;

$this->loadModel('story');

/* Generate sidebar to display module tree menu. */
$fnGenerateSideBar = function() use ($moduleTree, $moduleID)
{
    sidebar
    (
        moduleMenu(set(array
        (
            'modules'   => $moduleTree,
            'activeKey' => $moduleID,
            'closeLink' => createLink('execution', 'task')
        )))
    );
};

/* Build create story button. */
$fnBuildCreateStoryButton = function() use ($lang, $product, $isProjectStory, $storyType, $productID, $branch, $moduleID, $projectID, $from)
{
    if(!common::canModify('product', $product)) return null;

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

        return dropdown
        (
            icon('plus'),
            $createBtnTitle,
            span(setClass('caret')),
            setClass('btn primary'),
            set::items($items),
        );
    }

    return item(set(array
    (
        'text'  => $createBtnTitle,
        'icon'  => 'plus',
        'type'  => 'dropdown',
        'class' => $from == 'project' ? 'secondary' : 'primary',
        'url'   => $createBtnLink
    )));
};

/* Build link story button. */
$fnBuildLinkStoryButton = function() use($lang, $product, $productID, $projectHasProduct, $project, $projectID)
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
    $dataToggle  = '';
    if(common::hasPriv('projectstory', 'importPlanStories'))
    {
        $buttonLink  = empty($productID) ? '' : '#linkStoryByPlan';
        $buttonTitle = $lang->execution->linkStoryByPlan;
        $dataToggle  = 'data-toggle="modal"';
    }
    if(common::hasPriv('projectstory', 'linkStory'))
    {
        $buttonLink  = $this->createLink('projectstory', 'linkStory', "project=$projectID");
        $buttonTitle = $lang->execution->linkStory;
        $dataToggle  = '';
    }

    if(empty($buttonLink)) return null;

    if(!empty($productID) && common::hasPriv('projectstory', 'linkStory') && common::hasPriv('projectstory', 'importPlanStories'))
    {
        $items = array();
        $items[] = array('text' => $lang->execution->linkStory,       'url' => createLink('projectstory', 'linkStory', "project=$projectID"));
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

/* DataTable columns. */
$setting = $this->datatable->getSetting('product');
$cols    = array_values($setting);
foreach($cols as $key => $col)
{
    $col->name  = $col->id;
    if($col->id == 'title' || $col->id == 'id')
    {
        $col->sortType     = true;
        $col->nestedToggle = true;
        $col->checkbox     = true;
    }

    if($col->id == 'assignedTo')
    {
        $col->userMap = $users;
    }

    $cols[$key] = $col;
}

/* Build action menus. */
$fnBuildActions = function($story) use ($lang, $storyType)
{
    $demoUrl = '/index.php?m=product&f=export&programID=0&status=noclosed&orderBy=program_asc&param=0';

    if(!common::canBeChanged('story', $story))
    {
        return array(array
        (
            'name'        => 'closed',
            'hint'        => $lang->close,
            'disabled'    => false,
            'data-toggle' => 'modal',
            'data-url'    => $demoUrl
        ));
    }

    $storyReviewer = isset($story->reviewer) ? $story->reviewer : array();
    if($story->URChanged)
    {
        return array(array('name' => 'processStoryChange', 'data-toggle' => 'modal', 'data-url' => $demoUrl));
    }

    $actions = array();

    /* Change button. */
    $isClickable = $this->story->isClickable($story, 'change');
    $actions[] = array
    (
        'name'        => 'change',
        'data-toggle' => 'modal',
        'data-url'    => $demoUrl,
        'hint'        => $isClickable ? null : $lang->story->changeTip,
        'disabled'    => !$isClickable,
    );

    /* Submit review button. */
    $actSubmitreview = array();
    $actReview       = array();
    $actRecall       = array();
    if(strpos('draft,changing', $story->status) !== false)
    {
        $actSubmitreview = array('name' => 'submitreview', 'data-toggle' => 'modal', 'data-url' => $demoUrl);
    }
    else
    {
        $isClickable = $this->story->isClickable($story, 'review');
        $hint        = $this->lang->story->review;
        if(!$isClickable && $story->status != 'closed')
        {
            if($story->status == 'active')
            {
                $hint = $this->lang->story->reviewTip['active'];
            }
            elseif($storyReviewer && in_array($this->app->user->account, $storyReviewer))
            {
                $hint = $this->lang->story->reviewTip['reviewed'];
            }
            elseif($storyReviewer && !in_array($this->app->user->account, $storyReviewer))
            {
                $hint = $this->lang->story->reviewTip['notReviewer'];
            }
        }

        $actReview = array('name' => 'review', 'data-toggle' => 'modal', 'data-url' => $demoUrl, 'hint' => $hint, 'disabled' => !$isClickable);
    }

    $isClickable = $this->story->isClickable($story, 'recall');
    $hint        = $story->status == 'changing' ? $this->lang->story->recallChange : $this->lang->story->recall;
    $hint        = $isClickable ? $hint : $this->lang->story->recallTip['actived'];
    $actRecall   = array('name' => 'recall', 'data-toggle' => 'modal', 'data-url' => $demoUrl, 'hint' => $hint, 'disabled' => !$isClickable);

    if(!empty($actSubmitreview))
    {
        $actions[] = $actSubmitreview;
        $actions[] = array('name' => 'other', 'type' => 'dropdown', 'items' => array($actRecall));
    }
    else
    {
        if($actReview['disabled'] && !$actRecall['disabled'])
        {
            $actions[] = $actRecall;
            $actions[] = array('name' => 'other', 'type' => 'dropdown', 'items' => array($actReview));
        }
        else
        {
            $actions[] = $actReview;
            $actions[] = array('name' => 'other', 'type' => 'dropdown', 'items' => array($actRecall));
        }
    }

    if($this->app->tab == 'product' && $storyType == 'requirement')
    {
        $actions[] = array('name' => 'close', 'data-toggle' => 'modal', 'data-url' => $demoUrl);
    }

    if(($this->app->rawModule == 'projectstory' || ($this->app->tab != 'product' && $storyType == 'requirement')) && $this->config->vision != 'lite')
    {

        $actions[] = array('name' => 'close', 'data-toggle' => 'modal', 'data-url' => $demoUrl);

        /* Unlink story button. */
        if(!empty($execution) && $execution->hasProduct)
        {
            $actions[] = array('name' => 'unlinkStory', 'data-toggle' => 'modal', 'data-url' => $demoUrl);
        }
    }

    if($this->app->tab == 'product' && $storyType == 'story')
    {
        $actions[] = array('name' => 'close', 'data-toggle' => 'modal', 'data-url' => $demoUrl);
    }

    /* Edit button. */
    $actions[] = array('name' => 'edit', 'data-toggle' => 'modal', 'data-url' => $demoUrl, 'hint' => $hint, 'disabled' => !$isClickable);

    if($story->type != 'requirement' && $this->config->vision != 'lite')
    {
        $actions[] = array('name' => 'testcase', 'data-toggle' => 'modal', 'data-url' => $demoUrl);
    }

    /* Batch create button. */
    $shadow = $this->dao->findByID($story->product)->from(TABLE_PRODUCT)->fetch('shadow');
    if($this->app->rawModule != 'projectstory' || $this->config->vision == 'lite' || $shadow)
    {
        $isClick = $this->isClickable($story, 'batchcreate');
        $title   = $story->type == 'story' ? $this->lang->story->subdivideSR : $this->lang->story->subdivide;
        if(!$isClick && $story->status != 'closed')
        {
            if($story->parent > 0)
            {
                $title = $this->lang->story->subDivideTip['subStory'];
            }
            elseif(!empty($story->twins))
            {
                $title = $this->lang->story->subDivideTip['twinsSplit'];
            }
            else
            {
                if($story->status != 'active') $title = sprintf($this->lang->story->subDivideTip['notActive'], $story->type == 'story' ? $this->lang->SRCommon : $this->lang->URCommon);
                if($story->status == 'active' && $story->stage != 'wait') $title = sprintf($this->lang->story->subDivideTip['notWait'], zget($this->lang->story->stageList, $story->stage));
            }
        }

        $actions[] = array
        (
            'name'        => 'batchCreate',
            'data-toggle' => 'modal',
            'data-url'    => $demoUrl,
            'hint'        => $title,
            'disabled'    => !$isClick,
        );
    }


    return $actions;
};

/* DataTable data. */
$data = array();
foreach($stories as $story)
{
    $story->taskCount = $storyTasks[$story->id];
    $story->actions   = $fnBuildActions($story);

    $data[] = $story;

    if(!isset($story->children)) continue;

    /* Children. */
    foreach($story->children as $key => $child)
    {
        $child->taskCount = $storyTasks[$child->id];
        $child->actions   = $fnBuildActions($child);

        $data[] = $child;
    }
}

data('storyBrowseType', $storyBrowseType);

$fnGenerateSideBar();

featureBar
(
    set::current($browseType),
    set::linkParams("productID={$productID}&browseType={key}"),
    set::moreMenuLinkCallback(function($browseType) use($projectIDParam, $productID, $branch, $storyType)
    {
        global $app;
        return createLink($app->rawModule, $app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType={$browseType}&param=0&storyType=$storyType");
    }),
    li(searchToggle(set::module('story')))
);

toolbar
(
    item(set(array
    (
        'text' => $lang->project->report,
        'icon' => 'bar-chart',
        'class' => 'ghost'
    ))),
    item(set(array
    (
        'text'  => $lang->export,
        'icon'  => 'export',
        'class' => 'ghost',
        'url'   => createLink('story', 'export', "productID=$productID&orderBy=$orderBy&executionID=$projectID&browseType=$browseType&storyType=$storyType"),
    ))),
    item(set(array
    (
        'text'  => $lang->import,
        'icon'  => 'import',
        'class' => 'ghost',
        'url'   => createLink('story', 'import', "productID=$productID"),
    ))),
    $fnBuildCreateStoryButton(),
    $fnBuildLinkStoryButton()
);

dtable
(
    set::customCols(true),
    set::cols($cols),
    set::data($data),
    set::className('shadow rounded'),
    set::footPager(usePager()),
    set::nested(true),
    set::footer(jsRaw("function(){return window.footerGenerator.call(this, '{$summary}');}"))
);

render();
