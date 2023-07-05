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
class func
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
     * 收集方式。可选项：实时更新|定时任务|动作触发
     * Collection methods of measurement.Options: realtime|cron|action
     *
     * @var string
     * @access public
     */
    public $collectType;

    /**
     * 范围。可选项：全局|项目集|项目|执行|产品|个人|团队
     * Range of measurement.Options: global|project|execution|product|user|dept
     *
     * @var string
     * @access public
     */
    public $scope;

    /**
     * 目的。可选项：规模估算|质量控制|工时统计|成本计算|效率提升|工期控制
     * Purpose of measurement.Options: scale|qc|hour|cost|rate|time
     *
     * @var string
     * @access public
     */
    public $purpose;

    /**
     * 收集频率。
     * Frequence of collection.
     *
     * @var string
     * @access public
     */
    public $collectCFG;

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
    public $leftJoins;

    /**
     * 过滤条件。
     * Filters of data.
     *
     * @var array
     * @access public
     */
    public $filters;

    /**
     * 分组方式。
     * Groups of data.
     *
     * @var array
     * @access public
     */
    public $groups;

    /**
     * 指标。
     * Indicators of measurement.
     *
     * @var array
     * @access public
     */
    public $indicators;

    /**
     * 指标结果。
     * Result of indicators.
     *
     * @var array|float
     * @access public
     */
    public $result;

    /**
     * 根据主表和连接表，抽取数据库表和字段。
     *
     * @access public
     * @return void
     */
    public function getTables()
    {
    }

    /**
     * 根据主表和连接表，生成sql。
     *
     * @access public
     * @return void
     */
    public function getSql($fields)
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
        /* 1. 判断过滤条件是否满足，不满足则不计算。*/
        /* 2. 遍历指标，进行计算。*/
        /* 2.1 如果指标中包含了分组类型，则在result中维护分组。*/
        /* 2.2 指标包含了筛选条件，满足筛选条件时记录数据, 否则直接记录数据。*/
    }

    /**
     * 获取度量项结果。
     *
     * @access public
     * @return void
     */
    public function getResult()
    {
    }

    /**
     * 解析过滤条件，返回当前数据满足过滤条件的bool值。
     *
     * @param  array    $data
     * @access public
     * @return bool
     */
    public function parseCondition($data)
    {
        $operator   = $this->filter['operator'];
        $conditions = $this->filter['conditions'];

        return $this->traverseCondition($data, $operator, $conditions);
    }

    /**
     * 根据指标属性获取对应指标的数据值。
     *
     * @param  array    $data
     * @param  array    $indicator
     * @access public
     * @return string
     */
    public function getIndicatorValue($data, $indicator)
    {
        $table    = $indicator['table'];
        $field    = $indicator['field'];

        return $this->getValue($data, $table, $field);
    }

    /**
     * 递归遍历过滤条件，返回过滤条件的bool值。
     *
     * @param  array  $data
     * @param  string $operator
     * @param  int    $conditions
     * @access private
     * @return void
     */
    private function traverseCondition($data, $operator, $conditions)
    {
        $judgeBool = array();
        foreach($conditions as $condition)
        {
            if(isset($condition['conditions']))
            {
                $subOperator    = $condition['operator'];
                $subConditions  = $condition['conditions'];
                $judgeBool[]    = $this->traverseCondition($data, $subOperator, $subConditions);
                continue;
            }

            $table    = $condition['table'];
            $field    = $condition['field'];
            $value    = $condition['value'];

            $dataValue   = $this->getValue($data, $table, $field);
            $judgeBool[] = self::getBoolByCondition($condition['operator'], $dataValue, $value);

            // $judgeBool[] = eval("return {$dataValue} $condition['operator'] $value;");
        }

        $result = $operator == 'and' ? true : false;
        foreach($judgeBool as $flag)
        {
            $result = $operator == 'and' ? ($result && $flag) : ($result || $flag);
        }

        return $result;
    }

    /**
     * 根据字段和表，获取字段的数据值。
     * Get value of field.
     *
     * @param  array    $data
     * @param  string   $table
     * @param  string   $field
     * @access private
     * @return void
     */
    private function getValue($data, $table, $field)
    {
        return is_array($data) ? $data[$field] : ((array)$data)[$field];
    }

    /**
     * 根据参数计算bool值。
     * Compute boolean value by condition.
     *
     * @param  string           $operator
     * @param  string|number    $left
     * @param  string|number    $right
     * @static
     * @access public
     * @return void
     */
    public static function getBoolByCondition($operator, $left, $right)
    {
        $flag = false;
        switch($operator)
        {
            case '=':
                $flag = $left == $right;
                break;
            case '>':
                $flag = $left >  $right;
                break;
            case '>=':
                $flag = $left >= $right;
                break;
            case '<':
                $flag = $left <  $right;
                break;
            case '<=':
                $flag = $left <= $right;
                break;
            case '!=':
                $flag = $left != $right;
                break;
        }
        return $flag;
    }

    /**
     * 根据字段分组。
     * Group by field.
     *
     * @param  string $field
     * @param  object $data
     * @access public
     * @return void
     */
    public function groupBy($field, $data)
    {
        $value = $data->$field;
        if(empty($this->result[$value])) $this->result[$value] = array();
        $this->result[$value][] = $data;
    }
}
