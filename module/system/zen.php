<?php
declare(strict_types=1);
/**
 * The zen file of system module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     system
 * @link        https://www.zentao.net
 */
class systemZen extends system
{
    /**
     * 获取CPU使用率。
     * Get CPU usage.
     *
     * @param  object    $metrics
     * @access protected
     * @return array
     */
    protected function getCpuUsage(object $metrics): array
    {
        $rate = $metrics->rate;
        $tip  = "{$rate}% = {$metrics->usage} / {$metrics->capacity}";

        if(empty($color) && $rate == 0)               $color = 'gray';
        if(empty($color) && $rate > 0 && $rate < 50)  $color = 'var(--color-secondary-500)';
        if(empty($color) && $rate >= 0 && $rate < 70) $color = 'var(--color-warning-500)';
        if(empty($color) && $rate >= 0 && $rate < 90) $color = 'var(--color-important-500)';
        if(empty($color) && $rate >= 80)              $color = 'var(--color-danger-500)';

        return array('color' => $color, 'tip' => $tip, 'rate' => $rate);
    }

    /**
     * 获取内存使用率。
     * Get memory usage.
     *
     * @param  object    $metrics
     * @access protected
     * @return array
     */
    protected function getMemUsage(object $metrics): array
    {
        $rate = $metrics->rate;
        $tip  = "{$rate}% = " . helper::formatKB($metrics->usage) . ' / ' . helper::formatKB($metrics->capacity);

        if(empty($color) && $rate == 0)               $color = 'gray';
        if(empty($color) && $rate > 0 && $rate < 50)  $color = 'var(--color-secondary-500)';
        if(empty($color) && $rate >= 0 && $rate < 70) $color = 'var(--color-warning-500)';
        if(empty($color) && $rate >= 0 && $rate < 90) $color = 'var(--color-important-500)';
        if(empty($color) && $rate >= 80)              $color = 'var(--color-danger-500)';

        return array('color' => $color, 'tip' => $tip, 'rate' => $rate);
    }
}
