<?php
declare(strict_types=1);
/**
 * The view view file of design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     design
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('type', strtolower($design->type));

/* 版本列表。Version list. */
$versions = array();
for($i = $design->version; $i >= 1; $i--)
{
    $versionItem = setting()
        ->text("#{$i}")
        ->url(inlink('view', "designID={$design->id}&version=$i"));

    $versionItem->selected($version == $i);
    $versions[] = $versionItem;
}

$versionBtn = count($versions) > 1 ? to::title(dropdown
(
    btn(set::type('ghost'), setClass('text-link font-normal text-base'), "#{$version}"),
    set::items($versions)
)) : null;

detailHeader
(
    set::backUrl(createLink('design', 'browse', "projectID={$design->project}")),
    to::title(entityLabel(set(array('entityID' => $design->id, 'level' => 1, 'text' => $design->name))), $design->deleted ? span(setClass('label danger'), $lang->deleted) : null),
    $versionBtn
);

$canModify = common::canModify('project', $project);

/* Construct suitable actions for the current task. */
$operateMenus = array();
foreach($config->design->view->operateList['main'] as $operate)
{
    if(!$canModify || !common::hasPriv('design', $operate) || $design->deleted || !$this->design->isClickable($design, $operate)) continue;

    if(empty($repos) && $operate == 'linkCommit')
    {
        $config->design->actionList[$operate]['url']      = createLink('repo', 'create', "projectID={$design->project}");
        $config->design->actionList[$operate]['data-app'] = 'project';
        unset($config->design->actionList[$operate]['data-toggle']);
    }
    $operateMenus[] = $config->design->actionList[$operate];
}

/* Construct common actions for task. */
$commonActions = array();
foreach($config->design->view->operateList['common'] as $operate)
{
    if(!$canModify || !common::hasPriv('design', $operate) || $design->deleted || !empty($design->frozen)) continue;
    if($operate == 'delete') $config->design->actionList['delete']['class'] = 'ajax-submit';
    $commonActions[] = $config->design->actionList[$operate];
}

$moduleName = empty($project->hasProduct) ? 'projectstory' : (isset($design->storyInfo) ? $design->storyInfo->type : 'story');
$storyName  = zget($stories, $design->story, '');
$storyItem  = array();
if($common::hasPriv($moduleName, 'view'))
{
    $storyItem[] = a(
        set::href(helper::createLink($moduleName, 'view', "id={$design->story}")),
        $storyName
    );
}
else
{
    $storyItem[] = $storyName;
}
if($this->design->isClickable($design, 'confirmStoryChange'))
{
    $storyItem[] = span
    (
        '(',
        $lang->story->changed,
        common::hasPriv('design', 'confirmStoryChange') ? a
        (
            setClass('mx-1 px-1 primary-pale'),
            set::href(helper::createLink('design', 'confirmStoryChange', "id={$design->id}")),
            $lang->design->confirmStoryChange
        ) : '',
        ')'
    );
}

detailBody
(
    $versionBtn,
    sectionList
    (
        section
        (
            setID('desc'),
            set::title($lang->design->desc),
            set::content(empty($design->desc) ? $lang->noDesc : $design->desc),
            set::useHtml(true)
        )
    ),
    $design->files ? fileList
    (
        set::files($design->files)
    ) : null,
    history(),
    floatToolbar
    (
        set::prefix
        (
            array(array('icon' => 'back', 'text' => $lang->goback, 'data-back' => 'design-browse,projectstory-track,my-effort,my-index', 'data-url' => createLink('design', 'browse', "projectID={$design->project}"), 'className' => 'open-url'))
        ),
        set::main($operateMenus),
        set::suffix($commonActions),
        set::object($design)
    ),
    detailSide
    (
        tabs
        (
            set::id('detailTabs'),
            tabPane
            (
                set::title($lang->design->basicInfo),
                tableData
                (
                    item
                    (
                        set::name($lang->design->type),
                        zget($lang->design->typeList, $design->type)
                    ),
                    item
                    (
                        set::name($lang->design->product),
                        $design->productName
                    ),
                    item
                    (
                        set::name($lang->design->story),
                        $storyItem
                    ),
                    item
                    (
                        set::name($lang->design->submission),
                        html($design->commit)
                    ),
                    item
                    (
                        set::name($lang->design->assignedTo),
                        zget($users, $design->assignedTo)
                    ),
                    item
                    (
                        set::name($lang->design->createdBy),
                        zget($users, $design->createdBy)
                    ),
                    item
                    (
                        set::name($lang->design->createdDate),
                        substr($design->createdDate, 0, 11)
                    )
                )
            )
        )
    )
);

/* ====== Render page ====== */
render();
