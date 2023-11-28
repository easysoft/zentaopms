<?php
/**
 * 按系统统计的每日完成任务数。
 * Count of daily finished task.
 *
 * 范围：system
 * 对象：task
 * 目的：scale
 * 度量名称：按系统统计的每日完成任务数
 * 单位：个
 * 描述：按系统统计的每日完成任务数是指每日完成的任务总量。该度量项可以用来评估团队或组织每日的工作效率和任务完成能力。
 * 定义：所有的任务个数求和;完成时间为某日;过滤已删除的任务;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_finished_task extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.finishedDate');

    public $result = array();

    public function calculate($row)
    {
        $finishedDate = $row->finishedDate;
        if(empty($finishedDate)) return false;

        $year = substr($finishedDate, 0, 4);
        if($year == '0000') return false;
        $month = substr($finishedDate, 5, 2);
        $day   = substr($finishedDate, 8, 2);

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = array();
        if(!isset($this->result[$year][$month][$day])) $this->result[$year][$month][$day] = 0;

        $this->result[$year][$month][$day] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
