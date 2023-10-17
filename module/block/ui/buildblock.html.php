<?php
declare(strict_types=1);
/**
 * The build block view file of build module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     build
 * @link        https://www.zentao.net
 */
namespace zin;

if(!$longBlock)
{
    unset($config->block->build->dtable->fieldList['product']);
    unset($config->block->build->dtable->fieldList['project']);
}

panel
(
    set::title($block->title),
    set::className('build-block list-block ' . ($longBlock ? 'block-long' : 'block-sm')),
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
        set::bordered(false),
        set::fixedLeftWidth('0.5'),
        set::horzScrollbarPos('inside'),
        set::cols(array_values($config->block->build->dtable->fieldList)),
        set::data(array_values($builds)),
    )
);

render();
