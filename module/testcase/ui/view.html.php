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

jsVar('viewParams', "caseID={$case->id}&version={$version}&from={$from}&taskID={$taskID}&stepsType=");

$steps = array();
if($stepsType == 'table')
{
    foreach($case->steps as $step)
    {
        $stepClass = $step->type == 'step' ? 'step-group' : "step-{$step->type}";
        $stepClass .= count($steps) > 0 && $step->grade == 1 ? ' mt-2' : ' border-t-0';

        $steps[] = cell
            (
                setClass("step {$stepClass} border align-top flex"),
                cell
                (
                    setClass('text-left flex border-r step-id'),
                    width('1/2'),
                    span
                    (
                        setClass('pr-2 pl-' . (($step->grade - 1) * 2)),
                        $step->name
                    ),
                    html(nl2br(str_replace(' ', '&nbsp;', $step->desc)))
                ),
                cell
                (
                    setClass('text-left flex'),
                    width('1/2'),
                    html(nl2br(str_replace(' ', '&nbsp;', $step->expect)))
                )
            );
    }
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
    if(!isset($case->linkCaseTitles)) $case->linkCaseTitles = array();

    $linkCaseTitles = array();
    foreach($case->linkCaseTitles as $linkCaseID => $linkCaseTitle)
    {
        $linkCaseTitles[] = a
        (
            set::href($this->createLink('testcase', 'view', "caseID={$linkCaseID}", '', true)),
            setData(array('toggle' => 'modal')),
            "#{$linkCaseID} {$linkCaseTitle}"
        );
    }
    $fromCaseItem = item
    (
        set::name($lang->testcase->fromCase),
        $linkCaseTitles
    );

    $libItem = item
    (
        set::name($lang->testcase->lib),
        hasPriv('caselib', 'browse') ? a(set::href($this->createLink('caselib', 'browse', "libID={$case->lib}")), $libName) : $libName
    );

    $mainActions   = array();
    $suffixActions = array();
    if($case->needconfirm)
    {
        if(hasPriv('testcase', 'confirmstorychange')) $mainActions[] = array('icon' => 'view', 'text' => $lang->confirm, 'hint' => $lang->confirm, 'url' => createLink('testcase', 'confirmstorychange', "caseID={$case->id}"), 'className' => 'ajax-submit');
    }
    else
    {
        if(($this->config->testcase->needReview || !empty($this->config->testcase->forceReview)) && hasPriv('testcase', 'review') && $case->status == 'wait') $mainActions[] = array('icon' => 'glasses', 'text' => $lang->testcase->reviewAB, 'hint' => $lang->testcase->reviewAB, 'url' => createLink('testcase', 'review', "caseID={$case->id}"), 'data-toggle' => 'modal');
        if(!isAjaxRequest('modal'))
        {
            if(hasPriv('testcase', 'edit'))
            {
                $editParams = "caseID={$case->id}";
                if($this->app->tab == 'project')   $editParams .= "&comment=false&projectID={$this->session->project}";
                if($this->app->tab == 'execution') $editParams .= "&comment=false&executionID={$this->session->execution}";
                $suffixActions[] = array('icon' => 'edit', 'text' => '', 'hint' => $lang->testcase->edit, 'url' => createLink('testcase', 'edit', $editParams));
            }
        }
        if(hasPriv('caselib', 'createCase')) $mainActions[] = array('icon' => 'copy', 'text' => '', 'hint' => $lang->testcase->copy, 'url' => createLink('caselib', 'createCase', "libID={$case->lib}&moduleID={$case->module}&param={$case->id}"));
        $suffixActions[] = array('icon' => 'trash', 'text' => '', 'hint' => $lang->testcase->delete, 'url' => createLink('testcase', 'delete', "caseID={$case->id}"), 'className' => 'ajax-submit', 'data-confirm' => $lang->testcase->confirmDelete);
    }
    $actions = array('mainActions' => $mainActions, 'suffixActions' => $suffixActions);
}
else
{

    $productItem = item
    (
        set::name($lang->testcase->product),
        set::href($productLink),
        $product->name
    );

    if($product->type != 'normal')
    {
        $branchItem = item
        (
            set::name(sprintf($lang->product->branch, $lang->product->branchName[$product->type])),
            set::href($branchLink),
            $branchName
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
            if(isset($modulePath[$key + 1])) $moduleItems[] = icon('angle-right');
        }

    }
    $moduleItem = item
    (
        set::name($lang->testcase->module),
        empty($modulePath) ? '/' : $moduleItems
    );

    $param = $tab == 'project' ? "&version=0&projectID={$this->session->project}" : '';
    $confirmStatusChange = '';
    if($case->story && $case->storyStatus == 'active' && $case->latestStoryVersion > $case->storyVersion)
    {
        $confirmStatusChange = span
            (
                setClass('warning'),
                $lang->story->changed,
                common::hasPriv('testcase', 'confirmStoryChange', $case) ? a(set::href($this->createLink('testcase', 'confirmStoryChange', "caseID={$case->id}")), setData('app', $app->tab), $lang->confirm) : ''
            );
    }
    $storyItem = item
    (
        set::name($lang->testcase->story),
        isset($case->storyTitle) && hasPriv('story', 'view') ? a(set::href($this->createLink('story', 'view', "storyID={$case->story}{$param}")), setData(array('toggle' => 'modal', 'size' => 'lg')), "#{$case->story}:{$case->storyTitle}") : (isset($case->storyTitle) ? "#{$case->story}:{$case->storyTitle}" : ''), set::labelProps(array('data-toggle' => 'modal', 'data-size' => 'lg')),
        $confirmStatusChange
    );

    $app->loadLang('testtask');
    $lastRunTimeItem = item
    (
        set::name($lang->testtask->lastRunTime),
        !helper::isZeroDate($case->lastRunDate) ? $case->lastRunDate : ''
    );

    $lastRunResultItem = item
    (
        set::tdClass("result-testcase status-{$case->lastRunResult}"),
        set::name($lang->testtask->lastRunResult),
        $case->lastRunResult ? $lang->testcase->resultList[$case->lastRunResult] : $lang->testcase->unexecuted
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
            set::labelProps(array('data-toggle' => 'modal', 'data-size' => 'lg')),
            set('title', $case->fromBugData->title)
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
                set::labelProps(array('data-toggle' => 'modal', 'data-size' => 'lg')),
                set('title', $bug->title)
            );
        }
    }
    $linkBugItem = item
    (
        set::tdClass('linkBugTitles'),
        !empty($linkBugs) ? set::collapse(true) : '',
        set::name($lang->testcase->legendLinkBugs),
        $linkBugs
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
                set::text($linkCaseTitle),
                set::labelProps(array('data-toggle' => 'modal', 'data-size' => 'lg')),
                set('title', $linkCaseTitle)
            );
        }
    }
    $linkCaseItem = item
    (
        set::tdClass('linkCaseTitles'),
        set::name($lang->testcase->linkCase),
        !empty($linkCases) ? set::collapse(true) : '',
        $linkCases
    );

    $actions = $this->loadModel('common')->buildOperateMenu($case);
    foreach($actions as $actionType => $typeActions)
    {
        foreach($typeActions as $index => $action)
        {
            if(!isset($action['url'])) continue;
            $actions[$actionType][$index]['url'] = str_replace('%executionID%', (string)$this->session->execution, $action['url']);
        }
    }
}

