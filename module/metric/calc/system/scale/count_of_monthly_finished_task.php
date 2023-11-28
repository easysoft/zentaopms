<?php
/**
 * 按系统统计的月度完成任务数。
 * Count of monthly finished task.
 *
 * 范围：system
 * 对象：task
 * 目的：scale
 * 度量名称：按系统统计的月度完成任务数
 * 单位：个
 * 描述：按系统统计的月度完成任务数是指在某月已经完成的任务总量。该度量项可以用来评估团队或组织在某月内的工作效率和完成能力。较高的月度完成任务数表示团队或组织在项目执行方面表现出较好的效率。
 * 定义：所有的任务个数求和;完成时间为某年某月;过滤已删除的任务;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_finished_task extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.closedDate');

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

        $this->result[$year][$month] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'month', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
