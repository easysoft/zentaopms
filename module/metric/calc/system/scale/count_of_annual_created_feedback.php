<?php
/**
 * 按系统统计的年度新增反馈数。
 * Count of annual created feedback.
 *
 * 范围：system
 * 对象：feedback
 * 目的：scale
 * 度量名称：按系统统计的年度新增反馈数
 * 单位：个
 * 描述：按系统统计的年度新增反馈数是指在某年度收集到的用户反馈的数量。这个度量项可以帮助团队了解用户对产品的发展趋势和需求变化，并进行产品策略的调整和优化。较高的年度新增反馈数可能暗示着产品的用户基础扩大或者功能迭代带来了更多用户参与。
 * 定义：所有的反馈个数求和;创建时间为某年;过滤已删除的反馈;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_feedback extends baseCalc
{
    public $dataset = 'getFeedbacks';

    public $fieldList = array('openedDate');

    public $result = array();

    public function calculate($row)
    {
        if(empty($row->openedDate)) return false;

        $year = substr($row->openedDate, 0, 4);
        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = 0;
        $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $value) $records[] = array('year' => $year, 'value' => $value);
        return $this->filterByOptions($records, $options);
    }
}
