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
     * @var int
     * @access public
     */
    public $dao = null;

    /**
     * 参数列表。
     * fieldList
     *
     * @var int
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
    public $result = array();

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
        $this->result += 1;
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
        return array((object)array('value' => $this->result));
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
                $row = (object)$row;
                if(!isset($row->$scope)) continue;
                $satisify = ($satisify && in_array($row->$scope, $option));
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
}
