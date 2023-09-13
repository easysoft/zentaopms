<?php
declare(strict_types=1);
/**
 * The link view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

$repoName = $this->dao->select('name')->from(TABLE_REPO)->where('id')->eq($MR->repoID)->fetch('name');
dropmenu(set::objectID($MR->repoID), set::text($repoName), set::tab('repo'));

jsVar('type', $type);
jsVar('orderBy', $orderBy);
jsVar('sortLink', createLink('mr', 'link', "MRID={$MR->id}&type={type}&orderBy={orderBy}&recTotal=&recPerPage=&pageID="));

dropmenu(set::objectID($MR->repoID), set::tab('repo'));

$actionMenu = array();
$actionMenu['title'] = $lang->actions;
$actionMenu['type']  = 'actions';
$actionMenu['menu']  = array('unlink');
$actionMenu['list']['unlink']['icon']      = 'unlink';
$actionMenu['list']['unlink']['hint']      = $lang->productplan->unlinkStory;
$actionMenu['list']['unlink']['className'] = 'ajax-submit';

$storyCols = $config->release->dtable->story->fieldList;
$storyCols['actions']               = $actionMenu;
$storyCols['id']['checkbox']        = false;
$storyCols['title']['data-toggle']  = '';
$storyCols['title']['nestedToggle'] = false;
$storyCols['title']['link']         = array('module' => 'story', 'method' => 'view', 'params' => 'storyID={id}', 'target' => '_blank');

$storyCols['actions']['list']['unlink']['data-confirm'] = $lang->productplan->confirmUnlinkStory;
$storyCols['actions']['list']['unlink']['url']          = $this->createLink('mr', 'unlink', "MRID=$MR->id&productID=$product->id&type=story&linkID={id}&confirm=yes");

$stories = initTableData($stories, $storyCols);

$bugCols = $config->release->dtable->bug->fieldList;
$bugCols['actions'] = $actionMenu;
$bugCols['actions']['list']['unlink']['data-confirm'] = $lang->productplan->confirmUnlinkBug;
$bugCols['actions']['list']['unlink']['url']          = $this->createLink('mr', 'unlink', "MRID=$MR->id&productID=$product->id&type=bug&linkID={id}&confirm=yes");

$bugCols['id']['checkbox']       = false;
$bugCols['title']['data-toggle'] = '';
$bugCols['title']['link']        = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}', 'target' => '_blank');
$bugs = initTableData($bugs, $bugCols);

$taskCols = $config->mr->taskDtable->fieldList;
$taskCols['actions']    = $actionMenu;
$taskCols['actions']['list']['unlink']['data-confirm'] = $lang->mr->confirmUnlinkTask;
$taskCols['actions']['list']['unlink']['url']          = $this->createLink('mr', 'unlink', "MRID=$MR->id&productID=$product->id&type=task&linkID={id}&confirm=yes");

$tasks = initTableData($tasks, $taskCols);

if(common::hasPriv('mr', 'linkStory'))
{
    $linkStoryBtn = btn(set(array(
        'text'     => $lang->productplan->linkStory,
        'icon'     => 'link',
        'data-url' => inlink('linkStory', "MRID={$MR->id}&productID={$product->id}{$param}&orderBy={$orderBy}"),
        'class'    => 'link mr-actions',
        'type'     => 'primary',
        'onclick'  => 'showLink(this)',
    )));
}

if(common::hasPriv('mr', 'linkBug'))
{
    $linkBugBtn = btn(set(array(
        'text'     => $lang->productplan->linkBug,
        'icon'     => 'link',
        'data-url' => inlink('linkBug', "MRID={$MR->id}&productID={$product->id}{$param}&orderBy={$orderBy}"),
        'class'    => 'link mr-actions',
        'type'     => 'primary',
        'onclick'  => 'showLink(this)',
    )));
}

if(common::hasPriv('mr', 'linkTask'))
{
    $linkTaskBtn = btn(set(array(
        'text'     => $lang->mr->linkTask,
        'icon'     => 'link',
        'data-url' => inlink('linkTask', "MRID={$MR->id}&productID={$product->id}{$param}&browseType=unclosed&orderBy={$orderBy}"),
        'class'    => 'link mr-actions',
        'type'     => 'primary',
        'onclick'  => 'showLink(this)',
    )));
}

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
            $linkStoryBtn,
            dtable
            (
                set::id('storyTable'),
                set::userMap($users),
                set::cols($storyCols),
                set::data($stories),
                set::sortLink(jsRaw('createStorySortLink')),
                set::footPager(usePager('storyPager')),
            ),
        ),
        tabPane
        (
            set::key('mr-bug'),
            $linkBugBtn,
            dtable
            (
                set::id('bugTable'),
                set::userMap($users),
                set::cols($bugCols),
                set::data($bugs),
                set::sortLink(jsRaw('createBugSortLink')),
                set::footPager(usePager('bugPager')),
            ),
        ),
        tabPane
        (
            set::key('mr-task'),
            $linkTaskBtn,
            dtable
            (
                set::id('taskTable'),
                set::userMap($users),
                set::cols($taskCols),
                set::data($tasks),
                set::sortLink(jsRaw('createTaskSortLink')),
                set::footPager(usePager('taskPager')),
            ),
        ),
    ),
);

render();
