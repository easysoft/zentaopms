<?php
/**
 * 按系统统计的每日完成任务数。
 * Count of delayed finished task which finished.
 *
 * 范围：task
 * 对象：task
 * 目的：scale
 * 度量名称：按系统统计的每日完成任务数。
 * 单位：个
 * 描述：按系统统计的每日完成任务数。
 * 定义：已完成的任务个数求和;过滤已删除的任务;
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_delayed_finished_task extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public $dateCounts = array();

    public $startDate = '';

    public $endDate = '';

    public function calculate($row)
    {
        if(!empty($row->executionStartDate) && empty($this->startDate)) $this->startDate = $row->executionStartDate;
        if(!empty($row->executionEndDate)   && empty($this->endDate))   $this->endDate   = $row->executionEndDate;

        if(empty($row->finishedDate)) return false;

        $finishedDate = substr($row->finishedDate, 0, 10);
        if(!isset($this->dateCounts[$finishedDate])) $this->dateCounts[$finishedDate] = 0;

        $this->dateCounts[$finishedDate] ++;
    }

    public function getDatesBetween($startDate, $endDate)
    {
        $current = strtotime($startDate);
        $end     = strtotime($endDate);
        $dates   = [];

        while($current <= $end)
        {
            $dates[date('Y-m-d', $current)] = 0;

            $current += 86400;
        }
        return $dates;
    }

    public function getResult($options = array())
    {
        $dateList = $this->getDatesBetween($this->startDate, $this->endDate);
        foreach($dateList as $date => $value)
        {
            if(isset($this->dateCounts[$date])) $value = $this->dateCounts[$date];

            $this->result[$date] = $value;
        }

        $records = $this->getRecords(array('date', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
