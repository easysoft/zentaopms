<?php
declare(strict_types=1);
/**
 * The story view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */

namespace zin;

data('activeMenuID', $storyType);
jsVar('executionID', $execution->id);
jsVar('childrenAB',  $lang->story->childrenAB);
jsVar('modulePairs', $modulePairs);
jsVar('oldShowGrades', $showGrades);
jsVar('gradeGroup', $gradeGroup);
jsVar('hasProduct',  $execution->hasProduct);
jsVar('linkedTaskStories',  $linkedTaskStories);
jsVar('URChanged',          $lang->story->URChanged);
jsVar('confirmStoryToTask', $lang->execution->confirmStoryToTask);
jsVar('typeNotEmpty',       sprintf($lang->error->notempty, $lang->task->type));
jsVar('hourPointNotEmpty',  sprintf($lang->error->notempty, $lang->story->convertRelations));
jsVar('hourPointNotError',  sprintf($lang->story->float, $lang->story->convertRelations));
jsVar('blockID', $blockID);

$isFromDoc = $from === 'doc';

if($isFromDoc)
{
    $this->app->loadLang('doc');
    $products = $this->loadModel('product')->getPairs();
    $executionChangeLink = createLink('execution', 'story', "executionID={executionID}&storyType=$storyType&orderBy=$orderBy&type=$type&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID");

    jsVar('insertListLink', createLink('execution', 'story', "executionID=$executionID&storyType=$storyType&orderBy=$orderBy&type=$type&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID={blockID}"));

    formPanel
    (
        setID('zentaolist'),
        setClass('mb-4-important'),
        set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList['executionStory'])),
        set::actions(array()),
        to::titleSuffix
        (
            span
            (
                setClass('text-muted text-sm text-gray-600 font-light'),
                span
                (
                    setClass('text-warning mr-1'),
                    icon('help'),
                ),
                $lang->doc->previewTip
            )
        ),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('execution'),
                set::label($lang->doc->execution),
                set::control(array('required' => false)),
                set::items($executions),
                set::value($execution->id),
                set::required(),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                ),
                on::change('[name="execution"]')->do("loadModal('$executionChangeLink'.replace('{executionID}', $(this).val()))")
            )
        )
    );
}

/* Show feature bar. */
$queryMenuLink = createLink($app->rawModule, $app->rawMethod, "&executionID=$execution->id&storyType=$storyType&orderBy=$orderBy&type=bySearch&param={queryID}&recTotal={$pager->recTotal}&recPerPae={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID");

if(empty($param) && $this->cookie->storyModuleParam) $param = $this->cookie->storyModuleParam;
featureBar
(
    $isFromDoc ? null : to::leading
    (
        picker
        (
            set::tree(),
            set::name('showGrades'),
            set::items($gradeMenu),
            set::search(false),
            set::multiple(true),
            set::width('150px'),
            setStyle('justify-content', 'center'),
            set::display($lang->story->viewAllGrades),
            set::menu(array('checkbox' => true, 'itemProps' => array('innerComponent' => 'a'))),
            set::value($showGrades),
            set::toolbar
            (
                array('text' => $lang->confirm, 'onClick' => jsRaw('(e,info) => {setShowGrades();info.relativeTarget.close();}')),
                array('text' => $lang->cancel, 'onClick' => jsRaw('(e,info) => info.relativeTarget.close()')),
            )
        )
    ),
    set::param($param),
    set::searchModule('executionStory'),
    set::current($this->session->storyBrowseType),
    set::link(createLink($app->rawModule, $app->rawMethod, "&executionID=$execution->id&storyType=$storyType&orderBy=$orderBy&type={key}&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from={$from}&blockID={$blockID}")),
    set::queryMenuLinkCallback(array(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink))),
    set::isModal($isFromDoc),
    set::modalTarget('#stories_table'),
    li(searchToggle
    (
        set::simple($isFromDoc),
        set::module('executionStory'),
        set::open($type == 'bysearch'),
        $isFromDoc ? set::target('#docSearchForm') : null
    ))
);

if($isFromDoc) div(setID('docSearchForm'));

