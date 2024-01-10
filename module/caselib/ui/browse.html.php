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

$canView              = common::hasPriv('caselib', 'view');
$canExport            = common::hasPriv('caselib', 'exportTemplate');
$canImport            = common::hasPriv('caselib', 'import');
$canCreateLib         = common::hasPriv('caselib', 'create');
$canCreateCase        = common::hasPriv('caselib', 'createCase');
$canBatchCreateCase   = common::hasPriv('caselib', 'batchCreateCase');
$canBatchEdit         = common::hasPriv('testcase', 'batchEdit');
$canBatchDelete       = common::hasPriv('testcase', 'batchDelete');
$canBatchReview       = common::hasPriv('testcase', 'batchReview') and ($config->testcase->needReview or !empty($config->testcase->forceReview));
$canBatchChangeModule = common::hasPriv('testcase', 'batchChangeModule');
$canBatchAction       = ($canBatchEdit or $canBatchDelete or $canBatchReview or $canBatchChangeModule);

$cols = $this->loadModel('datatable')->getSetting('caselib');
$tableData = initTableData($cases, $cols, $this->testcase);

featureBar
(
    set::current($this->session->libBrowseType),
    set::linkParams("libID=$libID&browseType={key}&param=$param&orderBy=$orderBy&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"),
    li(searchToggle())
);

$createCaseItem      = array('text' => $lang->testcase->create, 'url' => helper::createLink('caselib', 'createCase', "libID=$libID&moduleID=" . (isset($moduleID) ? $moduleID : 0)));
$batchCreateCaseItem = array('text' => $lang->testcase->batchCreate, 'url' => helper::createLink('caselib', 'batchCreateCase', "libID=$libID&moduleID=" . (isset($moduleID) ? $moduleID : 0)));
toolbar
(
    $canView ? a
    (
        setClass('toolbar-item ghost btn btn-default'),
        set::href(createLink('caselib', 'view', "libID={$libID}")),
        set('data-toggle', 'modal'),
        set('data-id', 'viewLibModal'),
        icon('list-alt'),
        $lang->caselib->view
    ) : '',
    $canExport || $canImport ? btngroup
    (
        $canExport ? a
        (
            setClass('toolbar-item ghost btn btn-default'),
            set::href(createLink('caselib', 'exportTemplate', "libID={$libID}")),
            set('data-toggle', 'modal'),
            set('data-size', 'sm'),
            icon('export'),
            $lang->caselib->exportTemplate
        ) : '',
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
    foreach($modules as $moduleID => $module) $moduleItems[] = array('text' => $module, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('testcase', 'batchChangeModule', "moduleID=$moduleID"));
}

$navActions = array();
if($canBatchReview || $canBatchDelete || $canBatchChangeModule)
{
    $navActions = array
    (
        $canBatchReview ? array('text' => $lang->testcase->review, 'class' => 'not-hide-menu', 'items' => $reviewItems) : null,
        $canBatchDelete ? array('text' => $lang->delete, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('testcase', 'batchDelete', "libID=$libID")) : null,
        $canBatchChangeModule ? array('text' => $lang->testcase->module, 'class' => 'not-hide-menu', 'items' => $moduleItems) : null
    );
}

$footToolbar = $canBatchAction ? array('items' => array
(
    array('type' => 'btn-group', 'items' => array
    (
        $canBatchEdit ? array('text' => $lang->edit, 'className' => 'batch-btn not-open-url', 'data-url' => helper::createLink('testcase', 'batchEdit', "libID=$libID&branch=0&type=lib")) : null,
        !empty($navActions) ? array('caret' => 'up', 'btnType' => 'secondary', 'items' => $navActions, 'data-placement' => 'top-start') : null
    )),
), 'btnProps' => array('btnType' => 'secondary')) : null;

dtable
(
    set::cols($cols),
    set::data(array_values($tableData)),
    set::userMap($users),
    set::checkable($canBatchAction),
    set::emptyTip($lang->testcase->noCase),
    set::createTip($lang->testcase->create),
    set::createLink($canCreateCase ? createLink('caselib', 'createCase', "libID={$libID}&moduleID={$moduleID}") : ''),
    set::orderBy($orderBy),
    set::sortLink(createLink('caselib', 'browse', "libID={$libID}&browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager())
);

render();

