<?php
declare(strict_types=1);
/**
 * The mergebyproduct mode view file of upgrade module of ZenTaoPMS.
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
    foreach($data->noMergedProducts as $productID => $product)
    {
        $productGroups = array();
        if(isset($data->productGroups[$productID]))
        {
            foreach($data->productGroups[$productID] as $sprint)
            {
                $productGroups[] = div
                (
                    setClass('sprintItem mb-2'),
                    checkbox(setID("sprints-{$productID}-{$sprint->id}"), set::name("sprints[{$productID}][]"), set::text($sprint->name), set::value($sprint->id), setData(array('on' => 'change', 'call' => 'changeSprints', 'params' => 'event', 'product' => $productID, 'begin' => $product->createdDate, 'end' => $sprint->end, 'status' => $sprint->status, 'pm' => $sprint->PM))),
                    input(setClass('hidden'), set::name("sprintIdList[{$productID}][{$sprint->id}]"), set::value($sprint->id))
                );
            }
        }
        $checkBoxGroup[] = div
        (
            setClass('mt-4 flex py-4'),
            set::style(array('background-color' => 'var(--color-gray-50)')),
            cell
            (
                set::width('1/2'),
                setClass('productList px-4 flex items-center overflow-hidden'),
                checkbox(setID("products{$productID}"), set::name('products[]'), set::text($product->name), set::value($product->id), setData(array('on' => 'change', 'call' => 'changeProducts', 'params' => 'event', 'begin' => $product->createdDate, 'programid' => $product->program, 'productid' => $productID)))
            ),
            cell
            (
                set::width('1/2'),
                setClass('productList px-4'),
                div
                (
                    setClass('scroll-handle'),
                    $productGroups
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
                setClass('flex'),
                cell
                (
                    set::width('1/2'),
                    setClass('item checkbox-primary px-4 overflow-hidden'),
                    checkbox(setID('checkAllProducts'), set::text($lang->productCommon), setData(array('on' => 'change', 'call' => 'changeAllProducts')))
                ),
                cell
                (
                    set::width('1/2'),
                    setClass('item checkbox-primary px-4'),
                    checkbox(setID('checkAllSprints'), set::text($lang->projectCommon), setData(array('on' => 'change', 'call' => 'changeAllSprints')))
                )
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
            setID('programBox'),
            $createProgram($data)
        )
    );
};
