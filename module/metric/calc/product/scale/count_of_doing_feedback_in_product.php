<?php
/**
 * 按产品统计的处理中的反馈数。
 * Count of doing feedback in product.
 *
 * 范围：product
 * 对象：feedback
 * 目的：scale
 * 度量名称：按产品统计的处理中的反馈数。
 * 单位：个
 * 描述：按产品统计的处理中的反馈数表示产品中状态为处理中的反馈数量之和。该数值越大，说明团队并行处理的反馈越多，可以帮助团队了解当前的工作负载情况。
 * 定义：产品中所有反馈个数求和，状态为处理中，过滤已删除的反馈，过滤已删除的产品。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_doing_feedback_in_product extends baseCalc
{
    public $dataset = 'getFeedbacks';

    public $fieldList = array('t1.product', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        if($row->status == 'commenting')
        {
            if(!isset($this->result[$row->product])) $this->result[$row->product] = 0;
            $this->result[$row->product] ++;
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('product', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
