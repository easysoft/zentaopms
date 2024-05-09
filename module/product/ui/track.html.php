<?php
declare(strict_types=1);
/**
* The UI file of product module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     product
* @link        https://www.zentao.net
*/

namespace zin;

if($config->URAndSR && $this->app->rawModule == 'projectstory' && $this->session->hasProduct)
{
    $productItems = array();
    foreach($projectProducts as $id => $name)
    {
        $productItems[] = array('text' => $name, 'url' => createLink('projectstory', 'track', "projectID={$this->session->project}&productID={$id}"), 'active' => $productID == $id);
    }
}

$getRequirements = function($tracks)
{
    global $app, $config, $lang;
    $requirementItems = array();
    $tab              = $app->rawModule == 'projectstory' ? 'project' : 'product';
    $module           = $app->rawModule == 'projectstory' ? 'projectstory' : 'story';
    foreach($tracks as $key => $requirement)
    {
        $track   = ($key == 'noRequirement') ? $requirement : $requirement->track;
        $rowspan = count($track);
        $title   = $lang->story->noRequirement;
        if($config->URAndSR && $key != 'noRequirement') $title = common::hasPriv($requirement->type, 'view') ? a(
            set('href', createLink('story', 'view', "storyID={$requirement->id}")),
            set('title', $requirement->title),
            set('data-app', $tab),
            $requirement->title
       ) : $requirement->title;

        $requirementItems[] = h::tr(
            $config->URAndSR ? h::td(
                $rowspan != 0 ? set('rowspan', $rowspan) : null,
                setClass('requirement'),
                $key != 'noRequirement' ? label(
                    setClass('primary-pale ring-primary mr-1'),
                    zget($lang->story->statusList, $requirement->status)
                ) : null,
                set('title', $key != 'noRequirement' ? $requirement->title : $lang->story->noRequirement),
                $title
            ) : null,
            count($track) != 0 ? getStoryTrack($track, $tab, $module) : null
            );
    }
    return $requirementItems;
};

function getStoryTrack($track, $tab, $module)
{
    $i          = 0;
    $storyItems = array();
    foreach($track as $storyID => $story)
    {
        if($i > 0)
        {
            $storyItems[] = h::tr(getTrackTd($storyID, $story, $tab, $module));
        }
        else
        {
            $storyItems[] = getTrackTd($storyID, $story, $tab, $module);
        }
        $i ++;
    }
    return $storyItems;
};

function getTrackTd($storyID, $story, $tab, $module)
{
    global $lang, $config;
    $trackItem = array();

    /* Story. */
    $trackItem[] = h::td(
        isset($story->parent) && $story->parent > 0 ? label(
            setClass('rounded-full light mr-1'),
            set('title', $lang->story->children),
            $lang->story->childrenAB
        ) : null,
        a(
            set('href', createLink($module, 'view', "storyID={$storyID}")),
            set('title', $story->title),
            set('data-app', $tab),
            $story->title
        )
    );

    $trackItem[] = h::td(getTaskTd($story->tasks)); // Task

    if(in_array($config->edition, array('max', 'ipd'))) $trackItem[] = h::td(getDesignTd($story->designs)); // Design

    $trackItem[] = h::td(getCaseTd($story->cases)); // Case

    if(in_array($config->edition, array('max', 'ipd')) && helper::hasFeature('devops')) $trackItem[] = h::td(getRevisionTd($story->revisions)); // Revision

    $trackItem[] = h::td(getBugTd($story->bugs)); // Bug

    return $trackItem;
};

function getTaskTd($tasks)
{
    $taskItems = array();
    foreach($tasks as $task)
    {
        $taskItems[] = a(
            set('href', createLink('task', 'view', "taskID={$task->id}")),
            set('title', $task->name),
            $task->name
        );
        $taskItems[] = br();
    }
    return $taskItems;
};

function getDesignTd($designs)
{
    $designItems = array();
    foreach($designs as $design)
    {
        $designItems[] = a(
            set('href', createLink('design', 'view', "designID={$design->id}")),
            set('title', $design->name),
            $design->name
        );
        $designItems[] = br();
    }
    return $designItems;
};

function getCaseTd($cases)
{
    $caseItems = array();
    foreach($cases as $case)
    {
        $caseItems[] = a(
            set('href', createLink('testcase', 'view', "caseID={$case->id}")),
            set('title', $case->title),
            $case->title
        );
        $caseItems[] = br();
    }
    return $caseItems;
};

function getRevisionTd($revisions)
{
    $revisionItems = array();
    foreach($revisions as $revision => $repoComment)
    {
        $revisionItems[] = a(
            set('href', createLink('design', 'revision', "revisionID={$revision}")),
            set('title', $repoComment),
            '#'. $revision . '-' . $repoComment
        );
        $revisionItems[] = br();
    }
    return $revisionItems;
};

function getBugTd($bugs)
{
    $bugItems = array();
    foreach($bugs as $bug)
    {
        $bugItems[] = a(
            set('href', createLink('bug', 'view', "bugID={$bug->id}")),
            set('title', $bug->title),
            $bug->title
        );
        $bugItems[] = br();
    }
    return $bugItems;
};

$colspan = 4;
if($config->URAndSR) $colspan ++;
if(in_array($config->edition, array('max', 'ipd'))) $colspan ++;
if(in_array($config->edition, array('max', 'ipd')) && helper::hasFeature('devops')) $colspan ++;
div
(
    setClass('main-col'),
    div(
        setClass('main-table'),
        h::table
        (
            setID('trackList'),
            setClass('table table-bordered'),
            h::thead
            (
                $config->URAndSR ? h::th(
                    !empty($productItems) ? dropdown(
                        btn(
                            setClass('ghost btn square btn-default'),
                            set::icon('product'),
                            $projectProducts[$productID]
                        ),
                        set::items($productItems),
                        set::placement('bottom-start')
                    ) : $lang->story->requirement
                ) : null,
                h::th($lang->story->story),
                h::th($lang->story->tasks),
                in_array($config->edition, array('max', 'ipd')) ? h::th($lang->story->design) : null,
                h::th($lang->story->case),
                in_array($config->edition, array('max', 'ipd')) && helper::hasFeature('devops') ? h::th($lang->story->repoCommit) : null,
                h::th($lang->story->bug)
            ),
            h::tbody
            (
                !empty($tracks) ? $getRequirements($tracks) : h::tr
                (
                    h::td
                    (
                        setClass('text-gray text-center empty-tip'),
                        set::colspan($colspan),
                        $lang->product->noData
                    )
                )
            )
        ),
        !empty($tracks) ? div
        (
            setClass('table-footer'),
            pager(
                set::_className('flex justify-end items-center'),
                set::linkCreator(createLink($app->rawModule, $app->rawMethod, "productID={$productID}&branch={$branch}&projectID={$projectID}&recTotal={$pager->recTotal}&recPerPage={recPerPage}&pagerID={page}"))
            )
        ) : null
    )
);

render();
