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
jsVar('bugID',     $bug->id);
jsVar('productID', $bug->product);
jsVar('branchID',  $bug->branch);
jsVar('errorNoExecution', $lang->bug->noExecution);
jsVar('errorNoProject',   $lang->bug->noProject);

$canCreateBug = hasPriv('bug', 'create');
$canViewRepo  = hasPriv('repo', 'revision');
$canViewMR    = hasPriv('mr', 'view');
$canViewBug   = hasPriv('bug', 'view');

$buildsHTML   = array();
$openedBuilds = explode(',', $legendLife['openedBuild']['text']);
foreach($openedBuilds as $openedBuild)
{
    if(!$openedBuild) continue;
    $buildsHTML[] = div(zget($builds, $openedBuild));
}

$osHTML = array();
$osList = explode(',', $legendBasic['os']['text']);
foreach($osList as $os)
{
    $osHTML[] = span(zget($lang->bug->osList, $os));
}

$browserHTML = array();
$browserList = explode(',', $legendBasic['browser']['text']);
foreach($browserList as $browser)
{
    $browserHTML[] = span(zget($lang->bug->browserList, $browser));
}

$mailtoHTML = array();
if(!empty($legendBasic['mailto']['text']))
{
    $mailtoList = explode(',', str_replace(' ', '', $legendBasic['mailto']['text']));
    foreach($mailtoList as $account)
    {
        $mailtoHTML[] = span(zget($users, $account));
    }
}

$duplicateLink = $bug->duplicateBug && $canViewBug ? a
(
    set('href', $this->createLink('bug', 'view', "bugID={$bug->duplicateBug}")),
    set('data-toggle', 'modal'),
    $bug->duplicateBugTitle
) : '';
$duplicateBug = $bug->duplicateBug ? "#{$bug->duplicateBug}:{$duplicateLink}" : '';

$relatedBugs = array();
foreach($legendMisc['relatedBug']['text'] as $relatedBugID => $relatedBugTitle)
{
    $relatedBugs[] = div(a
    (
        set('href', $this->createLink('bug', 'view', "bugID={$relatedBugID}")),
        set('data-toggle', 'modal'),
        span(label(set::class('dark-outline rounded-full mr-2'), $relatedBugID), $relatedBugTitle)
    ));
}

$linkMR = array();
foreach($legendMisc['linkMR']['text'] as $MRID => $linkMRTitle)
{
    $linkMR[] = div(a
    (
        $canViewMR ? set('href', $this->createLink('mr', 'view', "MRID={$MRID}")) : null,
        span(label(set::class('dark-outline rounded-full mr-2'), $MRID), $linkMRTitle)
    ));
}

$linkCommits = array();
foreach($legendMisc['linkCommit']['text'] as $commit)
{
    $linkCommits[] = div(a
    (
        $canViewRepo ? set('href', $this->createLink('repo', 'revision', "repoID={$commit->repo}&objectID=0&revision={$commit->revision}")) : null,
        "{$commit->comment}"
    ));
}

$legendBasic['os']['text']            = $osHTML;
$legendBasic['browser']['text']       = $browserHTML;
$legendBasic['mailto']['text']        = $mailtoHTML;
$legendBasic['severity']['text']      = severityLabel(set::level(zget($lang->bug->severityList, $legendBasic['severity']['text'])), set::isIcon(true));
$legendBasic['pri']['text']           = priLabel(zget($lang->bug->priList, $legendBasic['pri']['text']));
$legendLife['openedBuild']['text']    = $buildsHTML;
$legendLife['resolution']['text']     = div(zget($lang->bug->resolutionList, $bug->resolution) . $duplicateBug);
$legendExecStoryTask['story']['text'] = $bug->story ? div(label(set('class', 'dark-outline rounded-full size-sm mr-2'), $bug->story), span($bug->storyTitle)) : '';
$legendExecStoryTask['task']['text']  = $bug->task  ? div(label(set('class', 'dark-outline rounded-full size-sm mr-2'), $bug->task),  span($bug->taskName))   : '';;
$legendMisc['relatedBug']['text']     = $relatedBugs;
$legendMisc['linkCommit']['text']     = $linkCommits;
$legendMisc['linkMR']['text']         = $linkMR;

