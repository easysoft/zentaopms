<?php
declare(strict_types=1);
/**
 * The view view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

$canCreateCase = hasPriv('testcase', 'create');

$stepID = $childID = 0;
$steps = array();
foreach($case->steps as $step)
{
    if($step->type == 'group' || $step->type == 'step')
    {
        $stepID++;
        $childID = 0;
    }
    $stepClass  = $step->type == 'step' ? 'step-group' : "step-{$step->type}";
    $stepItemID = $step->type == 'item' ? span(setClass('input-group pr-1'), "{$stepID}.{$childID}") : '';

    $steps[] = h::tr
    (
        setClass("step {$stepClass}"),
        h::td
        (
            setClass('step-id text-center'),
            span
            (
                setClass($step->type == 'item' ? 'hidden' : ''),
                $stepID,
            ),
        ),
        h::td
        (
            setClass('text-left'),
            div
            (
                setClass('input-group'),
                $stepItemID,
                nl2br(str_replace(' ', '&nbsp;', $step->desc)),
            ),
        ),
        h::td
        (
            setClass('text-left'),
            nl2br(str_replace(' ', '&nbsp;', $step->expect)),
        ),
    );

    $childID ++;
}

$files = '';
foreach($case->files as $file) $files .= $file->title . ',';

/* Get data in legend of basic information. */
$app->loadLang('product');
$productLink = $case->product && hasPriv('product', 'view') ? $this->createLink('product', 'view', "productID={$case->product}") : '';
$branchLink  = $case->branch  && hasPriv('testcase', 'browse') ? $this->createLink('testcase', 'browse', "productID={$case->product}&branch={$case->branch}") : '';

