<?php
declare(strict_types=1);
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     bug
 * @link        http://www.zentao.net
 */
namespace zin;

$app->loadLang('product');
$moduleTitle = '';
if(empty($modulePath))
{
    $moduleTitle .= '/';
}
else
{
    if($bugModule->branch and isset($branches[$bugModule->branch]))
    {
        $moduleTitle .= $branches[$bugModule->branch] . '/';
    }

    foreach($modulePath as $key => $module)
    {
        $moduleTitle .= $module->name;
        if(isset($modulePath[$key + 1]))
        {
            $moduleTitle .= '/';
        }
    }
}

$openedBuilds = array();
foreach(explode(',', $bug->openedBuild) as $openedBuild)
{
    if(!$openedBuild) continue;
    $openedBuilds[] = div(zget($builds, $openedBuild));
}

$osList = array();
foreach(explode(',', $bug->os) as $os)
{
    $osList[] = span(zget($lang->bug->osList, $os));
}

$browserList = array();
foreach($browserList as $browser)
{
    $browserList[] = span(zget($lang->bug->browserList, $browser));
}

$mailtoList = '';
if(!empty($bug->mailto))
{
    foreach(explode(',', str_replace(' ', '', $bug->mailto)) as $account)
    {
        $mailtoList .= ' ' . zget($users, $account);
    }
}

$linkBugs = array();
foreach($bug->linkBugTitles as $linkBugID => $linkBugTitle)
{
    $linkBugs[] = a
    (
        set('href', helper::createLink('bug', 'view', "bugID=$linkBugID")),
        set('data-toggle', 'modal'),
        "#$linkBugID $linkBugTitle"
    );
}

$linkMR = array();
foreach($bug->linkMRTitles as $MRID => $linkMRTitle)
{
    $linkMR[] = a
    (
        set('href', helper::createLink('mr', 'view', "MRID=$MRID")),
        "#$MRID $linkMRTitle"
    );
}

$linkCommits = array();
foreach($linkCommits as $commit)
{
    $linkCommits[] = a
    (
        set('href', helper::createLink('repo', 'revision', "repoID={$commit->repo}&objectID=0&revision={$commit->revision}")),
        " $commit->comment"
    );
}

$files = '';
foreach($bug->files as $file) $files .= $file->title . ',';

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID(17),
            set::level(1),
            set::text($bug->title)
        )
    ),
    to::suffix
    (
        btn
        (
            set::icon('plus'),
            set::type('primary'),
            set::text($lang->bug->create)
        )
    )
);

detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->bug->legendSteps),
            set::content($bug->steps),
            set::useHtml(true)
        ),
        section
        (
            set::title($lang->files),
            set::content($files),
            set::useHtml(true)
        ),
        section
        (
            set::title($lang->bug->fromCase),
            set::content($bug->case ? "#$bug->case $bug->caseTitle" : ''),
            set::useHtml(true)
        ),
        history(),
        center
        (
            floatToolbar
            (
                set::prefix
                (
                    array(array('icon' => 'back', 'text' => $lang->goback))
                ),
                set::main($actionList),
                set::suffix
                (
                    array
                    (
                        array('icon' => 'edit', 'url' => helper::createLink('bug', 'edit', "bugID={$bug->id}")),
                        array('icon' => 'copy', 'url' => helper::createLink('bug', 'create', "productID={$bug->product}&branch={$bug->branch}&extras=bugID={$bug->id}")),
                        array('icon' => 'trash', 'url' => helper::createLink('bug', 'delete', "bugID={$bug->id}")),
                    )
                )
            )
        )
    ),
    detailSide
    (
        tabs
        (
            tabPane
            (
                set::key('legendBasicInfo'),
                set::title($lang->bug->legendBasicInfo),
                set::active(true),
                tableData
                (
                    item
                    (
                        set::name($lang->bug->product),
                        set::url(helper::createLink('product', 'view', "productID=$bug->product")),
                        set('data-app', 'product'),
                        $product->name
                    ),
                    item
                    (
                        set::name(sprintf($lang->product->branch, $lang->product->branchName[$product->type])),
                        set::url(helper::createLink('bug', 'browse', "productID=$bug->product&branch=$bug->branch")),
                        $branchName
                    ),
                    item
                    (
                        set::name($lang->bug->module),
                        $moduleTitle
                    ),
                    item
                    (
                        set::name($lang->bug->productplan),
                        set::url(helper::createLink('productplan', 'view', "planID=$bug->plan&type=bug")),
                        $bug->planName
                    ),
                    item
                    (
                        set::name($lang->bug->type),
                        zget($lang->bug->typeList, $bug->type),
                    ),
                    item
                    (
                        set::name($lang->bug->status),
                        set::class('status-' . $bug->status),
                        $this->processStatus('bug', $bug)
                    ),
                    item
                    (
                        set::name($lang->bug->severity),
                        severityLabel
                        (
                            set::level(zget($lang->bug->severityList, $bug->severity)),
                            set::isIcon(true)
                        ),
                    ),
                    item
                    (
                        set::name($lang->bug->pri),
                        priLabel(zget($lang->bug->priList, $bug->pri))
                    ),
                    item
                    (
                        set::name($lang->bug->activatedCount),
                        $bug->activatedCount
                    ),
                    item
                    (
                        set::name($lang->bug->activatedDate),
                        $bug->activatedDate
                    ),
                    item
                    (
                        set::name($lang->bug->confirmed),
                        $lang->bug->confirmedList[$bug->confirmed]
                    ),
                    item
                    (
                        set::name($lang->bug->lblAssignedTo),
                        zget($users, $bug->assignedTo) . ($bug->assignedDate ? $lang->at . $bug->assignedDate : '')
                    ),
                    item
                    (
                        set::name($lang->bug->deadline),
                        $bug->deadline . (isset($bug->delay) ? sprintf($lang->bug->delayWarning, $bug->delay) : '')
                    ),
                    item
                    (
                        set::name($lang->bug->feedbackBy),
                        $bug->feedbackBy
                    ),
                    item
                    (
                        set::name($lang->bug->notifyEmail),
                        $bug->notifyEmail
                    ),
                    item
                    (
                        set::name($lang->bug->os),
                        $osList
                    ),
                    item
                    (
                        set::name($lang->bug->browser),
                        $browserList
                    ),
                    item
                    (
                        set::name($lang->bug->keywords),
                        $bug->keywords
                    ),
                    item
                    (
                        set::name($lang->bug->mailto),
                        $mailtoList
                    )
                )
            ),
            tabPane
            (
                set::key('legendLife'),
                set::title($lang->bug->legendLife),
                tableData
                (
                    item
                    (
                        set::name($lang->bug->openedBy),
                        zget($users, $bug->openedBy) . ($bug->openedDate ? $lang->at . $bug->openedDate : '')
                    ),
                    item
                    (
                        set::name($lang->bug->openedBuild),
                        $openedBuilds
                    ),
                    item
                    (
                        set::name($lang->bug->lblResolved),
                        zget($users, $bug->resolvedBy) . ($bug->resolvedDate ? $lang->at . $bug->resolvedDate : '')
                    ),
                    item
                    (
                        set::name($lang->bug->resolvedBuild),
                        zget($builds, $bug->resolvedBuild)
                    ),
                    item
                    (
                        set::name($lang->bug->resolution),
                        div
                        (
                            zget($lang->bug->resolutionList, $bug->resolution) . ($bug->duplicateBug ?  "#$bug->duplicateBug:" : ''),
                            $bug->duplicateBug ? a
                            (
                                set('href', createLink('bug', 'view', "bugID=$bug->duplicateBug")),
                                set('data-toggle', 'modal'),
                                $bug->duplicateBugTitle
                            ) : ''
                        )
                    ),
                    item
                    (
                        set::name($lang->bug->closedBy),
                        zget($users, $bug->closedBy) . ($bug->closedDate ? $lang->at . $bug->closedDate : '')
                    ),
                    item
                    (
                        set::name($lang->bug->lblLastEdited),
                        zget($users, $bug->lastEditedBy) . ($bug->lastEditedDate ? $lang->at . $bug->lastEditedDate : '')
                    )
                )
            )
        ),
        tabs
        (
            tabPane
            (
                set::key('legendExecStoryTask'),
                set::title(!empty($project->multiple) ? $lang->bug->legendPRJExecStoryTask : $lang->bug->legendExecStoryTask),
                set::active(true),
                tableData
                (
                    item
                    (
                        set::name($lang->bug->project),
                        set::url(helper::createLink('project', 'view', "projectID=$bug->project")),
                        $bug->projectName
                    ),
                    item
                    (
                        set::name((isset($project->model) and $project->model == 'kanban') ? $lang->bug->kanban : $lang->bug->execution),
                        set::url(helper::createLink('execution', 'browse', "executionID=$bug->execution")),
                        $bug->executionName
                    ),
                    item
                    (
                        set::name($lang->bug->story),
                        $bug->story ? a
                        (
                            set::href(helper::createLink('story', 'view', "storyID=$bug->story")),
                            set('data-toggle', 'modal'),
                            "#$bug->story $bug->storyTitle"
                        ) : ''
                    ),
                    item
                    (
                        set::name($lang->bug->task),
                        $bug->task ? a
                        (
                            set::href(helper::createLink('task', 'view', "taskID=$bug->task")),
                            set('data-toggle', 'modal'),
                            "$bug->taskName"
                        ) : ''
                    )
                )
            ),
            tabPane
            (
                set::key('legendMisc'),
                set::title($lang->bug->legendMisc),
                tableData
                (
                    item
                    (
                        set::name($lang->bug->linkBug),
                        $linkBugs
                    ),
                    item
                    (
                        set::name($lang->bug->fromCase),
                        $bug->case ? a
                        (
                            set::href(helper::createLink('testcase', 'view', "caseID=$bug->case&caseVersion=$bug->caseVersion")),
                            set('data-toggle', 'modal'),
                            "#$bug->case $bug->caseTitle"
                        ) : ''
                    ),
                    item
                    (
                        set::name($lang->bug->toCase),
                        $toCases
                    ),
                    item
                    (
                        set::name($lang->bug->toStory),
                        $bug->toStory ? a
                        (
                            set::href(helper::createLink('story', 'view', "storyID=$bug->toStory")),
                            set('data-toggle', 'modal'),
                            "#$bug->toStory $bug->toStoryTitle"
                        ) : ''
                    ),
                    item
                    (
                        set::name($lang->bug->toTask),
                        $bug->toTask ? a
                        (
                            set::href(helper::createLink('task', 'view', "taskID=$bug->toTask")),
                            set('data-toggle', 'modal'),
                            "#$bug->toTask $bug->toTaskTitle",
                        ) : ''
                    ),
                    item
                    (
                        set::name($lang->bug->linkMR),
                        $linkMR
                    ),
                    item
                    (
                        set::name($lang->bug->linkCommit),
                        $linkCommits
                    )
                )
            )
        )
    )
);

render();
