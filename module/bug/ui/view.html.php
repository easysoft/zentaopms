<?php
declare(strict_types=1);
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
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

$moduleHTML = array();
$moduleTitle = '';
$moduleItems = array();
if(empty($modulePath))
{
    $moduleTitle  .= '/';
    $moduleItems[] = span('/');
}
else
{
    if($bug->branch and isset($branches[$bug->branch]))
    {
        $moduleTitle  .= $branches[$bug->branch] . '/';
        $moduleItems[] = span($branches[$bug->branch], icon('angle-right'));
    }

    foreach($modulePath as $key => $module)
    {
        $moduleTitle  .= $module->name;
        $moduleItems[] = $product->shadow ? span($module->name) : a(set::href(helper::createLink('bug', 'browse', "productID=$bug->product&branch=$bug->branch&browseType=byModule&param=$module->id")), $module->name);
        if(isset($modulePath[$key + 1]))
        {
            $moduleTitle  .= '/';
            $moduleItems[] = icon('angle-right');
        }
    }
}
$legendBasic['module']['text'] = $moduleItems;

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
    setData
    (
        array
        (
            'toggle' => 'modal',
            'size'   => 'lg',
        )
    ),
    $bug->duplicateBugTitle
) : '';
$duplicateBug = $bug->duplicateBug ? span("#{$bug->duplicateBug}:", $duplicateLink) : '';

$relatedBugs = array();
foreach($legendMisc['relatedBug']['text'] as $relatedBugID => $relatedBugTitle)
{
    $relatedBugs[] = div(a
    (
        set('href', $this->createLink('bug', 'view', "bugID={$relatedBugID}")),
        setData
        (
            array
            (
                'toggle' => 'modal',
                'size'   => 'lg',
            )
        ),
        span(label(setClass('dark-outline rounded-full mr-2'), $relatedBugID), $relatedBugTitle)
    ));
}

$linkMR = array();
foreach($legendMisc['linkMR']['text'] as $MRID => $linkMRTitle)
{
    $linkMR[] = div(a
    (
        $canViewMR ? set('href', $this->createLink('mr', 'view', "MRID={$MRID}")) : null,
        setData(array('app' => 'devops')),
        span(label(setClass('dark-outline rounded-full mr-2'), $MRID), $linkMRTitle)
    ));
}

$linkCommits = array();
foreach($legendMisc['linkCommit']['text'] as $commit)
{
    $linkCommits[] = div(a
    (
        setData(array('app' => 'devops')),
        $canViewRepo ? set('href', $this->createLink('repo', 'revision', "repoID={$commit->repo}&objectID=0&revision={$commit->revision}")) : null,
        "{$commit->comment}"
    ));
}

if(isset($bug->delay)) $legendBasic['deadline']['text'] = html($legendBasic['deadline']['text']);

$legendBasic['os']['text']         = $osHTML;
$legendBasic['browser']['text']    = $browserHTML;
$legendBasic['mailto']['text']     = $mailtoHTML;
$legendBasic['severity']['text']   = severityLabel($legendBasic['severity']['text'], set::text($lang->bug->severityList), set::isIcon(true));
$legendBasic['pri']['text']        = priLabel($legendBasic['pri']['text'], set::text($lang->bug->priList));
$legendLife['openedBuild']['text'] = $buildsHTML;
$legendLife['resolution']['text']  = div(zget($lang->bug->resolutionList, $bug->resolution), $duplicateBug);
$legendMain['story']['text']       = $bug->story ? div
(
    label
    (
        setClass('dark-outline rounded-full size-sm mr-2'),
        $bug->story
    ),
    span($bug->storyTitle),
    $bug->storyStatus == 'active' && $bug->latestStoryVersion > $bug->storyVersion && common::hasPriv('bug', 'confirmStoryChange') ? span
    (
        ' (',
        a
        (
            set::href(createLink('bug', 'confirmStoryChange', "bugID=$bug->id")),
            $lang->confirm,
        ),
        ')'
    ) : ''
) : '';
$legendMain['task']['text']       = $bug->task  ? div(label(setClass('dark-outline rounded-full size-sm mr-2'), $bug->task),  span($bug->taskName))   : '';;
$legendMisc['relatedBug']['text'] = $relatedBugs;
$legendMisc['linkCommit']['text'] = $linkCommits;
$legendMisc['linkMR']['text']     = $linkMR;

