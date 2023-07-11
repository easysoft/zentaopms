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
     * 根据代号获取计算实时度量项的结果。
     * Get result of calculate metric by code.
     *
     * @param  string $code
     * @param  array  $options
     * @access public
     * @return array
     */
    public function getResultByCode($code, $options = array())
    {
        $metric = $this->dao->select('id,code,scope,purpose')->from(TABLE_METRIC)->where('code')->eq($code)->fetch();
        if(!$metric) return false;

        $calcPath = $this->metricTao->getCalcRoot() . $metric->scope . DS . $metric->purpose . DS . $metric->code . '.php';
        if(!is_file($calcPath)) return false;

        include $this->metricTao->getBaseCalcPath();
        include $calcPath;
        $calculator = new $metric->code;

        $rows = $calculator->getStatement($this->dao)->fetchAll();
        foreach($rows as $row) $calculator->calculate($row);

        return $this->metricTao->filterByOptions($calculator->getResult(), $options);
    }

    /**
     * 根据代号列表批量获取度量项的结果。
     * Get result of calculate metric by code list.
     *
     * @param  array $codes
     * @param  array $options
     * @access public
     * @return array
     */
    public function getResultByCodes($codes, $options = array())
    {
        $results = array();
        foreach($codes as $code)
        {
            $result = $this->getResultByCode($code, $options);
            if($result) $results[$code] = $result;
        }

        return $results;
    }

    /**
     * Get executable metric list.
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
     * Get executable calculator list.
     *
     * @access public
     * @return array
     */
    public function getExecutableCalcList()
    {
        $funcRoot = $this->metricTao->getCalcRoot();

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
     * Get calculator instance list.
     *
     * @access public
     * @return array
     */
    public function getCalcInstanceList()
    {
        $calcList = $this->getExecutableCalcList();

        include $this->app->getModuleRoot() . DS . 'metric' . DS . 'calc.class.php';
        $calcInstances = array();
        foreach($calcList as $id => $calc)
        {
            $file      = $calc->file;
            $className = $calc->code;

            require_once $file;
            $metricInstance = new $className;
            $metricInstance->id = $id;

            $calcInstances[$className] = $metricInstance;
        }

        return $calcInstances;
    }

    /**
     * Get instance of data set object.
     *
     * @access public
     * @return dataset
     */
    public function getDataset()
    {
        $datasetPath = $this->metricTao->getDatasetPath();
        include $datasetPath;
        return new dataset($this->dao);
    }

    /**
     * Classify calculator instance list by its data set.
     *
     * @param  array  $calcInstanceList
     * @access public
     * @return array
     */
    public function classifyCalc($calcList)
    {
        $datasetCalcGroup = array();
        $otherCalcList    = array();
        foreach($calcList as $calc)
        {
            if(empty($calc->dataset))
            {
                $otherCalcList[] = $calc;
                continue;
            }

            $dataset = $calc->dataset;
            if(!isset($datasetCalcGroup[$dataset])) $datasetCalcGroup[$dataset] = array();
            $datasetCalcGroup[$dataset][] = $calc;
        }

        $classifiedCalcGroup = array();
        foreach($datasetCalcGroup as $dataset => $calcList)
        {
            $classifiedCalcGroup[] = (object)array('dataset' => $dataset, 'calcList' => $calcList);
        }

        foreach($otherCalcList as $calc)
        {
            $classifiedCalcGroup[] = (object)array('dataset' => '', 'calcList' => $calcList);
        }
        return $classifiedCalcGroup;
    }

    /**
     * Unite field list of each calc.
     *
     * @param  array  $calcList
     * @access public
     * @return string
     */
    public function uniteFieldList($calcList)
    {
        $fieldList = array();
        foreach($calcList as $calcInstance) $fieldList  = array_merge($fieldList, $calcInstance->fieldList);
        return implode(',', array_unique($fieldList));
    }
}
