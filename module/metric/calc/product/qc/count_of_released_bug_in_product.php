<?php
/**
 * [度量项名称]。
 * [度量项名称（英文）].
 *
 * 范围：[范围]
 * 对象：[对象]
 * 目的：[目的]
 * 度量名称：[度量项名称]
 * 单位：[单位]
 * 描述：[描述度量项的含义]
 * 定义：[描述度量项如何定义，计算规则等]
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    XXX <XXX@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class xxx_of_xxx_in_xxx extends baseCalc
{
    /**
     * 通用数据源，详情见通用数据源列表。
     */
    public $dataset = null;

    /**
     * 通用数据源对应使用的字段，具体字段根据使用的通用数据源来设置。
     *
     * 如 public $dataset = "getProjectBugs";
     * 涉及的表有 zt_bug t1, zt_product t2, zt_project t3
     * 那么$fieldList可以使用 zt_bug表、zt_product表、zt_project表的所有字段；
     * 例如 public $fieldList = array('t1.resolvedBy', 't1.resolvedDate', 't2.name as productName', 't3.name as projectName');
     */
    public $fieldList = array();

    /**
     * 度量项计算临时结果。
     *
     * 如果度量项为单个值，   例如【按全局统计的bug数】，    则为int类型；
     * 如果度量项为单个维度， 例如【按产品统计的bug数】，    则为array(productID => value);
     * 如果度量项为多个维度 ，例如【按产品统计的每月bug数】，则为array(productID => array(year => array(month => value)));
     */
    public $result = array();

    /**
     * 获取自定义数据源pdo句柄。
     *
     * 如果设置了通用数据源，该函数将不会被执行；
     * 可以使用this->dao进行数据库查询；
     * 返回值为PDOStatement。
     */
    public function getStatement()
    {
        /**
         * 例如【按全局统计的有效研发需求数】
         * return $this->dao->select('count(t1.id) as value')->from(TABLE_STORY)->alias('t1')
         *       ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
         *       ->where('t1.deleted')->eq(0)
         *       ->andWhere('t2.deleted')->eq(0)
         *       ->andWhere('t2.shadow')->eq(0)
         *       ->andWhere('t1.type')->eq('story')
         *       ->andWhere('t1.closedReason')->notin('duplicate,willnotdo,bydesign,cancel')
         *       ->query();
         */
        return $this->dao->XXX->query();
    }

    /**
     * 计算度量项。
     *
     * 对数据源查询得到的数据集进行逐行计算，该函数实现对于一行数据的计算逻辑
     * 计算完成后将数据临时记录到$this->result上
     *
     * @param object $row 数据源的一行数据
     */
    public function calculate($row)
    {
        /**
         * 例如【按全局统计的研发需求总数】
         * $this->result += 1;
         *
         * 例如【按全局统计的未关闭Bug数】
         * if($row->status != 'closed') $this->result += 1;
         *
         * 例如【按全局统计的月度修复Bug数】
         * $closedDate = $row->closedDate;
         * if(empty($closedDate)) return false;
         *
         * $year = substr($closedDate, 0, 4);
         * if($year == '0000') return false;
         * $month = substr($closedDate, 5, 2);
         *
         * if(!isset($this->result[$year])) $this->result[$year] = array();
         * if(!isset($this->result[$year][$month])) $this->result[$year][$month] = 0;
         * if($row->status == 'closed' and $row->resolution == 'fixed') $this->result[$year][$month] += 1;
         */
    }

    /**
     * 汇总并获取度量项计算结果。
     *
     * @param array $options 筛选参数，如果不传参，则返回度量项所有数据
     *                       产品id为5：              array('product' => '5')
     *                       产品id为5，6，7：        array('product' => '5,6,7')
     *                       产品id为5，年份为2023年：array('product' => '5', 'year' => '2023')
     */
    public function getResult($options = array())
    {
        /**
         * $this->getRecords($keys) 将 $this->result拍平为
         * array(product => 1, year => 2023, month => 03)
         * $keys为拍平后的键值数组，需要与$this->result的层级顺序保持一致。
         *
         * 例如【按全局统计的bug数】
         * $this->result = 200;
         * $this->getRecords(array('value'));
         * 逻辑等同于
         * $records = array(array('value' => $this->result = 200));
         *
         * 例如【按产品统计的bug数】
         * $this->result = array(1 => 25, 2 => 33, 5 => 21, ...);
         * $this->getRecords(array('product', 'value'));
         * 逻辑等同于
         * $records = array();
         * foreach($this->result as $product => $value)
         * {
         *     $records[] = array('product' => $product, 'value' => $value);
         * }
         *
         * 例如【按产品统计的每月bug数】
         * $this->result = array
         * (
         *     1 => array
         *         (
         *             2023 => array(08 => 21, 12 => 44, ...),
         *             2022 => array(08 => 63, 12 => 29, ...),
         *             ...
         *         ),
         *     2 => array
         *         (
         *             2023 => array(04 => 21, 10 => 44, ...),
         *             2022 => array(05 => 63, 07 => 29, ...),
         *             ...
         *         ),
         *     ...
         * );
         * $this->getRecords(array('product', 'year', 'month', 'value'));
         * 逻辑等同于
         * $records = array();
         * foreach($this->result as $product => $years)
         * {
         *     foreach($years as $year => $months)
         *     {
         *         foreach($months as $month => $value)
         *         {
         *             $records[] = array('product' => $product, 'year' => $year, 'month' => $month, 'value' => $value);
         *         }
         *     }
         * }
         */
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
