<?php
/**
 * The model file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easycorp.ltd>
 * @package     metric
 * @version     $Id: model.php 5145 2013-07-15 06:47:26Z zhouxin@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class metricModel extends model
{
    /**
     * Get metric func root.
     *
     * @access public
     * @return string
     */
    public function getFuncRoot()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'func' . DS;
    }

    /**
     * Get metric data set path.
     *
     * @access public
     * @return string
     */
    public function getDatasetPath()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'dataset.php';
    }

    /**
     * Get executable metric code list.
     *
     * @access public
     * @return array
     */
    public function getExecutableMetric()
    {
        $currentWeek = date('w');
        $currentDay  = date('d');
        $now         = date('H:i');

        $metricList = $this->select('code,collectConf')->from(TABLE_BASICMEAS)
            ->where('collectType')->eq('cron')
            ->fetchAll();

        $excutableMetrics = array();
        foreach($metricList as $metric)
        {
            if($metric->cronType == 'week' and strpos($metric->cronList, $currentWeek) === false)  continue;
            if($metric->cronType == 'month' and strpos($metric->cronList, $currentDay) === false) continue;
            if($now >= $metric->execTime) continue;

            $excutableMetric[] = $metric;
        }
        return $excutableMetrics;
    }

    /**
     * Get metric list.
     *
     * @access public
     * @return array
     */
    public function getMetricList()
    {
        $funcRoot = $this->getFuncRoot();

        $fileList = array();
        foreach($this->config->metric->scopeList as $scope)
        {
            foreach($this->config->metric->purposeList as $purpose)
            {
                $pattern = $funcRoot . $scope . DS . $purpose . DS . '*.php';
                $matchedFiles = glob($pattern);
                if($matchedFiles !== false) $fileList = array_merge($fileList, $matchFiles);
            }
        }

        $metricList = array();
        $excutableMetric = $this->getExecutableMetric();
        foreach($fileList as $file)
        {
            $code = rtrim(basename($file), '.php');
            if(!in_array($code, $excutableMetric)) continue;
            $metricList[$code] = $file;
        }

        return $metricList;
    }

    /**
     * Get metric instance list.
     *
     * @access public
     * @return mixed
     */
    public function getInstanceList()
    {
        $metricList = $this->getMetricList();

        $metricInstances = array();
        foreach($metricList as $className => $file)
        {
            include_once $file;
            $metricInstances[$className] = new $className;
        }

        return $metricInstances;
    }

    /**
     * Get instance of data set object.
     *
     * @access public
     * @return mixed
     */
    public function getDataset()
    {
        $datasetPath = $this->getDatasetPath();
        include $datasetPath;
        return new dataset($this->dao);
    }

    /**
     * Classify metric by its data set.
     *
     * @param  array  $metricInstances
     * @access public
     * @return array
     */
    public function classifyMetric($metricInstances)
    {
        $classifiedInstances = array();
        $otherInstances      = array();
        foreach($metricInstances as $instance)
        {
            if(empty($instance->dataset))
            {
                $otherInstances[] = $instance;
                continue;
            }

            $dataset = $instance->dataset;
            if(!isset($classifiedInstances[$dataset])) $classifiedInstances[$dataset] = array();
            $classifiedInstances[$dataset][] = $instance;
        }
        return array($otherInstances, $classifiedInstances);
    }

    /**
     * Unite field list of each metric.
     *
     * @param  array  $metricInstances
     * @access public
     * @return string
     */
    public function uniteFieldList($metricInstances)
    {
        $fieldList = array();
        foreach($metricInstances as $metricInstance) $fieldList  = array_merge($fieldList, $metricInstance->fieldList);
        return implode(',', array_unique($fieldList));
    }
}
