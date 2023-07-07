<?php
declare(strict_types=1);
/**
 * The tao file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easysoft.ltd>
 * @package     metric
 * @link        https://www.zentao.net
 */

class metricTao extends metricModel
{
    /**
     * Get root of metric calculator.
     *
     * @access public
     * @return string
     */
    public function getCalcRoot()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'calc' . DS;
    }

    /**
     * Get path of calculator data set.
     *
     * @access public
     * @return string
     */
    public function getDatasetPath()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'dataset.php';
    }
}
