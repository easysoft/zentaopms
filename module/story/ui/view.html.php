<?php
declare(strict_types=1);
/**
 * The view view file of story module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */
namespace zin;

$confirmDelete = $this->lang->story->confirmDelete;
if($story->type == 'requirement') $confirmDelete = str_replace($lang->SRCommon, $lang->URCommon, $confirmDelete);

jsVar('relievedTip', $lang->story->relievedTip);
jsVar('unlinkStoryTip', $lang->story->unlinkStory);
jsVar('confirmDeleteTip', $confirmDelete);
jsVar('storyType', $story->type);

$isInModal  = isInModal();
$otherParam = 'storyID=&projectID=';
$tab        = 'product';
if($this->app->rawModule == 'projectstory' or $this->app->tab == 'project')
{
    $otherParam = "storyID=&projectID={$this->session->project}";
    $tab        = 'project';
}
if($this->app->rawModule == 'execution') $tab = 'execution';
$createStoryLink = $this->createLink('story', 'create', "productID={$story->product}&branch={$story->branch}&moduleID={$story->module}&$otherParam&bugID=0&planID=0&todoID=0&extra=&storyType=$story->type");

$versions = array();
for($i = $story->version; $i >= 1; $i --)
{
    $versionItem = array('text' => "#{$i}", 'url' => inlink('view', "storyID={$story->id}&version=$i&param=0&storyType={$story->type}"));
    if(isInModal())
    {
        $versionItem['data-load'] = 'modal';
        $versionItem['data-target'] = '.modal-content';
    }
    $versions[] = $versionItem;
}

$menus = $this->story->buildOperateMenu($story, 'view', $project ? $project : null);
foreach($menus['dropMenus'] as $dropMenuKey => $dropItems) menu(setID($dropMenuKey), setClass('menu dropdown-menu'), set::items($dropItems));

/* Get module items. */
$moduleTitle = '';
$moduleItems = array();
if(empty($modulePath))
{
    $moduleTitle  .= '/';
    $moduleItems[] = span('/');
}
else
{
    if($storyModule->branch and isset($branches[$storyModule->branch]))
    {
        $moduleTitle  .= $branches[$storyModule->branch] . '/';
        $moduleItems[] = span($branches[$storyModule->branch], icon('angle-right'));
    }

    foreach($modulePath as $key => $module)
    {
        $moduleTitle  .= $module->name;
        $moduleItems[] = $product->shadow ? span($module->name) : a(set::href(helper::createLink('product', 'browse', "productID=$story->product&branch=$story->branch&browseType=byModule&param=$module->id")), $module->name);
        if(isset($modulePath[$key + 1]))
        {
            $moduleTitle  .= '/';
            $moduleItems[] = icon('angle-right');
        }
    }
}

/* Get min stage. */
$minStage    = $story->stage;
$stageList   = implode(',', array_keys($this->lang->story->stageList));
$minStagePos = strpos($stageList, $minStage);
if($story->stages and $branches)
{
    foreach($story->stages as $branch => $stage)
    {
        if(strpos($stageList, $stage) !== false and strpos($stageList, $stage) > $minStagePos)
        {
            $minStage    = $stage;
            $minStagePos = strpos($stageList, $stage);
        }
    }
}

/* Join mailto. */
$mailtoList = array();
if(!empty($story->mailto))
{
    foreach(explode(',', $story->mailto) as $account)
    {
        if(empty($account)) continue;
        $mailtoList[] = zget($users, trim($account));
    }
}
$mailtoList = implode($lang->comma, $mailtoList);

