<?php
declare(strict_types=1);
/**
* The scrumtest block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

if(!$longBlock)
{
    unset($config->block->testtask->dtable->fieldList['id']);
    unset($config->block->testtask->dtable->fieldList['product']);
    unset($config->block->testtask->dtable->fieldList['build']);
}

panel
(
    setClass('p-0 scrumlist-block list-block ' . ($longBlock ? 'block-long' : 'block-sm')),
    set::title($block->title),
    set::headingClass('border-b'),
    to::headingActions
    (
        a
        (
            set('class', 'text-gray'),
            set('href', $block->moreLink),
            $lang->more,
            icon('caret-right')
        )
    ),
    dtable
    (
        set::height(318),
        set::horzScrollbarPos('inside'),
        set::fixedLeftWidth($longBlock ? '0.33' : '0.5'),
        set::cols(array_values($config->block->testtask->dtable->fieldList)),
        set::data(array_values($testtasks))
    )
);

render();
