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
                    set::class('sprintItem mb-2'),
                    checkbox(set::id("sprints[{$productID}]"), set::name("sprints[{$productID}]"), set::text($sprint->name), set::value($sprint->id)),
                    input(set::class('hidden'), set::name("sprintIdList[{$productID}][{$sprint->id}]"), set::value($sprint->id))
                );
            }
        }
        $checkBoxGroup[] = div
        (
            set::class('mt-4 flex py-4'),
            set::style(array('background-color' => 'var(--color-gray-50)')),
            cell
            (
                set::width('1/2'),
                set::class('productList px-4 flex items-center overflow-hidden'),
                checkbox(set::id('products'), set::text($product->name), set::value($product->id))
            ),
            cell
            (
                set::width('1/2'),
                set::class('productList px-4'),
                div
                (
                    set::class('scroll-handle'),
                    $productGroups
                )
            )
        );
    }

    $content = '';
    if($data->noMergedProductCount) $content .= sprintf($lang->upgrade->productCount, $data->noMergedProductCount) . ',';
    if($data->noMergedSprintCount)  $content .= sprintf($lang->upgrade->projectCount, $data->noMergedSprintCount) . ',';
    $content = rtrim(',', $content);
    return div
    (
        div
        (
            set::style(array('background-color' => 'var(--color-secondary-50)')),
            set::class('p-4'),
            div(set::class('text-secondary'), sprintf($lang->upgrade->mergeSummary, $content)), div(set::class('text-secondary'), html($lang->upgrade->mergeByProject))
        ),
        div
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
                        set::width('1/2'),
                        set::class('item checkbox-primary px-4 overflow-hidden'),
                        checkbox(set::id('checkAllProducts'), set::text($lang->productCommon))
                    ),
                    cell
                    (
                        set::width('1/2'),
                        set::class('item checkbox-primary px-4'),
                        checkbox(set::id('checkAllProjects'), set::text($lang->projectCommon))
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
                set::class('border ml-4 p-4'),
                set::id('programBox'), $createProgram($data)
            )
        )
    );
};