$taskItems = array();
if($story->type == 'story')
{
    foreach($story->tasks as $executionTasks)
    {
        foreach($executionTasks as $task)
        {
            if(!isset($executions[$task->execution])) continue;
            $execution     = isset($story->executions[$task->execution]) ? $story->executions[$task->execution] : '';
            $executionLink = !empty($execution->multiple) ? $this->createLink('execution', 'view', "executionID=$task->execution") : $this->createLink('project', 'view', "projectID=$task->project");
            $executionName = $executions[$task->execution];
            $taskItems[] = h::li
            (
                set::title($task->name),
                (isset($execution->type) && $execution->type == 'kanban' && $isInModal) ? span(setClass('muted title'), $executionName) : a(set::href($executionLink), setClass('muted title'), $executionName),
                label(setClass('circle size-sm'), $task->id),
                common::hasPriv('task', 'view') ? a(set::href($this->createLink('task', 'view', "taskID=$task->id")), setClass('title'), setData(array('toggle' => 'modal')), $task->name) : span(setClass('title'), $task->name),
                label(setClass("status-{$task->status} size-sm"), $this->lang->task->statusList[$task->status])
            );
        }
    }

    if(empty($story->tasks))
    {
        foreach($story->executions as $executionID => $execution)
        {
            if(!$execution->multiple) continue;
            if(!isset($executions[$executionID])) continue;
            if(isset($story->tasks[$executionID])) continue;

            $taskItems[] = h::li
            (
                set::title($execution->name),
                ($execution->type == 'kanban' && $isInModal) ? span(setClass('muted title'), $executions[$executionID]) : a(set::href($this->createLink('execution', 'view', "executionID=$executionID")), setClass('muted title'), $executions[$executionID])
            );
        }
    }
}

if(!empty($story->children))
{
    $cols['id']         = $config->story->dtable->fieldList['id'];
    $cols['title']      = $config->story->dtable->fieldList['title'];
    $cols['pri']        = $config->story->dtable->fieldList['pri'];
    $cols['assignedTo'] = $config->story->dtable->fieldList['assignedTo'];
    $cols['estimate']   = $config->story->dtable->fieldList['estimate'];
    $cols['status']     = $config->story->dtable->fieldList['status'];
    $cols['actions']    = $config->story->dtable->fieldList['actions'];
    $cols['id']['checkbox']        = false;
    $cols['title']['nestedToggle'] = false;
    $cols['assignedTo']['type']    = 'users';

    $options = array('users' => $users);
    foreach($story->children as $child) $child = $this->story->formatStoryForList($child, $options);
}

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($story->id),
            set::level(1),
            set::text(''),
            $story->parent > 0 ? label(setClass('circle child'), $lang->story->childrenAB) : null,
            $story->parent > 0 && isset($story->parentName) ? span(a(set::href(inlink('view', "storyID={$story->parent}&version=0&param=0&storyType=$story->type")), $story->parentName), ' / ') : null,
            span(setStyle(array('color' => $story->color)), $story->title)
        ),
        count($versions) > 1 ? dropdown
        (
            btn(setClass('btn-link'), "#{$version}"),
            set::items($versions)
        ) : null,
        $story->deleted ? span(setClass('label danger'), $lang->story->deleted) : null
    ),

    $isInModal ? null : to::suffix
    (
        btn
        (
            set::icon('plus'),
            set::type('primary'),
            set::text($lang->story->create),
            common::hasPriv('story', 'create') ? set::url($createStoryLink) : null
        )
    )
);

