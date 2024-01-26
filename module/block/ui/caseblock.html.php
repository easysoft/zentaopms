<?php
declare(strict_types=1);
/**
* The case block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

if(!$longBlock)
{
    unset($config->block->case->dtable->fieldList['pri']);
    unset($config->block->case->dtable->fieldList['type']);
    unset($config->block->case->dtable->fieldList['status']);
    unset($config->block->case->dtable->fieldList['lastRunDate']);
}

foreach($cases as $case) $case->lastRunDate = formatTime($case->lastRunDate, DT_DATE1);

blockPanel
(
    setClass('case-block list-block'),
    dtable
    (
        setID('case'),
        set::height(320),
        set::bordered(false),
        set::horzScrollbarPos('inside'),
        set::cols(array_values($config->block->case->dtable->fieldList)),
        set::data(array_values($cases))
    )
);

render();
