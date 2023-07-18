<?php
/**
 * 按全局统计的年度关闭反馈数。
 * count_of_annual_closed_feedback.
 *
 * 范围：global
 * 对象：feedback
 * 目的：scale
 * 度量名称：按全局统计的年度关闭反馈数
 * 单位：个
 * 描述：按全局统计的年度关闭反馈数是指在某年度处理并关闭的用户反馈的数量。这个度量项可以帮助团队评估在某年度对用户反馈的响应能力和问题解决能力。较高的年度关闭反馈数可能暗示着团队能够高效地解决用户反馈并持续改进产品，提升用户满意度和产品质量。
 * 定义：所有的反馈个数求和
关闭时间为某年
过滤已删除的反馈
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
class count_of_annual_closed_feedback extends baseCalc
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