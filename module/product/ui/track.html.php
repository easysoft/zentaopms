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

dropmenu();

/* No data. */
if(empty($tracks))
{
    div
    (
        setClass('shadow canvas p-8 text-center'),
        $lang->noData
    );
    render();
    return;
}

/* Table column headers. */
function generateHeaderList($self, $config, $lang, $projectProducts, $productID)
{
    $fnGenerateTH = function(string $label): wg
    {
        return h::th(setClass('border border-slate-300 py-0.5 px-2 leading-8'), $label);
    };

    $cols = array();

    if($config->URAndSR)
    {
        if($self->app->rawModule == 'projectstory' && $self->session->hasProduct)
        {
            /* Project story list. */
            $items = array();
            foreach($projectProducts as $product)
            {
                $items[] = array
                (
                    'text'   => $product->name,
                    'url'    => createLink('projectstory', 'track', "projectID={$self->session->project}&productID={$product->id}"),
                    'active' => $productID == $product->id
                );
            }

            $cols[] = dropdown
            (
                to::trigger(btn($projectProducts[$productID]->name)),
                set::items($items)
            );
        }
        else
        {
            $cols[] = $fnGenerateTH($lang->story->requirement);
        }
    }

    $cols[] = $fnGenerateTH($lang->story->story);
    $cols[] = $fnGenerateTH($lang->story->tasks);
    $cols[] = $config->edition == 'max' ? $fnGenerateTH($lang->story->design) : null;
    $cols[] = $fnGenerateTH($lang->story->case);
    $cols[] = ($config->edition == 'max' && helper::hasFeature('devops')) ? $fnGenerateTH($lang->story->repoCommit) : null;
    $cols[] = $fnGenerateTH($lang->story->bug);

    return $cols;
}

/* Generate table rows. */
function generateRowList($self, $lang, $config, $tracks, $module, $tab)
{
    $fnGenerateTD = function(): wg
    {
        return h::td
        (
            setClass('border border-slate-300 py-0.5 px-2'),
            func_get_args()
        );
    };

    $rowList  = array();
    $cellList = array();
    foreach($tracks as $key => $requirement)
    {
        $track   = ($key == 'noRequirement') ? $requirement : $requirement->track;
        $rowspan = count($track);
        $title   = $lang->story->noRequirement;

        /* If with the setting to show requirement, then attach the requirement title. */
        if($config->URAndSR)
        {
            if($key != 'noRequirement')
            {
                if(common::hasPriv($requirement->type, 'view'))
                {
                    $title = btn
                    (
                        set::url(createLink('story', 'view', "storyID=$requirement->id")),
                        $requirement->title
                    );
                }
                else
                {
                    $title = $requirement->title;
                }
            }

            $cellList[] = $fnGenerateTD
            (
                $rowspan != 0 ? set::rowspan($rowspan) : null,
                set::title($key != 'noRequirement' ? $requirement->title : $lang->story->noRequirement),
                ($key != 'noRequirement') ? span(zget($lang->story->statusList, $requirement->status)) : null,
                $title
            );
        }

        /* If current row without track data, then skip current row. */
        if(count($track) == 0)
        {
            $rowList[] = h::tr($cellList);
            continue;
        }

        /* Attach fields to current row. */
        $textClass = 'ghost px-2';
        $i         = 0;
        foreach($track as $storyID => $story)
        {
            $tpCellList = array();

            /* Story title. */
            $tpCellList[] = $fnGenerateTD
            (
                (isset($story->parent) && $story->parent > 0) ? span
                (
                    set::title($self->lang->story->children),
                    $self->lang->story->childrenAB
                ) : null,
                btn
                (
                    setClass($textClass),
                    set::url(createLink($module, 'view', "storyID=$storyID")),
                    set::title($story->title),
                    set('data-app', $tab),
                    $story->title
                )
            );
            /* Task list. */
            $taskCount = count($story->tasks);
            $tpCellList[] = $fnGenerateTD
            (
                array_map(function($taskID, $task) use($textClass, $taskCount)
                {
                    $wg = btn
                    (
                        setClass($textClass),
                        set::url(createLink('task', 'view', "taskID=$taskID")),
                        set::title($task->name),
                        $task->name
                    );

                    if($taskCount > 1) return array($wg, br());

                    return $wg;
                }, array_keys($story->tasks), array_values($story->tasks))
            );
            /* Design field for Max edition. */
            $tpCellList[] = ($config->edition == 'max') ? $fnGenerateTD(array_map
            (
                function($designID, $design) use($textClass)
                {
                    return btn
                    (
                        setClass($textClass),
                        set::url(createLink('design', 'view', "designID=$designID")),
                        set::title($design->name),
                        $design->name
                    );
                },
                array_keys($story->designs),
                array_values($story->designs)
            )) : null;
            /* Case list. */
            $tpCellList[] = $fnGenerateTD
            (
                array_map(function($caseID, $case) use($textClass)
                {
                    return btn
                    (
                        setClass($textClass),
                        set::url(createLink('testcase', 'view', "caseID=$caseID")),
                        set::title($case->title),
                        $case->title
                    );
                }, array_keys($story->cases), array_values($story->cases))
            );
            /* Revision field for Max edition. */
            if($config->edition == 'max' and helper::hasFeature('devops'))
            {
                $tpCellList[] = $fnGenerateTD
                (
                    array_map(function($repoID, $repoComment) use($textClass)
                    {
                        return btn
                        (
                            setClass($textClass),
                            set::url(createLink('design', 'revision', "repoID=$repoID")),
                            set('data-app', 'devops'),
                            "#$repoID-$repoComment"
                        );
                    }, array_keys($story->revisions), array_values($story->revisions))
                );
            }
            /* Bug list. */
            $tpCellList[] = $fnGenerateTD
            (
                array_map(function($bugID, $bug) use($textClass)
                {
                    return btn
                    (
                        setClass($textClass),
                        set::url(createLink('bug', 'view', "bugID=$bugID")),
                        set::title($bug->title),
                        $bug->title
                    );
                }, array_keys($story->bugs), array_values($story->bugs))
            );

            if($i > 0)
            {
                /* Wrap multiple rows. */
                $cellList[] = h::tr($tpCellList);
            }
            else
            {
                $cellList[] = $tpCellList;
            }

            $i++;
        }

        $rowList[] = h::tr($cellList);
    }

    return $rowList;
}

