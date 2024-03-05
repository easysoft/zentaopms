<?php
declare(strict_types=1);
/**
 * The bugblock view file of block module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     block
 * @link        https://www.zentao.net
 */
namespace zin;

if(!$longBlock)
{
    unset($config->block->bug->dtable->fieldList['id']);
    unset($config->block->bug->dtable->fieldList['confirmed']);
    unset($config->block->bug->dtable->fieldList['deadline']);
}

blockPanel
(
    setClass('bug-block list-block'),
    dtable
    (
        setID('bug-list'),
        set::height(318),
        set::bordered(false),
        set::horzScrollbarPos('inside'),
        set::fixedLeftWidth('50%'),
        set::cols(array_values($config->block->bug->dtable->fieldList)),
        set::data(array_values($bugs))
    )
);

render();
