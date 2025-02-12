<?php
declare(strict_types=1);
/**
 * The import from lib view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('app', $app->tab);
jsVar('productID', $productID);
jsVar('branch', $branch);

featureBar
(
    to::before
    (
        backBtn
        (
            $lang->goback,
            set::icon('back'),
            set::url($this->session->caseList),
            set::className('secondary')
        ),
        picker
        (
            zui::width('200px'),
            set::name('fromlib'),
            set::items($libraries),
            set::value($libID),
            set::required(true),
            on::change("toggleLib")
        )
    )
);

searchForm
(
    set::module('testsuite'),
    set::simple(true),
    set::show(true)
);

$config->testcase->importfromlib->dtable->fieldList['fromModule']['map'] = $libModules;
$config->testcase->importfromlib->dtable->fieldList['branch']['controlItems'] = array();
if($product->type == 'normal') unset($config->testcase->importfromlib->dtable->fieldList['branch']);

foreach($cases as $case)
{
    $case->fromModule = $case->module;

    $caseBranchItems   = array();
    $caseBranch        = ($branch == 'all' || empty($branch)) ? 0 : $branch;
    $canImportBranches = array();
    foreach($branches as $branchID => $branchName)
    {
        if(empty($canImportModules[$branchID][$case->id])) continue;
        $caseBranchItems[] = array('text' => $branchName, 'value' => $branchID);
        $canImportBranches[$branchID] = $branchID;
    }
    if(!empty($canImportBranches) && !isset($canImportBranches[$caseBranch])) $caseBranch = key($canImportBranches);

    $case->branchItems = $caseBranchItems;
    $case->branch      = $caseBranch;
    if($case->id != key($cases))
    {
        $case->module = 'ditto';
    }
    else
    {
        $case->module = 0;
    }

    $case->moduleItems = $canImportModules[$caseBranch][$case->id];
}

$footToolbar = array('items' => array(array('text' => $lang->testcase->import, 'btnType' => 'secondary', 'className' => 'import-btn')));

$sortLink = createLink('testcase', 'importFromLib', "product={$productID}&branch={$branch}&libID={$libID}&orderBy={name}_{sortType}&browseType={$browseType}&queryID={$queryID}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&projectID={$projectID}");

formBase
(
    setID('importFromLibForm'),
    set::action(createLink('testcase', 'importFromLib', "product={$productID}&branch={$branch}&libID={$libID}")),
    set::actions(array()),
    dtable
    (
        set::cols($config->testcase->importfromlib->dtable->fieldList),
        set::data($cases),
        set::onRenderCell(jsRaw('window.renderModuleItem')),
        set::checkable(true),
        set::sortLink($sortLink),
        set::footToolbar($footToolbar),
        set::footPager(usePager()),
        set::plugins(array('form'))
    )
);

render();
