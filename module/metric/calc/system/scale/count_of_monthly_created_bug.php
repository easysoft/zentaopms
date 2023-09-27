<?php
/**
 * 按全局统计的月度新增Bug数。
 * Count of monthly created bug.
 *
 * 范围：global
 * 对象：bug
 * 目的：scale
 * 度量名称：按全局统计的月度新增Bug数
 * 单位：个
 * 描述：按全局统计的月度新增Bug数是指在一个月内新发现的Bug数量。这个度量项反映了系统或项目在一个月内出现的新缺陷数量。月度新增Bug数的增加可能需要关注质量控制和测试过程中的问题。
 * 定义：所有Bug个数求和;创建时间为某年某月;过滤已删除的Bug;过滤已删除的产品;
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
class count_of_monthly_created_bug extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.openedDate');

    public $result = array();

    public function calculate($row)
    {
        $openedDate = $row->openedDate;
        if(empty($openedDate)) return false;

        $year = substr($openedDate, 0, 4);
        if($year == '0000') return false;
        $month = substr($openedDate, 5, 2);

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = 0;
        $this->result[$year][$month] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'month', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
