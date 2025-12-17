<?php
declare(strict_types=1);
/**
 * The browse view file of caselib module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     caselib
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('confirmBatchDelete', $lang->testcase->confirmBatchDelete);

$isFromDoc = $from === 'doc';
$isFromAI  = $from === 'ai';

jsVar('isFromDoc', $isFromDoc);
jsVar('isFromAI', $isFromAI);

if($isFromDoc || $isFromAI)
{
    $this->app->loadLang('doc');
    $caseLibs = $this->caselib->getPairs();
    $libChangeLink = createLink($app->rawModule, $app->rawMethod, "libID={libID}&browseType={$browseType}&param={$param}&orderBy={$orderBy}&recToTal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID");
    $insertListLink = createLink($app->rawModule, $app->rawMethod, "libID={$libID}&browseType={$browseType}&param={$param}&orderBy={$orderBy}&recToTal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID={blockID}");

    formPanel
    (
        setID('zentaolist'),
        setClass('mb-4-important'),
        set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList['caselib'])),
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
                set::label($lang->doc->caselib),
                set::control(array('required' => false)),
                set::items($caseLibs),
                set::value($libID),
                set::required(),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                ),
                on::change('[name="product"]')->do("loadModal('$libChangeLink'.replace('{libID}', $(this).val()))")
            )
        )
    );
}

$canView              = common::hasPriv('caselib', 'view');
$canExport            = common::hasPriv('caselib', 'exportCase');
$canExportTemplate    = common::hasPriv('caselib', 'exportTemplate');
$canImport            = common::hasPriv('caselib', 'import');
$canCreateLib         = common::hasPriv('caselib', 'create');
$canCreateCase        = common::hasPriv('caselib', 'createCase');
$canBatchCreateCase   = common::hasPriv('caselib', 'batchCreateCase');
$canBatchEdit         = common::hasPriv('caselib', 'batchEditCase');
$canBatchDelete       = common::hasPriv('testcase', 'batchDelete');
$canBatchReview       = common::hasPriv('testcase', 'batchReview') and ($config->testcase->needReview or !empty($config->testcase->forceReview));
$canBatchChangeModule = common::hasPriv('testcase', 'batchChangeModule');
$canBatchAction       = ($canBatchEdit or $canBatchDelete or $canBatchReview or $canBatchChangeModule);

$cols = $this->loadModel('datatable')->getSetting('caselib');
if(!empty($cols['pri'])) $cols['pri']['priList'] = $lang->testcase->priList;
if($isFromDoc || $isFromAI)
{
    if(isset($cols['actions'])) unset($cols['actions']);
    foreach($cols as $key => $col)
    {
        $cols[$key]['sortType'] = false;
        if(isset($col['link'])) unset($cols[$key]['link']);
        if($key == 'title') $cols[$key]['link'] = array('url' => createLink('caselib', 'viewCase', "caseID={id}&version={version}"), 'data-toggle' => 'modal', 'data-size' => 'lg');
    }
}

$tableData = initTableData($cases, $cols, $this->testcase);

featureBar
(
    set::current($this->session->libBrowseType),
    set::isModal($isFromDoc || $isFromAI),
    set::linkParams("libID=$libID&browseType={key}&param=$param&orderBy=$orderBy&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID&from=$from&blockID=$blockID"),
    li(searchToggle
    (
        set::simple($isFromDoc || $isFromAI),
        set::module('caselib'),
        set::open(strtolower($browseType) == 'bysearch'),
        ($isFromDoc || $isFromAI) ? set::target('#docSearchForm') : null,
        ($isFromDoc || $isFromAI) ? set::onSearch(jsRaw('function(){$(this.element).closest(".modal").find("#featureBar .nav-item>.active").removeClass("active").find(".label").hide()}')) : null
    ))
);

if($isFromDoc || $isFromAI)
{
    div(setID('docSearchForm'));
}

$createCaseItem      = array('text' => $lang->testcase->create, 'url' => helper::createLink('caselib', 'createCase', "libID=$libID&moduleID=" . (isset($moduleID) ? $moduleID : 0)));
$batchCreateCaseItem = array('text' => $lang->testcase->batchCreate, 'url' => helper::createLink('caselib', 'batchCreateCase', "libID=$libID&moduleID=" . (isset($moduleID) ? $moduleID : 0)));

$exportItems = array();
if($canExport)
{
    $link = $this->createLink('caselib', 'exportCase', "libID={$libID}&orderBy={$orderBy}&browseType={$browseType}");
    $exportItems[] = array('text' => $lang->caselib->exportCase, 'url' => $link, 'data-toggle' => 'modal');
}
if($canExportTemplate)
{
    $link = $this->createLink('caselib', 'exportTemplate', "libID={$libID}");
    $exportItems[] = array('text' => $lang->caselib->exportTemplate, 'url' => $link, 'data-toggle' => 'modal', 'data-size' => 'sm');
}

toolbar
(
    setClass(array('hidden' => $isFromDoc || $isFromAI)),
    $canView ? a
    (
        setClass('toolbar-item ghost btn btn-default'),
        set::href(createLink('caselib', 'view', "libID={$libID}")),
        set('data-toggle', 'modal'),
        set('data-id', 'viewLibModal'),
        icon('list-alt'),
        $lang->caselib->view
    ) : '',
    !empty($exportItems) || $canImport ? btngroup
    (
        $exportItems ? dropdown
        (
            btn
            (
                setClass('btn ghost square'),
                set::icon('export'),
            ),
            set::items($exportItems),
            set::placement('bottom-end')
        ) : null,
        $canImport ? a
        (
            setClass('toolbar-item ghost btn btn-default'),
            set::href(createLink('caselib', 'import', "libID={$libID}")),
            set('data-toggle', 'modal'),
            set('data-size', 'sm'),
            icon('import'),
            $lang->testcase->fileImport
        ) : ''
    ) : '',
    $canCreateLib ? btn
    (
        setClass('btn secondary'),
        set::icon('plus'),
        set::url(helper::createLink('caselib', 'create')),
        set('data-toggle', 'modal'),
        $lang->caselib->create
    ) : '',
    $canCreateCase && $canBatchCreateCase ? btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url(helper::createLink('caselib', 'createCase', "libID=$libID&moduleID=" . (isset($moduleID) ? $moduleID : 0))),
            $lang->testcase->create
        ),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'),
            setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items(array($createCaseItem, $batchCreateCaseItem)),
            set::placement('bottom-end')
        )
    ) : null,
    $canCreateCase && !$canBatchCreateCase ? item(set($createCaseItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null,
    $canBatchCreateCase && !$canCreateCase ? item(set($batchCreateCaseItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null
);

$settingLink = $this->createLink('tree', 'browse', "libID={$libID}&view=caselib&currentModuleID=0&branch=0&from={$lang->navGroup->caselib}");
$closeLink   = $this->createLink('caselib', 'browse', "libID=$libID&browseType=$browseType&param=0&orderBy=$orderBy");
if(!$isFromDoc && !$isFromAI)
{
    sidebar
    (
        moduleMenu
        (
            set::modules($moduleTree),
            set::activeKey($moduleID),
            set::settingLink($settingLink),
            set::closeLink($closeLink)
        )
    );
}

$reviewItems = array();
if($canBatchReview)
{
    foreach($lang->testcase->reviewResultList as $key => $result)
    {
        if($key == '') continue;
        $reviewItems[] = array('text' => $result, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => $this->createLink('testcase', 'batchReview', "result=$key"));
    }
}

$moduleItems = array();
if($canBatchChangeModule)
{
    foreach($modules as $moduleIdKey => $moduleName) $moduleItems[] = array('text' => $moduleName, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('testcase', 'batchChangeModule', "moduleID=$moduleIdKey"));
}

$navActions = array();
if($canBatchReview || $canBatchDelete || $canBatchChangeModule)
{
    $navActions = array
    (
        $canBatchReview ? array('text' => $lang->testcase->review, 'class' => 'not-hide-menu', 'items' => $reviewItems) : null,
        $canBatchDelete ? array('text' => $lang->delete, 'innerClass' => 'batch-btn ajax-btn not-open-url batch-delete-btn', 'data-url' => helper::createLink('testcase', 'batchDelete', "libID=$libID")) : null
    );
}

$footToolbar = null;
if($isFromDoc)
{
    $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToDoc('#caselib', 'caselib', $blockID, '$insertListLink')"));
}
elseif($isFromAI)
{
    $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToAI('#caselib', 'case')"));
}
elseif($canBatchAction)
{
    $btnGroupItems = array();
    if($canBatchEdit) $btnGroupItems[] = array('text' => $lang->edit, 'className' => 'batch-btn not-open-url', 'data-url' => helper::createLink('caselib', 'batchEditCase', "libID=$libID&branch=0&type=lib"));
    if(!empty($navActions)) $btnGroupItems[] = array('caret' => 'up', 'btnType' => 'secondary', 'items' => $navActions, 'data-placement' => 'top-start');

    $toolbarItems = array(array('type' => 'btn-group', 'items' => $btnGroupItems));
    if($canBatchChangeModule) $toolbarItems[] = array('caret' => 'up', 'text' => $lang->testcase->moduleAB, 'class' => 'not-hide-menu', 'items' => $moduleItems, 'data-menu' => array('searchBox' => true));

    $footToolbar = array('items' => $toolbarItems, 'btnProps' => array('btnType' => 'secondary'));
}

$caseCreateLink = $canCreateCase ? createLink('caselib', 'createCase', "libID={$libID}&moduleID={$moduleID}") : '';

dtable
(
    setID('caselib'),
    set::cols($cols),
    set::data(array_values($tableData)),
    set::customData(array('modules' => $modulePairs)),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::userMap($users),
    set::checkable($canBatchAction || $isFromDoc || $isFromAI),
    set::emptyTip($lang->testcase->noCase),
    set::orderBy($orderBy),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    ($isFromDoc || $isFromAI) ? null : set::customCols(true),
    ($isFromDoc || $isFromAI) ? null : set::sortLink(createLink('caselib', 'browse', "libID={$libID}&browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    ($isFromDoc || $isFromAI) ? null : set::createTip($lang->testcase->create),
    ($isFromDoc || $isFromAI) ? null : set::createLink($caseCreateLink),
    ($isFromDoc || $isFromAI) ? set::afterRender(jsCallback()->call('toggleCheckRows', $idList)) : null,
    ($isFromDoc || $isFromAI) ? set::onCheckChange(jsRaw('window.checkedChange')) : null,
    ($isFromDoc || $isFromAI) ? set::height(400) : null
);

render();