$linkStoryByPlanTips = $multiBranch ? sprintf($lang->execution->linkBranchStoryByPlanTips, $lang->project->branch) : $lang->execution->linkNormalStoryByPlanTips;
$linkStoryByPlanTips = $execution->multiple ? $linkStoryByPlanTips : str_replace($lang->execution->common, $lang->projectCommon, $linkStoryByPlanTips);
modal
(
    setID('linkStoryByPlan'),
    set::modalProps(array('title' => $lang->execution->linkStoryByPlan)),
    div
    (
        setClass('flex-auto'),
        icon('info-sign', setClass('warning-pale rounded-full mr-1')),
        $linkStoryByPlanTips
    ),
    form
    (
        setClass('text-center', 'py-4'),
        set::actions(array('submit')),
        set::submitBtnText($lang->execution->linkStory),
        formGroup
        (
            set::label($lang->execution->selectStoryPlan),
            set::required(true),
            setClass('text-left'),
            picker
            (
                set::name('plan'),
                set::required(true),
                set::items($allPlans)
            )
        )
    )
);

/* Show tool bar. */
if(!$product)
{
    $product = new stdclass();
    $product->id = 0;
}

$canModifyProduct                     = common::canModify('product', $product);
$canModifyExecution                   = common::canModify('execution', $execution);
$canOpreate['create']                 = $canModifyProduct && $canModifyExecution && hasPriv('story', 'create');
$canOpreate['batchCreate']            = $canModifyProduct && $canModifyExecution && hasPriv('story', 'batchCreate');
$canOpreate['createEpic']             = $canModifyProduct && $canModifyExecution && hasPriv('epic', 'create') && strpos($project->storyType, 'epic') !== false && $this->config->enableER;
$canOpreate['batchCreateEpic']        = $canModifyProduct && $canModifyExecution && hasPriv('epic', 'batchCreate') && strpos($project->storyType, 'epic') !== false && $this->config->enableER;
$canOpreate['createRequirement']      = $canModifyProduct && $canModifyExecution && hasPriv('requirement', 'create') && strpos($project->storyType, 'requirement') !== false && $this->config->URAndSR;
$canOpreate['batchCreateRequirement'] = $canModifyProduct && $canModifyExecution && hasPriv('requirement', 'batchCreate') && strpos($project->storyType, 'requirement') !== false && $this->config->URAndSR;

$createLink                 = createLink('story', 'create', "product={$product->id}&branch=0&moduleID=0&storyID=0&objectID={$execution->id}&bugID=0&planID=0&todoID=0&extra=&storyType={$storyType}") . "#app={$app->tab}";
$batchCreateLink            = createLink('story', 'batchCreate', "productID={$product->id}&branch=0&moduleID=0&storyID=0&executionID={$execution->id}&plan=0&storyType={$storyType}") . "#app={$app->tab}";
$createEpicLink             = createLink('epic', 'create', "product={$product->id}&branch=0&moduleID=0&storyID=0&objectID={$execution->id}") . "#app={$app->tab}";
$batchCreateEpicLink        = createLink('epic', 'batchCreate', "productID={$product->id}&branch=0&moduleID=0&storyID=0&executionID={$execution->id}") . "#app={$app->tab}";
$createRequirementLink      = createLink('requirement', 'create', "product={$product->id}&branch=0&moduleID=0&storyID=0&objectID={$execution->id}") . "#app={$app->tab}";
$batchCreateRequirementLink = createLink('requirement', 'batchCreate', "productID={$product->id}&branch=0&moduleID=0&storyID=0&executionID={$execution->id}") . "#app={$app->tab}";

/* Tutorial create link. */
if(commonModel::isTutorialMode())
{
    $wizardParams   = helper::safe64Encode("productID={$product->id}&branch=0&moduleID=0");
    $createLink     = $this->createLink('tutorial', 'wizard', "module=story&method=create&params={$wizardParams}");
    $canBatchCreate = false;
}

