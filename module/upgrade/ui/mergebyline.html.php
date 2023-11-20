<?php
declare(strict_types=1);
/**
 * The mergebyline mode view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

$getMergeData = function($data)
{
    global $lang;
    include_once('createprogram.html.php');

    $checkBoxGroup = array();
    foreach($data->productlines as $line)
    {
        $lineGroups = array();
        foreach($data->lineGroups[$line->id] as $productID => $product)
        {
            $productGroups = array();
            if(isset($data->productGroups[$productID]))
            {
                foreach($data->productGroups[$productID] as $sprint)
                {
                    $productGroups[] = div
                    (
                        setClass('sprintItem mb-2'),
                        checkbox(setID("sprints-{$line->id}-{$productID}-{$sprint->id}"), set::name("sprints[$line->id][$productID][]"), set::text($sprint->name), set::value($sprint->id), setData(array('on' => 'change', 'call' => 'changeSprints', 'params' => 'event', 'line' => $line->id, 'product' => $productID, 'begin' => $sprint->begin, 'end' => $sprint->end, 'status' => $sprint->status, 'pm' => $sprint->PM))),
                        input(setClass('hidden'), set::name("sprintIdList[$line->id][$productID][$sprint->id]"), set::value($sprint->id))
                    );
                }
            }
            $lineGroups[] = div
            (
                setClass('flex mb-2'),
                cell
                (
                    set::width('1/2'),
                    setClass('productItem p-4 overflow-hidden'),
                    set::style(array('background-color' => 'var(--color-gray-50)')),
                    checkbox(setID("products-{$line->id}-{$productID}"), set::name("products[{$line->id}][]"), set::text($product->name), set::value($productID), setData(array('on' => 'change', 'call' => 'changeProducts', 'params' => 'event', 'line' => $line->id, 'productid' => $productID, 'begin' => $product->createdDate, 'programid' => $product->program))),
                    input(setClass('hidden'), set::name("productIdList[$line->id][$productID]"), set::value($productID))
                ),
                cell
                (
                    set::width('1/2'),
                    setClass('p-4 overflow-hidden'),
                    set::style(array('background-color' => 'var(--color-gray-50)')),
                    $productGroups
                )
            );
        }
        $checkBoxGroup[] = div
        (
            setClass('flex'),
            cell
            (
                set::width('1/3'),
                setClass('productList px-4 flex items-center border-t mr-2 overflow-hidden'),
                set::style(array('background-color' => 'var(--color-gray-50)')),
                checkbox(setID("productLines{$line->id}"), set::name("productLines[$line->id][]"), set::text($line->name), set::value($line->id), setData(array('on' => 'change', 'call' => 'changeLines', 'params' => 'event'))),
            ),
            cell
            (
                set::flex('1'),
                setClass('productList'),
                div
                (
                    setClass('scroll-handle'),
                    $lineGroups
                )
            )
        );
    }

    return div
    (
        setClass('flex mt-4'),
        cell
        (
            setID('source'),
            set::width('1/2'),
            setClass('border p-4 overflow-hidden'),
            div
            (
                setClass('flex mb-4'),
                cell
                (
                    set::width('1/3'),
                    setClass('item checkbox-primary px-4 overflow-hidden mr-3'),
                    checkbox(setID('checkAllLines'), set::text($lang->upgrade->allLines), setData(array('on' => 'change', 'call' => 'changeAllLines')))
                ),
                cell
                (
                    set::width('1/3'),
                    setClass('item checkbox-primary px-4 overflow-hidden'),
                    checkbox(setID('checkAllProducts'), set::text($lang->productCommon), setData(array('on' => 'change', 'call' => 'changeAllProducts')))
                ),
                cell
                (
                    set::width('1/3'),
                    setClass('item checkbox-primary px-4 overflow-hidden'),
                    checkbox(setID('checkAllSprints'), set::text($lang->projectCommon), setData(array('on' => 'change', 'call' => 'changeAllSprints')))
                ),
            ),
            div
            (
                $checkBoxGroup
            )
        ),
        cell
        (
            set::width('1/2'),
            setClass('border ml-4 p-4'),
            setID('programBox'), $createProgram($data)
        )
    );
};
