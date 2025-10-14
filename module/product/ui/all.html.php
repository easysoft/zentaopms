<?php
declare(strict_types=1);
/**
* The UI file of product module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     product
* @link        https://www.zentao.net
*/

namespace zin;

jsVar('orderBy', $orderBy);

/* Get field list for data table. */
if(str_contains($orderBy, 'line')) $orderBy = str_replace('line', 'productLine', $orderBy);

$cols = $this->loadModel('datatable')->getSetting('product');

/* Closure function for generating table data. */
$productStats = initTableData($productStats, $cols, $this->product);

/* Closure function for generating program menu. */
$fnGenerateProgramMenu = function($programList) use($lang, $programID, $browseType, $orderBy, $param, $recTotal, $recPerPage, $pageID)
{
    $programMenuLink = createLink(
        $this->app->rawModule,
        $this->app->rawMethod,
        array(
            'browseType' => $browseType == 'bySearch' ? 'noclosed' : $browseType,
            'orderBy'    => $orderBy,
            'param'      => $browseType == 'bySearch' ? 0 : $param,
            'recTotal'   => 0,
            'recPerPage' => $recPerPage,
            'pageID'     => $pageID,
            'programID'  => '{id}'
        )
    );

    return programMenu
    (
        set::title($lang->program->all),
        set::programs($programList),
        set::activeKey(!empty($programList) ? $programID : null),
        set::link(sprintf($programMenuLink, 0)),
        set::leadingAngle(false)
    );
};

jsVar('langSummary', $lang->product->pageSummary);

/* ====== Define the page structure with zin widgets ====== */
featureBar
(
    ($config->systemMode != 'ALM' && $config->systemMode != 'PLM') ? null : to::leading($fnGenerateProgramMenu($programList)),
    set::link(createLink
    (
        $this->app->rawModule,
        $this->app->rawMethod,
        array
        (
            'browseType' => '{key}',
            'orderBy'    => $orderBy,
            'param'      => $param,
            'recTotal'   => 0,
            'recPerPage' => $recPerPage,
            'pageID'     => $pageID,
            'programID'  => $programID
        )
    )),
    li(searchToggle(set::open($browseType == 'bySearch')))
);

$canCreate     = hasPriv('product', 'create');
$canExport     = hasPriv('product', 'export');
$canManageLine = in_array($this->config->systemMode, array('ALM', 'PLM')) && hasPriv('product', 'manageLine');
toolbar
(
    $canExport ? btn
    (
        set::className('ghost text-darker pr-0'),
        set::icon('export'),
        toggle::modal(array('url' => createLink('product', 'export', "programID=$programID&status=$browseType&orderBy=$orderBy&param=$param"))),
        $lang->export
    ) : null,
    $canExport && $canManageLine ? div
    (
        setClass('divider')
    ) : null,
    $canManageLine ? btn
    (
        set::id('manageLineBtn'),
        set::className('ghost text-primary pl-0'),
        set::icon('edit'),
        toggle::modal(array('url' => createLink('product', 'manageLine', $browseType), 'id' => 'manageLineModal')),
        $lang->product->line
    ) : null,
    $canCreate ? btn
    (
        set::text($lang->product->create),
        set::icon('plus'),
        set::type('primary'),
        set::url(createLink('product', 'create')),
        set::className('create-product-btn')
    ) : null
);

$canBatchEdit   = hasPriv('product', 'batchEdit');
$canUpdateOrder = hasPriv('product', 'updateOrder')  && $orderBy == 'order_asc';
dtable
(
    set::id('products'),
    set::sortable($canUpdateOrder),
    set::onSortEnd($canUpdateOrder ? jsRaw('window.onSortEnd') : null),
    set::canSortTo($canUpdateOrder ? jsRaw('window.canSortTo') : null),
    set::cols($cols),
    set::data($productStats),
    set::userMap($users),
    set::customCols(true),
    set::checkable($canBatchEdit),
    set::sortLink(createLink('product', 'all', "browseType={$browseType}&orderBy={name}_{sortType}&param={$param}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}&programID={$programID}")),
    set::orderBy($orderBy),
    set::plugins(array('header-group', 'sortable')),
    $canBatchEdit ? set::footToolbar
    (
        item
        (
            set::url('product', 'batchEdit'),
            set::text($lang->edit),
            setData('load', 'post'),
            setData('dataMap', 'productIDList[]:#products~checkedIDList')
        )
    ) : null,
    set::footPager(usePager()),
    set::emptyTip($lang->product->noProduct),
    set::createTip($lang->product->create),
    set::createLink($canCreate ? createLink('product', 'create') : '')
);

render();
