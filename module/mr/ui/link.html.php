<?php
declare(strict_types=1);
/**
 * The link view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('type', $type);

$actionMenu = array();
$actionMenu['title'] = $lang->actions;
$actionMenu['type']  = 'actions';
$actionMenu['menu']  = array('unlink');
$actionMenu['list']['unlink'] = array('icon' => 'unlink', 'hint' => $lang->productplan->unlinkStory);

$storyCols = array();
$storyCols['id']         = $config->story->dtable->fieldList['id'];
$storyCols['pri']        = $config->story->dtable->fieldList['pri'];
$storyCols['title']      = $config->story->dtable->fieldList['title'];
$storyCols['module']     = $config->story->dtable->fieldList['module'];
$storyCols['openedBy']   = $config->story->dtable->fieldList['openedBy'];
$storyCols['assignedTo'] = $config->story->dtable->fieldList['assignedTo'];
$storyCols['estimate']   = $config->story->dtable->fieldList['estimate'];
$storyCols['status']     = $config->story->dtable->fieldList['status'];
$storyCols['stage']      = $config->story->dtable->fieldList['stage'];
$storyCols['actions']    = $actionMenu;
$storyCols['id']['checkbox'] = false;
$storyCols['module']['map']  = $modulePairs;
$stories = initTableData($stories, $storyCols);

$bugCols = array();
$bugCols['id']         = $config->bug->dtable->fieldList['id'];
$bugCols['pri']        = $config->bug->dtable->fieldList['pri'];
$bugCols['title']      = $config->bug->dtable->fieldList['title'];
$bugCols['openedBy']   = $config->bug->dtable->fieldList['openedBy'];
$bugCols['assignedTo'] = $config->bug->dtable->fieldList['assignedTo'];
$bugCols['status']     = $config->bug->dtable->fieldList['status'];
$bugCols['actions']    = $actionMenu;
$bugCols['id']['checkbox'] = false;
$bugs = initTableData($bugs, $bugCols);

$taskCols = array();
$taskCols['id'] = $config->task->dtable->fieldList['id'];
$taskCols['pri']        = $config->task->dtable->fieldList['pri'];
$taskCols['name']       = $config->task->dtable->fieldList['name'];
$taskCols['finishedBy'] = $config->task->dtable->fieldList['finishedBy'];
$taskCols['assignedTo'] = $config->task->dtable->fieldList['assignedTo'];
$taskCols['status']     = $config->task->dtable->fieldList['status'];
$taskCols['actions']    = $actionMenu;
$taskCols['id']['checkbox'] = false;
$tasks = initTableData($tasks, $taskCols);



detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($MR->id),
            set::level(1),
            set::text($MR->title)
        ),
        $MR->deleted ? h::span
        (
            setClass('label danger'),
            $lang->product->deleted,
        ) : null,
    )
);

panel
(
    div
    (
        set::id('mrMenu'),
        nav
        (
            li
            (
                setClass('nav-item'),
                a($lang->mr->view)
            ),
            li
            (
                setClass('nav-item'),
                a($lang->mr->viewDiff, set::href(inlink('diff', "MRID={$MR->id}")))
            ),
            li
            (
                setClass('nav-item story'),
                a(icon($lang->icons['story']), $lang->productplan->linkedStories, set::href('#mr-story'), set('data-toggle', 'tab'))
            ),
            li
            (
                setClass('nav-item bug'),
                a(icon($lang->icons['bug']), $lang->productplan->linkedBugs, set::href('#mr-bug'), set('data-toggle', 'tab'))
            ),
            li
            (
                setClass('nav-item task'),
                a(icon('todo'), $lang->mr->linkedTasks, set::href('#mr-task'), set('data-toggle', 'tab'))
            ),
        )
    ),
    tabs
    (
        tabPane
        (
            set::key('mr-story'),
            dtable
            (
                set::cols($storyCols),
                set::data($stories),
            ),
        ),
        tabPane
        (
            set::key('mr-bug'),
            dtable
            (
                set::cols($bugCols),
                set::data($bugs),
            ),
        ),
        tabPane
        (
            set::key('mr-task'),
            dtable
            (
                set::cols($taskCols),
                set::data($tasks),
            ),
        ),
    ),
);

render();
