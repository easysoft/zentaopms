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
        setStyle(array('margin-right' => '20px')),
        set(array
        (
            'title'       => $lang->program->all,
            'programs'    => $programList,
            'activeKey'   => !empty($programList) ? $programID : null,
            'link'        => sprintf($programMenuLink, 0),
        ))
    );
};

/* ====== Define the page structure with zin widgets ====== */
featureBar
(
    to::before($fnGenerateProgramMenu($programList)),
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

toolbar
(
    btn
    (
        setClass('ghost text-darker'),
        set::icon('export'),
        set('data-toggle', 'modal'),
        set('data-url', createLink('product', 'export', "programID=$programID&status=$browseType&orderBy=$orderBy&param=$param")),
        $lang->export
    ),
    div(setClass('nav-divider')),
    $config->systemMode == 'ALM' ? btn
    (
        setClass('ghost text-primary'),
        set::icon('edit'),
        set('data-toggle', 'modal'),
        set('data-url', createLink('product', 'manageLine', $browseType)),
        set('data-id', 'manageLineModal'),
        $lang->product->editLine
    ) : null,
    item(set(array
    (
        'text'  => $lang->product->create,
        'icon'  => 'plus',
        'class' => 'primary',
        'url'   => createLink('product', 'create')
    )))
);

$canBatchEdit = common::hasPriv('product', 'batchEdit');
dtable
(
    setID('products'),
    set::cols($cols),
    set::data($fnGenerateTableData($productStats)),
    set::userMap($users),
    set::customCols(true),
    set::checkable($canBatchEdit),
    set::sortLink(createLink('product', 'all', "browseType={$browseType}&orderBy={name}_{sortType}&recTotal={$recTotal}&recPerPage={$recPerPage}")),
    set::footToolbar(array
    (
        'type'  => 'btn-group',
        'items' => array(
            $canBatchEdit ? array
            (
                'text'      => $lang->edit,
                'className' => 'secondary batch-btn',
                'data-page' => 'batch',
                'data-formaction' => $this->createLink('product', 'batchEdit')
            ) : null,
        )
    )),
    set::footPager(usePager())
);

jsVar('langSummary', $lang->product->pageSummary);

render();
