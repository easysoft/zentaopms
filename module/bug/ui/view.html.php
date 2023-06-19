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

$canViewMr          = hasPriv('mr', 'view');
$canViewProduct     = hasPriv('product', 'view');
$canViewPlan        = hasPriv('productplan', 'view');
$canViewProduct     = hasPriv('project', 'view');
$canViewStory       = hasPriv('story', 'view');
$canViewTask        = hasPriv('task', 'view');
$canViewCase        = hasPriv('testcase', 'view');
$canViewRepo        = hasPriv('repo', 'revision');
$canBrowseBug       = hasPriv('bug', 'browse');
$canBrowseExecution = hasPriv('execution', 'browse');
$canCreateBug       = hasPriv('bug', 'create');

$moduleTitle = '';
if(empty($modulePath))
{
    $moduleTitle .= '/';
}
else
{
    if($bugModule->branch && isset($branches[$bugModule->branch])) $moduleTitle .= $branches[$bugModule->branch] . '/';

    foreach($modulePath as $key => $module)
    {
        $moduleTitle .= $module->name;

        if(isset($modulePath[$key + 1])) $moduleTitle .= '/';
    }
}

/* Handling special tags in bug descriptions. */
$tplStep   = strip_tags(trim($lang->bug->tplStep));
$steps     = str_replace('<p>' . $tplStep, '<p class="article-h4 my-1">' . $tplStep . '</p><p>', $bug->steps);
$tplResult = strip_tags(trim($lang->bug->tplResult));
$steps     = str_replace('<p>' . $tplResult, '<p class="article-h4 my-1">' . $tplResult . '</p><p>', $steps);
$tplExpect = strip_tags(trim($lang->bug->tplExpect));
$steps     = str_replace('<p>' . $tplExpect, '<p class="article-h4 my-1">' . $tplExpect . '</p><p>', $steps);
$steps     = str_replace('<p></p>', '', $steps);

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

$files = '';
foreach($bug->files as $file) $files .= "{$file->title},";

/* Prepare variables for legendBasic block.  */
$app->loadLang('product');
$branchTitle  = sprintf($lang->product->branch, $lang->product->branchName[$product->type]);
$fromCaseName = $bug->case ? "#{$bug->case} {$bug->caseTitle}" : '';
$productLink  = $bug->product && $canViewProduct ? $this->createLink('product',     'view',   "productID={$bug->product}")                           : '';
$branchLink   = $bug->branch  && $canBrowseBug   ? $this->createLink('bug',         'browse', "productID={$bug->product}&branch={$bug->branch}")     : '';
$planLink     = $bug->plan    && $canViewPlan    ? $this->createLink('productplan', 'view',   "planID={$bug->plan}&type=bug")                        : '';
$fromCaseLink = $bug->case    && $canViewCase    ? $this->createLink('testcase',    'view',   "caseID={$bug->case}&caseVersion={$bug->caseVersion}") : '';

$legendBasic = array();
if(empty($product->shadow))    $legendBasic['product'] = array('name' => $lang->bug->product, 'text' => $product->name, 'href' => $productLink, 'attr' => array('data-app' => 'product'));
if($product->type != 'normal') $legendBasic['branch']  = array('name' => $branchTitle,        'text' => $branchName,    'href' => $branchLink);
$legendBasic['module'] = array('name' => $lang->bug->module, 'text' => $moduleTitle);
if(empty($product->shadow) || !empty($project->multiple)) $legendBasic['productplan'] = array('name' => $lang->bug->plan, 'text' => $bug->planName, 'href' => $planLink);
$legendBasic['fromCase']       = array('name' => $lang->bug->fromCase,       'text' => $fromCaseName, 'href' => $fromCaseLink, 'attr' => array('data-toggle' => 'modal'));
$legendBasic['type']           = array('name' => $lang->bug->type,           'text' => zget($lang->bug->typeList, $bug->type));
$legendBasic['severity']       = array('name' => $lang->bug->severity,       'text' => severityLabel(set::level(zget($lang->bug->severityList, $bug->severity)), set::isIcon(true)));
$legendBasic['pri']            = array('name' => $lang->bug->pri,            'text' => priLabel(zget($lang->bug->priList, $bug->pri)));
$legendBasic['status']         = array('name' => $lang->bug->status,         'text' => $this->processStatus('bug', $bug), 'attr' => array('class' => 'status-' . $bug->status));
$legendBasic['activatedCount'] = array('name' => $lang->bug->activatedCount, 'text' => $bug->activatedCount);
$legendBasic['activatedDate']  = array('name' => $lang->bug->activatedDate,  'text' => $bug->activatedDate);
$legendBasic['confirmed']      = array('name' => $lang->bug->confirmed,      'text' => $lang->bug->confirmedList[$bug->confirmed]);
$legendBasic['assignedTo']     = array('name' => $lang->bug->lblAssignedTo,  'text' => zget($users, $bug->assignedTo) . $lang->at . $bug->assignedDate);
$legendBasic['deadline']       = array('name' => $lang->bug->deadline,       'text' => $bug->deadline . (isset($bug->delay) ? sprintf($lang->bug->notice->delayWarning, $bug->delay) : ''));
$legendBasic['feedbackBy']     = array('name' => $lang->bug->feedbackBy,     'text' => $bug->feedbackBy);
$legendBasic['notifyEmail']    = array('name' => $lang->bug->notifyEmail,    'text' => $bug->notifyEmail);
$legendBasic['os']             = array('name' => $lang->bug->os,             'text' => $osList);
$legendBasic['browser']        = array('name' => $lang->bug->browser,        'text' => $browserList);
$legendBasic['keywords']       = array('name' => $lang->bug->keywords,       'text' => $bug->keywords);
$legendBasic['mailto']         = array('name' => $lang->bug->mailto,         'text' => $mailtoList);

