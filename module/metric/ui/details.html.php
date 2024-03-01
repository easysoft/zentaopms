<?php
declare(strict_types=1);
/**
 * The implement file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin<zhouxin@easycorp.ltd>
 * @package     metric
 * @link        http://www.zentao.net
 */
namespace zin;

$buildItems = function($items): array
{
    $itemList = array();
    foreach($items as $item)
    {
        $itemList[] = item
        (
            set::name($item['name']),
            !empty($item['href']) ? a
            (
                set::href($item['href']),
                !empty($item['attr']) && is_array($item['attr']) ? set($item['attr']) : null,
                html($item['text'])
            ) : html($item['text']),
            set::collapse(!empty($item['text']))
        );
    }

    return $itemList;
};

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::level(1),
            set::text($lang->metric->details)
        )
    )
);

panel
(
    setClass('clear-shadow'),
    set::bodyClass('relative'),
    div
    (
        div
        (
            setClass('border-b border-gray-100 my-2 py-2'),
            span
            (

                $lang->metric->legendBasicInfo,
                setClass('bg-gray-100 text-md font-bold px-2 py-3')
            )
        ),
        div
        (
            tableData
            (
                $buildItems($legendBasic)
            )
        ),
        div
        (
            setClass('border-b border-gray-100 my-2 py-2'),
            span
            (
                $lang->metric->legendCreateInfo,
                setClass('bg-gray-100 text-md font-bold px-2 py-3')
            )
        ),
        div
        (
            tableData
            (
                $buildItems($createEditInfo)
            )
        )
    )
);

render();
