<?php
/**
 * 按系统统计的月度修复Bug数。
 * Count of monthly fixed bug.
 *
 * 范围：system
 * 对象：bug
 * 目的：scale
 * 度量名称：按系统统计的月度修复Bug数
 * 单位：个
 * 描述：按系统统计的月度修复Bug数是指在一个月内解决并关闭的Bug数量。反映了一个系统或软件每个月修复的Bug数量，用于评估质量改进、故障管理、变更管理、资源规划以及趋势分析与问题预测等方面。通过跟踪和分析月度修复Bug数，可以及时发现和解决问题，改善系统的质量和可靠性。
 * 定义：所有Bug个数求和;状态为已关闭;解决方案为已解决;关闭时间为某年某月;过滤已删除的Bug;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_fixed_bug extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.closedDate', 't1.status', 't1.resolution');

    public $result = array();

    public function calculate($row)
    {
        $closedDate = $row->closedDate;
        if(empty($closedDate)) return false;

        $year = substr($closedDate, 0, 4);
        if($year == '0000') return false;
        $month = substr($closedDate, 5, 2);

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = 0;
        if($row->status == 'closed' and $row->resolution == 'fixed') $this->result[$year][$month] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'month', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