/* Prepare variables for legendLife block. */
$duplicateLink = $bug->duplicateBug && $canViewBug ? a
    (
        set('href', $this->createLink('bug', 'view', "bugID={$bug->duplicateBug}")),
        set('data-toggle', 'modal'),
        $bug->duplicateBugTitle
    ) : '';
$duplicateBug = $bug->duplicateBug ? "#{$bug->duplicateBug}:{$duplicateLink}" : '';

$legendLife  = array();
$legendLife['openedBy']      = array('name' => $lang->bug->openedBy,      'text' => zget($users, $bug->openedBy) . ($bug->openedDate ? $lang->at . $bug->openedDate : ''));
$legendLife['openedBuild']   = array('name' => $lang->bug->openedBuild,   'text' => $openedBuilds);
$legendLife['resolvedBy']    = array('name' => $lang->bug->lblResolved,   'text' => zget($users, $bug->resolvedBy) . ($bug->resolvedDate ? $lang->at . $bug->resolvedDate : ''));
$legendLife['resolvedBuild'] = array('name' => $lang->bug->resolvedBuild, 'text' => zget($builds, $bug->resolvedBuild));
$legendLife['resolution']    = array('name' => $lang->bug->resolution,    'text' => div(zget($lang->bug->resolutionList, $bug->resolution) . $duplicateBug));
$legendLife['closedBy']      = array('name' => $lang->bug->closedBy,      'text' => zget($users, $bug->closedBy) . ($bug->closedDate ? $lang->at . $bug->closedDate : ''));
$legendLife['lastEditedBy']  = array('name' => $lang->bug->lblLastEdited, 'text' => zget($users, $bug->lastEditedBy, $bug->lastEditedBy) . ($bug->lastEditedDate ? $lang->at . $bug->lastEditedDate : ''));

/* Prepare variables for legendExecStoryTask block. */
$executionTitle = isset($project->model) && $project->model == 'kanban' ? $lang->bug->kanban : $lang->bug->execution;
$storyName      = $bug->story ? div(label(set('class', 'dark-outline rounded-full size-sm mr-2'), $bug->story), span($bug->storyTitle)) : '';
$taskName       = $bug->task  ? div(label(set('class', 'dark-outline rounded-full size-sm mr-2'), $bug->task),  span($bug->taskName))   : '';
$projectLink    = $bug->project   && $canViewProduct     ? $this->createLink('project',   'view',   "projectID={$bug->project}")     : '';
$executionLink  = $bug->execution && $canBrowseExecution ? $this->createLink('execution', 'browse', "executionID={$bug->execution}") : '';
$storyLink      = $bug->story     && $canViewStory       ? $this->createLink('story',     'view',   "storyID={$bug->story}")         : '';
$taskLink       = $bug->task      && $canViewTask        ? $this->createLink('task',      'view',   "taskID={$bug->task}")           : '';

$legendExecStoryTask = array();
$legendExecStoryTask['project']   = array('name' => $lang->bug->project, 'text' => zget($bug, 'projectName', ''), 'href' => $projectLink);
$legendExecStoryTask['execution'] = array('name' => $executionTitle,     'text' => $bug->executionName,           'href' => $executionLink);
$legendExecStoryTask['story']     = array('name' => $lang->bug->story,   'text' => $storyName,                    'href' => $storyLink, 'attr' => array('data-toggle' => 'modal'));
$legendExecStoryTask['task']      = array('name' => $lang->bug->task,    'text' => $taskName,                     'href' => $taskLink,  'attr' => array('data-toggle' => 'modal'));