/* Handling special tags in bug descriptions. */
$tplStep   = strip_tags(trim($lang->bug->tplStep));
$steps     = str_replace('<p>' . $tplStep, '<p class="font-bold my-1">' . $tplStep . '</p><p>', $bug->steps);
$tplResult = strip_tags(trim($lang->bug->tplResult));
$steps     = str_replace('<p>' . $tplResult, '<p class="font-bold my-1">' . $tplResult . '</p><p>', $steps);
$tplExpect = strip_tags(trim($lang->bug->tplExpect));
$steps     = str_replace('<p>' . $tplExpect, '<p class="font-bold my-1">' . $tplExpect . '</p><p>', $steps);
$steps     = str_replace('<p></p>', '', $steps);

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($bug->id),
            set::level(1),
            span(setStyle('color', $bug->color), $bug->title)
        )
    ),
    to::suffix
    (
        !isAjaxRequest('modal') && $canCreateBug ?  btn
        (
            set::icon('plus'),
            set::type('primary'),
            set::text($lang->bug->create),
            set::url($this->createLink('bug', 'create', "productID={$product->id}"))
        ) : null
    )
);

/**
 * Build content of table data.
 *
 * @param  array  $items
 * @access public
 * @return array
 */
$buildItems = function($items): array
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
            set::collapse(!empty($item['text']))
        );
    }

    return $itemList;
};

$actions = $this->loadModel('common')->buildOperateMenu($bug);
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
        fileList
        (
            set::files($bug->files),
            set::padding(false)
        )
    ),
    history(),
    floatToolbar
    (
        set::object($bug),
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), setClass('ghost text-white'), $lang->goback)),
        set::main($actions['mainActions']),
        set::suffix($actions['suffixActions'])
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
                    $buildItems($legendBasic)
                )
            ),
            tabPane
            (
                set::key('legendLife'),
                set::title($lang->bug->legendLife),
                tableData
                (
                    $buildItems($legendLife)
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
                    $buildItems($legendMain)
                )
            ),
            tabPane
            (
                set::key('legendMisc'),
                set::title($lang->bug->legendMisc),
                tableData
                (
                    set::useTable(false),
                    $buildItems($legendMisc)
                )
            )
        )
    )
);

modal
(
    setID('toTask'),
    set::modalProps(array('title' => $lang->bug->selectProjects)),
    to::footer
    (
        div
        (
            setClass('toolbar gap-4 w-full justify-center'),
            btn($lang->bug->nextStep, setID('toTaskButton'), setClass('primary')),
            btn($lang->cancel, setID('cancelButton'), setData(array('dismiss' => 'modal')))
        )
    ),
    formPanel
    (
        on::change('#taskProjects', 'changeTaskProjects'),
        set::actions(''),
        formRow
        (
            formGroup
            (
                set::label($lang->bug->selectProjects),
                set::required(true),
                set::control('picker'),
                set::name('taskProjects'),
                set::items($projects)
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
                    setID('executionBox'),
                    picker
                    (
                        set::name('execution'),
                        set::items($executions)
                    )
                )
            )
        )
    )
);

if(!isInModal())
{
    floatPreNextBtn
    (
        !empty($preAndNext->pre)  ? set::preLink(createLink('bug', 'view', "bugID={$preAndNext->pre->id}"))   : null,
        !empty($preAndNext->next) ? set::nextLink(createLink('bug', 'view', "bugID={$preAndNext->next->id}")) : null
    );
}

render();