$tab          = $this->app->rawModule == 'projectstory' ? 'project' : 'product';
$module       = $this->app->rawModule == 'projectstory' ? 'projectstory' : 'story';
$linkTemplate = createLink('product', 'track', array('productID' => $productID, 'branch' => $branch, 'projectID' => $projectID, 'recTotal' => $pager->recTotal, 'recPerPage' => '{recPerPage}', 'page' => '{page}'));

div
(
    setClass('shadow canvas'),

    h::table
    (
        setClass('w-full text-left border-collapse border border-slate-400'),
        h::thead
        (
            h::tr(generateHeaderList($this, $config, $lang, $projectProducts, $productID))
        ),
        h::tbody(
            generateRowList($this, $lang, $config, $tracks, $module, $tab)
        )
    ),

    div
    (
        setClass('flex justify-end py-1.5 px-4'),
        pager
        (
            set::page($pager->pageID),
            set::recTotal($pager->recTotal),
            set::recPerPage($pager->recPerPage),
            set::linkCreator($linkTemplate),
            set::items(array
            (
                array('type' => 'info',      'text' => $lang->pager->totalCountAB),
                array('type' => 'size-menu', 'text' => $lang->pager->pageSizeAB),
                array('type' => 'link',      'hint' => $lang->pager->firstPage,    'page' => 'first', 'icon' => 'icon-first-page'),
                array('type' => 'link',      'hint' => $lang->pager->previousPage, 'page' => 'prev',  'icon' => 'icon-angle-left'),
                array('type' => 'info',      'text' => '{page}/{pageTotal}'),
                array('type' => 'link',      'hint' => $lang->pager->nextPage,     'page' => 'next',  'icon' => 'icon-angle-right'),
                array('type' => 'link',      'hint' => $lang->pager->lastPage,     'page' => 'last',  'icon' => 'icon-last-page'),
            ))
        )
    )
);

render();
