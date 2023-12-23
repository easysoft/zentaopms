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

if(!$longBlock)
{
    unset($config->block->product->dtable->fieldList['progress']);
    unset($config->block->product->dtable->fieldList['plans']);
    unset($config->block->product->dtable->fieldList['unresolvedBugs']);
}

foreach($productStats as $product)
{
    if(!empty($product->PO))
    {
        $product->PO        = zget($users, $product->PO);
        $product->POAvatar  = zget($avatarList, $product->PO);
        $product->POAccount = $product->PO;
    }
    $product->progress = $product->totalStories == 0 ? 0 : round($product->closedStories / $product->totalStories * 100);
    if($product->progress) $product->progress .= '%';
}

blockPanel
(
    setClass('list-block'),
    dtable
    (
        setID('product-list'),
        set::height(318),
        set::bordered(false),
        set::horzScrollbarPos('inside'),
        set::fixedLeftWidth('0.25'),
        set::cols(array_values($config->block->product->dtable->fieldList)),
        set::data(array_values($productStats))
    )
);

render();
