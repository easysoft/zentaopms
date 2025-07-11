<?php
declare(strict_types=1);
/**
 * The browse scene view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('modules', $modulePairs);

include 'header.html.php';

$footToolbar          = null;
$canBatchChangeModule = common::canModify('product', $product) && hasPriv('testcase', 'batchChangeModule') && !empty($productID) && ((isset($product->type) && $product->type == 'normal') || $branch !== 'all');
if($canBatchChangeModule)
{
    $moduleItems = array();
    foreach($modules as $changeModuleID => $module) $moduleItems[] = array('text' => $module, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('testcase', 'batchChangeModule', "moduleID={$changeModuleID}"));
    $footToolbar = array('items' => array
    (
        array('caret' => 'up', 'text' => $lang->testcase->moduleAB, 'type' => 'dropdown', 'items' => $moduleItems, 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true))
    ), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));
}

$subSceneCount = count(array_filter(array_map(function($scene){return $scene->grade > 1;}, $scenes)));

$cols = $this->config->scene->dtable->fieldList;
$cols['title']['nestedToggle'] = $subSceneCount > 0;

initTableData($scenes, $cols, $this->testcase);

div
(
    dtable
    (
        set::id('scenes'),
        set::plugins(array('sortable')),
        set::nested(true),
        set::sortable(true),
        set::onSortEnd(jsRaw('window.onSortEnd')),
        set::canSortTo(jsRaw('window.canSortTo')),
        set::onRenderCell(jsRaw('window.onRenderCell')),
        set::sortLink(inlink('browseScene', "productID=$product->id&branch=$branch&moduleID=$moduleID&orderBy={name}_{sortType}")),
        set::checkable($canBatchChangeModule),
        set::cols($cols),
        set::data(array_values($scenes)),
        set::userMap($users),
        set::orderBy($orderBy),
        set::footPager(usePager()),
        set::footToolbar($footToolbar),
        set::emptyTip($lang->testcase->noScene),
        set::createTip($lang->testcase->createScene),
        set::createLink($canCreateScene ? $createSceneLink : ''),
        set::customData(array('modules' => $modulePairs))
    )
);

modal
(
    setID('dragModal'),
    set::title($lang->testcase->dragModalTitle),
    set::size('sm'),
    divider(),
    div(setClass('my-4'), $lang->testcase->dragModalDesc),
    div($lang->testcase->dragModalOrder),
    div($lang->testcase->dragModalScene),
    div(setClass('my-4'), $lang->testcase->dragModalAction),
    divider(),
    div
    (
        setClass('mt-4 pull-right'),
        btn(setClass('primary mr-2'), $lang->testcase->dragModalChangeScene, set('data-on', 'click'), set('data-call', 'clickChangeScenen')),
        btn(setClass('primary mr-2'), $lang->testcase->dragModalChangeOrder, set('data-on', 'click'), set('data-call', 'clickChangeOrder')),
        btn($lang->close, set('data-dismiss', 'modal'))
    )
);