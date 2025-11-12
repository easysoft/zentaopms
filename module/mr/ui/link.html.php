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

$this->loadModel('task');
$this->loadModel('release');
$app->loadLang('productplan');
$app->loadLang('bug');

$module     = $app->tab == 'devops' ? 'repo' : $app->tab;
$moduleName = $app->rawModule;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

jsVar('type', $type);
jsVar('orderBy', $orderBy);
jsVar('sortLink', createLink($moduleName, 'link', "MRID={$MR->id}&type={type}&orderBy={orderBy}"));

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
$storyCols['title']['data-toggle']  = 'modal';
$storyCols['title']['data-size']    = 'lg';
$storyCols['title']['nestedToggle'] = false;
$storyCols['title']['link']         = array('module' => 'story', 'method' => 'view', 'params' => 'storyID={id}');

$storyCols['actions']['list']['unlink']['data-confirm'] = $lang->productplan->confirmUnlinkStory;
$storyCols['actions']['list']['unlink']['url']          = $this->createLink($moduleName, 'unlink', "MRID=$MR->id&type=story&linkID={id}&confirm=yes");

$stories = initTableData($stories, $storyCols);

$bugCols = $config->release->dtable->bug->fieldList;
$bugCols['resolvedBuild']['map'] = $builds;
$bugCols['actions'] = $actionMenu;
$bugCols['actions']['list']['unlink']['data-confirm'] = $lang->productplan->confirmUnlinkBug;
$bugCols['actions']['list']['unlink']['url']          = $this->createLink($moduleName, 'unlink', "MRID=$MR->id&type=bug&linkID={id}&confirm=yes");
$bugCols['actions']['list']['unlink']['hint']         = $lang->productplan->unlinkBug;

$bugCols['id']['checkbox']       = false;
$bugCols['title']['data-toggle'] = 'modal';
$bugCols['title']['data-size']   = 'lg';
$bugCols['title']['link']        = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}');
$bugs = initTableData($bugs, $bugCols);

$taskCols = $config->mr->taskDtable->fieldList;
$taskCols['assignedTo']['currentUser'] = $app->user->account;

$taskCols['actions'] = $actionMenu;
$taskCols['actions']['list']['unlink']['data-confirm'] = $lang->mr->confirmUnlinkTask;
$taskCols['actions']['list']['unlink']['url']          = $this->createLink($moduleName, 'unlink', "MRID=$MR->id&type=task&linkID={id}&confirm=yes");
$taskCols['actions']['list']['unlink']['hint']         = $lang->mr->unlinkTask;

$tasks = initTableData($tasks, $taskCols);

$linkStoryBtn = $linkBugBtn = $linkTaskBtn = null;
if(common::hasPriv($moduleName, 'linkStory'))
{
    $linkStoryBtn = btn(set(array(
        'text'     => $lang->productplan->linkStory,
        'icon'     => 'link',
        'data-url' => createLink($moduleName, 'linkStory', "MRID={$MR->id}&repoID={$MR->repoID}&browseType=&param={$param}&orderBy={$orderBy}"),
        'class'    => 'link mr-actions',
        'type'     => 'primary',
        'onclick'  => 'showLink(this)'
    )));
}

if(common::hasPriv($moduleName, 'linkBug'))
{
    $linkBugBtn = btn(set(array(
        'text'     => $lang->productplan->linkBug,
        'icon'     => 'link',
        'data-url' => createLink($moduleName, 'linkBug', "MRID={$MR->id}&repoID={$MR->repoID}&browseType=&param={$param}&orderBy={$orderBy}"),
        'class'    => 'link mr-actions',
        'type'     => 'primary',
        'onclick'  => 'showLink(this)'
    )));
}

if(common::hasPriv($moduleName, 'linkTask'))
{
    $linkTaskBtn = btn(set(array(
        'text'     => $lang->mr->linkTask,
        'icon'     => 'link',
        'data-url' => createLink($moduleName, 'linkTask', "MRID={$MR->id}&repoID={$MR->repoID}&browseType=&param={$param}&browseType=unclosed&orderBy={$orderBy}"),
        'class'    => 'link mr-actions',
        'type'     => 'primary',
        'onclick'  => 'showLink(this)'
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
            $lang->product->deleted
        ) : null
    )
);

include 'header.html.php';
panel
(
    setClass('relative'),
    div
    (
        set::id('mrMenu'),
        $headers
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
                set::loadPartial(true),
                set::sortLink(jsRaw('createStorySortLink')),
                set::footPager(usePager('storyPager'))
            )
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
                set::loadPartial(true),
                set::sortLink(jsRaw('createBugSortLink')),
                set::footPager(usePager('bugPager'))
            )
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
                set::loadPartial(true),
                set::sortLink(jsRaw('createTaskSortLink')),
                set::footPager(usePager('taskPager'))
            )
        )
    )
);

render();
