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

/**
 * 根据字段打印表格内容。
 * print table.
 *
 * @param array $fields
 * @return wg
 */
function printTable($fields): wg
{
    global $lang;

    $trs = array();
    foreach($fields as $field)
    {
        $trs[] = h::tr
        (
            h::th($field['th']),
            h::td
            (
                !empty($field['url']) ?
                a
                (
                    set('href', $field['url']),
                    set('class', $field['class']),
                    !empty($field['data-app'])    ? set('data-app', 'product') : null,
                    !empty($field['data-toggle']) ? set('data-toggle', 'modal') : null,
                    $field['tr']
                ) : span
                (
                    set('class', $field['class']),
                    $field['tr']
                )
            )
        );
    }
    return h::table(h::tbody($trs));
}

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
foreach(explode(',', str_replace(' ', '', $bug->mailto)) as $account)
{
    $mailtoList .= ' ' . zget($users, $account);
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
foreach($bug->files as $file)
{
    $files .= $file->title . ',';
}

panel
(
    div
    (
        set('class', 'flex'),
        cell
        (
            set('width', '70%'),
            set('class', 'border-r'),
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
                set::content
                (
                    "#$bug->case $bug->caseTitle"
                ),
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
        cell
        (
            set('width', '30%'),
            set('class', 'px-4'),
            tabs
            (
                set::items
                (
                    array
                    (
                        array
                        (
                            'id' => 'legendBasicInfo', 'label' => $lang->bug->legendBasicInfo, 'data' => printTable(array
                            (
                                array('th' => $lang->bug->product, 'tr' => $product->name, 'url' => helper::createLink('product', 'view', "productID=$bug->product"), 'data-app' => 'product'),
                                array('th' => sprintf($lang->product->branch, $lang->product->branchName[$product->type]), 'tr' => $branchName, 'url' => helper::createLink('bug', 'browse', "productID=$bug->product&branch=$bug->branch")),
                                array('th' => $lang->bug->module,         'tr' => $moduleTitle),
                                array('th' => $lang->bug->productplan,    'tr' => $bug->planName, 'url' => helper::createLink('productplan', 'view', "planID=$bug->plan&type=bug")),
                                array('th' => $lang->bug->type,           'tr' => zget($lang->bug->typeList, $bug->type)),
                                array('th' => $lang->bug->status,         'tr' => $this->processStatus('bug', $bug), 'class' => 'status-' . $bug->status),
                                array('th' => $lang->bug->severity,       'tr' => severityLabel(set::level(zget($lang->bug->severityList, $bug->severity)), set::isIcon(true))),
                                array('th' => $lang->bug->pri,            'tr' => priLabel(zget($lang->bug->priList, $bug->pri))),
                                array('th' => $lang->bug->activatedCount, 'tr' => $bug->activatedCount),
                                array('th' => $lang->bug->activatedDate,  'tr' => $bug->activatedDate),
                                array('th' => $lang->bug->confirmed,      'tr' => $lang->bug->confirmedList[$bug->confirmed]),
                                array('th' => $lang->bug->lblAssignedTo,  'tr' => zget($users, $bug->assignedTo) . $lang->at . $bug->assignedDate),
                                array('th' => $lang->bug->deadline,       'tr' => $bug->deadline . (isset($bug->delay) ? printf($lang->bug->delayWarning, $bug->delay) : '')),
                                array('th' => $lang->bug->feedbackBy,     'tr' => $bug->feedbackBy),
                                array('th' => $lang->bug->notifyEmail,    'tr' => $bug->notifyEmail),
                                array('th' => $lang->bug->os,             'tr' => $osList),
                                array('th' => $lang->bug->browser,        'tr' => $browserList),
                                array('th' => $lang->bug->keywords,       'tr' => $bug->keywords),
                                array('th' => $lang->bug->mailto,         'tr' => $mailtoList)
                            )), 'active' => true
                        ),
                        array
                        (
                            'id' => 'legendLife', 'label' => $lang->bug->legendLife, 'data' => printTable(array
                            (
                                array('th' => $lang->bug->openedBy,      'tr' => zget($users, $bug->openedBy) . $lang->at . $bug->openedDate),
                                array('th' => $lang->bug->openedBuild,   'tr' => $openedBuilds),
                                array('th' => $lang->bug->lblResolved,   'tr' => zget($users, $bug->resolvedBy) . $lang->at . $bug->resolvedDate),
                                array('th' => $lang->bug->resolvedBuild, 'tr' => zget($builds, $bug->resolvedBuild)),
                                array('th' => $lang->bug->resolution,    'tr' => div(zget($lang->bug->resolutionList, $bug->resolution) . "#$bug->duplicateBug:", a(set('href', createLink('bug', 'view', "bugID=$bug->duplicateBug")), set('data-toggle', 'modal'), $bug->duplicateBugTitle))),
                                array('th' => $lang->bug->closedBy,      'tr' => zget($users, $bug->closedBy) . $lang->at . $bug->closedDate),
                                array('th' => $lang->bug->lblLastEdited, 'tr' => zget($users, $bug->lastEditedBy, $bug->lastEditedBy) . $lang->at . $bug->lastEditedDate)
                            ))
                        )
                    )
                )
            ),
            tabs
            (
                set::items
                (
                    array
                    (
                        array
                        (
                            'id' => 'legendExecStoryTask', 'label' => (!empty($project->multiple) ? $lang->bug->legendPRJExecStoryTask : $lang->bug->legendExecStoryTask), 'data' => printTable(array
                            (
                                array('th' => $lang->bug->project, 'tr' => $bug->projectName, 'url' => helper::createLink('project', 'view', "projectID=$bug->project")),
                                array('th' => (isset($project->model) and $project->model == 'kanban') ? $lang->bug->kanban : $lang->bug->execution, 'tr' => $bug->executionName, 'url' => helper::createLink('execution', 'browse', "executionID=$bug->execution")),
                                array('th' => $lang->bug->story, 'tr' => "#$bug->story $bug->storyTitle", 'url' => helper::createLink('story', 'view', "storyID=$bug->story"), 'data-toggle' => 'modal'),
                                array('th' => $lang->bug->task, 'tr' => "$bug->taskName", 'url' => helper::createLink('task', 'view', "taskID=$bug->task"), 'data-toggle' => 'modal'),
                            )), 'active' => true
                        ),
                        array
                        (
                            'id' => 'legendMisc', 'label' => $lang->bug->legendMisc, 'data' => printTable(array
                            (
                                array('th' => $lang->bug->linkBug,    'tr' => $linkBugs),
                                array('th' => $lang->bug->fromCase,   'tr' => "#$bug->case $bug->caseTitle", 'url' => helper::createLink('testcase', 'view', "caseID=$bug->case&caseVersion=$bug->caseVersion"), 'data-toggle' => 'modal'),
                                array('th' => $lang->bug->toCase,     'tr' => $toCases),
                                array('th' => $lang->bug->toStory,    'tr' => "#$bug->toStory $bug->toStoryTitle", 'url' => helper::createLink('story', 'view', "storyID=$bug->toStory"), 'data-toggle' => 'modal'),
                                array('th' => $lang->bug->toTask,     'tr' => "#$bug->toTask $bug->toTaskTitle",   'url' => helper::createLink('task', 'view', "taskID=$bug->toTask"),    'data-toggle' => 'modal'),
                                array('th' => $lang->bug->linkMR,     'tr' => $linkMR),
                                array('th' => $lang->bug->linkCommit, 'tr' => $linkCommits),
                            ))
                        )
                    )
                )
            )
        )
    )
);

render();
