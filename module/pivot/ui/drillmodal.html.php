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
    set::title($lang->pivot->step3->drillView)
);

dtable
(
    set::striped(true),
    set::bordered(true),
    set::cols($cols),
    set::data($datas),
    set::onRenderCell(jsRaw('window.renderDrillResult'))
);
