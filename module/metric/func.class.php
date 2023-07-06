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
class baseMetric
{
    /**
     * 来源数据集。
     * dataset
     *
     * @var int
     * @access public
     */
    public $dataset;

    /**
     * 参数列表。
     * fieldList
     *
     * @var int
     * @access public
     */
    public $fieldList;

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
    public $result;

    /**
     * 获取数据查询句柄。
     * Get pdo statement of sql query.
     *
     * @param  object    $dao
     * @access public
     * @return PDOStatement
     */
    public function getStatement($dao)
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
     * @access public
     * @return void
     */
    public function getResult()
    {
        if(empty($this->result)) return null;
        return array((object)array('value' => $this->result));
    }
}