$fromCaseItem      = array();
$libItem           = array();
$productItem       = array();
$branchItem        = array();
$moduleItem        = array();
$storyItem         = array();
$lastRunTimeItem   = array();
$lastRunResultItem = array();
$linkCaseItem      = array();
$caseStage         = array();
$caseChange        = array();
if($isLibCase)
{
    $linkCaseTitles = array();
    foreach($case->linkCaseTitles as $linkCaseID => $linkCaseTitle)
    {
        $linkCaseTitles[] = a
        (
            set::href($this->createLink('testcase', 'view', "caseID={$linkCaseID}", '', true)),
            set('data-toggle', 'modal'),
            "#{$linkCaseID} {$linkCaseTitle}",
        );
    }
    $fromCaseItem = item
    (
        set::name($lang->testcase->fromCase),
        $linkCaseTitles,
    );

    $libItem = item
    (
        set::name($lang->testcase->lib),
        hasPriv('caselib', 'browse') ? a(set::href($this->createLink('caselib', 'browse', "libID={$case->lib}")), $libName) : $libName,
    );
}
else
{

    $productItem = item
    (
        set::name($lang->testcase->product),
        set::href($productLink),
        $product->name,
    );

    if($product->type != 'normal')
    {
        $branchItem = item
        (
            set::name(sprintf($lang->product->branch, $lang->product->branchName[$product->type])),
            set::href($branchLink),
            $branchName,
        );
    }

    $tab         = $app->tab;
    $moduleItems = array();
    if(!empty($modulePath))
    {
        $canBrowseCaselib         = hasPriv('caselib', 'browse');
        $canBrowseTestCase        = hasPriv('testcase', 'browse');
        $canBrowseProjectTestCase = hasPriv('testcase', 'browse');

        if($caseModule->branch && isset($branches[$caseModule->branch])) $moduleItems[] = $branches[$caseModule->branch] . $lang->arrow;
        foreach($modulePath as $key => $module)
        {
            if($tab == 'qa' || $tab == 'ops')
            {
                if($isLibCase)
                {
                    $moduleItems[] = $canBrowseCaselib ? a(set::href($this->createLink('caselib', 'browse', "libID={$case->lib}&browseType=byModule&param={$module->id}")), $module->name) : $module->name;
                }
                else
                {
                    $moduleItems[] = $canBrowseTestCase ? a(set::href($this->createLink('testcase', 'browse', "productID={$case->product}&branch={$module->branch}&browseType=byModule&param={$module->id}")), $module->name) : $module->name;
                }
            }
            else if($tab == 'project')
            {
                $moduleItems[] = $canBrowseProjectTestCase ? a(set::href($this->createLink('project', 'testcase', "projectID={$this->session->project}&productID=$case->product&branch=$module->branch&browseType=byModule&param=$module->id")), $module->name) : $module->name;
            }
            else
            {
                $moduleItems[] = $module->name;
            }
            if(isset($modulePath[$key + 1])) $moduleItems[] = ' / ';
        }

    }
    $moduleItem = item
    (
        set::name($lang->testcase->module),
        empty($modulePath) ? '/' : $moduleItems,
    );

    $param = $tab == 'project' ? "&version=0&projectID={$this->session->project}" : '';
    $confirmStatusChange = '';
    if($case->story && $case->storyStatus == 'active' && $case->latestStoryVersion > $case->storyVersion)
    {
        $confirmStatusChange = span
            (
                setClass('warning'),
                $lang->testcase->changed,
                common::hasPriv('testcase', 'confirmStoryChange', $case) ? a(set::href($this->createLink('testcase', 'confirmStoryChange', "caseID={$case->id}")), $lang->confirm) : '',
            );
    }
    $storyItem = item
    (
        set::name($lang->testcase->story),
        isset($case->storyTitle) && hasPriv('story', 'view') ? a(set::href($this->createLink('story', 'view', "storyID={$case->story}{$param}")), set('data-toggle', 'modal'), "#{$case->story}:{$case->storyTitle}") : (isset($case->storyTitle) ? "#{$case->story}:{$case->storyTitle}" : ''),
        set::labelProps(array('data-toggle' => 'modal', 'data-size' => '1200')),
        $confirmStatusChange,
    );

    $app->loadLang('testtask');
    $lastRunTimeItem = item
    (
        set::name($lang->testtask->lastRunTime),
        !helper::isZeroDate($case->lastRunDate) ? $case->lastRunDate : '',
    );

    $lastRunResultItem = item
    (
        setClass("result-testcase status-{$case->lastRunResult}"),
        set::name($lang->testtask->lastRunResult),
        $case->lastRunResult ? $lang->testcase->resultList[$case->lastRunResult] : $lang->testcase->unexecuted,
    );

    $linkBugs = array();
    if($case->fromBug)
    {
        $linkBugs[] = entityLabel
        (
            set::href($this->createLink('bug', 'view', "bugID={$case->fromBug}")),
            set::level(4),
            set::entityID($case->fromBug),
            set::text($case->fromBugData->title),
            set::labelProps(array('data-toggle' => 'modal', 'data-size' => '1200')),
            set('title', $case->fromBugData->title),
        );
    }
    if($case->toBugs)
    {
        foreach($case->toBugs as $bugID => $bug)
        {
            $linkBugs[] = entityLabel
            (
                set::href($this->createLink('bug', 'view', "bugID={$bugID}")),
                set::level(4),
                set::entityID($bugID),
                set::text($bug->title),
                set::labelProps(array('data-toggle' => 'modal', 'data-size' => '1200')),
                set('title', $bug->title),
            );
        }
    }
    $linkBugsItem = item
    (
        setClass('linkBugTitles'),
        set::collapse(true),
        set::name($lang->testcase->legendLinkBugs),
        $linkBugs,
    );

    $linkCases = array();
    if(isset($case->linkCaseTitles))
    {
        foreach($case->linkCaseTitles as $linkCaseID => $linkCaseTitle)
        {
            $linkCases[] = entityLabel
            (
                set::href($this->createLink('testcase', 'view', "caseID={$linkCaseID}")),
                set::level(4),
                set::entityID($linkCaseID),
                set::text($case->fromBugData->linkCaseTitle),
                set::labelProps(array('data-toggle' => 'modal', 'data-size' => '1200')),
                set('title', $linkCaseTitle),
            );
        }
    }
    $linkCaseItem = item
    (
        setClass('linkCaseTitles'),
        set::name($lang->testcase->linkCase),
        set::collapse(true),
        $linkCases,
    );
}

if($case->stage)
{
    foreach(explode(',', $case->stage) as $stage)
    {
        if(empty($stage)) continue;
        $caseStage[] = zget($lang->testcase->stageList, $stage) . '<br />';
    }

}

if($case->version > $case->currentVersion && $from == 'testtask')
{
    $caseChange[] = span
    (
        setClass('warning'),
        set('title', $lang->testcase->fromTesttask),
        $lang->testcase->changed,
        hasPriv('testcase', 'confirmchange') ? a(setClass('btn btn-mini btn-info'), set::href($this->createLink('testcase', 'confirmchange', "caseID={$case->id}&taskID={$taskID}")), $lang->testcase->sync) : '',
    );
}
if(isset($case->fromCaseVersion) && $case->fromCaseVersion > $case->version && $from != 'testtask' && !empty($case->product))
{
    $caseChange[] = span
    (
        setClass('warning'),
        set('title', $lang->testcase->fromCaselib),
        $lang->testcase->changed,
        hasPriv('testcase', 'confirmLibcaseChange') ? a(setClass('btn btn-mini btn-info'), set::href($this->createLink('testcase', 'confirmLibcaseChange', "caseID={$case->id}&libcaseID={$case->fromCaseID}")), $lang->testcase->sync) : '',
        hasPriv('testcase', 'ignoreLibcaseChange') ? a(setClass('btn btn-mini btn-info'), set::href($this->createLink('testcase', 'ignoreLibcaseChange', "caseID={$case->id}")), $lang->testcase->ignore) : '',
    );
}

