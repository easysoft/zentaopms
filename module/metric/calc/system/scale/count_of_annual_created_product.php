<?php
/**
 * 按系统统计的年度新增产品数。
 * Count of annual created product.
 *
 * 范围：system
 * 对象：product
 * 目的：scale
 * 度量名称：按系统统计的年度新增产品数
 * 单位：个
 * 描述：按系统统计的年度新增产品数反映了组织每年新增加的产品数量，用于评估组织的产品创新能力和市场拓展情况。
 * 定义：所有的产品个数求和;创建时间为某年;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_product extends baseCalc
{
    public $dataset = 'getProducts';

    public $fieldList = array('t1.createdDate');

    public $result = array();

    public function calculate($data)
    {
        $createdDate = $data->createdDate;
        if(empty($createdDate)) return false;

        $year = substr($createdDate, 0, 4);
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