if($case->stage)
{
    foreach(explode(',', $case->stage) as $stage)
    {
        if(empty($stage)) continue;
        $caseStage[] = div(zget($lang->testcase->stageList, $stage));
    }

}

if(isset($case->fromCaseVersion) && $case->fromCaseVersion > $case->version && $from != 'testtask' && !empty($case->product))
{
    $caseChange[] = span
    (
        set('title', $lang->testcase->fromCaselib),
        ' (',
        $lang->testcase->changed,
        hasPriv('testcase', 'confirmLibcaseChange') ? a(setClass('btn size-xs primary-pale mx-1 ajax-submit'), set::href($this->createLink('testcase', 'confirmLibcaseChange', "caseID={$case->id}&libcaseID={$case->fromCaseID}")), $lang->testcase->sync) : '',
        hasPriv('testcase', 'ignoreLibcaseChange') ? a(setClass('btn size-xs primary-pale mx-1 ajax-submit'), set::href($this->createLink('testcase', 'ignoreLibcaseChange', "caseID={$case->id}")), $lang->testcase->ignore) : '',
        ')'
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
$versions  = array();
for($i = $case->version; $i >= 1; $i --) $versions[] = array('text' => "#{$i}", 'url' => inlink('view', "caseID={$case->id}&version={$i}"), 'active' => $i == $version);
detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($case->id),
            set::level(1),
            span(setStyle('color', $case->color), $case->title)
        ),
        count($versions) > 1 ? dropdown
        (
            btn(setClass('btn-link'), "#{$version}"),
            set::items($versions)
        ) : null,
        $case->deleted ? span(setClass('label danger'), $lang->case->deleted) : null
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
        on::click('.steps-section .step-change-view-btn .icon', 'toggleStepsView'),
        section
        (
            setClass(empty($case->precondition) ? 'hidden' : ''),
            set::title($lang->testcase->precondition),
            set::content(nl2br($case->precondition)),
            set::useHtml(true)
        ),
        section
        (
            setClass('steps-section'),
            set::title($lang->testcase->steps),
            !empty($case->steps) ? to::actions
            (
                row
                (
                    setClass('step-change-view ml-2 py-1 border'),
                    width('fit'),
                    cell
                    (
                        setClass('px-1.5 leading-4 border-r step-change-view-btn'),
                        $stepsType == 'table' ? setClass('text-primary') : '',
                        icon
                        (
                            set::size('9'),
                            'table-large'
                        )
                    ),
                    cell
                    (
                        setClass('px-1.5 leading-4 step-change-view-btn'),
                        $stepsType != 'table' ? setClass('text-primary') : '',
                        icon
                        (
                            set::size('9'),
                            'tree'
                        )
                    )
                )
            ) : null,
            !empty($case->steps) ? div
            (
                $stepsType == 'table' ? div
                (
                    setID('stepsTable'),
                    div
                    (
                        setClass('steps-header'),
                        div
                        (
                            setClass('text-left inline-block steps border'),
                            width('1/2'),
                            $lang->testcase->stepDesc
                        ),
                        div
                        (
                            setClass('text-left inline-block border border-l-0'),
                            width('1/2'),
                            $lang->testcase->stepExpect
                        )
                    ),
                    div
                    (
                        setClass('steps-body'),
                        $steps
                    )
                ) : div
                (
                    setID('stepsView'),
                    mindmap
                    (
                        set::data($case->mindMapSteps),
                        set::readonly(true)
                    )
                )
            ) : div
            (
                setClass('canvas text-center py-2'),
                p
                (
                    setClass('py-2 my-2'),
                    span
                    (
                        setClass('text-gray'),
                        $lang->noData
                    )
                )
            ),
            set::useHtml(true)
        ),
        $case->files ? fileList
        (
            set::files($case->files),
            set::padding(false)
        ) : null
    ),
    history(set::objectID($case->id)),
    floatToolbar
    (
        set::object($case),
        $isInModal ? null : to::prefix(backBtn(set::icon('back'), setClass('ghost text-white'), $lang->goback)),
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
                set::key('legendBasicInfo' . $case->id),
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
                        zget($lang->case->typeList, $case->type)
                    ),
                    item
                    (
                        set::name($lang->testcase->stage),
                        $caseStage
                    ),
                    item
                    (
                        set::name($lang->testcase->pri),
                        priLabel($case->pri, set::text($lang->case->priList))
                    ),
                    item
                    (
                        set::name($lang->testcase->status),
                        $this->processStatus('testcase', $case),
                        $caseChange
                    ),
                    $lastRunTimeItem,
                    $lastRunResultItem,
                    item
                    (
                        set::name($lang->testcase->keywords),
                        $case->keywords
                    )
                )
            ),
            tabPane
            (
                set::key('legendOpenAndEdit' . $case->id),
                set::title($lang->testcase->legendOpenAndEdit),
                tableData
                (
                    item
                    (
                        set::name($lang->testcase->openedBy),
                        zget($users, $case->openedBy) . $lang->at . $case->openedDate
                    ),
                    item
                    (
                        set::name($lang->testcase->reviewedBy),
                        $reviewedBy
                    ),
                    item
                    (
                        set::name($lang->testcase->reviewedDate),
                        !empty($case->reviewedBy) ? $case->reviewedDate : ''
                    ),
                    item
                    (
                        set::name($lang->testcase->lblLastEdited),
                        !empty($case->lastEditedBy) ? zget($users, $case->lastEditedBy) . $lang->at . $case->lastEditedDate : ''
                    )
                )
            )
        ),
        tabs
        (
            isset($linkBugItem) || $linkCaseItem ? set::collapse(true) : true,
            tabPane
            (
                set::key('otherReleted' . $case->id),
                set::title($lang->testcase->legendOther),
                set::active(true),
                tableData
                (
                    set::useTable(false),
                    isset($linkBugItem) ? $linkBugItem : null,
                    $linkCaseItem
                )
            )
        )
    )
);

if(!isInModal())
{
    floatPreNextBtn
        (
            !empty($preAndNext->pre)  ? set::preLink(createLink('testcase', 'view', "testcaseID={$preAndNext->pre->id}"))   : null,
            !empty($preAndNext->next) ? set::nextLink(createLink('testcase', 'view', "testcaseID={$preAndNext->next->id}")) : null
        );
}

render();