$statusClass = $story->URChanged ? 'status-changed' : "status-{$story->status}";
detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->story->legendSpec),
            set::content(empty($story->spec) ? $lang->noDesc : $story->spec),
            set::useHtml(true)
        ),
        section
        (
            set::title($lang->story->legendVerify),
            set::content(empty($story->verify) ? $lang->noDesc : $story->verify),
            set::useHtml(true)
        ),
        $story->files ? fileList
        (
            set::files($story->files),
            set::showDelete(false),
            set::object($story)
        ) : null,
        empty($story->children) ? null : section
        (
            set::title($story->type == 'requirement' ? $lang->story->story : $lang->story->children),
            dtable
            (
                set::cols($cols),
                set::data(array_values($story->children))
            )
        )
    ),
    history(set::objectID($story->id)),
    floatToolbar
    (
        set::object($story),
        $isInModal ? null : to::prefix(backBtn(setClass('btn-default ghost text-white'), set::icon('back'), $lang->goback)),
        $story->deleted ? null : set::main($menus['mainMenu']),
        $story->deleted ? null : set::suffix($menus['suffixMenu'])
    ),
    detailSide
    (
        tabs
        (
            set::collapse(true),
            tabPane
            (
                set::key('legendBasicInfo'),
                set::title($lang->story->legendBasicInfo),
                set::active(true),
                tableData
                (
                    $product->shadow ? null : item
                    (
                        set::name($lang->story->product),
                        common::hasPriv('product', 'view') ? a(set::href($this->createLink('product', 'view', "productID=$story->product")), $product->name) : $product->name
                    ),
                    $product->type == 'normal' ? null : item
                    (
                        set::name($lang->story->branch),
                        common::hasPriv('product', 'browse') ? a(set::href($this->createLink('product', 'browse', "productID=$story->product&branch=$story->branch")), $branches[$story->branch]) : $branches[$story->branch]
                    ),
                    item
                    (
                        set::name($lang->story->module),
                        set::title($moduleTitle),
                        $moduleItems
                    ),
                    ($story->type != 'requirement' and $story->parent != -1 and !$hiddenPlan) ? item
                    (
                        set::trClass('plan-line'),
                        set::name($lang->story->plan),
                        empty($story->planTitle) ? null : array_values(array_map(function($planID, $planTitle)
                        {
                            $items   = array();
                            $items[] = common::hasPriv('productplan', 'view') ? a(set::href(helper::createLink('productplan', 'view', "planID={$planID}")), $planTitle) : $planTitle;
                            $items[] = h::br();
                            return $items;
                        }, array_keys($story->planTitle), array_values($story->planTitle)))
                    ) : null,
                    item
                    (
                        setID('source'),
                        set::name($lang->story->source),
                        zget($lang->story->sourceList, $story->source, '')
                    ),
                    item
                    (
                        setID('sourceNoteBox'),
                        set::name($lang->story->sourceNote),
                        $story->sourceNote
                    ),
                    item
                    (
                        set::name($lang->story->status),
                        span
                        (
                            setClass("status-story $statusClass"),
                            $story->URChanged ? $lang->story->URChanged : $this->processStatus('story', $story)
                        )
                    ),
                    $story->type == 'requirement' ? null : item
                    (
                        set::trClass('stage-line'),
                        set::name($lang->story->stage),
                        zget($lang->story->stageList, $minStage, '')
                    ),
                    item
                    (
                        set::name($lang->story->category),
                        zget($lang->story->categoryList, $story->category)
                    ),
                    item
                    (
                        set::name($lang->story->pri),
                        priLabel($story->pri, set::text($lang->story->priList))
                    ),
                    item
                    (
                        set::name($lang->story->estimate),
                        $story->estimate . $config->hourUnit
                    ),
                    in_array($story->source, $config->story->feedbackSource) ? item
                    (
                        set::name($lang->story->feedbackBy),
                        $story->feedbackBy
                    ) : null,
                    in_array($story->source, $config->story->feedbackSource) ? item
                    (
                        set::name($lang->story->notifyEmail),
                        $story->notifyEmail
                    ) : null,
                    item
                    (
                        set::name($lang->story->keywords),
                        $story->keywords
                    ),
                    item
                    (
                        set::name($lang->story->legendMailto),
                        $mailtoList
                    )
                )
            ),
            tabPane
            (
                set::key('legendLifeTime'),
                set::title($lang->story->legendLifeTime),
                tableData
                (
                    item
                    (
                        set::name($lang->story->openedBy),
                        zget($users, $story->openedBy) . $lang->at . $story->openedDate
                    ),
                    item
                    (
                        set::name($lang->story->assignedTo),
                        $story->assignedTo ? zget($users, $story->assignedTo) . $lang->at . $story->assignedDate : null
                    ),
                    item
                    (
                        set::name($lang->story->reviewers),
                        array_values(array_map(function($reviewer, $result) use($users)
                        {
                            global $lang;
                            return !empty($result) ? span(setClass('mr-2'), set::title($lang->story->reviewed), set::style(array('color' => '#cbd0db')), zget($users, $reviewer)) : span(setClass('mr-2'), set::title($lang->story->toBeReviewed), zget($users, $reviewer));
                        }, array_keys($reviewers), array_values($reviewers))),
                    ),
                    item
                    (
                        set::name($lang->story->reviewedDate),
                        $story->reviewedDate
                    ),
                    item
                    (
                        set::name($lang->story->closedBy),
                        $story->closedBy ? zget($users, $story->closedBy) . $lang->at . $story->closedDate : null
                    ),
                    item
                    (
                        set::tdClass('resolution'),
                        set::name($lang->story->closedReason),
                        $story->closedReason ? zget($lang->story->reasonList, $story->closedReason) : null,
                        isset($story->extraStories[$story->duplicateStory]) ? a(set::href(inlink('view', "storyID=$story->duplicateStory")), set::title($story->extraStories[$story->duplicateStory]), "#{$story->duplicateStory} {$story->extraStories[$story->duplicateStory]}") : null
                    ),
                    item
                    (
                        set::name($lang->story->lastEditedBy),
                        $story->lastEditedBy ? zget($users, $story->lastEditedBy) . $lang->at . $story->lastEditedDate : null
                    )
                )
            )
        ),
        tabs
        (
            set::collapse(true),
            !empty($twins) ? tabPane
            (
                set::key('legendTwins'),
                set::title($lang->story->twins),
                set::active(true),
                h::ul
                (
                    array_values(array_map(function($twin) use($story, $branches)
                    {
                        global $lang;
                        $branch     = isset($branches[$twin->branch]) ? $branches[$twin->branch] : '';
                        $stage      = $lang->story->stageList[$twin->stage];
                        $labelClass = $story->branch == $twin->branch ? 'primary' : '';

                        return h::li
                        (
                            setClass('twins'),
                            $branch ? label(setClass($labelClass . ' circle branch size-sm'), set::title($branch), $branch) : null,
                            label(setClass('circle size-sm'), $twin->id),
                            common::hasPriv('story', 'view') ? a(set::href($this->createLink('story', 'view', "id={$twin->id}")), setClass('title'), set::title($twin->title), setData(array('toggle' => 'modal')), setData('size', 'lg'), $twin->title) : span(setClass('title'), $twin->title),
                            label(setClass('size-sm'), set::title($stage), $stage),
                            common::hasPriv('story', 'relieved') ? a(set::title($lang->story->relievedTwins), setClass("relievedTwins unlink hidden size-xs"), on::click('unlinkTwins'), setData(array('id' => $twin->id)), icon('unlink')) : null
                        );
                    }, $twins))
                )
            ) : null,
            ($this->config->URAndSR && !$hiddenURS && $config->vision != 'or') ? tabPane
            (
                set::key('legendStories'),
                set::title($story->type == 'story' ? $lang->story->requirement : $lang->story->story),
                set::active(empty($twins)),
                h::ul
                (
                    array_values(array_map(function($relation) use($story)
                    {
                        $relationType  = $story->type == 'story' ? 'requirement' : 'story';
                        $canViewStory  = common::hasPriv($relationType, 'view', null, "storyType=$relationType");
                        $canLinkStory  = common::hasPriv($story->type, 'linkStory');

                        return h::li
                        (
                            setClass('relateStories'),
                            set::title($relation->title),
                            label(setClass('circle size-sm'), $relation->id),
                            $canViewStory ? a(set::href(helper::createLink('story', 'view', "id={$relation->id}&version=0&param=0&storyType=$relationType")), setClass('title'), setData(array('toggle' => 'modal', 'size' => 'lg')), $relation->title) : span(setClass('title'), $relation->title),
                            $canLinkStory ? a(set('url', helper::createLink('story', 'linkStory', "storyID=$story->id&type=remove&linkedID={$relation->id}&browseType=&queryID=0&storyType=$story->type")), setClass('unlink unlinkStory hidden'), icon('link')) : null
                        );
                    }, $relations)),
                    !common::hasPriv($story->type, 'linkStory') ? null : h::li(a(set::href(helper::createLink('story', 'linkStory', "storyID=$story->id&type=linkStories&linkedID=0&browseType=&queryID=0&storyType=$story->type")), setData(array('toggle' => 'modal', 'size' => 'lg')), setID('linkButton'), setClass('btn secondary size-sm'), icon('plus'), $lang->story->link . ($story->type == 'story' ? $lang->story->requirement : $lang->story->story)))
                )
            ) : null,
            $story->type == 'story' && common::hasPriv('story', 'tasks') ? tabPane
            (
                set::key('legendProjectAndTask'),
                set::title($lang->story->legendProjectAndTask),
                set::active((!$this->config->URAndSR || $hiddenURS) && empty($twins)),
                h::ul(setClass('list-unstyled'), $taskItems)
            ) : null,
            $config->vision == 'or' ? tabPane
            (
                set::key('legendRelated'),
                set::title($lang->story->legendRelated),
                tableData
                (
                    set::useTable(false),
                    $story->type == 'story' && !empty($fromBug) && common::hasPriv('story', 'bugs') ? item
                    (
                        set::collapse(true),
                        set::name($lang->story->legendFromBug),
                        h::ul
                        (
                            h::li
                            (
                                set::title($fromBug->title),
                                label(setClass('circle size-sm'), $fromBug->id),
                                common::hasPriv('bug', 'view') ? a(set::href($this->createLink('bug', 'view', "bugID=$fromBug->id")), setClass('title'), setData(array('toggle' => 'modal')), set::title($fromBug->title), $fromBug->title) : span(setClass('title'), $fromBug->title)
                            )
                        )
                    ) : null,
                    $story->type == 'story' ? item
                    (
                        set::collapse(true),
                        set::name($lang->story->legendBugs),
                        empty($bugs) ? null : h::ul
                        (
                            array_values(array_map(function($bug) use($lang)
                            {
                                return h::li
                                (
                                    set::title($bug->title),
                                    label(setClass('circle size-sm'), $bug->id),
                                    common::hasPriv('bug', 'view') ? a(set::href(helper::createLink('bug', 'view', "bugID=$bug->id")), setClass('title'), setData(array('toggle' => 'modal')), set::title($bug->title), $bug->title) : span(setClass('title'), $bug->title),
                                    label(setClass("status-{$bug->status} size-sm"), $lang->bug->statusList[$bug->status])
                                );
                            }, $bugs))
                        )
                    ) : null,
                    $story->type == 'story' && common::hasPriv('story', 'cases') ? item
                    (
                        set::collapse(true),
                        set::name($lang->story->legendCases),
                        empty($cases) ? null : h::ul
                        (
                            array_values(array_map(function($case)
                            {
                                return h::li
                                (
                                    set::title($case->title),
                                    label(setClass('circle size-sm'), $case->id),
                                    common::hasPriv('testcase', 'view') ? a(set::href(helper::createLink('testcase', 'view', "caseID=$case->id")), setClass('title'), setData(array('toggle' => 'modal')), set::title($case->title), $case->title) : span(setClass('title'), $case->title)
                                );
                            }, $cases))
                        )
                    ) : null,
                    $story->type == 'story' ? item
                    (
                        set::collapse(true),
                        set::name($lang->story->legendBuilds),
                        empty($builds) ? null : h::ul
                        (
                            array_values(array_map(function($build)
                            {
                                global $app;
                                $tab = $app->tab == 'product' ? 'project' : $app->tab;
                                return h::li
                                (
                                    set::title($build->name),
                                    label(setClass('circle size-sm'), $build->id),
                                    common::hasPriv('build', 'view') ? a(set::href(helper::createLink('build', 'view', "buildID=$build->id")), setClass('title'), setData(array('app' => $tab)), set::title($build->name), $build->name) : span(setClass('title'), $build->name)
                                );
                            }, $builds))
                        )
                    ) : null,
                    $story->type == 'story' ? item
                    (
                        set::collapse(true),
                        set::name($lang->story->legendReleases),
                        empty($releases) ? null : h::ul
                        (
                            array_values(array_map(function($release)
                            {
                                global $app;
                                $tab           = $app->tab == 'execution' ? 'product'        : $app->tab;
                                $releaseModule = $app->tab == 'project'   ? 'projectrelease' : 'release';
                                return h::li
                                (
                                    set::title($release->name),
                                    label(setClass('circle size-sm'), $release->id),
                                    common::hasPriv($releaseModule, 'view') ? a(set::href(helper::createLink($releaseModule, 'view', "releaseID=$release->id")), setClass('title'), setData(array('app' => $tab)), set::title($release->name), $release->name) : span(setClass('title'), $release->name)
                                );
                            }, $releases))
                        )
                    ) : null,
                    item
                    (
                        set::collapse(true),
                        set::name($lang->story->linkStories),
                        empty($story->linkStoryTitles) ? null : h::ul
                        (
                            array_values(array_map(function($storyID, $storyTitle) use($storyProducts, $story)
                            {
                                global $app;
                                $hasPriv = ($app->user->admin || str_contains(",{$app->user->view->products},", ",{$storyProducts[$storyID]},"));
                                return h::li
                                (
                                    set::title($storyTitle),
                                    label(setClass('circle size-sm'), $storyID),
                                    $hasPriv ? a(set::href(helper::createLink('story', 'view', "storyID=$storyID&version=0&param=0&storyType=$story->type")), setClass('title'), setData(array('toggle' => 'modal')), set::title($storyTitle), $storyTitle) : span(setClass('title'), $storyTitle)
                                );
                            }, array_keys($story->linkStoryTitles), array_values($story->linkStoryTitles)))
                        )
                    ),
                    $story->type == 'story' && helper::hasFeature('devops') ? item
                    (
                        set::collapse(true),
                        set::name($lang->story->linkMR),
                        empty($linkedMRs) ? null : h::ul
                        (
                            array_values(array_map(function($MRID, $linkMRTitle)
                            {
                                return h::li
                                (
                                    set::title($linkMRTitle),
                                    label(setClass('circle size-sm'), $MRID),
                                    common::hasPriv('mr', 'view') ? a
                                    (
                                        set::href(helper::createLink('mr', 'view', "MRID=$MRID")),
                                        setClass('title'),
                                        set::title($linkMRTitle),
                                        setData(array('app', 'devops')),
                                        $linkMRTitle
                                    ) : span(setClass('title'), $linkMRTitle)
                                );
                            }, array_keys($linkedMRs), array_values($linkedMRs)))
                        )
                    ) : null,
                    item
                    (
                        set::collapse(true),
                        set::name($lang->story->linkCommit),
                        empty($linkedCommits) ? null : h::ul
                        (
                            array_values(array_map(function($commit) use($storyProducts)
                            {
                                return h::li
                                (
                                    set::title($commit->comment),
                                    label(setClass('circle size-sm'), substr($commit->revision, 0, 10)),
                                    common::hasPriv('repo', 'revision') ? a
                                    (
                                        set::href(helper::createLink('repo', 'revision', "repoID={$commit->repo}&objectID=0&revision={$commit->revision}")),
                                        setClass('title'),
                                        set::title($commit->comment),
                                        setData(array('app' => 'devops')),
                                        $commit->comment
                                    ) : span(setClass('title'), $commit->comment)
                                );
                            }, $linkedCommits))
                        )
                    )
                )
            ) : null
        )
    )
);

if(isset($libs))
{
    modal
    (
        setID('importToLib'),
        set::title($lang->story->importToLib),
        form
        (
            set::action($this->createLink('story', 'importToLib', "storyID=$story->id")),
            formGroup
            (
                set::label($lang->story->lib),
                picker
                (
                    set::name('lib'),
                    set::items($libs),
                    set::required(true)
                )
            ),
            (!common::hasPriv('assetlib', 'approveStory') && !common::hasPriv('assetlib', 'batchApproveStory')) ? formGroup
            (
                set::label($lang->story->approver),
                picker
                (
                    set::name('assignedTo'),
                    set::items($approvers)
                )
            ) : null,
            set::submitBtnText($lang->import),
            set::actions(array('submit'))
        )
    );
}

if(!isInModal())
{
    floatPreNextBtn
    (
        !empty($preAndNext->pre)  ? set::preLink(createLink('story', 'view', "id={$preAndNext->pre->id}"))   : null,
        !empty($preAndNext->next) ? set::nextLink(createLink('story', 'view', "id={$preAndNext->next->id}")) : null
    );
}

render();
