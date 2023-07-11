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
    unset($config->block->product->dtable->fieldList['activeBugs']);
}

foreach($productStats as $product)
{
    if(!empty($product->PO))
    {
        $product->PO        = zget($users, $product->PO);
        $product->POAvatar  = zget($userAvatars, $product->PO);
        $product->POAccount = $product->PO;
    }
}

panel
(
    setClass('p-0'),
    set::title($block->title),
    set::bodyClass('p-0 no-shadow border-t'),
    to::headingActions
    (
        hasPriv('product', 'all') ? h::nav
        (
            setClass('toolbar'),
            btn
            (
                setClass('ghost toolbar-item size-sm z-10'),
                set::url(createLink('product', 'all', "browseType={$block->params->type}")),
                $lang->more,
                span(setClass('caret-right')),
            )
        ) : '',
    ),
    dtable
    (
        set::height(318),
        set::shadow(false),
        set::fixedLeftWidth('0.25'),
        set::cols(array_values($config->block->product->dtable->fieldList)),
        set::data(array_values($productStats))
    )
);

render();
