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
                    set::className('sprintItem mb-2'),
                    checkbox(set::id("sprints-{$productID}-{$sprint->id}"), set::name("sprints[{$productID}][]"), set::text($sprint->name), set::value($sprint->id), set('data-on', 'change'), set('data-call', 'changeSprints'), set('data-params', 'event'), set('data-product', $productID), set('data-begin', $product->createdDate), set('data-end', $sprint->end), set('data-status', $sprint->status), set('data-pm', $sprint->PM)),
                    input(set::className('hidden'), set::name("sprintIdList[{$productID}][{$sprint->id}]"), set::value($sprint->id))
                );
            }
        }
        $checkBoxGroup[] = div
        (
            set::className('mt-4 flex py-4'),
            set::style(array('background-color' => 'var(--color-gray-50)')),
            cell
            (
                set::width('1/2'),
                set::className('productList px-4 flex items-center overflow-hidden'),
                checkbox(set::id("products{$productID}"), set::name('products[]'), set::text($product->name), set::value($product->id), set('data-on', 'change'), set('data-call', 'changeProducts'), set('data-params', 'event'), set('data-begin', $product->createdDate), set('data-programid', $product->program), set('data-productid', $productID))
            ),
            cell
            (
                set::width('1/2'),
                set::className('productList px-4'),
                div
                (
                    set::className('scroll-handle'),
                    $productGroups
                )
            )
        );
    }

    return div
    (
        set::className('flex mt-4'),
        cell
        (
            set::id('source'),
            set::width('1/2'),
            set::className('border p-4 overflow-hidden'),
            div
            (
                set::className('flex'),
                cell
                (
                    set::width('1/2'),
                    set::className('item checkbox-primary px-4 overflow-hidden'),
                    checkbox(set::id('checkAllProducts'), set::text($lang->productCommon), set('data-on', 'change'), set('data-call', 'changeAllProducts'))
                ),
                cell
                (
                    set::width('1/2'),
                    set::className('item checkbox-primary px-4'),
                    checkbox(set::id('checkAllSprints'), set::text($lang->projectCommon), set('data-on', 'change'), set('data-call', 'changeAllSprints'))
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
            set::className('border ml-4 p-4'),
            set::id('programBox'),
            $createProgram($data)
        )
    );
};