$createItems = array();
$batchItems  = array();
if($canOpreate['batchCreate']) $batchItems[] = array('text' => $lang->SRCommon, 'url' => $batchCreateLink);
if(in_array($execution->attribute, array('mix', 'request', 'design')) || !$execution->multiple)
{
    if($canOpreate['createRequirement'])      $createItems[] = array('text' => $lang->requirement->create, 'url' => $createRequirementLink);
    if($canOpreate['createEpic'])             $createItems[] = array('text' => $lang->epic->create,  'url' => $createEpicLink);
    if($canOpreate['batchCreateRequirement']) $batchItems[]  = array('text' => $lang->URCommon, 'url' => $batchCreateRequirementLink);
    if($canOpreate['batchCreateEpic'])        $batchItems[]  = array('text' => $lang->ERCommon, 'url' => $batchCreateEpicLink);
}

if(!empty($product->id))
{
    if(count($batchItems) > 1)
    {
        $createItems[] = array('text' => $lang->story->batchCreate, 'items' => $batchItems);
    }
    else
    {
        $batchItems[0]['text'] = $lang->story->batchCreate;
        $createItems = array_merge($createItems, $batchItems);
    }
}

$canLinkStory     = ($execution->hasProduct || $app->tab == 'execution') && $canModifyProduct && $canModifyExecution && hasPriv('execution', 'linkStory');
$canlinkPlanStory = ($execution->hasProduct || $app->tab == 'execution') && $canModifyProduct && $canModifyExecution && hasPriv('execution', 'importPlanStories') && $storyType == 'story';
$linkStoryUrl     = createLink('execution', 'linkStory', "project={$execution->id}&browseType=&param=0&orderBy=id_desc&recPerPage=50&pageID=1&extra=&storyType=$storyType");

if(commonModel::isTutorialMode())
{
    $wizardParams     = helper::safe64Encode("project={$execution->id}");
    $linkStoryUrl     = createLink('tutorial', 'wizard', "module=execution&method=linkStory&params=$wizardParams");
    $canlinkPlanStory = false;
}

$linkItem     = array('text' => $lang->story->linkStory, 'url' => $linkStoryUrl, 'data-app' => $app->tab);
$linkPlanItem = array('text' => $lang->execution->linkStoryByPlan, 'url' => '#linkStoryByPlan', 'data-toggle' => 'modal', 'data-size' => 'sm');

$createBtnGroup = null;
if(!$isFromDoc)
{
    if($canOpreate['create'])
    {
        $createBtnGroup = btngroup
        (
            btn
            (
                setClass('btn secondary'),
                set::icon('plus'),
                set::url($createLink),
                $lang->story->create
            ),
            empty($createItems) ? null : dropdown
            (
                btn(setClass('btn secondary dropdown-toggle'),
                setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
                set::items($createItems),
                set::placement('bottom-end')
            )
        );
    }
    elseif(count($createItems) == 1)
    {
        $createBtnGroup = item(set($createItems[0] + array('class' => 'btn secondary', 'icon' => 'plus')));
    }
}

$product && !$isFromDoc ? toolbar
(
    common::hasPriv('execution', 'storykanban') && $storyType == 'story' ? btnGroup
    (
        btn
        (
            setClass('text-primary font-bold shadow-inner bg-canvas'),
            set::icon('format-list-bulleted'),
            set::hint($lang->execution->list),
            set::url(inlink('story', "executionID={$execution->id}&storyType={$storyType}&orderBy={$orderBy}&type=all")),
            setData('app', $app->tab)
        ),
        btn
        (
            set::icon('kanban'),
            set::hint($lang->execution->kanban),
            set::url($this->createLink('execution', 'storykanban', "executionID={$execution->id}")),
            setData('app', $app->tab)
        ),
    ) : null,
    hasPriv('story', 'report') ? item(set(array
    (
        'text'  => $lang->story->report->common,
        'icon'  => 'bar-chart',
        'class' => 'ghost',
        'url'   => createLink('story', 'report', "productID={$product->id}&branchID=&storyType={$storyType}&browseType={$type}&moduleID={$param}&chartType=pie&projectID={$execution->id}") . "#app={$app->tab}"
    ))) : null,
    hasPriv('story', 'export') && ($linkedProductCount < 2 || $type == 'byproduct' || $type == 'bymodule') ? item(set(array
    (
        'text'        => $lang->export,
        'icon'        => 'export',
        'class'       => 'ghost',
        'url'         => createLink('story', 'export', "productID={$product->id}&orderBy=$orderBy&executionID=$execution->id&browseType=$type&storyType=$storyType"),
        'data-toggle' => 'modal'
    ))) : null,

    $createBtnGroup,

    $canLinkStory && $canlinkPlanStory ? btngroup
    (
        btn(
            setClass('btn primary'),
            set::icon('link'),
            set::url($linkStoryUrl),
            setData('app', $app->tab),
            $lang->story->linkStory
        ),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'),
            setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items(array_filter(array($linkItem, $linkPlanItem))),
            set::placement('bottom-end')
        )
    ) : null,
    $canLinkStory && !$canlinkPlanStory ? item(set($linkItem + array('class' => 'btn primary link-story-btn', 'icon' => 'link'))) : null,
    $canlinkPlanStory && !$canLinkStory ? item(set($linkPlanItem + array('class' => 'btn primary', 'icon' => 'link'))) : null
) : null;

