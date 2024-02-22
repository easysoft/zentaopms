<?php
declare(strict_types=1);
/**
* The singleproductplanblock view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Mengyi Liu <liumengyi@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

jsVar('delay', $lang->block->delay);

if(!$longBlock)
{
    unset($config->block->plan->dtable->fieldList['id']);
    unset($config->block->plan->dtable->fieldList['product']);
    unset($config->block->plan->dtable->fieldList['hour']);
    unset($config->block->plan->dtable->fieldList['bugs']);
}
else
{
    $config->block->plan->dtable->fieldList['product']['map'] = $products;
    foreach($plans as $plan) $plan->hour .= $config->hourUnit;
}

blockPanel
(
    setClass('plan-block list-block'),
    dtable
    (
        set::height(318),
        set::fixedLeftWidth($longBlock ? '0.33' : '0.5'),
        set::onRenderCell(jsRaw('window.onRenderPlanNameCell')),
        set::cols(array_values($config->block->plan->dtable->fieldList)),
        set::data(array_values($plans))
    )
);

render();
