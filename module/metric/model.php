<?php

use function zin\wg;

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
    public $errorInfo = '';

    /**
     * 获取度量数据表的表头。
     * Get header of result table in view.
     *
     * @param  object $metric
     * @access public
     * @return array
     */
    public function getViewTableHeader($metric)
    {
        $dataFields = $this->getMetricRecordDateField($metric);
        $dateType   = $this->getDateTypeByCode($metric->code);

        $dataFields[] = 'id';
        $dataFields[] = 'value';
        $dataFields[] = 'date';
        $dataFieldStr = implode(',', $dataFields);

        $header = array();
        if($metric->scope != 'system')
        {
            $scope = $metric->scope;
            $header[] = array('name' => 'scope', 'title' => $this->lang->metric->tableHeader[$scope], 'width' => 159);
        }

        if($dateType != 'nodate')  $header[] = array('name' => 'date',  'title' => $this->lang->metric->date, 'width' => 96);
        $header[] = array('name' => 'value', 'title' => $this->lang->metric->value, 'width' => 96);
        $header[] = array('name' => 'calcTime', 'title' => $this->lang->metric->calcTime, 'width' => 128);

        return $header;
    }

    /**
     * 获取度量数据表的数据。
     * Get data of result table.
     *
     * @param  object    $metric
     * @param  array     $result
     * @access public
     * @return array|false
     */
    public function getViewTableData($metric, $result)
    {
        if(empty($result)) return array();

        $scope    = $metric->scope;
        $dateType = $this->getDateTypeByCode($metric->code);
        if($scope != 'system') $objectPairs = $this->getPairsByScope($scope);

        $tableData = array();
        foreach($result as $record)
        {
            $record = (array)$record;
            if(isset($record['date'])) $record['calcTime'] = date("Y-m-d H:i", strtotime($record['date']));

            $row = $this->buildDateCell($record, $dateType);
            if($scope != 'system')
            {
                $row->scope   = isset($objectPairs[$record[$scope]]) ? $objectPairs[$record[$scope]] : $record[$scope];
                $row->scopeID = $record[$scope];
            }
            $row->value = is_numeric($record['value']) ? round((float)$record['value'], 2) : $record['value'];

            $row->calcType     = zget($record, 'calcType', '');
            $row->calculatedBy = zget($record, 'calculatedBy', '');

            $tableData[] = $row;
        }

        return $tableData;
    }

    /**
     * 对 headers 进行分组满足表头合并单元格, 返回经过分组的 headers 和 data。
     * Return grouped headers and data for table headers merges cell.
     *
     * @param  array  $header
     * @param  array  $data
     * @param  bool   $withCalcTime
     * @access public
     * @return array
     */
    public function getGroupTable($header, $data, $dateType, $withCalcTime = true)
    {
        if(!$header or !$data) return array(array(), array());

        $headerLength = count($header);

        if($headerLength == 2)
        {
            return $this->getTimeTable($data, $dateType, $withCalcTime); // $dateType should eq nodate
        }
        elseif($headerLength == 3)
        {
            if($this->isObjectMetric($header))
            {
                return $this->getObjectTable($header, $data, $dateType, $withCalcTime); // $dateType should eq nodate
            }
            else
            {
                return $this->getTimeTable($data, $dateType, $withCalcTime);
            }
        }
        elseif($headerLength == 4)
        {
            return $this->getObjectTable($header, $data, $dateType, $withCalcTime);
        }

        return array($header, $data);
    }

    /**
     * 构建有时间属性的度量项的表格数据。
     * Build table data for metric with time.
     *
     * @param  array  $data
     * @param  string $dateType
     * @param  bool   $withCalcTime
     * @access public
     * @return array
     */
    public function getTimeTable($data, $dateType = 'day', $withCalcTime = true)
    {
        usort($data, function($a, $b) use ($dateType)
        {
            if($dateType == 'week')
            {
                list($yearA, $weekA) = explode('-', $a->dateString);
                list($yearB, $weekB) = explode('-', $b->dateString);

                list($firstDayOfWeekA, $lastDayOfWeekA) = $this->getStartAndEndOfWeek($yearA, $weekA, 'date');
                list($firstDayOfWeekB, $lastDayOfWeekB) = $this->getStartAndEndOfWeek($yearB, $weekB, 'date');

                $dateA = strtotime($firstDayOfWeekA);
                $dateB = strtotime($firstDayOfWeekB);
            }
            else
            {
                $dateA = strtotime($a->dateString);
                $dateB = strtotime($b->dateString);
            }

            if ($dateA == $dateB) return 0;

            return ($dateA > $dateB) ? -1 : 1;
        });

        $groupHeader = array();

        $groupHeader[] = array('name' => 'date', 'title' => zget($this->lang->metric, $dateType, ''), 'align' => 'center', 'width' => 96);
        $groupHeader[] = array('name' => 'value', 'title' => $this->lang->metric->value, 'align' => 'center', 'width' => 68);
        $groupData   = array();

        $users = $this->loadModel('user')->getPairs('noletter');
        foreach($data as $dataInfo)
        {
            $value       = $withCalcTime ? array($dataInfo->value, $dataInfo->calcTime, $dataInfo->calcType, zget($users, $dataInfo->calculatedBy)) : $dataInfo->value;
            $date        = isset($dataInfo->date) ? $dataInfo->date : $dataInfo->dateString;
            $dataSeries  = array('date' => $date, 'value' => $value);
            $groupData[] = $dataSeries;
        }

        return array($groupHeader, $groupData);
    }

   /**
    * 构建有对象属性的度量项的表格数据。
    * Build table data for metric with object.
    *
    * @param  array  $data
    * @param  string $dateType
    * @param  bool   $withCalcTime
    * @access public
    * @return array
    */
    public function getObjectTable($header, $data, $dateType = 'day', $withCalcTime = true)
    {
        $groupHeader = array();
        $groupData   = array();

        $headerField = current($header)['name'];
        $headerTitle = current($header)['title'];

        $groupHeader[] = array('name' => $headerField, 'title' => $headerTitle, 'fixed' => 'left', 'width' => 128);
        $dateField     = 'dateString';
        usort($data, function($a, $b) use($dateField)
        {
            $dateA = strtotime($a->$dateField);
            $dateB = strtotime($b->$dateField);

            if ($dateA == $dateB) {
                return 0;
            }

            return ($dateA > $dateB) ? -1 : 1;
        });

        $times     = array();
        $objects   = array();
        $users = $this->loadModel('user')->getPairs('noletter');
        foreach($data as $dataInfo)
        {
            $time         = substr($dataInfo->$dateField, 0, 10);
            $calcTime     = $dataInfo->calcTime;
            $object       = $dataInfo->scope;
            $value        = $dataInfo->value;
            $calcType     = $dataInfo->calcType;
            $calculatedBy = zget($users, $dataInfo->calculatedBy);

            if(!isset($times[$time]))     $times[$time]     = $time;
            if(!isset($objects[$object])) $objects[$object] = array();
            $objects[$object][$time] = $withCalcTime ? array($value, $calcTime, $calcType, $calculatedBy) : $value;
        }
        /* e.g $times = array('2023-10-14', '2023-10-15'), $objects = array('object1' => array('2023-10-14' => 2, '2023-10-15 => 3)) */

        $numberHeaderWidth = 68;
        foreach($times as $time)
        {
            $header = array('name' => $time, 'align' => 'center', 'width' => $numberHeaderWidth);
            if($dateType == 'year')
            {
                $header['title'] = sprintf($this->lang->metric->yearFormat, $time);
            }
            elseif($dateType == 'month')
            {
                list($year, $month) = explode('-', $time);
                $header['title']       = $this->lang->datepicker->monthNames[(int)$month - 1];
                $header['headerGroup'] = sprintf($this->lang->metric->yearFormat, $year);
            }
            elseif($dateType == 'week')
            {
                list($year, $week) = explode('-', $time);
                $header['title']       = sprintf($this->lang->metric->weekFormat, $week);
                $header['headerGroup'] = sprintf($this->lang->metric->yearFormat, $year);
            }
            elseif($dateType == 'day' or $dateType == 'nodate')
            {
                list($year, $month, $day) = explode('-', $time);
                $header['title']       = sprintf($this->lang->metric->monthDayFormat, $month, $day);
                $header['headerGroup'] = sprintf($this->lang->metric->yearFormat, $year);
            }

            $groupHeader[] = $header;
        }

        foreach($objects as $object => $datas)
        {
            $objectData = array($headerField => $object);
            foreach($times as $time) $objectData[$time] = isset($datas[$time]) ? $datas[$time] : 0;
            $groupData[] = $objectData;
        }

        return array($groupHeader, $groupData);
    }

    /**
     * 获取所有范围的键值对。
     * Get scope pairs.
     *
     * @param  bool   $all
     * @access public
     * @return array
     */
    public function getScopePairs($all = true)
    {
        $metrics = $all ? array() : $this->metricTao->fetchMetricsByScope($this->config->metric->scopeList);
        $scopePairs = array();
        foreach($this->lang->metric->scopeList as $scope => $name)
        {
            if(!$all && !isset($metrics[$scope])) continue;

            $scopePair = new stdclass();
            $scopePair->value = $scope;
            $scopePair->label = $name;

            $scopePairs[] = $scopePair;
        }

        return $scopePairs;
    }


    /**
     * 获取度量项数据列表。
     * Get metric data list.
     *
     * @param  string $scope
     * @param  string $stage
     * @param  int    $param
     * @param  string $type
     * @param  int    $queryID
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array|false
     */
    public function getList($scope, $stage = 'all', $param = 0, $type = '', $queryID = 0, $sort = 'id_desc', $pager = null)
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

        $object = null;
        $purpose = null;
        if($type == 'byTree')
        {
            $object_purpose = explode('_', $param);
            $object = $object_purpose[0];
            if(count($object_purpose) == 2) $purpose = $object_purpose[1];
        }

        $query = $type == 'bysearch' ? $this->session->metricQuery : '';

        $metrics = $this->metricTao->fetchMetrics($scope, $stage, $object, $purpose, $query, $sort, $pager);
        $metrics = $this->processOldMetrics($metrics);

        return $metrics;
    }

    /**
     * 以对象为维度分组度量项。
     * Group metrics by object.
     *
     * @param  array  $metrics
     * @access public
     * @return array
     */
    public function groupMetricByObject($metrics)
    {
        $groupMetrics = array_fill_keys(array_keys($this->lang->metric->objectList), array());
        foreach($metrics as $metric)
        {
            $group = isset($groupMetrics[$metric->object]) ? $metric->object : 'other';
            $groupMetrics[$group][] = $metric;
        }

        $purposes = array_keys($this->lang->metric->purposeList);
        foreach($groupMetrics as $key => $metrics)
        {
            if(empty($metrics))
            {
                unset($groupMetrics[$key]);
                continue;
            }

            usort($metrics, function($a, $b) use ($purposes) {
                $aIndex = array_search($a->purpose, $purposes);
                $bIndex = array_search($b->purpose, $purposes);

                if($aIndex === $bIndex) return 0;
                return ($aIndex < $bIndex) ? -1 : 1;
            });

            $groupMetrics[$key] = $metrics;
        }

        return $groupMetrics;
    }

    /**
     * 根据id列表获取度量项列表。
     * Get metrics by id list.
     *
     * @param  array  $metricIDList
     * @access public
     * @return array|false
     */
    public function getMetricsByIDList($metricIDList)
    {
        return $this->metricTao->fetchMetricsByIDList($metricIDList);
    }

    /**
     * 根据code列表获取度量项列表。
     * Get metrics by code list.
     *
     * @param  array  $codeList
     * @access public
     * @return array|false
     */
    public function getMetricsByCodeList($codeList)
    {
        return $this->metricTao->fetchMetricsByCodeList($codeList);
    }

    /**
     * 获取度量库数据的收集方式和采集人。
     * Get calculate type and calculate people by metric record id.
     *
     * @param  int    $recordID
     * @access public
     * @return object|false
     */
    public function getRecordCalcInfo($recordID)
    {
        return $this->dao->select('calcType, calculatedBy')->from(TABLE_METRICLIB)->where('id')->eq($recordID)->fetch();
    }

    /**
     * 获取旧度量项列表。
     * Get old metric list.
     *
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getOldMetricList(string $orderBy = 'id_desc'): array
    {
        return $this->dao->select('*')->from(TABLE_BASICMEAS)->where('deleted')->eq(0)->orderby($orderBy)->fetchAll('id');
    }

    /**
     * 通过筛选器筛选度量项。
     * Get metric list by filters.
     *
     * @param  array  $filters
     * @param  string $stage
     * @access public
     * @return array|false
     */
    public function getListByFilter($filters, $stage)
    {
        return $this->metricTao->fetchMetricsWithFilter($filters, $stage);
    }

    /**
     * 通过我的收藏筛选度量项。
     * Get metric list by collect.
     *
     * @param  string $stage
     * @access public
     * @return array|false
     */
    public function getListByCollect($stage)
    {
        return $this->metricTao->fetchMetricsByCollect($stage);
    }

    /**
     * 获取模块树数据。
     * Get module tree data.
     *
     * @param  string $scope
     * @access public
     * @return void
     */
    public function getModuleTreeList($scope)
    {
        return $this->metricTao->fetchModules($scope);
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
    public function getByCode(string $code, string|array $fieldList = '*')
    {
        static $metricsWithCode;
        if(is_array($fieldList)) $fieldList = implode(',', $fieldList);
        if(!isset($metricsWithCode[$code . $fieldList])) $metricsWithCode[$code . $fieldList] = $this->fetchMetricByCode($code, $fieldList);

        return $metricsWithCode[$code . $fieldList];
    }

    /**
     * 根据ID获取度量项信息。
     * Get metric info by id.
     *
     * @param  int          $metricID
     * @param  string|array $fieldList
     * @access public
     * @return object|false
     */
    public function getByID(int $metricID, string|array $fieldList = '*')
    {
        if($metricID === 0) return false;
        if(is_array($fieldList)) $fieldList = implode(',', $fieldList);
        $metric = $this->dao->select($fieldList)->from(TABLE_METRIC)->where('id')->eq($metricID)->fetch();

        if(!$metric) return false;

        if($this->isOldMetric($metric))
        {
            $oldMetric = $this->getOldMetricByID($metric->fromID);

            $metric->sql         = $oldMetric->configure;
            $metric->params      = $oldMetric->params;
            $metric->oldUnit     = $oldMetric->unit;
            $metric->collectType = $oldMetric->collectType;
            $metric->collectConf = $oldMetric->collectConf;
            $metric->execTime    = $oldMetric->execTime;
        }
        if(empty($metric->dateType) and isset($metric->code)) $metric->dateType = $this->getDateTypeByCode($metric->code);

        return $metric;
    }

    /**
     * 根据ID获取旧版度量项信息。
     * Get old metric info by id.
     *
     * @param  int $measurementID
     * @access public
     * @return object|false
     */
    public function getOldMetricByID($measurementID)
    {
        $measurement = $this->dao->select('*')->from(TABLE_BASICMEAS)->where('id')->eq($measurementID)->fetch();
        if(!$measurement) return false;

        if($measurement->collectType == 'action')
        {
            $collectConf = json_decode($measurement->collectConf);
            $measurement->collectConf         = new stdclass();
            $measurement->collectConf->action = $collectConf->action;
            $measurement->collectConf->type   = '';
            $measurement->collectConf->week   = '';
        }
        else
        {
            $measurement->collectConf = json_decode($measurement->collectConf);
            if(is_object($measurement->collectConf))
            {
                $measurement->collectConf->module = '';
                $measurement->collectConf->action = '';
            }
        }

        return $measurement;
    }

    /**
     * 获取dao对象。
     * Get dao object.
     *
     * @access public
     * @return object
     */
    public function getDAO()
    {
        $dbType = $this->config->metricDB->type;
        if($dbType === 'sqlite')
        {
            $dao = clone $this->dao;
            $dao->reset();

            $dao->dbh    = $this->app->connectSqlite();
            $dao->driver = 'sqlite';

            return $dao;
        }
        return $this->dao;
    }

    /**
     * 获取度量项数据源句柄。
     * Get data source statement of calculator.
     *
     * @param  object $calculator
     * @param  string $returnType
     * @access public
     * @return PDOStatement|string
     */
    public function getDataStatement($calculator, $returnType = 'statement', $vision = 'rnd')
    {
        $dao = $this->getDAO();
        $statement = null;
        if(!empty($calculator->dataset))
        {
            include_once $this->getDatasetPath();

            $dataset    = new dataset($dao, $this->config, $vision);
            $dataSource = $calculator->dataset;
            $fieldList  = implode(',', $calculator->fieldList);
            $statement  = $dataset->$dataSource($fieldList);
        }

        if($calculator->useSCM)
        {
            $calculator->setDAO($dao);
            $scm = $this->app->loadClass('scm');
            $calculator->setSCM($scm);
        }

        if($returnType == 'sql') return $statement->get();
        return $statement;
    }

    /**
     * 获取度量数据的日期字段。
     * Get date field of metric data.
     *
     * @param  object $metric
     * @access protected
     * @return array
     */
    protected function getMetricRecordDateField(object $metric): array
    {
        $dataFields = array();
        $dateType   = $this->getDateTypeByCode($metric->code);

        if($dateType != 'nodate') $dataFields[] = 'year';
        if($dateType == 'month')  $dataFields[] = 'month';
        if($dateType == 'week')   $dataFields[] = 'week';
        if($dateType == 'day')
        {
            $dataFields[] = 'month';
            $dataFields[] = 'day';
        }

        if($metric->scope != 'system') $dataFields[] = $metric->scope;

        return $dataFields;
    }

    /**
     * 设置默认的度量数据筛选参数。
     * Set default options of metric data.
     *
     * @param  array $options
     * @param  array $dataFields
     * @access public
     * @return array
     */
    public function setDefaultOptions(array $options, array $dataFields): array
    {
        if(!empty($options)) return $options;

        $dateType = $this->getDateType($dataFields);

        if($dateType != 'nodate')
        {
            $dateLabels  = $this->getDateLabels($dateType);
            $defaultDate = $this->getDefaultDate($dateLabels);
            $options = array('dateType' => $dateType, 'dateLabel' => $defaultDate);
        }

        return $options;
    }

    /**
     * 根据代号计算度量项。
     * Calculate metric by code.
     *
     * @param  string $code
     * @access public
     * @return void
     */
    public function calculateMetricByCode($code)
    {
        $metric = $this->getByCode($code);
        if(!$metric) return false;

        $calculator = $this->getCalculator($metric->scope, $metric->purpose, $metric->code);

        $this->calculateDefaultMetric($calculator, $this->config->vision);

        return $calculator;
    }

    /**
     * 获取数组格式的度量项结果。
     * Get result by code with array format.
     *
     * @param  int    $code
     * @param  array  $options
     * @param  string $type
     * @param  int    $pager
     * @param  string $vision
     * @access public
     * @return void
     */
    public function getResultByCodeWithArray($code, $options = array(), $type = 'realtime', $pager = null, $vision = 'rnd')
    {
        $metric = $this->getByCode($code);
        if(!$metric) return array();
        $dataFields = $this->getMetricRecordDateField($metric);

        $result = array();
        if($vision == 'rnd')
        {
            $records = $this->metricTao->fetchMetricRecordsWithOption($code, $dataFields, $options, $pager);
            if(empty($records)) return array();

            foreach($records as $index => $record)
            {
                $record          = (array)$record;
                $record['value'] = (float)$record['value'];

                $result[] = $record;
            }

            return $result;
        }

        return $this->getResultByCode($code, $options, 'realtime', $pager, $vision);
    }

    /**
     * 合并度量数据。
     * Merge record.
     *
     * @param  array    $record
     * @param  array    $result
     * @access public
     * @return array
     */
    public function mergeRecord($record, $result)
    {
        $uniqueKey = $this->getUniqueKeyByRecord($record);
        if(isset($result[$uniqueKey]))
        {
            $result[$uniqueKey]['value'] += $record['value'];
            return $result;
        }
        $result[$uniqueKey] = $record;
        return $result;
    }

    /**
     * 根据度量数据的字段生成唯一键。
     * Get unique key by record field.
     *
     * @param  array    $record
     * @access public
     * @return string
     */
    public function getUniqueKeyByRecord($record)
    {
        $record = (array)$record;
        $uniqueKeys = array();
        $ignoreLibFields = $this->config->metric->ignoreLibFields;
        foreach($record as $field => $value)
        {
            if(in_array($field, $ignoreLibFields) || empty($value)) continue;
            $uniqueKeys[] = $field . $value;
        }

        return empty($uniqueKeys) ? 'none' : implode('_', $uniqueKeys);
    }

    /**
     * 根据代号获取计算实时度量项的结果。
     * Get result of calculate metric by code.
     *
     * @param  string      $code
     * @param  array       $options e.g. array('product' => '1,2,3', 'year' => '2023')
     * @param  string      $type
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function getResultByCode($code, $options = array(), $type = 'realtime', $pager = null, $vision = 'rnd')
    {
        if($type == 'cron')
        {
            $metric = $this->getByCode($code);
            if(!empty($metric))
            {
                $dataFields = $this->getMetricRecordDateField($metric);
                $options    = $this->setDefaultOptions($options, $dataFields);

                return $this->metricTao->fetchMetricRecords($code, $dataFields, $options, $pager);
            }
        }

        $metric = $this->getByCode($code);
        if(!$metric) return false;

        $calculator = $this->getCalculator($metric->scope, $metric->purpose, $metric->code);
        if(!$calculator) return array();

        /* 因为是单个度量项的计算，所以需要优先查看是否支持可用的性能优化，如果有的话，使用性能优化方式计算。*/
        /* Because this is a single metric calculation, it is important to first look to see if any performance optimizations are supported and, if so, use them. */
        $calculated = false;
        $calculated += $this->calculateReuseMetric($calculator, $options, $type, $pager, $vision);
        $calculated += $this->calculateSingleMetric($calculator, $vision);

        /* 如果没有可用的性能优化方式，那么使用默认的方式计算。*/
        /* If no optimizations are available, the default calculation is used. */
        if(!$calculated) $this->calculateDefaultMetric($calculator, $vision);

        $records = $calculator->getResult($options);

        if(!empty($records))
        {
            $time = helper::now();
            foreach($records as $index => $record)
            {
                $records[$index]['date']         = $time;
                $records[$index]['calcType']     = 'cron';
                $records[$index]['calculatedBy'] = 'system';
            }
        }

        return $records;
    }

    /**
     * 根据度量项和数据计算度量项结果。
     * Calculate metric result by metrics and data.
     *
     * @param  array $metrics
     * @param  array $data
     * @access public
     * @return void
     */
    public function getResultByCodeFromData($metrics, $data)
    {
        $results = array();
        foreach($metrics as $metric)
        {
            $calculator = $this->getCalculator($metric->scope, $metric->purpose, $metric->code);
            foreach($data as $row) $calculator->calculate($row);
            $results[$metric->code] = $calculator->getResult();
        }

        return $results;
    }

    /**
     * 获取度量项计算对象。
     * Get metric calculator.
     *
     * @param  string    $scope
     * @param  string    $purpose
     * @param  string    $code
     * @access public
     * @return object
     */
    public function getCalculator($scope, $purpose, $code)
    {
        $calcPath = $this->getCalcRoot() . $scope . DS . $purpose . DS . $code . '.php';
        if(!is_file($calcPath)) return false;

        include_once $this->getBaseCalcPath();
        include_once $calcPath;
        $calculator = new $code;
        $calculator->setHolidays($this->loadModel('holiday')->getList());
        $calculator->setWeekend(isset($this->config->project->weekend) ? $this->config->project->weekend : 2);
        if($calculator->useSCM && ($this->config->inQuickon || $this->config->inCompose)) $calculator->setGitFoxRepos($this->loadModel('repo')->getGitFoxRepos());

        return $calculator;
    }

    /**
     * 计算重用度量项。
     * Calculate reuse metric.
     *
     * @param  object    $calculator
     * @param  array     $options
     * @param  string    $type
     * @param  object    $pager
     * @param  string    $vision
     * @access public
     * @return bool
     */
    public function calculateReuseMetric($calculator, $options, $type, $pager, $vision)
    {
        if(!$calculator->reuse) return false;

        $reuseMetrics = array();
        foreach($calculator->reuseMetrics as $key => $reuseMetric)
        {
            $reuseMetrics[$key] = $this->getResultByCode($reuseMetric, $options, $type, $pager, $vision);
        }

        $calculator->calculate($reuseMetrics);

        return true;
    }

    /**
     * 计算可独立计算度量项。
     * Calculate single metric.
     *
     * @param  object  $calculator
     * @param  string  $vision
     * @access public
     * @return bool
     */
    public function calculateSingleMetric($calculator, $vision)
    {
        if(!$calculator->supportSingleQuery) return false;

        $sql = $this->getDataStatement($calculator, 'sql', $vision);
        $calculator->setDAO($this->getDAO());
        $calculator->setSingleSql($sql);
        $calculator->enableSingleQuery();

        return true;
    }

    /**
     * 计算普通度量项。
     * Calculate default metric.
     *
     * @param  object    $calculator
     * @param  string    $vision
     * @access public
     * @return void
     */
    public function calculateDefaultMetric($calculator, $vision)
    {
        $statement = $this->getDataStatement($calculator, 'statement', $vision);
        $dbType = $this->config->metricDB->type;
        if($dbType == 'duckdb')
        {
            $this->loadModel('bi');
            $sql = $statement->get();
            $dbh = $this->app->loadDriver('duckdb');
            $statement = $dbh->query($sql);
        }
        if($statement) $rows = $statement->fetchAll();
        if(!empty($rows)) foreach($rows as $row) $calculator->calculate($row);
    }

    /**
     * 根据代号获取计算最新度量项的结果。
     * Get result of latest calculate metric by code.
     *
     * @param  string      $code
     * @param  array       $options e.g. array('product' => '1,2,3', 'year' => '2023')
     * @param  string      $type
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function getLatestResultByCode($code, $options = array(), $pager = null, $vision = 'rnd')
    {
        $metric     = $this->getByCode($code);
        $dataFields = $this->getMetricRecordDateField($metric);
        $options    = $this->setDefaultOptions($options, $dataFields);

        return $this->metricTao->fetchLatestMetricRecords($code, $dataFields, $options, $pager);
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
    public function getResultByCodes($codes, $options = array(), $vision = 'rnd')
    {
        $results = array();
        foreach($codes as $code)
        {
            $result = $this->getResultByCode($code, $options, 'realtime', null, $vision);
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
    public function getExecutableMetric($includes = 'all')
    {
        return $this->dao->select('id, code')->from(TABLE_METRIC)
            ->where('deleted')->eq('0')
            ->beginIF(is_array($includes))->andWhere('code')->in($includes)->fi()
            ->fetchPairs();
    }

    /**
     * 删除度量数据中的重复数据。
     * Delete duplication record in metric data.
     *
     * @param  string $code
     * @access public
     * @return bool
     */
    public function deduplication(string $code): bool
    {
        $metric = $this->getByCode($code);
        $fields = $this->getRecordFields($code);
        if(!in_array($metric->scope, $fields)) $fields[] = $metric->scope;

        if(empty($fields)) return false;

        $this->metricTao->setDeleted($code, 1);
        $this->metricTao->keepLatestRecords($code, $fields);
        $this->metricTao->executeDelete($code);

        return dao::isError();
    }

    /**
     * 获取日志文件路径。
     * Get log file.
     *
     * @access public
     * @return string
     */
    public function getLogFile(): string
    {
        return $this->app->getTmpRoot() . 'log/metriclib.' . date('Ymd') . '.log.php';
    }

    /**
     * 存储日志。
     * Save logs.
     *
     * @param  string $log
     * @access public
     * @return void
     */
    public function saveLogs(string $log): void
    {
        $logFile = $this->getLogFile();
        $log     = date('Y-m-d H:i:s') . ' ' . trim($log) . "\n";
        if(!file_exists($logFile)) $log = "<?php\ndie();\n?" . ">\n" . $log;

        file_put_contents($logFile, $log, FILE_APPEND);
    }

    /**
     * 获取度量数据有效字段。
     * Get metric record fields.
     *
     * @param  string $code
     * @access public
     * @return array|false
     */
    public function getRecordFields(string $code): array|false
    {
        $metric   = $this->getByCode($code);
        $dateType = $this->getDateTypeByCode($code);
        $fields   = array($metric->scope);

        if($dateType == 'nodate') return $fields;

        $fields[] = 'year';
        if($dateType == 'month' || $dateType == 'day') $fields[] = 'month';
        if($dateType == 'week') $fields[] = 'week';
        if($dateType == 'day') $fields[] = 'day';

        return $fields;
    }

    /**
     * 重建主键顺序。
     * Rebuild primary key order.
     *
     * @access public
     * @return void
     */
    public function rebuildPrimaryKey()
    {
        $this->metricTao->rebuildIdColumn();
    }

    /**
     * 根据度量项收集周期来清理过期的度量库数据。
     * Clear outdated metric records by cycle.
     *
     * @param  string $code
     * @param  string $cycle
     * @access public
     * @return array
     */
    public function clearOutDatedRecords($code, $cycle)
    {
        $year    = date('Y');
        $month   = date('n');
        $week    = date('W');
        $day     = date('j');

        if($cycle == 'year')
        {
            $this->dao->delete()->from(TABLE_METRICLIB)
                ->where('metricCode')->eq($code)
                ->andWhere('year')->eq($year)
                ->exec();
        }
        elseif($cycle == 'month')
        {
            $this->dao->delete()->from(TABLE_METRICLIB)
                ->where('metricCode')->eq($code)
                ->andWhere('year')->eq($year)
                ->andWhere('month')->eq($month)
                ->exec();
        }
        elseif($cycle == 'week')
        {
            $this->dao->delete()->from(TABLE_METRICLIB)
                ->where('metricCode')->eq($code)
                ->andWhere('year')->eq($year)
                ->andWhere('week')->eq($week)
                ->exec();
        }
        elseif($cycle == 'day')
        {
            $this->dao->delete()->from(TABLE_METRICLIB)
                ->where('metricCode')->eq($code)
                ->andWhere('year')->eq($year)
                ->andWhere('month')->eq($month)
                ->andWhere('day')->eq($day)
                ->exec();
        }
    }

    /**
     * 插入度量库数据。
     * Insert into metric lib.
     *
     * @param  array  $recordWithCode
     * @param  string $calcType
     * @access public
     * @return void
     */
    public function insertMetricLib($recordWithCode, $calcType = 'cron')
    {
        foreach($recordWithCode as $code => $records)
        {
            $schema = '';
            $values = array();
            foreach($records as $record)
            {
                if(empty($record)) continue;

                $record->calcType = $calcType;
                $record->calculatedBy = $calcType == 'inference' ? $this->app->user->account : 'system';

                $sql = $this->dao->insert(TABLE_METRICLIB)->data($record)->get();

                $position = strpos($sql, 'VALUES');

                if(empty($schema)) $schema .= substr($sql, 0, $position + 6);
                $values[] = substr($sql, $position + 6);
            }

            $rows = count($records);
            $time = helper::now();

            $this->dao->update(TABLE_METRIC)
                ->set('lastCalcRows')->eq($rows)
                ->set('lastCalcTime')->eq($time)
                ->where('code')->eq($code)
                ->exec();

            while($rows > 0)
            {
                $sql = $schema . implode(',', array_splice($values, 0, 10000));
                $this->dao->exec($sql);
                $rows -= 10000;
            }
        }

        return dao::isError();
    }

    /**
     * 获取可计算的度量项对象列表。
     * Get executable calculator list.
     *
     * @access public
     * @return array
     */
    public function getExecutableCalcList($includes = 'all')
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
        $excutableMetric = $this->getExecutableMetric($includes);
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
    public function getCalcInstanceList($includes = 'all')
    {
        $calcList = $this->getExecutableCalcList($includes);

        include_once $this->getBaseCalcPath();

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

        return $this->filterCalcByEdition($calcInstances);
    }

    /**
     * 获取通用数据集对象。
     * Get instance of data set object.
     *
     * @access public
     * @return dataset
     */
    public function getDataset($dao)
    {
        $datasetPath = $this->getDatasetPath();
        include_once $datasetPath;
        return new dataset($dao, $this->config);
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
        foreach($calcList as $code => $calc)
        {
            if(empty($calc->dataset))
            {
                $otherCalcList[$code] = $calc;
                continue;
            }

            $dataset = $calc->dataset;
            if(!isset($datasetCalcGroup[$dataset])) $datasetCalcGroup[$dataset] = array();
            $datasetCalcGroup[$dataset][$code] = $calc;
        }

        $classifiedCalcGroup = array();
        foreach($datasetCalcGroup as $dataset => $calcList) $classifiedCalcGroup[] = (object)array('dataset' => $dataset, 'calcList' => $calcList);

        foreach($otherCalcList as $code => $calc) $classifiedCalcGroup[] = (object)array('dataset' => '', 'calcList' => array($code => $calc));
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
        $uniqueList = array_unique($fieldList);
        $aliasList  = array();
        foreach($uniqueList as $field)
        {
            if(strpos(strtoupper($field), ' AS ') !== false)
            {
                $aliasList[] = $field;
                continue;
            }
            if(strpos($field, '.') !== false)
            {
                $alias = str_replace('.', '_', $field);
                list($table, $field) = explode('.', $field);
                $aliasList[] = "`$table`.`$field` AS `$alias`";
                continue;
            }
            $aliasList[] = "`$field`";
        }
        return implode(',', $aliasList);
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

        $this->loadModel('search')->setSearchParams($this->config->metric->browse->search);
    }

    /**
     * 为度量详情页构建操作按钮
     * Build operate menu.
     *
     * @param  object $metric
     * @access public
     * @return array
     */
    public function buildOperateMenu(object $metric): array
    {
        $menuList = array
        (
            'main'   => array(),
            'suffix' => array()
        );

        if($metric->builtin === '0')
        {
            $stage = $metric->stage;

            if($stage == 'wait' and common::haspriv('metric', 'edit'))
            {
                $editAction = $this->config->metric->actionList['edit'];
                $editAction['data-toggle'] = 'modal';
                $editAction['url']         = helper::createLink('metric', 'edit', "metricID={$metric->id}&viewType=view");

                $menuList['suffix']['edit'] = $editAction;
            }

            if($stage == 'wait' and common::haspriv('metric', 'implement') and !$this->isOldMetric($metric))
            {
                $menuList['main']['implement'] = $this->config->metric->actionList['implement'];
            }

            if($stage != 'wait' and common::haspriv('metric', 'delist'))
            {
                $menuList['main']['delist'] = $this->config->metric->actionList['delist'];
            }

            if(common::haspriv('metric', 'delete'))
            {
                $deleteAction = $this->config->metric->actionList['delete'];
                if(isset($metric->isUsed) && $metric->isUsed) $deleteAction['data-confirm'] = $this->lang->metric->confirmDeleteInUsed;
                $menuList['suffix']['delete'] = $deleteAction;
            }
        }

        if($metric->stage == 'released' && !empty($metric->dateType) && $metric->dateType != 'nodate' && common::haspriv('metric', 'recalculate'))
        {
            $menuList['main']['recalculate'] = $this->config->metric->actionList['recalculate'];
            $menuList['main']['recalculate']['text'] = $this->lang->metric->recalculateBtnText;
            $menuList['main']['recalculate']['hint'] = $this->lang->metric->recalculateBtnText;
        }

        return $menuList;
    }

    /**
     * 获取瀑布范围的瀑布对象列表。
     * Get object pairs by scope.
     *
     * @param  string $vision
     * @access public
     * @return array
     */
    public function getWaterfullProjectPairs($vision = 'rnd')
    {
        return $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq('project')
            ->andWhere('model')->in(array('waterfall', 'waterfallplus'))
            ->andWhere("vision LIKE '%{$vision}%'", true)
            ->orWhere("vision IS NULL")->markRight(1)
            ->fetchPairs();
    }

    /**
     * 获取范围的对象列表。
     * Get object pairs by scope.
     *
     * @param  string $scope
     * @param  bool   $withHierarchy
     * @access public
     * @return array
     */
    public function getPairsByScope($scope, $withHierarchy = false, $vision = 'rnd')
    {
        if(empty($scope) || $scope == 'system') return array();
        if($scope == 'dept'    && $withHierarchy) $scope = 'deptWithHierarchy';
        if($scope == 'program' && $withHierarchy) $scope = 'programWithHierarchy';

        $objectPairs = array();
        switch($scope)
        {
            case 'dept':
                $objectPairs = $this->loadModel('dept')->getDeptPairs();
                break;
            case 'deptWithHierarchy':
                $objectPairs = $this->loadModel('dept')->getOptionMenu();
                break;
            case 'user':
                $objectPairs = $this->loadModel('user')->getPairs('noletter|noempty|noclosed');
                break;
            case 'program':
                $objectPairs = $this->dao->select('id, name')->from(TABLE_PROGRAM)
                    ->where('deleted')->eq(0)
                    ->andWhere('type')->eq('program')
                    ->fetchPairs();
                break;
            case 'programWithHierarchy':
                $objectPairs = $this->loadModel('program')->getParentPairs();
                break;
            case 'product':
                $objectPairs = $this->dao->select('id, name')->from(TABLE_PRODUCT)
                    ->where('deleted')->eq(0)
                    ->andWhere('shadow')->eq(0)
                    ->andWhere("vision LIKE '%{$vision}%'", true)
                    ->orWhere("vision IS NULL")->markRight(1)
                    ->fetchPairs();
                break;
            case 'project':
                $objectPairs = $this->dao->select('id, name')->from(TABLE_PROJECT)
                    ->where('deleted')->eq(0)
                    ->andWhere('type')->eq('project')
                    ->andWhere("vision LIKE '%{$vision}%'", true)
                    ->orWhere("vision IS NULL")->markRight(1)
                    ->fetchPairs();
                break;
            case 'execution':
                $objectPairs = $this->dao->select('id, name')->from(TABLE_PROJECT)
                    ->where('deleted')->eq(0)
                    ->andWhere('type')->in('sprint,stage,kanban')
                    ->andWhere("vision LIKE '%{$vision}%'", true)
                    ->orWhere("vision IS NULL")->markRight(1)
                    ->fetchPairs();
                break;
            case 'repo':
                $objectPairs = $this->loadModel('repo')->getRepoPairs('repo');
                break;
            case 'artifactrepo':
                $serverID = 0;
                $this->loadModel('instance');

                if(method_exists($this->instance, 'getSystemServer'))
                {
                    $server = $this->instance->getSystemServer();
                    if(!empty($server)) $serverID = $server->id;
                }

                $objectPairs = $this->dao->select('id, name')->from(TABLE_ARTIFACTREPO)
                    ->where('deleted')->eq(0)
                    ->andWhere('type')->eq('gitfox')
                    ->andWhere('serverID')->eq($serverID)
                    ->fetchPairs();
                break;
            default:
                $objectPairs = $this->loadModel($scope)->getPairs();
                break;
        }

        return $objectPairs;
    }

    /**
     * 根据范围和创建日期获取对象列表。
     * Get object pairs by scope and createdDate.
     *
     * @param  string $scope
     * @param  string $date
     * @param  string $vision
     * @access public
     * @return array
     */
    public function getPairsByScopeAndDate($scope, $date, $vision = 'rnd')
    {
        $objectPairs = array();
        switch($scope)
        {
            case 'product':
                $objectPairs = $this->dao->select('id, name')->from(TABLE_PRODUCT)
                    ->where('deleted')->eq(0)
                    ->andWhere('shadow')->eq(0)
                    ->andWhere('createdDate')->le($date)
                    ->andWhere("closedDate IS NULL OR YEAR(closedDate)='0000'", true)
                    ->orWhere('closedDate')->ge($date)
                    ->markRight(1)
                    ->andWhere("vision LIKE '%{$vision}%'", true)
                    ->orWhere("vision IS NULL")->markRight(1)
                    ->fetchPairs();
                break;
            case 'project':
                $objectPairs = $this->dao->select('id, name')->from(TABLE_PROJECT)
                    ->where('deleted')->eq(0)
                    ->andWhere('type')->eq('project')
                    ->andWhere('openedDate')->le($date)
                    ->andWhere("closedDate IS NULL OR YEAR(closedDate)='0000'", true)
                    ->orWhere('closedDate')->ge($date)
                    ->markRight(1)
                    ->andWhere("vision LIKE '%{$vision}%'", true)
                    ->orWhere("vision IS NULL")->markRight(1)
                    ->fetchPairs();
                break;
            case 'execution':
                $objectPairs = $this->dao->select('id, name')->from(TABLE_PROJECT)
                    ->where('deleted')->eq(0)
                    ->andWhere('type')->in('sprint,stage,kanban')
                    ->andWhere('openedDate')->le($date)
                    ->andWhere("closedDate IS NULL OR YEAR(closedDate)='0000'", true)
                    ->orWhere('closedDate')->ge($date)
                    ->markRight(1)
                    ->andWhere("vision LIKE '%{$vision}%'", true)
                    ->orWhere("vision IS NULL")->markRight(1)
                    ->fetchPairs();
                break;
            case 'user':
                $objectPairs = $this->dao->select('account,realname')->from(TABLE_USER)
                    ->where('deleted')->eq(0)
                    ->andWhere('join', true)->le(substr($date, 0, 10))
                    ->orWhere("`join` IS NULL")->markRight(1)
                    ->andWhere("visions LIKE '%{$vision}%'", true)
                    ->orWhere("visions IS NULL")->markRight(1)
                    ->fetchPairs();
                break;
        }

        return $objectPairs;
    }

    /**
     * Get object pairs by id list.
     *
     * @param  string $scope
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getPairsByIdList($scope, $idList)
    {
        $field = $scope == 'user' ? 'realname' : 'name';
        $where = $scope == 'user' ? 'account' : 'id';

        return $this->dao->select("id, $field")->from($this->config->objectTables[$scope])
            ->where($where)->in($idList)
            ->fetchPairs();
    }

    /**
     * 获取度量项的日期字符串。
     * Build date cell.
     *
     * @param  array  $record
     * @param  string $dateType
     * @access public
     * @return object
     */
    public function buildDateCell($record, $dateType)
    {
        $row      = new stdclass();
        $year     = isset($record['year'])     ? $record['year']     : '';
        $month    = isset($record['month'])    ? $record['month']    : '';
        $week     = isset($record['week'])     ? $record['week']     : '';
        $day      = isset($record['day'])      ? $record['day']      : '';
        $calcTime = isset($record['calcTime']) ? $record['calcTime'] : '';

        $date = $dateString = false;
        if($dateType == 'nodate')
        {
            $date = $dateString = substr($calcTime, 0, 10);
        }
        elseif($dateType == 'day')
        {
            $date = $dateString = "{$year}-{$month}-{$day}";
        }
        elseif($dateType == 'week')
        {
            $date       = sprintf($this->lang->metric->weekCell, $year, $week);
            $dateString = "{$year}-{$week}";
        }
        elseif($dateType == 'month')
        {
            $date       = sprintf($this->lang->metric->yearMonthFormat, $year, $month);
            $dateString = "{$year}-{$month}";
        }
        elseif($dateType == 'year')
        {
            $date       = sprintf($this->lang->metric->yearFormat, $year);
            $dateString = $year;
        }

        $row->date       = $date;
        $row->dateString = $dateString;
        $row->dateType   = $dateType;
        $row->calcTime   = $calcTime;
        return $row;
    }

    /**
     * 检查度量项的计算文件是否存在。
     * Check if metric's calculator exists.
     *
     * @param  object $metric
     * @access public
     * @return bool
     */
    public function checkCalcExists($metric)
    {
        $calcName = $this->getCalcRoot() . $metric->scope . DS . $metric->purpose . DS . $metric->code . '.php';
        return file_exists($calcName);
    }

    /**
     * 没有度量的显示范围不做显示。
     * Unset scope item that have no metric.
     *
     * @param  string $stage
     * @access public
     * @return void
     */
    public function processScopeList($stage = 'all')
    {
        $metrics = $this->metricTao->fetchMetricsByScope($this->config->metric->scopeList);
        foreach($this->lang->metric->scopeList as $scope => $name)
        {
            if(!isset($metrics[$scope]))
            {
                unset($this->lang->metric->scopeList[$scope]);
                unset($this->lang->metric->featureBar['preview'][$scope]);
            }
        }

        if(count($this->lang->metric->featureBar['preview']) >= 7)
        {
            $i = 0;
            foreach($this->lang->metric->featureBar['preview'] as $key => $name)
            {
                $i ++;
                if($i >= 7)
                {
                    unset($this->lang->metric->featureBar['preview'][$key]);
                    $this->lang->metric->moreSelects[$key] = $name;
                }
            }

            $this->lang->metric->featureBar['preview']['more'] = $this->lang->metric->more;
        }

        $this->lang->metric->featureBar['preview']['collect'] = $this->lang->metric->collect;
    }

    /**
     * 根据后台配置的估算单位对列表赋值。
     * Assign unitList['measure'] by custom hourPoint.
     *
     * @access public
     * @return void
     */
    public function processUnitList()
    {
        $this->app->loadLang('custom');
        $key = zget($this->config->custom, 'hourPoint', '0');

        $this->lang->metric->unitList['measure'] = $this->lang->custom->conceptOptions->hourPoint[$key];
    }

    /**
     * 根据后台配置的是否开启用户需求设置对象列表。
     * Unset objectList['requirement'] if custom requirement is close.
     *
     * @access public
     * @return void
     */
    public function processObjectList()
    {
        if(!isset($this->config->custom->URAndSR) or !$this->config->custom->URAndSR) unset($this->lang->metric->objectList['requirement']);
    }

    public function buildFilterCheckList($filters)
    {
        $filterItems = array();

        $onchange = 'window.handleFilterCheck(this)';

        $scopeItems = array();
        foreach($this->lang->metric->scopeList as $value => $text)
        {
            $isChecked = (isset($filters['scope']) and in_array($value, $filters['scope']));
            $scopeItems[] = array('text' => $text, 'value' => $value, 'onchange' => $onchange, 'checked' => $isChecked);
        }
        $filterItems['scope'] = array
        (
            'class' => 'flex3 divider',
            'items' => $scopeItems
        );

        $objectItems = array();
        foreach($this->lang->metric->objectList as $value => $text)
        {
            $isChecked = (isset($filters['object']) and in_array($value, $filters['object']));
            $collapse  = in_array($value, $this->config->metric->collapseList) && !$isChecked;
            $objectItems[] = array('text' => $text, 'value' => $value, 'onchange' => $onchange, 'checked' => $isChecked, 'data-collapse' => $collapse);
        }
        $filterItems['object'] = array
        (
            'class' => 'flex5 divider',
            'items' => $objectItems
        );

        $purposeItems = array();
        foreach($this->lang->metric->purposeList as $value => $text)
        {
            $isChecked = (isset($filters['purpose']) and in_array($value, $filters['purpose']));
            $purposeItems[] = array('text' => $text, 'value' => $value, 'onchange' => $onchange, 'checked' => $isChecked);
        }
        $filterItems['purpose'] = array
        (
            'class' => 'flex2',
            'items' => $purposeItems
        );

        return $filterItems;
    }

    /**
     * 根据数据初始化操作按钮。
     * Init action button by data.
     *
     * @param  array  $metrics
     * @access public
     * @return array
     */
    public function initActionBtn(array $metrics, array $cols): array
    {
        foreach($metrics as $metric)
        {
            foreach($metric->actions as $key => $action)
            {
                $isClick = true;

                if($action['name'] == 'edit')      $isClick = $metric->canEdit;
                if($action['name'] == 'implement') $isClick = $metric->canImplement;
                if($action['name'] == 'delist')
                {
                    $isClick = $metric->canDelist;
                    if(!$isClick && $metric->builtin == '1') $metric->actions[$key]['hint'] = $this->lang->metric->builtinMetric;
                }
                if($action['name'] == 'recalculate')
                {
                    $isClick = $metric->canRecalculate;
                    if($metric->stage != 'released' || (!empty($metric->dateType) && $metric->dateType == 'nodate')) $metric->actions[$key]['hint'] = $this->lang->metric->tips->banRecalculate;
                }

                $metric->actions[$key]['disabled'] = !$isClick;
            }
        }

        $hasAction = !empty($metrics) && !empty($metrics[0]->actions);
        if(!$hasAction) unset($cols['actions']);

        return array($cols, $metrics);
    }

    /**
     * 判断度量项是否是旧版度量项。
     * Judge if the metric is old.
     *
     * @param  object $metric
     * @access public
     * @return bool
     */
    public function isOldMetric($metric)
    {
        return isset($metric->type) && $metric->type == 'sql';
    }

    /**
     * 创建SQL函数。
     * Create sql function.
     *
     * @param  string $sql
     * @param  object $measurement
     * @access public
     * @return array
     */
    public function createSqlFunction($sql, $measurement)
    {
        $measFunction = $this->getSqlFunctionName($measurement);
        $postFunction = $this->parseSqlFunction($sql);
        if(!$measFunction || !$postFunction) return array('result' => 'fail', 'errors' => $this->lang->metric->tips->nameError);

        $sql = str_replace($postFunction, $measFunction, $sql);

        try
        {
            $this->dbh->exec("DROP FUNCTION IF EXISTS `$measFunction`");
            $result = $this->dbh->exec($sql);
        }
        catch(PDOException $exception)
        {
            $message = sprintf($this->lang->metric->tips->createError, $exception->getMessage());
            return array('result' => 'fail', 'errors' => $message);
        }

        return array('result' => 'success');
    }

    /**
     * 获取旧度量项的SQL函数名。
     * Get sql function name of a old metric.
     *
     * @param  object $measurement
     * @access public
     * @return string
     */
    public function getSqlFunctionName($measurement)
    {
        if(!$measurement) return '';
        return strtolower("qc_{$measurement->code}");
    }

    /**
     * 处理请求参数。
     * Process post params.
     *
     * @access public
     * @return array
     */
    public function processPostParams()
    {
        return array_combine($this->post->varName, $this->post->queryValue);
    }

    /**
     * 执行旧版度量项。
     * Execute a sql metric.
     *
     * @param  object $measurement
     * @param  array  $vars
     * @access public
     * @return int|string
     */
    public function execSqlMeasurement($measurement, $vars)
    {
        $function = $this->getSqlFunctionName($measurement);
        if(!$function)
        {
            $this->errorInfo = $this->lang->metric->tips->nameError;
            return false;
        }

        $vars = (array) $vars;
        foreach($vars as $key => $param)
        {
            if(is_object($param))
            {
                unset($vars[$key]);
                continue;
            }

            $vars[$key] = $this->dbh->quote($param);
        }

        $params = join(',', $vars);

        try
        {
            $result = $this->dbh->query("select $function($params)")->fetch(PDO::FETCH_NUM);
        }
        catch(PDOException $exception)
        {
            $this->errorInfo = $exception->getMessage();
            return false;
        }

        return isset($result[0]) && $measurement->unit ? $result[0] : null;
    }

    /**
     * 获取度量筛选器的下拉选项。
     * Get options of a control.
     *
     * @param  string $optionType
     * @access public
     * @return array
     */
    public function getControlOptions($optionType)
    {
        $optionList = array();

        if($optionType == 'project')
        {
            $options = $this->loadModel('project')->getPairsByProgram();
        }
        elseif($optionType == 'product')
        {
            $options = $this->loadModel('product')->getPairs('nocode');
        }
        elseif($optionType == 'sprint')
        {
            $options = $this->loadModel('execution')->getPairs();
        }
        elseif($optionType == 'user')
        {
            $options = $this->loadModel('user')->getPairs('noletter');
        }
        elseif(strpos($optionType, '.') !== false)
        {
            list($moduleName, $varListName) = explode('.', $optionType);
            $this->app->loadLang($moduleName);
            $varListName .= 'List';
            $options = $this->lang->$moduleName->$varListName;
            unset($options[0]);
            unset($options['']);
        }
        else
        {
            $options = array('' => '');
        }

        return $options;
    }

    /**
     * 处理实现提示文本信息。
     * Process implement tips.
     *
     * @param  string $code
     * @access public
     * @return void
     */
    public function processImplementTips(string $code): void
    {
        $tmpRoot = $this->app->getTmpRoot();

        $instructionTips = $this->lang->metric->implement->instructionTips;

        foreach($instructionTips as $index => $tip)
        {
            $instructionTips[$index] = str_replace("{code}", $code, $instructionTips[$index]);
            $instructionTips[$index] = str_replace("{tmpRoot}", $tmpRoot, $instructionTips[$index]);
        }

        $this->lang->metric->implement->instructionTips = $instructionTips;
    }

    /**
     * 更新一个度量项的字段内容。
     * Update metric fields
     *
     * @param  string $metricID
     * @param  object $metric
     * @access public
     * @return void
     */
    public function updateMetricFields(string $metricID, object $metric): void
    {
        $this->dao->update(TABLE_METRIC)->data($metric)->where('id')->eq($metricID)->exec();
    }

    /**
     * 将旧度量项的信息附加到度量项列表中。
     * Append old metric info to metric list.
     *
     * @param  array $metrics
     * @access public
     * @return array
     */
    public function processOldMetrics($metrics)
    {
        $metricList = array();
        if(!in_array($this->config->edition, array('max', 'ipd')))
        {
            foreach($metrics as $metric)
            {
                $metric->isOldMetric = false;
                $metricList[] = $metric;
            }
        }
        else
        {
            $oldMetricList = $this->getOldMetricList();

            foreach($metrics as $metric)
            {
                $metric->isOldMetric = $this->isOldMetric($metric);
                $fromID = $metric->fromID;
                if($metric->isOldMetric && isset($oldMetricList[$fromID])) $metric->unit = $oldMetricList[$fromID]->unit;

                $metricList[] = $metric;
            }
        }

        return $metricList;
    }

    /**
     * 获取定时设置的标签。
     * Get label of collect configure.
     *
     * @param  object $metric
     * @access public
     * @return string
     */
    public function getCollectConfText($metric)
    {
        $collectConf = $metric->collectConf;
        $dateType    = $this->lang->metric->dateList[$collectConf->type];
        $dateConf    = $collectConf->type == 'week' ? $collectConf->week : $collectConf->month;

        $dateListText = '';
        if($collectConf->type == 'week')
        {
            foreach(explode(',', $dateConf) as $date) $dateListText .= $this->lang->metric->weekList[$date] . ',';
        }
        else
        {
            foreach(explode(',', $dateConf) as $date) $dateListText .= sprintf($this->lang->metric->monthText, $date) . ',';
        }

        return sprintf($this->lang->metric->collectConfText, $dateType, rtrim($dateListText, ','), $metric->execTime);
    }

    /**
     * 获取度量项的数据类型。
     * Get data type of metric.
     *
     * @param  array    $tableData
     * @access public
     * @return string|false
     */
    public function getMetricRecordType($code, $scope)
    {
        if(!$code) return false;

        $type = array();
        $dateType = $this->getDateTypeByCode($code);

        if($scope != 'system') $type[] = 'scope';
        if($dateType != 'nodate') $type[] = 'date';
        if(empty($type)) $type[] = 'system';

        return implode('-', $type);
    }

    /**
     * 获取一个echarts的legend配置。
     * Get lengend options of echarts by head and series.
     *
     * @param  array    $series
     * @param  string   $range object|time
     * @access public
     * @return array
     */
    public function getEchartLegend(array $series, string $range = 'time')
    {
        $legend = array('type' => 'scroll');

        if($range == 'object')
        {
            $selectedScope = array();
            foreach($series as $index => $se)
            {
                $selectedScope[$se['name']] = $index == 0 ? true : false;
            }

            $legend['selector'] = true;
            $legend['selected'] = $selectedScope;
        }

        return $legend;
    }

    /**
     * 通过header来判断一个度量项有没有对象的概念。
     * Judge whether a metric has the concept of an object.
     *
     * header 通过 metric 模块的 getViewTableHeader 方法取得
     * @param  array    $header 表头
     * @access public
     * @return bool
     */
    public function isObjectMetric(array $header): bool
    {
        return in_array('scope', array_column($header, 'name'));
    }

    /**
     * 通过header来判断一个度量项有没有日期的概念。
     * Judge whether a metric has the concept of an date.
     *
     * header 通过 metric 模块的 getViewTableHeader 方法取得
     * @param  array    $header 表头
     * @access public
     * @return bool
     */
    public function isDateMetric($header)
    {
        return in_array('date', array_column($header, 'name'));
    }

    /**
     * 获取一个echarts的配置项。
     * Get options of echarts by head and data.
     *
     * @param  array       $head 表头
     * @param  array       $data 数据
     * @param  string      $chartType 图表类型 barX|barY|line|pie
     * @access public
     * @return array|false
     */
    public function getEchartsOptions(array $header, array $data, string $chartType = 'line'): array|false
    {
        if(!$header || !$data) return false;
        $type = in_array($chartType, array('barX', 'barY')) ? 'bar' : $chartType;
        if($type == 'pie') return $this->getPieEchartsOptions($header, $data);

        $headLength = count($header);
        $options    = array();

        if($headLength == 2)
        {
            $options = $this->getTimeOptions($header, $data, $type, $chartType);
        }
        elseif($headLength == 3)
        {
            if($this->isObjectMetric($header))
            {
                $options = $this->getObjectOptions($data, $type, $chartType);
            }
            else
            {
                $options = $this->getTimeOptions($header, $data, $type, $chartType);
            }
        }
        elseif($headLength == 4)
        {
            $options = $this->getObjectOptions($data, $type, $chartType);
        }

        if($type == 'bar')
        {
            $xAxis = $options['xAxis'];
            $yAxis = $options['yAxis'];

            $options['xAxis'] = $chartType == 'barY' ? $yAxis : $xAxis;
            $options['yAxis'] = $chartType == 'barY' ? $xAxis : $yAxis;
        }

        return $options;
    }

    /**
     * 获取一个 对象属性度量的 echart options。
     * Get options of echarts by data.
     *
     * @param  array  $data 数据
     * @param  string $type 类型 line|bar
     * @access public
     * @return array
     */
    public function getObjectOptions(array $data, string $type, string $chartType): array
    {
        $dateField = !isset(current($data)->dateString) ? 'calcTime' : 'dateString';
        usort($data, function($a, $b) use ($dateField)
        {
            $keyA = $a->$dateField;
            $keyB = $b->$dateField;

            if($keyA == $keyB) return 0;

            return $keyA < $keyB ? -1 : 1;
        });

        $times   = array();
        $objects = array();
        foreach($data as $dataInfo)
        {
            $time   = substr($dataInfo->$dateField, 0, 10);
            $object = $dataInfo->scope;
            $value  = $dataInfo->value;

            if(!isset($times[$time]))     $times[$time] = $time;
            if(!isset($objects[$object])) $objects[$object] = array();
            $objects[$object][$time] = $value;
        }

        $xAxis  = array('type' => 'category', 'data' => array_keys($times));
        $yAxis  = array('type' => 'value', 'min' => 'dataMin', 'max' => 'dataMax');
        $series = array();

        if($type != 'line') $yAxis = array('type' => 'value');
        foreach($objects as $object => $datas)
        {
            $seriesData = array();
            foreach($times as $time)
            {
                $seriesData[] = isset($datas[$time]) ? $datas[$time] : 0;
            }

            $series[] = array('type' => $type, 'name' => $object, 'data' => $seriesData);
        }

        $legend = $this->getEchartLegend($series, 'object');

        $options = array();
        $options['xAxis']   = $xAxis;
        $options['yAxis']   = $yAxis;
        $options['legend']  = $legend;
        $options['series']  = $series;
        $options['grid']    = $this->config->metric->chartConfig->grid;
        $options['tooltip'] = $this->config->metric->chartConfig->tooltip;

        $dataLength = count($series[0]['data']);
        if($dataLength > 15) $options['dataZoom'] = $this->genDataZoom($dataLength, 15, $chartType == 'barY' ? 'y' : 'x');

        return $options;
    }

    /**
     * 获取一个 时间属性度量的 echart options。
     * Get options of echarts by head and data.
     *
     * @param  array  $head 表头
     * @param  string $type 类型 line|bar
     * @access public
     * @return array
     */
    public function getTimeOptions(array $header, array $data, string $type, string $chartType): array
    {
        list($x, $y) = $this->getEchartXY($header);

        usort($data, function($a, $b) use ($x)
        {
            $keyA = $a->$x;
            $keyB = $b->$x;

            if($keyA == $keyB) return 0;

            return $keyA < $keyB ? -1 : 1;
        });

        $xTime = array_column($data, $x);
        $xAxis = array('type' => 'category', 'data' => $xTime);
        $yAxis = array('type' => 'value', 'min' => 'dataMin', 'max' => 'dataMax');
        if($type != 'line') $yAxis = array('type' => 'value');

        $series = array('type' => $type, 'data' => array_column($data, $y));
        $legend = $this->getEchartLegend($series);

        $options = array();
        $options['xAxis']  = $xAxis;
        $options['yAxis']  = $yAxis;
        $options['legend'] = $legend;
        $options['series'] = $series;
        $options['grid']    = $this->config->metric->chartConfig->grid;
        $options['tooltip'] = $this->config->metric->chartConfig->tooltip;

        $dataLength = count($data);
        if($dataLength > 15) $options['dataZoom'] = $this->genDataZoom($dataLength, 15, $chartType == 'barY' ? 'y' : 'x');

        return $options;
    }

    /**
     * 生成数据缩放的配置。
     * Generate data zoom config.
     *
     * @param  int    $dataLength
     * @param  int    $initZoom
     * @param  stirng $axis
     * @access public
     * @return array
     */
    public function genDataZoom(int $dataLength, int $initZoom = 10, string $axis = 'x')
    {
        $percent = $initZoom / $dataLength * 100;
        $percent = $percent > 100 ? 100 : $percent;

        $dataZoom = $this->config->metric->chartConfig->dataZoom;
        $dataZoom['start'] = 0;
        $dataZoom['end']   = $percent;

        if($axis == 'x') $dataZoom['xAxisIndex'] = array(0);
        if($axis == 'y') $dataZoom['yAxisIndex'] = array(0);
        return array($dataZoom);
    }

    /**
     * 获取一个echarts pie的配置项。
     * Get options of echarts pie by head and data.
     *
     * @param  array  $head  表头
     * @param  array  $datas 数据
     * @access public
     * @return array
     */
    public function getPieEchartsOptions(array $header, array $datas): array
    {
        list($x, $y) = $this->getEchartXY($header);

        $seriesData = array();
        foreach($datas as $data)
        {
            $seriesData[] = array('name' => $data->$x, 'value' => $data->$y);
        }

        $options = array();
        $options['tooltip'] = array('trigger' => 'item');
        $options['legend']  = array('orient' => 'vertical', 'left' => 'left', 'type' => 'scroll');
        $options['series']  = array(array('type' => 'pie', 'radius' => '50%', 'data' => $seriesData, 'emphasis' => array('itemStyle' => array('shadowBlur' => 10, 'shadowOffsetX' => 0, 'shadowColor' => 'rgba(0, 0, 0, 0.5)'))));

        return $options;
    }

    /**
     * 获取图表 x轴 和 y轴的字段。
     * Get echart x and y field.
     *
     * @param  array  $header 表头
     * @access public
     * @return array
     */
    public function getEchartXY(array $header): array
    {
        $x = $y = '';
        $headLength = count($header);
        if($headLength == 2)
        {
            $x = $header[1]['name'];
            $y = $header[0]['name'];
        }
        elseif($headLength == 3)
        {
            $x = $header[0]['name'];
            $y = $header[1]['name'];
        }

        return array($x, $y);
    }

    /**
     * 获取一个图表可选的类型。
     * Get a echart typeList.
     *
     * @param  array  $header 表头
     * @access public
     * @return array
     */
    public function getChartTypeList(array $header): array
    {
        $chartTypeList = $this->lang->metric->chartTypeList;
        if($this->isObjectMetric($header)) unset($chartTypeList['pie']);

        return $chartTypeList;
    }

    /**
     * 更新度量项的创建时间
     * Update created date of metrics.
     *
     * @access public
     * @return void
     */
    public function updateMetricDate()
    {
        $this->dao->update(TABLE_METRIC)->set('createdDate')->eq(helper::now())->where('createdDate is null')->exec();
    }

    /**
     * 获取度量项计算文件的根目录。
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
     * 获取数据集文件的路径
     * Get path of calculator data set.
     *
     * @access public
     * @return string
     */
    public function getDatasetPath()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'dataset.php';
    }

    /**
     * 获取度量项基类文件的路径。
     * Get path of base calculator class.
     *
     * @access public
     * @return string
     */
    public function getBaseCalcPath()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'calc.class.php';
    }

    /**
     * 处理度量数据查询条件。
     * Process metric data query.
     *
     * @param  array     $query
     * @param  string    $key
     * @param  string    $type
     * @access public
     * @return object|string|false
     */
    public function processRecordQuery(array $query, string $key, string $type = 'common'): object|array|string|false
    {
        if($key == 'dateLabel')
        {
            if(isset($query[$key]))
            {
                $dateType  = $query['dateType'];
                $dateLabel = $query['dateLabel'];

                if($dateLabel == 'all') $dateStr = '1970-01-01';
                if(is_numeric($dateLabel)) $dateStr = date('Y-m-d', strtotime('-' . ((int)$dateLabel - 1) . " {$dateType}s"));

                $query['dateBegin'] = $dateStr;
                $query['dateEnd']   = date('Y-m-d');
            }

            $begin = $this->processRecordQuery($query, 'dateBegin', 'date');
            $end   = $this->processRecordQuery($query, 'dateEnd', 'date');
            return array($begin, $end);
        }

        if($key == 'calcDate' && $query['dateType'] == 'nodate')
        {
            if(!isset($query[$key])) $query['calcDate'] = 7;
            return date('Y-m-d', strtotime("-{$query[$key]} days"));
        }

        if(!isset($query[$key]) || empty($query[$key])) return false;

        if($type == 'date')
        {
            $dateStr = $query[$key];
            if($query['dateType'] == 'year')
            {
                $dateStr = "{$dateStr}-01-01";
            }
            elseif($query['dateType'] == 'month')
            {
                $dateStr = "{$dateStr}-01";
            }

            $timestamp = strtotime($dateStr);

            $year  = date('Y', $timestamp);
            $month = date('m', $timestamp);
            $day   = date('d', $timestamp);
            $week  = date('oW', $timestamp);

            $dateParse = new stdClass();
            $dateParse->year  = $year;
            $dateParse->month = "{$year}{$month}";
            $dateParse->week  = $week;
            $dateParse->day   = "{$year}{$month}{$day}";

            return $dateParse;
        }
        return $query[$key];
    }

    /**
     * 根据日期类型和日期，拆解出日期的年、月、周、日。
     * Get date values by date type and date.
     *
     * @param  string $date
     * @param  string $dateType
     * @access public
     * @return object|array
     */
    public function parseDateStr($date, $dateType = 'all')
    {
        $timestamp = strtotime($date);

        $year  = date('Y', $timestamp);
        $month = date('m', $timestamp);
        $day   = date('d', $timestamp);
        $week  = date('oW', $timestamp);
        $week  = substr($week, -2);

        $dateValues = new stdClass();
        $dateValues->year   = array('year' => $year);
        $dateValues->month  = array('year' => $year, 'month' => $month);
        $dateValues->week   = array('year' => $year, 'week' => $week);
        $dateValues->day    = array('year' => $year, 'month' => $month, 'day' => $day);
        $dateValues->nodate = array();

        return $dateType == 'all' ? $dateValues : $dateValues->$dateType;
    }

    /**
     * 获取度量数据的日期类型。
     * Get date type of metric data.
     *
     * @param  array    $dateFields
     * @access public
     * @return string
     */
    public function getDateType(array $dateFields): string
    {
        $dateList = array_intersect($dateFields, $this->config->metric->dateList);

        if(in_array('day',   $dateList)) return 'day';
        if(in_array('week',  $dateList)) return 'week';
        if(in_array('month', $dateList)) return 'month';
        if(in_array('year',  $dateList)) return 'year';

        return 'nodate';
    }

    /**
     * 根据编号获取度量项的日期属性。
     * Get date type by metric code.
     *
     * @param  string $code
     * @access public
     * @return string
     */
    public function getDateTypeByCode(string $code)
    {
        static $dateTypes = array();
        if(!isset($dateTypes[$code]))
        {
            /* Get dateType form db first. */
            $metric = $this->getByCode($code, 'dateType');

            if(!empty($metric->dateType)) $dateTypes[$code] = $metric->dateType;
            /* Get dateType from config second. */
            elseif(isset($this->config->metric->dateType[$code])) $dateTypes[$code] = $this->config->metric->dateType[$code];
            /* Return nodate if no matches. */
            else $dateTypes[$code] = 'nodate';
        }

        return $dateTypes[$code];
    }

    /**
     * 获取度量数据的日期类型。
     * Get date type of metric data.
     *
     * @param  array    $dateFields
     * @access public
     * @return string
     */
    public function getDateByDateType(string $dateType): string
    {
        if($dateType == 'day')   $sub = '-7 days';
        if($dateType == 'week')  $sub = '-1 month';
        if($dateType == 'month') $sub = '-1 year';
        if($dateType == 'year')  $sub = '-3 years';

        return date('Y-m-d', strtotime($sub));
    }

    /**
     * 解析SQL函数。
     * Parsing SQL function.
     *
     * @param  string $sql
     * @access public
     * @return string
     */
    public function parseSqlFunction($sql)
    {
        $pattern = "/create\s+function\s+`{0,1}([\$,a-z,A-z,_,0-9,\(,|)]+`{0,1})\(+/Ui";
        preg_match_all($pattern, $sql, $matches);

        if(empty($matches[1][0])) return null;
        return trim($matches[1][0], '`');
    }

    /**
     * 替换换行符和回车符为指定字符。
     * Replace CR and LF to char.
     *
     * @param  string $str
     * @param  string $replace
     * @access public
     * @return string
     */
    public function replaceCRLF(string $str, string $replace = ';'): string
    {
        $str = trim($str);
        if(strpos($str, "\n\r") !== false) $str = str_replace("\n\r", $replace, $str);
        if(strpos($str, "\r\n") !== false) $str = str_replace("\r\n", $replace, $str);
        if(strpos($str, "\n") !== false)   $str = str_replace("\n",   $replace, $str);
        if(strpos($str, "\r") !== false)   $str = str_replace("\r",   $replace, $str);

        return $str;
    }

    /**
     * 判断 header 是否有分组（合并单元格）。
     * Judge header whether there are merges cell.
     *
     * @param  array     $header
     * @access protected
     * @return array
     */
    protected function isHeaderGroup($header)
    {
        if(!$header) return false;

        foreach($header as $head)
        {
            if(isset($head['headerGroup'])) return true;
        }

        return false;
    }

    /**
     * 判断是否是按系统统计的度量项。
     * Determine whether it is metric in system.
     *
     * @param  array  $results
     * @access public
     * @return bool
     */
    public function isSystemMetric($results)
    {
        $firstRecord = (object)current($results);

        foreach($this->config->metric->excludeGlobal as $exclude)
        {
            if(isset($firstRecord->$exclude)) return false;
        }
        return true;
    }

    /**
     * 获取某一周的第一天和最后一天的日期。
     * Get the first and last day of a week.
     *
     * @param  int|string $year
     * @param  int|string $week
     * @param  string     $type date|datetime
     * @access public
     * @return bool
     */
    public function getStartAndEndOfWeek($year, $week, $type = 'datetime')
    {
        $firstDayOfYear = date('Y-01-01', strtotime("$year-01-01"));
        $firstDayOfWeek = date('N', strtotime($firstDayOfYear));

        $offsetDays = ($week - 1) * 7 - ($firstDayOfWeek - 1);

        $firstDayOfWeek = date('Y-m-d', strtotime("$firstDayOfYear +$offsetDays days"));
        $lastDayOfWeek  = date('Y-m-d', strtotime("$firstDayOfWeek +6 days"));

        if($type == 'datetime') return array("$firstDayOfWeek 00:00:00", "$lastDayOfWeek 23:59:59");
        if($type == 'date')     return array($firstDayOfWeek, $lastDayOfWeek);
    }

    /**
     * 判断某个度量项在某天是否被定时任务执行过。
     * Determine whether a metric has been executed by scheduled task on a certain day.
     *
     * @param  string $code
     * @param  string $date
     * @param  string $dateType
     * @access public
     * @return bool
     */
    public function isCalcByCron($code, $date, $dateType)
    {
        $startDate = '';
        $endDate   = '';
        $parsedDate = $this->parseDateStr($date, $dateType);
        if($dateType == 'year')
        {
            $startDate = "{$parsedDate['year']}-01-01 00:00:00";
            $endDate   = "{$parsedDate['year']}-12-31 23:59:59";
        }
        if($dateType == 'month')
        {
            $startDate = "{$parsedDate['year']}-{$parsedDate['month']}-01 00:00:00";

            $nextMonth = date('Y-m-01', strtotime("$startDate +1 month"));
            $endDate   = date('Y-m-d', strtotime("$nextMonth -1 day"));
            $endDate   = "{$endDate} 23:59:59";
        }
        if($dateType == 'day')
        {
            $startDate = "{$parsedDate['year']}-{$parsedDate['month']}-{$parsedDate['day']} 00:00:00";
            $endDate   = "{$parsedDate['year']}-{$parsedDate['month']}-{$parsedDate['day']} 23:59:59";
        }
        if($dateType == 'week') list($startDate, $endDate) = $this->getStartAndEndOfWeek($parsedDate['year'], $parsedDate['week']);

        $record = $this->dao->select('id')->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->andWhere('calcType')->eq('cron')
            ->andWhere('date')->ge($startDate)
            ->andWhere('date')->le($endDate)
            ->limit(1)
            ->fetch();

        return !empty($record);
    }

    /**
     * 获取度量项的收集周期。
     * Get collect cycle of metric.
     *
     * @param  array|object $record
     * @access public
     * @return string|null
     */
    public function getMetricCycle($record)
    {
        $record = (object)$record;

        if(isset($record->year) && !isset($record->month) && !isset($record->week)) return 'year';
        if(isset($record->year, $record->month) && !isset($record->day)) return 'month';
        if(isset($record->year, $record->month, $record->day)) return 'day';
        if(isset($record->year, $record->week)) return 'week';

        return null;
    }

    /**
     * 获取日期标签。
     * Get date labels.
     *
     * @param  string    $dateType
     * @access public
     * @return array|false
     */
    public function getDateLabels(string $dateType): array|false
    {
        if($dateType == 'nodate') return array();
        $objectKey = "{$dateType}Labels";

        return $this->lang->metric->query->$objectKey;
    }

    /**
     * 获取默认选中的日期。
     * Get default selected date.
     *
     * @param  array $dateLabels
     * @param  array $filters
     * @access public
     * @return string
     */
    public function getDefaultDate(array $dateLabels, array $filters = array()): string
    {
        $defaultDate = '';

        $dates = array_keys($dateLabels);
        return (string)current($dates);
    }

    /**
     * 获取没有数据时的提示信息。
     * Get no data tip.
     *
     * @param  string $code
     * @access public
     * @return string
     */
    public function getNoDataTip($code)
    {
        $recordOfCode = $this->metricTao->fetchMetricRecordByDate($code, null, 1);
        $recordOfAll  = $this->metricTao->fetchMetricRecordByDate('all', null, 1);

        /* 如果度量库没有任何数据，则说明度量项从来没有被采集过。*/
        /* If the metric library does not have any data, it means that the metric has never been collected. */
        if(empty($recordOfAll)) return $this->lang->metric->noDataBeforeCollect;

        /* 如果度量库有数据但没有该度量项的数据，则可以理解为收集过数据但是该度量项无数据。*/
        /* If the metric library has data but does not have data for this metric, it means that metric has been collected but there is no data for this metric. */
        if(!empty($recordOfAll) && empty($recordOfCode)) return $this->lang->metric->noDataAfterCollect;

        return $this->lang->metric->noData;
    }

    /**
     * 检查某个度量项在某个日期中是否被推算过。
     * Check whether a metric has been inferenced on a date
     *
     * @param  string $code
     * @param  string $dateType
     * @param  string $date
     * @access public
     * @return bool
     */
    public function checkHasInferenceOfDate($code, $dateType, $date)
    {
        if($dateType == 'day' || $dateType == 'nodate') return false;

        $date = $this->parseDateStr($date, $dateType);
        $records = $this->dao->select('id')->from(TABLE_METRICLIB)
           ->where('metricCode')->eq($code)
           ->andWhere('calcType')->eq('inference')
           ->beginIF($dateType == 'year')->andWhere('year')->eq($date['year'])->fi()
           ->beginIF($dateType == 'month')->andWhere('year')->eq($date['year'])->andWhere('month')->eq($date['month'])->fi()
           ->beginIF($dateType == 'week')->andWhere('year')->eq($date['year'])->andWhere('week')->eq($date['week'])->fi()
           ->fetch();

        return !empty($records);
    }

    /**
     * 检查是否是第一次执行重算。
     * Check if this is the first time inference record.
     *
     * @param  string|array|null $codes
     * @access public
     * @return bool
     */
    public function isFirstInference($codes = null)
    {
        $inferenceRecordCount = $this->dao->select('COUNT(id) AS count')->from(TABLE_METRICLIB)
            ->where('calcType')->eq('inference')
            ->beginIF($codes != null && !is_array($codes))->andWhere('metricCode')->eq($codes)->fi()
            ->beginIF($codes != null && is_array($codes))->andWhere('metricCode')->in($codes)->fi()
            ->fetch('count');

        return $inferenceRecordCount == 0;
    }

    /**
     * 根据动态获取安装禅道的大概时间。
     * Get date of install zentao accorrading action.
     *
     * @access public
     * @return string
     */
    public function getInstallDate()
    {
        $installedDate = $this->dao->select('value')->from(TABLE_CONFIG)
            ->where('section')->eq('global')
            ->andWhere('key')->eq('installedDate')
            ->limit(1)
            ->fetch('value');

        if(!empty($installedDate) && substr($installedDate, 0 ,4) != '0000') return $installedDate;

        return $this->dao->select('date')->from(TABLE_ACTION)
            ->orderBy('date_asc')
            ->limit(1)
            ->fetch('date');
    }

    /**
     * 根据版本过滤度量项。
     * Filter metric instance by edition.
     *
     * @param  array  $calcInstances
     * @access public
     * @return array
     */
    public function filterCalcByEdition($calcInstances)
    {
        foreach($calcInstances as $code => $instance)
        {
            $excludeDatasetConfig = $this->config->metric->excludeDatasetList;
            if(!empty($instance->dataset) && !empty($excludeDatasetConfig[$this->config->edition]))
            {
                $excludeDatasetList = $excludeDatasetConfig[$this->config->edition];
                if(in_array($instance->dataset, $excludeDatasetList)) unset($calcInstances[$code]);
            }
        }

        return $calcInstances;
    }
}