$isFromDoc ? null : sidebar
(
    moduleMenu(set(array(
        'modules'     => $moduleTree,
        'activeKey'   => $type == 'byproduct' ? "p_$param" : $param,
        'settingLink' => !$execution->hasProduct && !$execution->multiple ? createLink('tree', 'browse', "rootID={$product->id}&viewType=story") : null,
        'closeLink'   => $this->createLink('execution', 'story', "executionID={$execution->id}&storyType={$storyType}&orderBy={$orderBy}&type=byModule&param=0"),
        'app'         => !$execution->multiple ? 'project' : '',
        'settingApp'  => !$execution->multiple ? 'project' : ''
    )))
);

modal
(
    setID('taskModal'),
    set::modalProps(array('title' => $lang->story->batchToTask, 'titleClass' => 'flex-initial')),
    to::header
    (
        div
        (
            setClass('flex-auto'),
            icon('info-sign', setClass('warning-pale rounded-full mr-1')),
            $lang->story->batchToTaskTips
        )
    ),
    form
    (
        setClass('text-center', 'py-4'),
        setID('toTaskForm'),
        set::actions(array()),
        set::url(createLink('story', 'batchToTask', "executionID={$execution->id}&projectID={$execution->project}")),
        formGroup
        (
            setClass('text-left'),
            set::label($lang->task->type),
            set::required(true),
            set::width('1/2'),
            picker
            (
                set::required(true),
                set::name('type'),
                set::items($lang->task->typeList)
            )
        ),
        $lang->hourCommon !== $lang->workingHour ? formGroup
        (
            set::label($lang->story->one . $lang->hourCommon),
            set::required(true),
            set::width('1/2'),
            inputGroup
            (
                span
                (
                    setClass('input-group-addon'),
                    "≈ "
                ),
                input(set::name('hourPointValue')),
                span
                (
                    setClass('input-group-addon'),
                    $lang->workingHour
                )
            )
        ) : null,
        formGroup
        (
            set::label($lang->story->field),
            checkList
            (
                set::name('fields[]'),
                set::inline(true),
                set::value(array_keys($lang->story->convertToTask->fieldList)),
                set::items($lang->story->convertToTask->fieldList)
            ),
            input
            (
                set::type('hidden'),
                set::name('storyIdList')
            )
        ),
        formGroup
        (
            setClass('justify-center'),
            btn
            (
                set::text($lang->execution->next),
                set::btnType('submit'),
                set::type('primary')
            )
        )
    )
);

$checkObject = new stdclass();
$checkObject->execution = $execution->id;

$canBatchEdit        = common::hasPriv('story', 'batchEdit');
$canBatchClose       = common::hasPriv('story', 'batchClose') && $storyType != 'requirement';
$canBatchChangeStage = common::hasPriv('story', 'batchChangeStage') && $storyType != 'requirement';
$canBatchUnlink      = ($execution->hasProduct || $app->tab == 'execution') && common::hasPriv('execution', 'batchUnlinkStory');
$canBatchToTask      = common::hasPriv('story', 'batchToTask', $checkObject) && $storyType != 'requirement';
$canBatchAssignTo    = common::hasPriv($storyType, 'batchAssignTo');
$canBatchAction      = $canBeChanged && in_array(true, array($canBatchEdit, $canBatchClose, $canBatchChangeStage, $canBatchUnlink, $canBatchToTask, $canBatchAssignTo));

