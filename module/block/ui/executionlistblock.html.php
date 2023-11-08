<?php
declare(strict_types=1);
/**
* The executionlist block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

if(!$longBlock)
{
    unset($config->block->execution->dtable->fieldList['status']);
    unset($config->block->execution->dtable->fieldList['totalEstimate']);
    unset($config->block->execution->dtable->fieldList['totalConsumed']);
    unset($config->block->execution->dtable->fieldList['burns']);
}

blockPanel
(
    dtable
    (
        set::height(318),
        set::bordered(false),
        set::horzScrollbarPos('inside'),
        set::cols(array_values($config->block->execution->dtable->fieldList)),
        set::data(array_values($executions))
    )
);

render();
