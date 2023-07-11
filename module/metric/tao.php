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
     * 获取度量项计算文件的根目录。
     * Get root of metric calculator.
     *
     * @access protected
     * @return string
     */
    protected function getCalcRoot()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'calc' . DS;
    }

    /**
     * 获取数据集文件的路径
     * Get path of calculator data set.
     *
     * @access protected
     * @return string
     */
    protected function getDatasetPath()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'dataset.php';
    }

    /**
     * 获取度量项基类文件的路径。
     * Get path of base calculator class.
     *
     * @access protected
     * @return string
     */
    protected function getBaseCalcPath()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'calc.class.php';
    }

    /**
     * 根据选项过滤数据。
     * Filter rows by options.
     *
     * @param  array|int $rows
     * @param  array     $options
     * @access protected
     * @return array
     */
    protected function filterByOptions($rows, $options)
    {
        if(empty($options)) return $rows;

        $rows = (array)$rows;
        $options = $this->expandOptions($options);

        $filteredRows = array();
        foreach($options as $scope => $option)
        {
            foreach($rows as $row)
            {
                if(!isset($row->$scope)) continue;
                if(in_array($row->$scope, $option)) $filteredRows[] = $row;
            }
        }

        return $filteredRows;
    }

    /**
     * 扩展选项。
     * Expand options.
     *
     * @param  array  $options
     * @access protected
     * @return array
     */
    protected function expandOptions($options)
    {
        foreach($options as $scope => $option) $options[$scope] = explode(',', $option);
        return $options;
    }
}
