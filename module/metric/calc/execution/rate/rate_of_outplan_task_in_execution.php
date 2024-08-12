<?php
/**
 * 按执行统计的执行外任务消耗工时占比
 * Rate of outplan task in execution
 *
 * 范围：execution
 * 对象：effort
 * 目的：rate
 * 度量名称：按执行统计的执行外任务消耗工时占比
 * 单位：%
 * 描述：按执行统计的执行外任务消耗工时占比表示执行中非本期执行任务所消耗的工时在执行中开发人员可用工时的占比，可以帮助团队识别影响执行效率或计划完成率的影响因子。
 * 定义：按执行统计的执行外任务消耗工时占比=按执行统计的执行外任务消耗工时数/按执行统计的开发人员可用工时数
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_outplan_task_in_execution extends baseCalc
{
    public $result = array();

    public $reuse = true;

    public $reuseMetrics = array('consumed' => 'hour_of_outplan_task_in_execution', 'available' => 'hour_of_developer_available_in_execution');

    public $reuseRule = '{consumed} / {available}';

    public function calculate($metrics)
    {
        $consumedHours  = $metrics['consumed'];
        $availableHours = $metrics['available'];
        if(empty($consumedHours) || empty($availableHours)) return false;

        $all = array_merge($consumedHours, $availableHours);
        $consumedHours  = $this->generateUniqueKey($consumedHours);
        $availableHours = $this->generateUniqueKey($availableHours);

        $executions = array_column($all, 'execution', 'execution');
        foreach($executions as $execution)
        {
            $consumedHour  = isset($consumedHours[$execution])  ? $consumedHours[$execution]  : 0;
            $availableHour = isset($availableHours[$execution]) ? $availableHours[$execution] : 0;

            $this->result[$execution] = $availableHour == 0 ? 0 : round($consumedHour / $availableHour, 4);
        }
    }

    public function getResult($options = null)
    {
        $records = array();
        foreach($this->result as $execution => $value) $records[] = array('execution' => $execution, 'value' => $value);
        return $this->filterByOptions($records, $options);
    }

    public function generateUniqueKey($records)
    {
        $uniqueKeyRecords = array();
        foreach($records as $record)
        {
            $key = $record['execution'];
            $uniqueKeyRecords[$key] = $record['value'];
        }

        return $uniqueKeyRecords;
    }
}
