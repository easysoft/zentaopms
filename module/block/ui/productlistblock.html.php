<?php
declare(strict_types=1);
/**
* The product list block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      LiuRuoGu <liuruogu@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

foreach($productStats as $product)
{
    if(!empty($product->PO))
    {
        $product->po        = $product->PO;
        $product->poName    = zget($users, $product->PO);
        $product->poAvatar  = zget($userAvatars, $product->PO);
        $product->poAccount = $product->PO;
    }
}

panel
(
    dtable
    (
        setClass('shadow rounded'),
        set::cols(array_values($config->block->product->dtable->fieldList)),
        set::data(array_values($productStats))
    )
);

render();
