<?php
/**
 * 按人员统计的每日完成任务数。
 * Count of daily finished task in user.
 *
 * 范围：user
 * 对象：task
 * 目的：scale
 * 度量名称：按人员统计的每日完成任务数
 * 单位：个
 * 描述：按人员统计的日完成任务数表示每个人每日完成的任务数量之和。反映了每个人每日完成的任务规模。该数值越大，可能说明工作效率越高，任务完成速度越快。
 * 定义：某人某日完成的任务个数求和;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_finished_task_in_user extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.finishedDate', 't1.finishedBy');

    public $result = array();

    public function calculate($row)
    {
        $finishedDate = $row->finishedDate;
        $finishedBy   = $row->finishedBy;

        if(empty($finishedDate) || empty($finishedBy)) return false;

        $year = substr($finishedDate, 0, 4);
        if($year == '0000') return false;

        $date = date("Y-m-d", strtotime($finishedDate));
        list($year, $month, $day) = explode('-', $date);

        if(!isset($this->result[$finishedBy]))                      $this->result[$finishedBy] = array();
        if(!isset($this->result[$finishedBy][$year]))               $this->result[$finishedBy][$year] = array();
        if(!isset($this->result[$finishedBy][$year][$month]))       $this->result[$finishedBy][$year][$month] = array();
        if(!isset($this->result[$finishedBy][$year][$month][$day])) $this->result[$finishedBy][$year][$month][$day] = 0;

        $this->result[$finishedBy][$year][$month][$day] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('user', 'year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
