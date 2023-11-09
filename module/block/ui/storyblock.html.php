<?php
declare(strict_types=1);
/**
* The story block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

if(!$longBlock)
{
    unset($config->block->story->dtable->fieldList['status']);
    unset($config->block->story->dtable->fieldList['category']);
    unset($config->block->story->dtable->fieldList['estimate']);
    unset($config->block->story->dtable->fieldList['stage']);
}
else
{
    foreach($stories as $story) $story->estimate .= $config->hourUnit;
}

blockPanel
(
    setClass('list-block'),
    dtable
    (
        set::height(318),
        set::fixedLeftWidth($longBlock ? '0.5' : '0.8'),
        set::cols(array_values($config->block->story->dtable->fieldList)),
        set::data(array_values($stories))
    )
);

render();
