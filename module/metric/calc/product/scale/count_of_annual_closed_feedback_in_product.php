<?php
/**
 * 按产品统计的年度关闭反馈数。
 * Count of annual closed feedback in product.
 *
 * 范围：product
 * 对象：feedback
 * 目的：scale
 * 度量名称：按产品统计的年度关闭反馈数
 * 单位：个
 * 描述：按产品统计的年度关闭反馈数是指在某年度处理并关闭的用户反馈的数量。这个度量项可以帮助产品团队评估在某年度对用户反馈的响应能力和问题解决能力。较高的年度关闭反馈数可能暗示着团队能够高效地解决用户反馈并持续改进产品，提升用户满意度和产品质量。
 * 定义：产品中关闭时间为某年的反馈的个数求和;过滤已删除的反馈;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_closed_feedback_in_product extends baseCalc
{
    public $dataset = 'getFeedbacks';

    public $fieldList = array('t1.product', 't1.closedDate');

    public $result = array();

    public function calculate($data)
    {
        $product    = $data->product;
        $closedDate = $data->closedDate;

        if(empty($closedDate)) return false;

        $year = substr($closedDate, 0, 4);

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
