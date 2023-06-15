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
    $stepItemID = $step->type == 'item' ? span(setClass('input-group'), "{$stepID}.{$childID}") : '';

    $steps[] = h::tr
    (
        setClass("step {$stepClass}"),
        h::th
        (
            setClass('step-id'),
            div
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

$linkBugs = array();
$app->loadLang('bug');
if($case->fromBug)
{
    $linkBugs[] = h::tr
    (
        h::td
        (
            span
            (
                setClass('label justify-center rounded-full px-1.5 h-3.5 mr-1.5'),
                $case->fromBug,
            ),
            severityLabel
            (
                setClass('mr-1.5'),
                set::level(zget($lang->bug->severityList, $case->fromBugData->severity)),
                set::isIcon(true)
            ),
            a
            (
                set::href(hasPriv('bug', 'view') ? $this->createLink('bug', 'view', "bugID=$case->fromBug") : ''),
                set('data-toggle', 'modal'),
                $case->fromBugData->title,
            ),
        ),
        h::td
        (
            span
            (
                $lang->testcase->openedDate . ':',
            ),
            $case->fromBugData->openedDate,
        ),
    );
}
foreach($case->toBugs as $bugID => $bug)
{
    $linkBugs[] = h::tr
    (
        h::td
        (
            span
            (
                setClass('label justify-center rounded-full px-1.5 h-3.5 mr-1.5'),
                $bugID,
            ),
            severityLabel
            (
                setClass('mr-1.5'),
                set::level(zget($lang->bug->severityList, $bug->severity)),
                set::isIcon(true)
            ),
            a
            (
                set::href(hasPriv('bug', 'view') ? $this->createLink('bug', 'view', "bugID=$bugID") : ''),
                set('data-toggle', 'modal'),
                $bug->title,
            ),
        ),
        h::td
        (
            span
            (
                $lang->testcase->openedDate . ':',
            ),
            $bug->openedDate,
        ),
    );
}

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
            else if($tab == project)
            {
                $moduleItems[] = $canBrowseProjectTestCase ? a(set::href($this->createLink('project', 'testcase', "projectID={$this->session->project}&productID=$case->product&branch=$module->branch&browseType=byModule&param=$module->id")), $module->name) : $module->name;
            }
            else
            {
                $moduleItems[] = $module->name;
            }
            if(isset($modulePath[$key + 1])) $moduleItems[] = $lang->arrow;
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
        setClass("result-testcase {$case->lastRunResult}"),
        set::name($lang->testtask->lastRunResult),
        $case->lastRunResult ? $lang->testcase->resultList[$case->lastRunResult] : $lang->testcase->unexecuted,
    );

    $linkCases = array();
    if(isset($case->linkCaseTitles))
    {
        foreach($case->linkCaseTitles as $linkCaseID => $linkCaseTitle)
        {
            $linkCases[] = a
            (
                set::href($this->createLink('testcase', 'view', "caseID={$linkCaseID}")),
                set('data-toggle', 'modal'),
                set('title', $linkCaseTitle),
                "#{$linkCaseID} {$linkCaseTitle} <br />",
            );
        }
    }
    $linkCaseItem = item
    (
        setClass('linkCaseTitles'),
        set::name($lang->testcase->linkCase),
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
                setClass('table table-condensed table-hover table-striped table-bordered'),
                set::id('steps'),
                h::thead
                (
                    h::tr
                    (
                        h::th
                        (
                            $lang->testcase->stepID,
                            set::width('50px'),
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
        section
        (
            setClass(empty($linkBugs) ? 'hidden' : ''),
            set::title($lang->testcase->linkBug),
            h::table
            (
                setClass('table table-condensed table-hover table-striped table-bordered'),
                set::id('linkBugs'),
                h::tbody($linkBugs)
            ),
            set::useHtml(true),
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
                    $linkCaseItem,
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
    ),
);

render($isInModal ? 'modalDialog' : 'page');
