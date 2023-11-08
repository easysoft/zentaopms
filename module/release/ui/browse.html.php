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

dropmenu();

/* zin: Define the set::module('release') feature bar on main menu. */
featureBar
(
    set::current($type),
    set::linkParams("productID={$product->id}&branch={$branch}&type={key}&orderBy={$orderBy}&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"),
    li(searchToggle(set::module('release')))
);

/* zin: Define the toolbar on main menu. */
$canCreateRelease = hasPriv('release', 'create') && common::canModify('product', $product);
if($canCreateRelease) $createItem = array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->release->create, 'url' => $this->createLink('release', 'create', "productID={$product->id}&branch={$branch}"));
toolbar
(
    !empty($createItem) ? item(set($createItem)) : null,
);

jsVar('markerTitle', $lang->release->marker);
jsVar('showBranch', $showBranch);
jsVar('type', $type);

if($showBranch) $config->release->dtable->fieldList['branch']['map'] = $branchPairs;
dtable
(
    set::cols(array_values($config->release->dtable->fieldList)),
    set::data(array_values($releases)),
    set::plugins(array('cellspan')),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::getCellSpan(jsRaw('window.getCellSpan')),
    set::orderBy($orderBy),
    set::sortLink(createLink('release', 'browse', "productID={$product->id}&branch={$branch}&type={$type}&orderBy={name}_{sortType}&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footer([jsRaw("function(){return {html: '{$pageSummary}'};}"), 'flex', 'pager']),
    set::footPager(
        usePager
        (
            array('linkCreator' => helper::createLink('release', 'browse', "productID={$product->id}&branch={$branch}&type={$type}&orderBy={$orderBy}&param=$param&recTotal={recTotal}&recPerPage={recPerPage}&pageID={page}"), 'recTotal' => $pager->recTotal, 'recPerPage' => $pager->recPerPage),
        ),
    ),
    set::emptyTip($lang->release->noRelease),
    set::createTip($lang->release->create),
    set::createLink($canCreateRelease ? createLink('release', 'create', "productID={$product->id}&branch={$branch}") : ''),
);

/* ====== Render page ====== */
render();