$footToolbar = array();
if($isFromDoc) $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToDoc"));

if($canBatchAction && !$isFromDoc)
{
    if($canBatchToTask)
    {
        menu
        (
            setID('batchToTask'),
            setClass('dropdown-menu'),
            $canBatchToTask ? item(set(array(
                'text'  => $lang->story->batchToTask,
                'url'   => '#taskModal',
                'data-toggle' => 'modal'
            ))) : null,
        );
    }

    if($canBatchToTask || $canBatchEdit)
    {
        $editClass = $canBatchEdit ? 'batch-btn' : 'disabled';
        $items     = array(array('text' => $lang->edit, 'className' => "btn secondary size-sm {$editClass}", 'btnType' => 'primary', 'data-url' => createLink('story', 'batchEdit', "productID=0&executionID={$execution->id}&branch=0&storyType={$storyType}")));
        if($canBatchToTask) $items[] = array('caret' => 'up', 'className' => 'btn btn-caret size-sm secondary', 'url' => '#batchToTask', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start');
        $footToolbar['items'][] = array(
            'type'  => 'btn-group',
            'items' => $items
        );
    }

    if($canBatchAssignTo)
    {
        $assignedToItems = array();
        foreach ($users as $account => $name)
        {
            if($account == 'closed' || !$name) continue;

            $assignedToItems[] = array(
                'text'       => $name,
                'innerClass' => 'batch-btn ajax-btn',
                'data-url'   => createLink('story', 'batchAssignTo', "toryType={$storyType}&assignedTo={$account}")
            );
        }
    }

    if($canBatchAssignTo)
    {
        $footToolbar['items'][] = array(
            'caret'       => 'up',
            'text'        => $lang->story->assignedTo,
            'className'   => 'btn btn-caret size-sm secondary',
            'type'        => 'dropdown',
            'items'       => $assignedToItems,
            'menu'        => array('searchBox' => true)
        );
    }

    if($canBatchClose)
    {
        $footToolbar['items'][] = array(
            'text'      => $lang->close,
            'className' => 'btn batch-btn size-sm secondary',
            'data-url'  => $this->createLink('story', 'batchClose', "productID=0&executionID={$execution->id}")
        );
    }

    if($canBatchChangeStage)
    {
        $stageItems = array();
        foreach($lang->story->stageList as $stageID => $stage)
        {
            if($stageID == 'delivered' || $stageID == 'delivering') continue;
            $stageItems[] = array(
                'text'       => $stage,
                'innerClass' => 'batch-btn ajax-btn',
                'data-url'   => createLink('story', 'batchChangeStage', "stageID=$stageID")
            );
        }
    }

    if($canBatchChangeStage)
    {
        $footToolbar['items'][] = array(
            'caret'          => 'up',
            'text'           => $lang->story->stageAB,
            'className'      => 'btn btn-caret size-sm secondary',
            'type'           => 'dropdown',
            'items'          => $stageItems,
            'data-placement' => 'top-start'
        );
    }

    if($canBatchUnlink)
    {
        $footToolbar['items'][] = array(
            'text'      => $lang->execution->unlinkStoryAB,
            'className' => 'btn batch-btn ajax-btn size-sm secondary',
            'data-url'  => $this->createLink('execution', 'batchUnlinkStory', "executionID={$execution->id}")
        );
    }
}

/* DataTable columns. */
$config->story->dtable->fieldList['title']['title'] = $lang->story->title;
$cols    = array();
$setting = $this->loadModel('datatable')->getSetting('execution', 'story', false, $storyType);
if(!$canModifyExecution) $setting['actions']['actionsMap'] = array();
if($isFromDoc && isset($setting['actions'])) unset($setting['actions']);
if($storyType == 'requirement') unset($setting['plan'], $setting['stage'], $setting['taskCount'], $setting['bugCount'], $setting['caseCount']);
foreach($setting as $key => $col)
{
    if(!$execution->hasProduct && $col['name'] == 'branch') continue;
    if(!$execution->hasProduct && !$execution->multiple && $col['name'] == 'plan') continue;

    if($col['name'] == 'title')
    {
        $tab = $execution->multiple ? 'execution' : 'project';
        $col['link']  = createLink('execution', 'storyView', array('storyID' => '{id}', 'execution' => $execution->id)) . "#app={$tab}";
        $col['title'] = $this->lang->story->name;
    }

    if($isFromDoc)
    {
        $col['sortType'] = false;
        if(isset($col['link'])) unset($col['link']);
        if($key == 'assignedTo') $col['type'] = 'user';
    }

    $cols[] = $col;
}

/* DataTable data. */
$data        = array();
$actionMenus = array('submitreview', 'recall', 'recalledchange', 'review', 'dropdown', 'createTask', 'batchCreateTask', 'divider', 'storyEstimate', 'testcase', 'batchCreate', 'unlink', 'processStoryChange');
if(empty($execution->hasProduct) && empty($execution->multiple))
{
    $actionMenus = array('submitreview', 'recall', 'recalledchange', 'review', 'dropdown', 'createTask', 'batchCreateTask', 'edit', 'divider', 'storyEstimate', 'testcase', 'batchCreate', 'close', 'processStoryChange');
    if($storyType == 'requirement') $actionMenus = array('change', 'submitreview', 'recall', 'recalledchange', 'review', 'dropdown', 'edit', 'divider', 'batchCreate', 'close');
}
if(!$canModifyExecution) $actionMenus = array();

if($config->edition == 'ipd')
{
    $actionMenus[] = 'confirmDemandRetract';
    $actionMenus[] = 'confirmDemandUnlink';
}
$options = array('storyTasks' => $storyTasks, 'storyBugs' => $storyBugs, 'storyCases' => $storyCases, 'modules' => $modules ?? array(), 'plans' => (isset($plans) ? $plans : array()), 'users' => $users, 'execution' => $execution, 'actionMenus' => $actionMenus, 'branches' => $branchPairs);
foreach($stories as $story)
{
    $story->moduleID = $story->module;
    $story->from     = 'execution';
    $data[] = $this->story->formatStoryForList($story, $options, $storyType, $maxGradeGroup);
    if(!isset($story->children)) continue;
}

jsVar('cases', $storyCases);
jsVar('summary', $summary);
jsVar('checkedSummary', $lang->product->checkedAllSummary);
jsVar('storyType', $storyType);
dtable
(
    setClass('shadow rounded'),
    set::userMap($users),
    set::groupDivider(true),
    set::cols($cols),
    set::data($data),
    set::noNestedCheck(),
    set::footToolbar($footToolbar),
    set::onRenderCell(jsRaw('window.renderStoryCell')),
    set::orderBy($orderBy),
    set::footPager(usePager(array
    (
        'recPerPage'  => $pager->recPerPage,
        'recTotal'    => $pager->recTotal,
        'linkCreator' => helper::createLink('execution', 'story', "executionID={$execution->id}&storyType={$storyType}&orderBy=$orderBy&type={$type}&param={$param}&recTotal={recTotal}&recPerPage={recPerPage}&page={page}") . "#app={$app->tab}"
    ))),
    set::emptyTip($lang->execution->noStory),
    !$isFromDoc ? null : set::afterRender(jsCallback()->call('toggleCheckRows', $idList)),
    !$isFromDoc ? null : set::height(400),
    $isFromDoc ? null : set::customCols(array('url' => createLink('datatable', 'ajaxcustom', "module={$app->moduleName}&method={$app->methodName}&extra={$storyType}"), 'globalUrl' => createLink('datatable', 'ajaxsaveglobal', "module={$app->moduleName}&method={$app->methodName}&extra={$storyType}"))),
    $isFromDoc ? null : set::sortLink(createLink('execution', 'story', "executionID={$execution->id}&storyType={$storyType}&orderBy={name}_{sortType}&type={$type}&param={$param}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&page={$pager->pageID}")),
    $isFromDoc ? null : set::checkInfo(jsRaw('function(checkedIDList){return window.setStatistics(this, checkedIDList);}')),
    $isFromDoc ? null : set::createTip($lang->story->create),
    $isFromDoc ? null : set::createLink($canOpreate['create'] ? $createLink : '')
);

render();
