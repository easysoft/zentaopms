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
jsVar('orderBy', $orderBy);
jsVar('sortLink', createLink('mr', 'link', "MRID={$MR->id}&type={type}&orderBy={orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"));

$actionMenu = array();
$actionMenu['title'] = $lang->actions;
$actionMenu['type']  = 'actions';
$actionMenu['menu']  = array('unlink');
$actionMenu['list']['unlink']['icon']      = 'unlink';
$actionMenu['list']['unlink']['hint']      = $lang->productplan->unlinkStory;
$actionMenu['list']['unlink']['className'] = 'ajax-submit';

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
$storyCols['actions']['list']['unlink']['data-confirm'] = $lang->productplan->confirmUnlinkStory;
$storyCols['actions']['list']['unlink']['url']          = $this->createLink('mr', 'unlink', "MRID=$MR->id&productID=$product->id&type=story&linkID={id}&confirm=yes");
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
$bugCols['actions']['list']['unlink']['data-confirm'] = $lang->productplan->confirmUnlinkBug;
$bugCols['actions']['list']['unlink']['url']          = $this->createLink('mr', 'unlink', "MRID=$MR->id&productID=$product->id&type=bug&linkID={id}&confirm=yes");
$bugCols['id']['checkbox'] = false;
$bugs = initTableData($bugs, $bugCols);

$taskCols = array();
$taskCols['id']         = $config->task->dtable->fieldList['id'];
$taskCols['pri']        = $config->task->dtable->fieldList['pri'];
$taskCols['name']       = $config->task->dtable->fieldList['name'];
$taskCols['finishedBy'] = $config->task->dtable->fieldList['finishedBy'];
$taskCols['assignedTo'] = $config->task->dtable->fieldList['assignedTo'];
$taskCols['status']     = $config->task->dtable->fieldList['status'];
$taskCols['actions']    = $actionMenu;
$taskCols['actions']['list']['unlink']['data-confirm'] = $lang->mr->confirmUnlinkTask;
$taskCols['actions']['list']['unlink']['url']          = $this->createLink('mr', 'unlink', "MRID=$MR->id&productID=$product->id&type=task&linkID={id}&confirm=yes");
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
    setClass('relative'),
    div
    (
        set::id('mrMenu'),
        nav
        (
            li
            (
                setClass('nav-item' . ($type == 'view' ? ' active' : '')),
                a($lang->mr->view, set::href(inlink('view', "MRID={$MR->id}")))
            ),
            li
            (
                setClass('nav-item' . ($type == 'diff' ? ' active' : '')),
                a($lang->mr->viewDiff, set::href(inlink('diff', "MRID={$MR->id}")))
            ),
            li
            (
                setClass('nav-item story' . ($type == 'story' ? ' active' : '')),
                a(icon($lang->icons['story']), $lang->productplan->linkedStories, set::href('#mr-story'), set('data-toggle', 'tab'))
            ),
            li
            (
                setClass('nav-item bug' . ($type == 'bug' ? ' active' : '')),
                a(icon($lang->icons['bug']), $lang->productplan->linkedBugs, set::href('#mr-bug'), set('data-toggle', 'tab'))
            ),
            li
            (
                setClass('nav-item task' . ($type == 'task' ? ' active' : '')),
                a(icon('todo'), $lang->mr->linkedTasks, set::href('#mr-task'), set('data-toggle', 'tab'))
            ),
        )
    ),
    tabs
    (
        tabPane
        (
            set::key('mr-story'),
            btn(setClass('mr-actions primary'), setData('type', 'story'), set::icon('link'), $this->lang->productplan->linkStory),
            btn
            (
                setClass('mr-actions primary'),
                setData('linkType', 'bug'),
                setData('size', '1000px'),
                setData('toggle', 'modal'),
                set::url($this->createLink('mr', 'linkStory', "MRID={$MR->id}&productID={$product->id}{$param}&orderBy={$orderBy}")),
                set::icon('link'),
                $this->lang->productplan->linkStory
            ),
            dtable
            (
                set::id('storyTable'),
                set::userMap($users),
                set::cols($storyCols),
                set::data($stories),
                set::sortLink(jsRaw('createStorySortLink')),
                set::footPager(usePager(null, 'storyPager')),
            ),
        ),
        tabPane
        (
            set::key('mr-bug'),
            btn
            (
                setClass('mr-actions primary'),
                setData('linkType', 'bug'),
                setData('size', '900px'),
                setData('toggle', 'modal'),
                set::url($this->createLink('mr', 'linkBug', "MRID={$MR->id}&productID={$product->id}{$param}&orderBy={$orderBy}")),
                set::icon('bug'),
                $this->lang->productplan->linkBug
            ),
            dtable
            (
                set::id('bugTable'),
                set::userMap($users),
                set::cols($bugCols),
                set::data($bugs),
                set::sortLink(jsRaw('createBugSortLink')),
                set::footPager(usePager(null, 'bugPager')),
            ),
        ),
        tabPane
        (
            set::key('mr-task'),
            btn
            (
                setClass('mr-actions primary'),
                setData('linkType', 'task'),
                setData('size', '900px'),
                setData('toggle', 'modal'),
                set::url($this->createLink('mr', 'linkTask', "MRID={$MR->id}&productID={$product->id}{$param}&browseType=unclosed&orderBy={$orderBy}")),
                set::icon('todo'),
                $this->lang->mr->linkTask
            ),
            dtable
            (
                set::id('taskTable'),
                set::userMap($users),
                set::cols($taskCols),
                set::data($tasks),
                set::sortLink(jsRaw('createTaskSortLink')),
                set::footPager(usePager(null, 'taskPager')),
            ),
        ),
    ),
);

render();
