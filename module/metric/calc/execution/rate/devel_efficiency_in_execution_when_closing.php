<?php
/**
 * 按执行统计的执行关闭时执行开发效率
 * Devel efficiency in execution when closing.
 *
 * 范围：execution
 * 对象：execution
 * 目的：rate
 * 度量名称：按执行统计的执行关闭时执行开发效率
 * 单位：%
 * 描述：按执行统计的执行开发效率是指执行交付研发需求规模数与执行所有任务消耗工时的比率。该度量项反映了执行的开发速度，可以帮助团队识别潜在问题并采取改进措施提高研发效率。
 * 定义：复用： 按执行统计的任务消耗工时数、按执行统计的执行关闭时已交付的研发需求规模数；公式：按执行统计的执行关闭时已交付的研发需求规模数/按执行统计的任务消耗工时数
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class devel_efficiency_in_execution_when_closing extends baseCalc
{
    public $result = array();

    public $reuse = true;

    public $reuseMetrics = array('storyScale' => 'scale_of_delivered_story_in_execution_when_closing', 'taskConsumed' => 'consume_of_task_in_execution');

    public $reuseRule = '{storyScale} / {taskConsumed}';

    public function calculate($metrics)
    {
        $storyScale   = $metrics['storyScale'];
        $taskConsumed = $metrics['taskConsumed'];
        if(empty($storyScale) || empty($taskConsumed)) return false;

        $all = array_merge($storyScale, $taskConsumed);
        $storyScale   = $this->generateUniqueKey($storyScale);
        $taskConsumed = $this->generateUniqueKey($taskConsumed);

        $executions = array_column($all, 'execution', 'execution');
        foreach($executions as $execution)
        {
            $storyScaleItem   = isset($storyScale[$execution])   ? $storyScale[$execution]   : 0;
            $taskConsumedItem = isset($taskConsumed[$execution]) ? $taskConsumed[$execution] : 0;

            $this->result[$execution] = $taskConsumedItem == 0 ? 0 : round($storyScaleItem / $taskConsumedItem, 4);
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
