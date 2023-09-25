<?php
declare(strict_types=1);
/**
 * The browses view file of productplan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;

/* zin: Define the set::module('productplan') feature bar on main menu. */
featureBar
(
    set::current($browseType),
    set::linkParams("productID={$productID}&branch={$branch}&browseType={key}&queryID={$queryID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"),
    li(searchToggle(set::module('productplan'), set::open($browseType == 'bysearch')))
);

$canCreatePlan = common::canModify('product', $product) && common::hasPriv($app->rawModule, 'create');
$canCreatePlan ? toolbar
(
    item(set(array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->productplan->create, 'url' => createLink($app->rawModule, 'create', "productID={$productID}&branch={$branch}")))),
) : null;

$cols      = $this->loadModel('datatable')->getSetting('productplan');
$tableData = initTableData($plans, $cols, $this->productplan);
dtable
(
    set::cols($cols),
    set::data($tableData),
    set::customCols(true),
    set::footPager(
        usePager(array('linkCreator' => createLink($app->rawModule, 'browse', "productID={$productID}&branch={$branch}&browseType={$browseType}&queryID={$queryID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={recPerPage}&pageID={page}"))),
    )
);
