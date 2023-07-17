<?php
/**
 * 按全局统计的年度新增反馈数。
 * count_of_annual_created_feedback.
 *
 * 范围：global
 * 对象：feedback
 * 目的：scale
 * 度量名称：按全局统计的年度新增反馈数
 * 单位：个
 * 描述：按全局统计的年度新增反馈数是指在某年度收集到的用户反馈的数量。这个度量项可以帮助团队了解用户对产品的发展趋势和需求变化，并进行产品策略的调整和优化。较高的年度新增反馈数可能暗示着产品的用户基础扩大或者功能迭代带来了更多用户参与。
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
class count_of_annual_created_feedback extends baseCalc
{
    public $dataset = null;

    public $fieldList = array();

    //public funtion getStatement($dao)
    //{
    //}

    public function calculate($data)
    {
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->result, $options);
    }
}