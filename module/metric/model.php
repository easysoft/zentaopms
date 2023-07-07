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
    public function getCalcRoot()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'calc' . DS;
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

        $metricList = $this->dao->select('id,code,crontab,cronList,time')->from(TABLE_METRIC)
            ->where('when')->eq('cron')
            ->fetchAll();

        $excutableMetrics = array();
        foreach($metricList as $metric)
        {
            if($metric->crontab == 'week' and strpos($metric->cronList, $currentWeek) === false)  continue;
            if($metric->crontab == 'month' and strpos($metric->cronList, $currentDay) === false) continue;
            if($now < $metric->time) continue;

            $excutableMetrics[$metric->id] = $metric->code;
        }
        return $excutableMetrics;
    }

    /**
     * Get calc list.
     *
     * @access public
     * @return array
     */
    public function getCalcList()
    {
        $funcRoot = $this->getCalcRoot();

        $fileList = array();
        foreach($this->config->metric->scopeList as $scope)
        {
            foreach($this->config->metric->purposeList as $purpose)
            {
                $pattern = $funcRoot . $scope . DS . $purpose . DS . '*.php';
                $matchedFiles = glob($pattern);
                if($matchedFiles !== false) $fileList = array_merge($fileList, $matchedFiles);
            }
        }

        $calcList = array();
        $excutableMetric = $this->getExecutableMetric();
        foreach($fileList as $file)
        {
            $code = rtrim(basename($file), '.php');
            if(!in_array($code, $excutableMetric)) continue;
            $id = array_search($code, $excutableMetric);

            $calc = new stdclass();
            $calc->code = $code;
            $calc->file = $file;
            $calcList[$id] = $calc;
        }

        return $calcList;
    }

    /**
     * Get calc instance list.
     *
     * @access public
     * @return mixed
     */
    public function getCalcInstanceList()
    {
        $metricList = $this->getCalcList();

        include $this->app->getModuleRoot() . DS . 'metric' . DS . 'func.class.php';
        $metricInstances = array();
        foreach($metricList as $id => $metric)
        {
            $file      = $metric->file;
            $className = $metric->code;

            require_once $file;
            $metricInstance = new $className;
            $metricInstance->id = $id;

            $metricInstances[$className] = $metricInstance;
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
    public function classifyCalc($metricInstances)
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
