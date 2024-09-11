<?php
declare(strict_types = 1);
/**
 * The product summary view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$cols = $config->pivot->dtable->productSummary->fieldList;
$cols['PO']['map'] = $users;
$data        = new stdclass();
$data->cols  = $this->pivot->processDTableCols($cols);
$data->array = $this->pivot->processDTableData(array_keys($cols), $products);

$generateData = function() use ($lang, $title, $cols, $data, $products, $filters)
{
    $this->loadModel('bi');
    $this->app->loadLang('product');
    return array
    (
        div
        (
            setID('conditions'),
            setClass('flex bg-canvas p-2'),
            checkList
            (
                on::change('loadProductSummary'),
                set::inline(true),
                set::items(array(array('text' => $lang->pivot->closedProduct, 'value' => 'closedProduct'), array('text' => $lang->pivot->overduePlan, 'value' => 'overduePlan')))
            ),
            div
            (
                setStyle('width', '70%'),
                setClass('ml-4 flex gap-4'),
                inputGroup
                (
                    setID('product'),
                    setClass('filter w-1/3'),
                    $lang->pivot->otherLang->product,
                    picker
                    (
                        setClass('flex-auto'),
                        set::name('product'),
                        set::value($filters['productID']),
                        set::items($this->bi->getScopeOptions('product')),
                        on::change('loadProductSummary')
                    )
                ),
                inputGroup
                (
                    setID('productStatus'),
                    setClass('filter w-1/3'),
                    $lang->pivot->otherLang->productStatus,
                    picker
                    (
                        setClass('flex-auto'),
                        set::name('productStatus'),
                        set::value($filters['productStatus']),
                        set::required(true),
                        set::items($lang->product->statusList),
                        on::change('loadProductSummary')
                    )
                ),
                inputGroup
                (
                    setID('productType'),
                    setClass('filter w-1/3'),
                    $lang->pivot->otherLang->productType,
                    picker
                    (
                        setClass('flex-auto'),
                        set::name('productType'),
                        set::value($filters['productType']),
                        set::required(true),
                        set::items($lang->product->typeList),
                        on::change('loadProductSummary')
                    )
                ),
            )
        ),
        panel
        (
            setID('pivotPanel'),
            set::title($title),
            set::shadow(false),
            set::headingClass('h-14'),
            set::bodyClass('pt-0'),
            dtable
            (
                set::striped(true),
                set::bordered(true),
                set::cols($cols),
                set::data($products),
                set::emptyTip($lang->error->noData),
                set::height(jsRaw('getHeight')),
                set::plugins(array('cellspan')),
                set::getCellSpan(jsRaw('getCellSpan')),
                set::cellSpanOptions(array('name' => array(), 'PO' => array()))
            ),
            div
            (
                setID('exportData'),
                setClass('hidden'),
                rawContent(),
                $this->pivot->buildPivotTable($data, array()),
            )
        )
    );
};
