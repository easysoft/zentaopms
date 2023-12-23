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

/* Get field list for data table. */
$fnGetTableFieldList = function() use ($config)
{
    $fieldList = $this->loadModel('datatable') ->getSetting('product');

    $extendFieldList = $this->product->getFlowExtendFields();
    foreach($extendFieldList as $field => $name)
    {
        $extCol = $config->product->dtable->extendField;
        $extCol['name']  = $field;
        $extCol['title'] = $name;

        $fieldList[$field] = $extCol;
    }

    return $fieldList;
};
$cols = $fnGetTableFieldList();

/* Closure function for generating table data. */
$productStats = initTableData($productStats, $cols, $this->product);
$fnGenerateTableData = function($productList) use($users)
{
    $data = array();
    foreach($productList as $product) $data[] = $this->product->formatDataForList($product, $users);

    return $data;
};

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
            'recTotal'   => $recTotal,
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

$canCreate = hasPriv('product', 'create');
toolbar
(
    hasPriv('product', 'export') ? btn
    (
        set::className('ghost text-darker'),
        set::icon('export'),
        toggle::modal(array('url' => createLink('product', 'export', "programID=$programID&status=$browseType&orderBy=$orderBy&param=$param"))),
        $lang->export
    ) : null,
    in_array($this->config->systemMode, array('ALM', 'PLM')) && hasPriv('product', 'manageLine') ? btn
    (
        set::className('ghost text-primary'),
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

$canBatchEdit = hasPriv('product', 'batchEdit');
dtable
(
    set::id('products'),
    set::cols($cols),
    set::data($fnGenerateTableData($productStats)),
    set::userMap($users),
    set::customCols(true),
    set::checkable($canBatchEdit),
    set::sortLink(createLink('product', 'all', "browseType={$browseType}&orderBy={name}_{sortType}&recTotal={$recTotal}&recPerPage={$recPerPage}")),
    set::orderBy($orderBy),
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
