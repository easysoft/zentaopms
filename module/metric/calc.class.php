<?php
/**
 * 度量项定义基类。
 * Base class of measurement func.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @license   LGPL
 * @Link      https://www.zentao.net
 */
class baseCalc
{
    /**
     * 来源数据集。
     * dataset
     *
     * @var int
     * @access public
     */
    public $dataset = null;

    /**
     * 数据库连接。
     * Database connection.
     *
     * @var object
     * @access public
     */
    public $dao = null;

    /**
     * Git 仓库对象。
     * Git repository object.
     *
     * @var object
     * @access public
     */
    public $scm = null;

    /**
     * 参数列表。
     * fieldList
     *
     * @var array
     * @access public
     */
    public $fieldList = array();

    /**
     * 数据主表。
     * Main table.
     *
     * @var string
     * @access public
     */
    public $mainTable;

    /**
     * 连接表。
     * Left join tables.
     *
     * @var array
     * @access public
     */
    public $subTables;

    /**
     * 过滤条件。
     * Filters of data.
     *
     * @var array
     * @access public
     */
    public $filters;

    /**
     * 指标结果。
     * Result of indicators.
     *
     * @var array|float
     * @access public
     */
    public $result = 0;

    /**
     * 设置DAO 。
     * Set DAO.
     *
     * @param  object $dao
     * @access public
     * @return void
     */
    public function setDAO($dao)
    {
        $this->dao = $dao;
    }

    public function setSCM($scm)
    {
        $this->scm = $scm;
    }

    /**
     * 获取数据查询句柄。
     * Get pdo statement of sql query.
     *
     * @param  object    $dao
     * @access public
     * @return PDOStatement
     */
    public function getStatement()
    {
    }

    /**
     * 计算度量项。
     * Calculate metric.
     *
     * @param  object $data
     * @access public
     * @return void
     */
    public function calculate($data)
    {
    }

    /**
     * 获取度量项结果。
     *
     * @param  array  $options
     * @access public
     * @return mixed
     */
    public function getResult($options = array())
    {
        if(empty($this->result)) return null;
        return array((object)array('value' => 0));
    }

    /**
     * 根据选项过滤数据。
     * Filter rows by options.
     *
     * @param  array|int $rows
     * @param  array     $options array('product' => '1,2,3,4')
     * @access protected
     * @return array|false
     */
    protected function filterByOptions($rows, $options)
    {
        if(empty($options)) return $rows;

        $rows = (array)$rows;
        $options = $this->expandOptions($options);

        $filteredRows = array();
        foreach($rows as $row)
        {
            $satisify = true;
            foreach($options as $scope => $option)
            {
                $row = (array)$row;
                if(!isset($row[$scope])) continue;
                $satisify = ($satisify && in_array($row[$scope], $option));
            }
            if($satisify) $filteredRows[] = $row;
        }

        return !empty($filteredRows) ? $filteredRows : false;
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

    /**
     * 获取数据源的SQL语句
     * Get SQL of data source.
     *
     * @access public
     * @return string
     */
    public function getSQL($dao)
    {
        if(!empty($this->dataset))
        {
            $dataSource = $this->dataset;
            include_once dirname(__FILE__) . '/dataset.php';
            $dataset = new dataset($dao);

            $statement = $dataset->$dataSource(implode(',', $this->fieldList));
            return $statement->queryString;
        }

        return $this->dao->get();
    }

    /**
     * 打印数据源的SQL语句
     * Print SQL of data source.
     *
     * @param  object $dao
     * @access public
     * @return mixed
     */
    public function printSQL($dao)
    {
        a($dao);
        echo $this->getSQL($dao);
    }

    /**
     * 获取数据源的结果集。
     * Get rows of data source.
     *
     * @param  object $dao
     * @access public
     * @return array
     */
    public function getRows($dao = null)
    {
        if(!empty($this->dataset))
        {
            $dataSource = $this->dataset;
            include_once dirname(__FILE__) . '/dataset.php';
            $dataset = new dataset($dao);

            $statement = $dataset->$dataSource(implode(',', $this->fieldList));
            return $statement->fetchAll();
        }

        return $this->getStatement()->fetchAll();
    }

    /**
     * 转换计算结果数组为度量记录数据数组。
     * Get records from result.
     *
     * @param  array  $keyNames
     * @access public
     * @return array
     */
    public function getRecords($keyNames)
    {
        if(empty($keyNames)) return $this->result;

        if(current($keyNames) == 'value') return array(array('value' => $this->result));

        $records = array();
        $keyName = array_shift($keyNames);
        foreach($this->result as $key => $value) $records[] = array($keyName => $key, 'value' => $value);

        while($keyName = array_shift($keyNames))
        {
            if($keyName == 'value') break;

            $newRecords = array();
            foreach($records as $record)
            {
                foreach($record['value'] as $key => $value)
                {
                    $record[$keyName] = $key;
                    $record['value']  = $value;
                    $newRecords[] = $record;
                }
            }

            $records = $newRecords;
        }

        return $records;
    }

    /**
     * 获取日期的周数。
     * Get week of date.
     *
     * @param  string $dateStr
     * @access public
     * @return string
     */
    public function getWeek($dateStr)
    {
        if(empty($dateStr)) return false;

        if(strlen($dateStr) > 10) $dateStr = substr($dateStr, 0, 10);
        $date = DateTime::createFromFormat('Y-m-d', $dateStr);

        return substr($date->format('oW'), -2);
    }

    /**
     * 获取日期的年份。
     * Get year of date.
     *
     * @param  string $dateStr
     * @access public
     * @return string|false
     */
    public function getYear($dateStr)
    {
        if(empty($dateStr)) return false;

        if(strlen($dateStr) > 8) $dateStr = substr($dateStr, 0, 8);
        $year = substr($dateStr, 0, 4);

        return $year == '0000' ? false : $year;
    }
}
