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
                        set::class('sprintItem mb-2'),
                        checkbox(set::id("sprints-{$line->id}-{$productID}-{$sprint->id}"), set::name("sprints[$line->id][$productID][]"), set::text($sprint->name), set::value($sprint->id), set('data-on', 'change'), set('data-call', 'changeSprints'), set('data-params', 'event'), set('data-line', $line->id), set('data-product', $productID), set('data-begin', $sprint->begin), set('data-end', $sprint->end), set('data-status', $sprint->status), set('data-pm', $sprint->PM)),
                        input(set::class('hidden'), set::name("sprintIdList[$line->id][$productID][$sprint->id]"), set::value($sprint->id))
                    );
                }
            }
            $lineGroups[] = div
            (
                set::class('flex mb-2'),
                cell
                (
                    set::width('1/2'),
                    set::class('productItem p-4 overflow-hidden'),
                    set::style(array('background-color' => 'var(--color-gray-50)')),
                    checkbox(set::id("products-{$line->id}-{$productID}"), set::name("products[{$line->id}][]"), set::text($product->name), set::value($productID), set('data-on', 'change'), set('data-call', 'changeProducts'), set('data-params', 'event'), set('data-line', $line->id), set('data-productid', $productID), set('data-begin', $product->createdDate), set('data-programid', $product->program)),
                    input(set::class('hidden'), set::name("productIdList[$line->id][$productID]"), set::value($productID))
                ),
                cell
                (
                    set::width('1/2'),
                    set::class('p-4 overflow-hidden'),
                    set::style(array('background-color' => 'var(--color-gray-50)')),
                    $productGroups
                )
            );
        }
        $checkBoxGroup[] = div
        (
            set::class('flex'),
            cell
            (
                set::width('1/3'),
                set::class('productList px-4 flex items-center border-t mr-2 overflow-hidden'),
                set::style(array('background-color' => 'var(--color-gray-50)')),
                checkbox(set::id("productLines{$line->id}"),set::name("productLines[$line->id][]"), set::text($line->name), set::value($line->id), set('data-on', 'change'), set('data-call', 'changeLines'), set('data-params', 'event'))
            ),
            cell
            (
                set::flex('1'),
                set::class('productList'),
                div
                (
                    set::class('scroll-handle'),
                    $lineGroups
                )
            )
        );
    }

    return div
    (
        set::class('flex mt-4'),
        cell
        (
            set::id('source'),
            set::width('1/2'),
            set::class('border p-4 overflow-hidden'),
            div
            (
                set::class('flex'),
                cell
                (
                    set::width('1/3'),
                    set::class('item checkbox-primary px-4 overflow-hidden mr-3'),
                    checkbox(set::id('checkAllLines'), set::text($lang->upgrade->allLines), set('data-on', 'change'), set('data-call', 'changeAllLines'))
                ),
                cell
                (
                    set::width('1/3'),
                    set::class('item checkbox-primary px-4 overflow-hidden'),
                    checkbox(set::id('checkAllProducts'), set::text($lang->productCommon), set('data-on', 'change'), set('data-call', 'changeAllProducts'))
                ),
                cell
                (
                    set::width('1/3'),
                    set::class('item checkbox-primary px-4 overflow-hidden'),
                    checkbox(set::id('checkAllSprints'), set::text($lang->projectCommon), set('data-on', 'change'), set('data-call', 'changeAllSprints'))
                ),
            ),
            div
            (
                set::class('mt-4'),
                $checkBoxGroup
            )
        ),
        cell
        (
            set::width('1/2'),
            set::class('border ml-4 p-4'),
            set::id('programBox'), $createProgram($data)
        )
    );
};
