<?php
/**
 * 按系统统计的月度关闭项目数。
 * Count of monthly closed project.
 *
 * 范围：system
 * 对象：project
 * 目的：scale
 * 度量名称：按系统统计的月度关闭项目数
 * 单位：个
 * 描述：按系统统计的年度关闭项目数是指在某月度关闭的项目数量。这个度量项可以帮助团队了解某年度项目的执行情况和成果，并进行项目交付能力的评估。较高的年度关闭项目数表明团队在项目交付方面具有较高的效率。
 * 定义：所有的项目个数求和;关闭时间为某年某月;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_closed_project extends baseCalc
{
    public $dataset = 'getAllProjects';

    public $fieldList = array('t1.status', 't1.closedDate');

    public $result = array();

    public function calculate($row)
    {
        if(empty($row->closedDate) || $row->status != 'closed') return false;

        $year  = substr($row->closedDate, 0, 4);
        $month = substr($row->closedDate, 5, 2);

        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = 0;

        $this->result[$year][$month] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $months)
        {
            foreach($months as $month => $value)
            {
                $records[] = array('year' => $year, 'month' => $month, 'value' => $value);
            }
        }
        return $this->filterByOptions($records, $options);
    }
}
