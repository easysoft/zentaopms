<?php
declare(strict_types=1);
/**
* The plan list block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      LiuRuoGu <liuruogu@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

if(!$longBlock)
{
    unset($config->block->plan->dtable->fieldList['id']);
    unset($config->block->plan->dtable->fieldList['product']);
    unset($config->block->plan->dtable->fieldList['hour']);
    unset($config->block->plan->dtable->fieldList['bugs']);
}

foreach($plans as $plan) $plan->product = zget($products, $plan->product); 

panel
(
    set('class', 'plan-block'),
    dtable
    (
        set::cols(array_values($config->block->plan->dtable->fieldList)),
        set::data(array_values($plans))
    )
);

render();
