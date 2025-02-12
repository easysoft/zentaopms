<?php
declare(strict_types=1);
/**
 * The dashboard view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;

$status   = zget($cneMetrics, 'status', 'unknown');
$pageInfo = usePager();

$cpuInfo['tip']    = trim(substr($cpuInfo['tip'], strpos($cpuInfo['tip'], '=') + 1));
$memoryInfo['tip'] = trim(substr($memoryInfo['tip'], strpos($memoryInfo['tip'], '=') + 1));

jsVar('inQuickon',      $config->inQuickon);
jsVar('cpuInfo',        $cpuInfo);
jsVar('memoryInfo',     $memoryInfo);
jsVar('instanceIdList', helper::arrayColumn($instances, 'id'));

/* 资源统计 */
div
(
    setClass('bg-white p-5'),
    div(setClass('text-xl font-semibold mb-5'), $lang->system->cneStatistic),
    div
    (
        setClass('flex'),
        div
        (
            setClass('basis-2/5 border h-40 rounded-md row'),
            div
            (
                setClass('basis-1/2 justify-center pl-6 pt-10 pb-10'),
                div
                (
                    setClass('flex row basis-2/3 justify-evenly h-full ml-5'),
                    icon(zget($this->lang->CNE->statusIcons, $status), set::size(30), setClass('app-status-circle status-' . $status)),
                    div
                    (
                        setClass('p-2 ml-8 pr-6 flex col justify-between normal'),
                        setStyle('white-space', 'nowrap'),
                        div(setClass('text-xl font-semibold'), zget($lang->CNE->statusList, $status)),
                        $lang->system->cneStatus
                    ),
                    div(),
                    div(setClass('p-4 border-r'))
                )
            ),
            div
            (
                setClass('basis-1/2 justify-center p-6 pt-10 pb-10'),
                div
                (
                    setClass('flex row basis-2/3 justify-evenly text-center h-full'),
                    div
                    (
                        setClass('flex col justify-between'),
                        div(setClass('text-4xl font-semibold text-primary'), $cneMetrics->node_count),
                        $lang->system->nodeQuantity
                    ),
                    div
                    (
                        setClass('flex col justify-between'),
                        div(setClass('text-4xl font-semibold text-primary'), zget($pageInfo, 'recTotal', 0)),
                        $lang->system->serviceQuantity
                    )
                )
            )
        ),
        div
        (
            setClass('basis-3/5 h-40 flex row justify-evenly ml-3'),
            div
            (
                setClass(' flex row'),
                div
                (
                    zui::ProgressCircle
                    (
                        set::percent($cpuInfo['rate']),
                        set::size(160),
                        set::circleColor($cpuInfo['color']),
                        set::circleWidth(8),
                        set::text('')

                    ),
                    setClass('relative'),
                    span
                    (
                        setClass('absolute text-lg'),
                        setStyle(array('transform' => 'translate(-50%, -50%)', 'top' => '50%', 'left' => '50%', 'white-space' => 'nowrap')),
                        icon('cpu', setClass('mr-1'), set::size(20),setStyle('color', $cpuInfo['color'])),
                        $lang->system->cpuUsage
                    )
                ),
                div
                (
                    setClass('flex col justify-around p-5'),
                    div(setClass('text-4xl font-medium'), $cpuInfo['rate'], span('%', setClass('text-xl ml-1'))),
                    $cpuInfo['tip']
                )
            ),
            div
            (
                setClass(' flex row'),
                div
                (
                    zui::ProgressCircle
                    (
                        set::percent($memoryInfo['rate']),
                        set::size(160),
                        set::circleColor($memoryInfo['color']),
                        set::circleWidth(8),
                        set::text('')

                    ),
                    setClass('relative'),
                    span
                    (
                        setClass('absolute text-lg'),
                        setStyle(array('transform' => 'translate(-50%, -50%)', 'top' => '50%', 'left' => '50%', 'white-space' => 'nowrap')),
                        icon('memory', setClass('mr-1'), set::size(20), setStyle('color', $memoryInfo['color'])),
                        $lang->system->memUsage
                    )
                ),
                div
                (
                    setClass('flex col justify-around p-5'),
                    div(setClass('text-4xl font-medium'), $memoryInfo['rate'], span('%', setClass('text-xl ml-1'))),
                    $memoryInfo['tip']
                )
            )
        )
    )
);

/* 运行中服务 */
$instances = initTableData($instances, $config->system->dtable->instanceList->fieldList, $this->system);
div
(
    setClass('bg-white p-5 mt-5'),
    div(setClass('text-xl font-semibold mb-5'), $lang->instance->runningService),
    dtable
    (
        set::cols($config->system->dtable->instanceList->fieldList),
        set::data($instances),
        set::loadPartial(true),
        set::onRenderCell(jsRaw('window.renderInstanceList')),
        set::footPager(usePager())
    )
);
