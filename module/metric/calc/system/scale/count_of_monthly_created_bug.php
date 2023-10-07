<?php
/**
 * 按系统统计的月度新增Bug数。
 * Count of monthly created bug.
 *
 * 范围：system
 * 对象：bug
 * 目的：scale
 * 度量名称：按系统统计的月度新增Bug数
 * 单位：个
 * 描述：按系统统计的月度新增Bug数是指在一个月内新发现的Bug数量。反映了一个系统或软件每个月新增的Bug数量，用于评估及时发现问题、变更管理与影响评估、趋势分析与问题预测以及资源规划与优化等方面。通过跟踪和分析月度新增Bug数，可以及时发现质量问题、优化变更管理、预测系统质量趋势，并合理安排资源，从而提升系统的质量和可靠性。
 * 定义：所有Bug个数求和;创建时间为某年某月;过滤已删除的Bug;过滤已删除的产品;
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
