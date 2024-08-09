<?php
/**
 * 按执行统计的研发需求计划负载。
 * Story planning load by execution statistics
 *
 * 范围：execution
 * 对象：execution
 * 目的：qc
 * 度量名称：按执行统计的研发需求计划负载
 * 单位：%
 * 描述：按执行统计的研发需求计划负载是指执行开始时计划的需求规模数与执行开发人员可用工时数的比率。该度量项反映了团队的工作负载，可以帮助团队进行资源调配和需求规划。
 * 定义：复用：按执行统计的截止执行开始当天研发需求规模数、按执行统计的开发人员可用工时；公式：按执行统计的截止执行开始当天研发需求规模数/按执行统计的开发人员可用工时
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Daitingting <daitingting@xirangit.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class workload_of_plan_in_execution extends baseCalc
{
    public $result = array();

    public $reuse = true;

    public $reuseMetrics = array('story' => 'scale_of_story_in_execution_when_starting', 'hour' => 'hour_of_developer_available_in_execution');

    public $reuseRule = '{story} / {hour}';

    public function calculate($metrics)
    {
        $stories = $metrics['story'];
        $hours   = $metrics['hour'];
        if(empty($hours) || empty($stories)) return false;

        $all = array_merge($hours, $stories);

        $stories = $this->generateUniqueKey($stories);
        $hours   = $this->generateUniqueKey($hours);

        $executions = array_column($all, 'execution', 'execution');
        foreach($executions as $execution)
        {
            $story = isset($stories[$execution]) ? $stories[$execution] : 0;
            $hour  = isset($hours[$execution]) ? $hours[$execution] : 0;

            if($story == 0) continue;
            $this->result[$execution] = round($story / $hour, 4);
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
