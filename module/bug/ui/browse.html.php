<?php
declare(strict_types=1);
/**
 * The browse view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('productID',      $product->id);
jsVar('branch',         $branch);
jsVar('today',          date('Y-m-d'));
jsVar('caseCommonLang', $this->lang->testcase->common);
jsVar('from',           $from);
jsVar('blockID',        $blockID);

$queryMenuLink = createLink('bug', 'browse', "productID={$product->id}&branch={$branch}&browseType=bySearch&param={queryID}");
$currentType   = $browseType == 'bysearch' ? $param : ($browseType == 'bymodule' ? $this->session->bugBrowseType : $browseType);
$isFromDoc     = $from === 'doc';

if($isFromDoc)
{
    $this->app->loadLang('doc');
    $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
    $productChangeLink = createLink($app->rawModule, $app->rawMethod, "productID={productID}&branch=$branch&browseType=$browseType&param=$param&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID");

    jsVar('insertListLink', createLink($app->rawModule, $app->rawMethod, "productID=$product->id&branch=$branch&browseType=$browseType&param=$param&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID={blockID}"));

    formPanel
    (
        setID('zentaolist'),
        setClass('mb-4-important'),
        set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList['productBug'])),
        set::actions(array()),
        set::showExtra(false),
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
                set::name('product'),
                set::label($lang->doc->product),
                set::control(array('required' => false)),
                set::items($products),
                set::value($product->id),
                set::required(),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                ),
                on::change('[name="product"]')->do("loadModal('$productChangeLink'.replace('{productID}', $(this).val()))")
            )
        )
    );
}

featureBar
(
    set::current($currentType),
    set::linkParams("product={$product->id}&branch={$branch}&browseType={key}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID"),
    set::isModal($isFromDoc),
    set::queryMenuLinkCallback(array(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink))),
    li(searchToggle
    (
        set::simple($isFromDoc),
        set::open($browseType == 'bysearch'),
        $isFromDoc ? set::target('#docSearchForm') : null
    ))
);

if($isFromDoc)
{
    div(setID('docSearchForm'));
}

$canBeChanged         = common::canModify('product', $product);
$canBatchEdit         = $canBeChanged && hasPriv('bug', 'batchEdit');
$canBatchConfirm      = $canBeChanged && hasPriv('bug', 'batchConfirm');
$canBatchActivate     = $canBeChanged && hasPriv('bug', 'batchActivate');
$canBatchChangeBranch = $canBeChanged && hasPriv('bug', 'batchChangeBranch');
$canBatchChangeModule = $canBeChanged && hasPriv('bug', 'batchChangeModule');
$canBatchResolve      = $canBeChanged && hasPriv('bug', 'batchResolve');
$canBatchAssignTo     = $canBeChanged && hasPriv('bug', 'batchAssignTo');
$canBatchClose        = hasPriv('bug', 'batchClose');
$canManageModule      = hasPriv('tree', 'browse');
$canBatchAction       = $canBatchEdit || $canBatchConfirm || $canBatchClose || $canBatchActivate || $canBatchChangeBranch || $canBatchChangeModule || $canBatchResolve || $canBatchAssignTo;

if(!isonlybody())
{
    $canCreate      = false;
    $canBatchCreate = false;
    if($canBeChanged)
    {
        $canCreate      = hasPriv('bug', 'create');
        $canBatchCreate = hasPriv('bug', 'batchCreate');

        $selectedBranch  = $branch != 'all' ? $branch : 0;
        $createLink      = $this->createLink('bug', 'create', "productID={$product->id}&branch=$selectedBranch&extra=moduleID=$currentModuleID");
        $batchCreateLink = $this->createLink('bug', 'batchCreate', "productID={$product->id}&branch=$branch&executionID=0&moduleID=$currentModuleID");
        if(commonModel::isTutorialMode())
        {
            $wizardParams = helper::safe64Encode("productID={$product->id}&branch=$branch&extra=moduleID=$currentModuleID");
            $createLink   = $this->createLink('tutorial', 'wizard', "module=bug&method=create&params=$wizardParams");
        }

        $createItem      = array('text' => $lang->bug->create,      'url' => $createLink);
        $batchCreateItem = array('text' => $lang->bug->batchCreate, 'url' => $batchCreateLink);
    }

    toolbar
    (
        setClass(array('hidden' => $isFromDoc)),
        hasPriv('bug', 'report') ? item(set(array
        (
            'icon'  => 'bar-chart',
            'text'  => $lang->bug->report->common,
            'class' => 'ghost',
            'url'   => createLink('bug', 'report', "productID=$product->id&browseType=$browseType&branch=$branch&module=$currentModuleID")
        ))) : null,
        hasPriv('bug', 'export') ? item(set(array
        (
            'text'  => $lang->export,
            'icon'  => 'export',
            'class' => 'ghost',
            'url'   => createLink('bug', 'export', "productID={$product->id}&browseType={$browseType}"),
            'data-toggle' => 'modal'
        ))) : null,
        $canCreate && $canBatchCreate ? btngroup
        (
            btn(setClass('btn primary create-bug-btn'), set::icon('plus'), set::url($createLink), $lang->bug->create),
            dropdown
            (
                btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
                set::items(array($createItem, $batchCreateItem)),
                set::placement('bottom-end')
            )
        ) : null,
        $canCreate && !$canBatchCreate ? item(set($createItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null,
        $canBatchCreate && !$canCreate ? item(set($batchCreateItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null
    );
}

$closeLink   = createLink('bug', 'browse', "productID={$product->id}&branch={$branch}&browseType=byModule&param=0&orderBy={$orderBy}&recTotal=0&recPerPage={$pager->recPerPage}");
$settingLink = $canManageModule ? createLink('tree', 'browse', "productID={$product->id}&view=bug&currentModuleID=0&branch=0&from={$this->lang->navGroup->bug}") : '';


if(!$isFromDoc)
{
    sidebar
    (
        moduleMenu(set(array
        (
            'modules'     => $moduleTree,
            'activeKey'   => $currentModuleID,
            'closeLink'   => $closeLink,
            'settingLink' => $settingLink
        )))
    );
}

$resolveItems = array();
foreach($lang->bug->resolutionList as $key => $resolution)
{
    if(empty($key) || $key == 'duplicate' || $key == 'tostory') continue;
    if($key == 'fixed')
    {
        $buildItems = array();
        foreach($builds as $key => $build) $buildItems[] = array('text' => $build, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => createLink('bug', 'batchResolve', "resolution=fixed&resolvedBuild=$key"));

        $resolveItems[] = array('text' => $resolution, 'class' => 'not-hide-menu', 'items' => $buildItems);
    }
    else
    {
        $resolveItems[] = array('text' => $resolution, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => createLink('bug', 'batchResolve', "resolution=$key"));
    }
}

$batchItems = array
(
    array('text' => $lang->bug->confirm,  'innerClass' => 'batch-btn ajax-btn not-open-url ' . ($canBatchConfirm ? '' : 'hidden'), 'data-url' => helper::createLink('bug', 'batchConfirm')),
    array('text' => $lang->bug->close,    'innerClass' => 'batch-btn ajax-btn not-open-url ' . ($canBatchClose ? '' : 'hidden'), 'data-url' => helper::createLink('bug', 'batchClose')),
    array('text' => $lang->bug->activate, 'innerClass' => 'batch-btn not-open-url ' . ($canBatchActivate ? '' : 'hidden'), 'data-url' => helper::createLink('bug', 'batchActivate', "productID=$product->id&branch=$branch")),
    array('text' => $lang->bug->resolve,  'innerClass' => 'not-hide-menu ' . ($canBatchResolve ? '' : 'hidden'), 'items' => $resolveItems)
);

$branchItems = array();
foreach($branchTagOption as $branchID => $branchName)
{
    $branchItems[] = array('text' => $branchName, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('bug', 'batchChangeBranch', "branchID=$branchID"));
}

$moduleItems = array();
foreach($modules as $moduleID => $module)
{
    $moduleItems[] = array('text' => $module, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('bug', 'batchChangeModule', "moduleID=$moduleID"));
}

$assignedToItems = array();
foreach ($memberPairs as $key => $value)
{
    $key = base64_encode((string)$key); // 编码用户名中的特殊字符
    $assignedToItems[] = array('text' => $value, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('bug', 'batchAssignTo', "assignedTo=$key&productID={$product->id}&type=product"));
}

$footToolbar = array();
if($canBatchAction)
{
    $footToolbar['items'] = array();
    $footToolbar['items'][] = array('type' => 'btn-group', 'items' => array
    (
        array('text' => $lang->edit, 'className' => 'primary batch-btn not-open-url', 'disabled' => ($canBatchEdit ? '': 'disabled'), 'data-url' => createLink('bug', 'batchEdit', "productID={$product->id}&branch=$branch")),
        array('caret' => 'up', 'data-placement' => 'top-start', 'class' => 'btn btn-caret size-sm primary not-open-url', 'items' => $batchItems)
    ));
    if($canBatchChangeBranch && $product->type != 'normal')
    {
        $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->product->branchName[$product->type], 'type' => 'dropdown', 'data-placement' => 'top-start', 'items' => $branchItems);
    }
    if($canBatchChangeModule)
    {
        $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->bug->abbr->module, 'type' => 'dropdown', 'data-placement' => 'top-start', 'items' => $moduleItems, 'data-menu' => array('searchBox' => true));
    }
    if($canBatchAssignTo)
    {
        $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->bug->assignedTo, 'type' => 'dropdown', 'data-placement' => 'top-start', 'items' => $assignedToItems, 'data-menu' => array('searchBox' => true));
    }
    $footToolbar['btnProps'] = array('size' => 'sm', 'btnType' => 'secondary');
}
if($isFromDoc) $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToDoc"));

$cols = $this->loadModel('datatable')->getSetting('bug');

if(isset($cols['branch']))         $cols['branch']['map']         = array(BRANCH_MAIN => $lang->trunk) + $branchTagOption;
if(isset($cols['project']))        $cols['project']['map']        = array('') + $projectPairs;
if(isset($cols['execution']))      $cols['execution']['map']      = array('') + $executions;
if(isset($cols['plan']))           $cols['plan']['map']           = array('') + $plans;
if(isset($cols['task']))           $cols['task']['map']           = array('') + $tasks;
if(isset($cols['toTask']))         $cols['toTask']['map']         = array('') + $tasks;
if(isset($cols['story']))          $cols['story']['map']          = array('') + $stories;
if(isset($cols['activatedCount'])) $cols['activatedCount']['map'] = array('');
if($product->type == 'normal') unset($cols['branch']);
foreach($cols as $colName => $col)
{
    if(!isset($col['sortType'])) $cols[$colName]['sortType'] = true;
}

if($isFromDoc)
{
    if(isset($cols['actions'])) unset($cols['actions']);
    foreach($cols as $key => $col)
    {
        $cols[$key]['sortType'] = false;
        if(isset($col['link'])) unset($cols[$key]['link']);
        if($key == 'assignedTo') $cols[$key]['type'] = 'user';
    }
}

$bugs = initTableData($bugs, $cols, $this->bug);

dtable
(
    set::id('bugs'),
    set::cols($cols),
    set::data(array_values($bugs)),
    set::userMap($users),
    set::checkable($canBatchAction || $isFromDoc),
    set::orderBy($orderBy),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    !$isFromDoc ? null : set::afterRender(jsCallback()->call('toggleCheckRows', $idList)),
    !$isFromDoc ? null : set::height(400),
    $isFromDoc ? null : set::customCols(true),
    $isFromDoc ? null : set::sortLink(inlink('browse', "product={$product->id}&branch={$branch}&browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::modules($modulePairs),
    set::emptyTip($lang->bug->notice->noBug),
    $isFromDoc ? null : set::createTip($lang->bug->create),
    $isFromDoc ? null : set::createLink($canBeChanged && hasPriv('bug', 'create') ? createLink('bug', 'create', "productID={$product->id}&branch={$branch}&extra=moduleID=$currentModuleID") : '')
);

render();