/* Prepare variables for legendMisc block. */
$relatedBugs = array();
if(!empty($bug->relatedBugTitles))
{
    foreach($bug->relatedBugTitles as $relatedBugID => $relatedBugTitle)
    {
        $relatedBugs[] = a
        (
            set('href', $this->createLink('bug', 'view', "bugID={$relatedBugID}")),
            set('data-toggle', 'modal'),
            "#{$relatedBugID} {$relatedBugTitle}"
        );
    }
}

$linkMR = array();
foreach($bug->linkMRTitles as $MRID => $linkMRTitle)
{
    $linkMR[] = a
    (
        $canViewMr ? set('href', $this->createLink('mr', 'view', "MRID={$MRID}")) : null,
        "#{$MRID} {$linkMRTitle}"
    );
}

$linkCommits = array();
foreach($linkCommits as $commit)
{
    $linkCommits[] = a
    (
        $canViewRepo ? set('href', $this->createLink('repo', 'revision', "repoID={$commit->repo}&objectID=0&revision={$commit->revision}")) : null,
        " {$commit->comment}"
    );
}

$toStoryName  = $bug->toStory ? "#{$bug->toStory} {$bug->toStoryTitle}" : '';
$toTaskName   = $bug->toTask  ? "#{$bug->toTask} {$bug->toTaskTitle}"   : '';
$toStoryLink  = $bug->toStory && $canViewStory ? $this->createLink('story', 'view', "storyID={$bug->toStory}") : '';
$toTaskLink   = $bug->toTask  && $canViewTask  ? $this->createLink('task',  'view', "taskID={$bug->toTask}")   : '';

$legendMisc = array();
$legendMisc['relatedBug'] = array('name' => $lang->bug->relatedBug, 'text' => $relatedBugs);
$legendMisc['toCase']     = array('name' => $lang->bug->toCase,     'text' => $bug->toCases);
$legendMisc['toStory']    = array('name' => $lang->bug->toStory,    'text' => $toStoryName,  'href' => $toStoryLink,  'attr' => array('data-toggle' => 'modal'));
$legendMisc['toTask']     = array('name' => $lang->bug->toTask,     'text' => $toTaskName,   'href' => $toTaskLink,   'attr' => array('data-toggle' => 'modal'));
$legendMisc['linkMR']     = array('name' => $lang->bug->linkMR,     'text' => $linkMR);
$legendMisc['linkCommit'] = array('name' => $lang->bug->linkCommit, 'text' => $linkCommits);

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
            set::text($lang->bug->create),
            $canCreateBug ? set::url($this->createLink('bug', 'create', "productID={$product->id}")) : null
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
            set::content($steps),
            set::useHtml(true)
        ),
        section
        (
            set::title($lang->files),
            set::content($files),
            set::useHtml(true)
        ),
        /* section
        (
            set::title($lang->bug->fromCase),
            set::content($bug->case ? "#$bug->case $bug->caseTitle" : ''),
            set::useHtml(true)
        ) */
    ),
    history(),
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
                array('icon' => 'edit',  'url' => $this->createLink('bug', 'edit',   "bugID={$bug->id}")),
                array('icon' => 'copy',  'url' => $this->createLink('bug', 'create', "productID={$bug->product}&branch={$bug->branch}&extras=bugID={$bug->id}")),
                array('icon' => 'trash', 'url' => $this->createLink('bug', 'delete', "bugID={$bug->id}")),
            )
        )
    ),
    detailSide
    (
        tabs
        (
            set::collapse(true),
            tabPane
            (
                set::key('legendBasicInfo'),
                set::title($lang->bug->legendBasicInfo),
                set::active(true),
                tableData
                (
                    buildItems($legendBasic)
                )
            ),
            tabPane
            (
                set::key('legendLife'),
                set::title($lang->bug->legendLife),
                tableData
                (
                    buildItems($legendLife)
                )
            )
        ),
        tabs
        (
            set::collapse(true),
            tabPane
            (
                set::key('legendExecStoryTask'),
                set::title(!empty($project->multiple) ? $lang->bug->legendPRJExecStoryTask : $lang->bug->legendExecStoryTask),
                set::active(true),
                tableData
                (
                    buildItems($legendExecStoryTask)
                )
            ),
            tabPane
            (
                set::key('legendMisc'),
                set::title($lang->bug->legendMisc),
                tableData
                (
                    buildItems($legendMisc)
                )
            )
        )
    )
);

render(isAjaxRequest('modal') ? 'modalDialog' : 'page');

/**
 * Build content of table data.
 *
 * @param  array  $items
 * @access public
 * @return string
 */
function buildItems($items)
{
    $itemList = array();
    foreach($items as $item)
    {
        $itemList[] = item
        (
            set::name($item['name']),
            !empty($item['href']) ? a
            (
                set::href($item['href']),
                !empty($item['attr']) && is_array($item['attr']) ? set($item['attr']) : null,
                $item['text']
            ) : $item['text']
        );
    }

    return $itemList;
}