/* Get data in legend of open and edit. */
$reviewedBy = '';
foreach(explode(',', $case->reviewedBy) as $account)
{
    $reviewedBy .= ' ' . zget($users, trim($account));
}
$reviewedBy = trim($reviewedBy);

$isInModal = isAjaxRequest('modal');
detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID(17),
            set::level(1),
            set::text($case->title)
        )
    ),
    $isInModal ? to::prefix('') : null,
    !$isInModal ? to::suffix
    (
        btn
        (
            set::icon('plus'),
            set::type('primary'),
            set::text($lang->case->create),
            $canCreateCase ? set::url($this->createLink('testcase', 'create', "productID={$case->product}&branch={$case->branch}&moduleID={$case->module}")) : null
        )
    ) : null
);

detailBody
(
    sectionList
    (
        section
        (
            setClass(empty($case->precondition) ? 'hidden' : ''),
            set::title($lang->testcase->precondition),
            set::content(nl2br($case->precondition)),
            set::useHtml(true),
        ),
        section
        (
            set::title($lang->testcase->steps),
            h::table
            (
                setClass('table condensed bordered'),
                set::id('steps'),
                h::thead
                (
                    h::tr
                    (
                        h::th
                        (
                            $lang->testcase->stepID,
                            set::width('60px'),
                        ),
                        h::th
                        (
                            $lang->testcase->stepDesc,
                            setClass('text-left'),
                        ),
                        h::th
                        (
                            $lang->testcase->stepExpect,
                            setClass('text-left'),
                        ),
                    ),
                ),
                h::tbody($steps)
            ),
            set::useHtml(true),
        ),
        section
        (
            setClass(empty($files) ? 'hidden' : ''),
            set::title($lang->files),
            set::content(trim($files, ',')),
            set::useHtml(true),
        ),
        history(),
        center
        (
            floatToolbar
            (
                !$isInModal ? set::prefix
                (
                    array(array('icon' => 'back', 'text' => $lang->goback))
                ) : null,
                set::main($this->testcase->buildOperateMenu($case, 'view')),
                set::suffix
                (
                    array
                    (
                        array('icon' => 'edit',  'url' => $this->createLink('testcase', 'edit',   "caseID={$case->id}")),
                        array('icon' => 'copy',  'url' => $this->createLink('testcase', 'create', "productID={$case->product}&branch={$case->branch}&moduleID={$case->module}&from=testcase&param={$case->id}")),
                        array('icon' => 'trash', 'url' => $this->createLink('testcase', 'delete', "caseID={$case->id}")),
                    ),
                ),
            ),
        ),
    ),
    detailSide
    (
        tabs
        (
            set::collapse(true),
            tabPane
            (
                set::key('legendBasicInfo'),
                set::title($lang->testcase->legendBasicInfo),
                set::active(true),
                tableData
                (
                    $fromCaseItem,
                    $libItem,
                    $productItem,
                    $branchItem,
                    $moduleItem,
                    $storyItem,
                    item
                    (
                        set::name($lang->testcase->type),
                        zget($lang->case->typeList, $case->type),
                    ),
                    item
                    (
                        set::name($lang->testcase->stage),
                        $caseStage,
                    ),
                    item
                    (
                        set::name($lang->testcase->pri),
                        priLabel(zget($lang->case->priList, $case->pri)),
                    ),
                    item
                    (
                        set::name($lang->testcase->status),
                        $this->processStatus('testcase', $case),
                        $caseChange,
                    ),
                    $lastRunTimeItem,
                    $lastRunResultItem,
                    item
                    (
                        set::name($lang->testcase->keywords),
                        $case->keywords,
                    ),
                ),
            ),
            tabPane
            (
                set::key('legendOpenAndEdit'),
                set::title($lang->testcase->legendOpenAndEdit),
                tableData
                (
                    item
                    (
                        set::name($lang->testcase->openedBy),
                        zget($users, $case->openedBy) . $lang->at . $case->openedDate,
                    ),
                    item
                    (
                        set::name($lang->testcase->reviewedBy),
                        $reviewedBy,
                    ),
                    item
                    (
                        set::name($lang->testcase->reviewedDate),
                        !empty($case->reviewedBy) ? $case->reviewedDate : '',
                    ),
                    item
                    (
                        set::name($lang->testcase->lblLastEdited),
                        !empty($case->lastEditedBy) ? zget($users, $case->lastEditedBy) . $lang->at . $case->lastEditedDate : '',
                    ),
                )
            )
        ),
        tabs
        (
            set::collapse(true),
            tabPane
            (
                set::key('otherReleted'),
                set::title($lang->testcase->legendOther),
                set::active(true),
                tableData
                (
                    set::useTable(false),
                    $linkBugsItem,
                    $linkCaseItem,
                ),
            ),
        ),
    ),
);

render($isInModal ? 'modalDialog' : 'page');
