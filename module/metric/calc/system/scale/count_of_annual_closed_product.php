<?php
/**
 * 按全局统计的年度结束产品数。
 * Count of annual closed product.
 *
 * 范围：global
 * 对象：product
 * 目的：scale
 * 度量名称：按全局统计的年度结束产品数
 * 单位：个
 * 描述：按全局统计的年度结束产品数表示每年停止研发和运营的产品数量。此度量项反映了组织每年停止研发和运营的产品数量，可以用于评估组织的产品组合调整和战略转型情况。
 * 定义：所有的产品个数求和;关闭时间为某年;过滤已删除的产品;（过滤影子产品）;
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_closed_product extends baseCalc
{
    public $dataset = 'getProducts';

    public $fieldList = array('t1.closedDate');

    public $result = array();

    public function calculate($data)
    {
        $closedDate = $data->closedDate;
        if(empty($closedDate)) return false;

        $year = substr($closedDate, 0, 4);
        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = 0;
        $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $value)
        {
            $records[] = array('year' => $year, 'value' => $value);
        }

        return $this->filterByOptions($records, $options);
    }
}