/* Handling special tags in bug descriptions. */
$tplStep   = strip_tags(trim($lang->bug->tplStep));
$steps     = str_replace('<p>' . $tplStep, '<p class="article-h4 my-1">' . $tplStep . '</p><p>', $bug->steps);
$tplResult = strip_tags(trim($lang->bug->tplResult));
$steps     = str_replace('<p>' . $tplResult, '<p class="article-h4 my-1">' . $tplResult . '</p><p>', $steps);
$tplExpect = strip_tags(trim($lang->bug->tplExpect));
$steps     = str_replace('<p>' . $tplExpect, '<p class="article-h4 my-1">' . $tplExpect . '</p><p>', $steps);
$steps     = str_replace('<p></p>', '', $steps);

$files = '';
foreach($bug->files as $file) $files .= "{$file->title},";

/* build operate menu. */
$moduleName = $app->moduleName;
$methodName = $app->methodName;
foreach($config->{$moduleName}->actions->{$methodName} as $menu => $actionList)
{
    $$menu = array();
    foreach($actionList as $action)
    {
        $actionData = $config->{$moduleName}->actionList[$action];

        if(!empty($actionData['url']) && is_array($actionData['url']))
        {
            $module = $actionData['url']['module'];
            $method = $actionData['url']['method'];
            $params = $actionData['url']['params'];
            if(!common::hasPriv($module, $method)) continue;
            $actionData['url'] = helper::createLink($module, $method, $params);
        }
        else if(!empty($actionData['data-url']) && is_array($actionData['data-url']))
        {
            $module = $actionData['data-url']['module'];
            $method = $actionData['data-url']['method'];
            $params = $actionData['data-url']['params'];
            if(!common::hasPriv($module, $method)) continue;
            $actionData['data-url'] = helper::createLink($module, $method, $params);
        }
        else
        {
            if(!common::hasPriv($moduleName, $action)) continue;
        }
        if(!$this->{$moduleName}->isClickable($$moduleName, $action)) continue;

        if($menu == 'suffixActions' && !empty($actionData['text'])) $actionData['text'] = '';

        $$menu[] = $actionData;
    }
}

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($bug->id),
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
    ),
    history(),
    floatToolbar
    (
        set::object($bug),
        to::prefix(backBtn(set::icon('back'), $lang->goback)),
        set::main($mainActions),
        set::suffix($suffixActions)
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
                set::key('legendMain'),
                set::title(!empty($project->multiple) ? $lang->bug->legendPRJExecStoryTask : $lang->bug->legendExecStoryTask),
                set::active(true),
                tableData
                (
                    buildItems($legendMain)
                )
            ),
            tabPane
            (
                set::key('legendMisc'),
                set::title($lang->bug->legendMisc),
                tableData
                (
                    set::useTable(false),
                    buildItems($legendMisc)
                )
            )
        )
    )
);

modal
(
    set::id('toTask'),
    set::modalProps(array('title' => $lang->bug->selectProjects)),
    to::footer
    (
        div
        (
            set::class('toolbar gap-4 w-full justify-center'),
            btn($lang->bug->nextStep, set::id('toTaskButton'), setClass('primary')),
            btn($lang->cancel, set::id('cancelButton'), set('data-dismiss', 'modal'))
        )
    ),
    form
    (
        on::change('#taskProjects', 'changeTaskProjects'),
        set::actions(),
        formRow
        (
            formGroup
            (
                set::label($lang->bug->selectProjects),
                set::required(true),
                set::control('select'),
                set::name('taskProjects'),
                set::items($projects),
            )
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->bug->execution),
                set::required(true),
                inputGroup
                (
                    set('id', 'executionBox'),
                    select
                    (
                        set::name('execution'),
                        set::items(),
                    )
                )
            )
        )
    )
);

render();

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
            ) : $item['text'],
            set::collapse(!empty($item['text'])),
        );
    }

    return $itemList;
}
