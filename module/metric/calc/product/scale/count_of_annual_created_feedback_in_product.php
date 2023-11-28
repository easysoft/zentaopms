<?php
/**
 * 按产品统计的年度新增反馈数。
 * Count of annual created feedback in product.
 *
 * 范围：product
 * 对象：feedback
 * 目的：scale
 * 度量名称：按产品统计的年度新增反馈数
 * 单位：个
 * 描述：按产品统计的年度新增反馈数是指在某年度收集到的用户反馈的数量。这个度量项可以帮助团队了解用户对产品的发展趋势和需求变化，并进行产品策略的调整和优化。较高的年度新增反馈数可能暗示着产品的用户基础扩大或者功能迭代带来了更多用户参与，同时暗示产品问题可能有很多。
 * 定义：产品中创建时间为某年的反馈的个数求和;过滤已删除的反馈;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_feedback_in_product extends baseCalc
{
    public $dataset = 'getFeedbacks';

    public $fieldList = array('t1.product', 't1.openedDate');

    public $result = array();

    public function calculate($data)
    {
        $product    = $data->product;
        $openedDate = $data->openedDate;

        if(empty($openedDate)) return false;

        $year = substr($openedDate, 0, 4);

        if($year == '0000') return false;

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = 0;

        $this->result[$product][$year] += 1;
    }

    public function getResult($options = null)
    {
        $records = array();
        foreach($this->result as $product => $years)
        {
            foreach($years as $year => $value)
            {
                $records[] = array('product' => $product, 'year' => $year, 'value' => $value);
            }
        }

        return $this->filterByOptions($records, $options);
    }
}
