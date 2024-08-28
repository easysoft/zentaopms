<?php
declare(strict_types=1);
/**
 * The drill data modal view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::titleClass('text-lg font-bold'),
    set::title($lang->pivot->stepDrill->drillView)
);

foreach($datas as $index => $data)
{
    foreach($data as $key => $value)
    {
        foreach($cols as $col)
        {
            if(isset($col['name']) && $col['name'] != $key) continue;
            if(isset($col['type']) && $col['type'] == 'user' && is_string($value) && strpos($value, ',') !== false) $datas[$index]->$key = explode(',', $value);
        }
    }
}

dtable
(
    isset($from) && $from == 'screen' ? set::_class('dark') : null,
    set::striped(true),
    set::bordered(true),
    set::cols($cols),
    set::data($datas),
    set::userMap($users),
    set::onRenderCell(jsRaw('window.renderDrillResult'))
);
