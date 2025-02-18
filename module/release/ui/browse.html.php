<?php
declare(strict_types=1);
/**
 * The browse view file of release module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     release
 * @link        https://www.zentao.net
 */
namespace zin;

$isFromDoc = $from == 'doc';

$isFromDoc ? null : dropmenu();

if($isFromDoc)
{
    jsVar('blockID', $blockID);

    $this->app->loadLang('doc');
    $productChangeLink = createLink('release', 'browse', "productID={productID}&branch=$branch&type=$type&orderBy=$orderBy&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID=$blockID");

    jsVar('insertListLink', createLink('release', 'browse', "productID={$product->id}&branch=$branch&type=$type&orderBy=$orderBy&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from&blockID={blockID}"));

    formPanel
    (
        setID('zentaolist'),
        setClass('mb-4-important'),
        set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList['productRelease'])),
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

/* zin: Define the set::module('release') feature bar on main menu. */
featureBar
(
    set::current($type),
    set::linkParams("productID={$product->id}&branch={$branch}&type={key}&orderBy={$orderBy}&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from={$from}&blockID={$blockID}"),
    set::isModal($isFromDoc),
    set::modalTarget('#releases_table'),
    li(searchToggle
    (
        set::simple($isFromDoc),
        set::open($type == 'bysearch'),
        set::module('release'),
        $isFromDoc ? set::target('#docSearchForm') : null
    ))
);

if($isFromDoc) div(setID('docSearchForm'));

/* zin: Define the toolbar on main menu. */
$canCreateRelease = hasPriv('release', 'create') && common::canModify('product', $product);
$canManageSystem  = hasPriv('system', 'browse') && common::canModify('product', $product);
if($canCreateRelease) $createItem = array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->release->create, 'url' => $this->createLink('release', 'create', "productID={$product->id}"));
if($canManageSystem)  $manageSystemItem = array('class' => 'primary', 'text' => $lang->release->manageSystem, 'url' => $this->createLink('system', 'browse', "productID={$product->id}"), 'data-app' => 'product');
toolbar
(
    setClass(array('hidden' => $isFromDoc)),
    !empty($manageSystemItem) ? item(set($manageSystemItem)) : null,
    !empty($createItem) ? item(set($createItem)) : null
);

jsVar('markerTitle', $lang->release->marker);
jsVar('showBranch', $showBranch);
jsVar('type', $type);

$cols = $this->loadModel('datatable')->getSetting('release');
if($showBranch) $cols['branch']['map'] = $branchPairs;

foreach(array_column($releases, 'system') as $system)
{
    if(!isset($appList[$system])) $appList[$system] = '';
}
if(!empty($cols['system'])) $cols['system']['map'] = array(0 => '') + $appList;

if($isFromDoc)
{
    $cols['id']['type'] = 'checkID';

    if(isset($cols['actions'])) unset($cols['actions']);

    foreach($cols as $key => $col)
    {
        $cols[$key]['sortType'] = false;
        if(isset($col['link'])) unset($cols[$key]['link']);
    }
}


$releases = initTableData($releases, $cols, $this->release);
dtable
(
    set::id('releases'),
    set::cols(array_values($cols)),
    set::data($releases),
    set::rowKey('rowID'),
    set::plugins(array('cellspan')),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::getCellSpan(jsRaw('window.getCellSpan')),
    set::orderBy($orderBy),
    set::footPager(
        usePager
        (
            array('linkCreator' => helper::createLink('release', 'browse', "productID={$product->id}&branch={$branch}&type={$type}&orderBy={$orderBy}&param=$param&recTotal={recTotal}&recPerPage={recPerPage}&pageID={page}&from={$from}&blockID={$blockID}"), 'recTotal' => $pager->recTotal, 'recPerPage' => $pager->recPerPage)
        )
    ),
    set::emptyTip($lang->release->noRelease),
    set::checkable($isFromDoc),
    $isFromDoc ? set::footToolbar(array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => 'insertListToDoc'))) : set::footer([jsRaw("function(){return {html: '{$pageSummary}'};}"), 'flex', 'pager']),
    !$isFromDoc ? null : set::afterRender(jsCallback()->call('toggleCheckRows', $idList)),
    !$isFromDoc ? null : set::onCheckChange(jsRaw('window.checkedChange')),
    !$isFromDoc ? null : set::height(400),
    $isFromDoc ? null : set::customCols(true),
    $isFromDoc ? null : set::sortLink(createLink('release', 'browse', "productID={$product->id}&branch={$branch}&type={$type}&orderBy={name}_{sortType}&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    $isFromDoc ? null : set::createTip($lang->release->create),
    $isFromDoc ? null : set::createLink($canCreateRelease ? createLink('release', 'create', "productID={$product->id}&branch={$branch}") : '')
);

/* ====== Render page ====== */
render();
