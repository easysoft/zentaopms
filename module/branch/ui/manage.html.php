<?php
declare(strict_types=1);
/**
 * The browse view file of branch module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     branch
 * @link        https://www.zentao.net
 */
namespace zin;
/* zin: Define the set::module('branch') feature bar on main menu. */
featureBar
(
    set::current($type),
    set::linkParams("productID={$product->id}&browseType={key}"),
);

/* zin: Define the toolbar on main menu. */
$canCreate    = hasPriv('branch', 'create');
$canBatchEdit = hasPriv('branch', 'batchEdit');
if($canCreate) $createItem = array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->branch->create, 'url' => $this->createLink('branch', 'create', "productID={$product->id}", '', true), 'data-toggle' => 'modal');
toolbar
(
    !empty($createItem) ? item(set($createItem)) : null,
);

jsVar('confirmclose',    $lang->branch->confirmClose);
jsVar('confirmactivate', $lang->branch->confirmActivate);

jsVar('orderBy', $orderBy);
jsVar('sortLink', helper::createLink('branch', 'manage', "productID={$product->id}&browseType={$browseType}&orderBy={orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"));
$tableData = initTableData($branchList, $config->branch->dtable->fieldList, $this->branch);

dtable
(
    set::cols($config->branch->dtable->fieldList),
    set::data($tableData),
    set::sortLink(jsRaw('createSortLink')),
    set::footPager(
        usePager(),
        set::recPerPage($pager->recPerPage),
        set::recTotal($pager->recTotal),
        set::linkCreator(helper::createLink('branch', 'manage', "productID={$product->id}&browseType={$browseType}&orderBy={$orderBy}&recTotal={recTotal}&recPerPage={recPerPage}&pageID={page}")),
    ),
);

/* ====== Render page ====== */
render();
