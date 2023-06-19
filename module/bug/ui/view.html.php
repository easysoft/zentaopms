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
$mailtoList = explode(',', str_replace(' ', '', $legendBasic['mailto']['text']));
foreach($mailtoList as $account)
{
    $mailtoHTML[] = span(zget($users, $account));
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
    $relatedBugs[] = a
    (
        set('href', $this->createLink('bug', 'view', "bugID={$relatedBugID}")),
        set('data-toggle', 'modal'),
        "#{$relatedBugID} {$relatedBugTitle}"
    );
}

$linkMR = array();
foreach($legendMisc['linkMR']['text'] as $MRID => $linkMRTitle)
{
    $linkMR[] = a
    (
        $canViewMR ? set('href', $this->createLink('mr', 'view', "MRID={$MRID}")) : null,
        "#{$MRID} {$linkMRTitle}"
    );
}

$linkCommits = array();
foreach($legendMisc['linkCommit']['text'] as $commit)
{
    $linkCommits[] = a
    (
        $canViewRepo ? set('href', $this->createLink('repo', 'revision', "repoID={$commit->repo}&objectID=0&revision={$commit->revision}")) : null,
        " {$commit->comment}"
    );
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
