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
     * 获取度量项数据列表。
     * Get metric data list.
     *
     * @param  string $type
     * @param  int    $queryID
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array|false
     */
    public function getList($type = '', $queryID = 0, $sort = '', $pager = null)
    {
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('metricQuery', $query->sql);
                $this->session->set('metricForm', $query->form);
            }
            else
            {
                $this->session->set('metricQuery', ' 1 = 1');
            }
        }

        $metrics = $this->dao->select('*')->from(TABLE_METRIC)
            ->where('deleted')->eq('0')
            ->beginIF($this->session->metricQuery)->andWhere($this->session->metricQuery)->fi()
            ->orderBy($sort)
            ->page($pager)
            ->fetchAll();

        return $metrics;
    }

    /**
     * 根据代号获取度量项信息。
     * Get metric info by code.
     *
     * @param  string       $code
     * @param  string|array $fieldList
     * @access public
     * @return object|false
     */
    public function getByCode($code, $fieldList = '*')
    {
        if(is_array($fieldList)) $fieldList = implode(',', $fieldList);
        return $this->dao->select($fieldList)->from(TABLE_METRIC)->where('code')->eq($code)->fetch();
    }

    /**
     * 获取度量项数据源句柄。
     * Get data source statement of calculator.
     *
     * @param  object $calculator
     * @access public
     * @return PDOStatement|string
     */
    public function getDataStatement($calculator, $returnType = 'statement')
    {
        if(!empty($calculator->dataset))
        {
            include_once $this->metricTao->getDatasetPath();

            $dataset    = new dataset($this->dao);
            $dataSource = $calculator->dataset;
            $fieldList  = implode(',', $calculator->fieldList);

            $statement = $dataset->$dataSource($fieldList);
            $sql       = $dataset->dao->get();
        }
        else
        {
            $calculator->setDAO($this->dao);

            $statement = $calculator->getStatement();
            $sql       = $calculator->dao->get();
        }

        return $returnType == 'sql' ? $sql : $statement;
    }

    /**
     * 根据代号获取计算实时度量项的结果。
     * Get result of calculate metric by code.
     *
     * @param  string $code
     * @param  array  $options e.g. array('product' => '1,2,3', 'year' => '2023')
     * @access public
     * @return array
     */
    public function getResultByCode($code, $options = array())
    {
        $metric = $this->dao->select('id,code,scope,purpose')->from(TABLE_METRIC)->where('code')->eq($code)->fetch();
        if(!$metric) return false;

        $calcPath = $this->metricTao->getCalcRoot() . $metric->scope . DS . $metric->purpose . DS . $metric->code . '.php';
        if(!is_file($calcPath)) return false;

        include_once $this->metricTao->getBaseCalcPath();
        include_once $calcPath;
        $calculator = new $metric->code;

        $statement = $this->getDataStatement($calculator);
        $rows = $statement->fetchAll();

        foreach($rows as $row) $calculator->calculate($row);
        return $calculator->getResult($options);
    }

    /**
     * 根据代号列表批量获取度量项的结果。
     * Get result of calculate metric by code list.
     *
     * @param  array $codes   e.g. array('code1', 'code2')
     * @param  array $options e.g. array('product' => '1,2,3', 'year' => '2023')
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
     * 获取可计算的度量项列表。
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
     * 获取可计算的度量项对象列表。
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
     * 获取度量项计算实例列表。
     * Get calculator instance list.
     *
     * @access public
     * @return array
     */
    public function getCalcInstanceList()
    {
        $calcList = $this->getExecutableCalcList();

        include $this->metricTao->getBaseCalcPath();
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
     * 获取通用数据集对象。
     * Get instance of data set object.
     *
     * @access public
     * @return dataset
     */
    public function getDataset()
    {
        $datasetPath = $this->metricTao->getDatasetPath();
        include_once $datasetPath;
        return new dataset($this->dao);
    }

    /**
     * 对度量项按照通用数据集进行归类，没有数据集不做归类。
     * Classify calculator instance list by its data set.
     *
     * @param  array  $calcList
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
        foreach($datasetCalcGroup as $dataset => $calcList) $classifiedCalcGroup[] = (object)array('dataset' => $dataset, 'calcList' => $calcList);

        foreach($otherCalcList as $calc) $classifiedCalcGroup[] = (object)array('dataset' => '', 'calcList' => $calcList);
        return $classifiedCalcGroup;
    }

    /**
     * 对度量项的字段列表取并集。
     * Unite field list of each calculator.
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

    /**
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID, $actionURL)
    {
        $this->config->metric->browse->search['actionURL'] = $actionURL;
        $this->config->metric->browse->search['queryID']   = $queryID;
        $this->config->metric->browse->search['params']['dept']['values']    = $this->loadModel('dept')->getOptionMenu();
        $this->config->metric->browse->search['params']['visions']['values'] = $this->loadModel('user')->getVisionList();

        $this->loadModel('search')->setSearchParams($this->config->metric->browse->search);
    }
}
