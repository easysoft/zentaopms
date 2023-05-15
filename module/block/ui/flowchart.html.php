<?php
declare(strict_types=1);
/**
* The flow chart view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Wangyuting <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

/**
 * 展示流程图。
 * Print flow chart. 
 */
function printFlowchart()
{
    global $lang;

    $charts = array();
    foreach($lang->block->flowchart as $flowName => $flow)
    {
        $items = array();
        $index = 0;
        foreach ($flow as $flowItem)
        {
            $items[] = div
            (
                set('class', 'flow-item flow-item-' . $index++),
                div
                (
                    set('class', 'flow-item-display'),
                    $flowItem
                )
            ); 
        }
        $charts[] = div
        (
            set('class', 'row flow-chart-row row-' . $flowName),
            $items
        );
    }

    return div(set('class', 'flowchart p-6'), $charts);
}
