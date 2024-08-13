<?php
/**
 * 按执行统计的执行关闭时研发需求按计划完成率。
 * Rate of planned developed story in execution when closing
 *
 * 范围：execution
 * 对象：story
 * 目的：rate
 * 度量名称：按执行统计的执行关闭时研发需求按计划完成率
 * 单位：%
 * 描述：按执行统计的研发需求按计划完成率是指执行关闭时已交付的研发需求与执行开始时计划的研发需求数的比率。该度量项反映了团队能否按期完成规划的需求，可以帮助团队识别研发中存在的潜在问题。
 * 定义：复用： 按执行统计的执行关闭时已交付的研发需求数、按执行统计的截止执行开始当天的研发需求数；公式：按执行统计的执行关闭时已交付的研发需求数/按执行统计的截止执行开始当天的研发需求数
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Songchenxuan <songchenxuan@xirangit.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_planned_developed_story_in_execution_when_closing extends baseCalc
{
    public $result = array();

    public $initRecord = false;

    public $reuse = true;

    public $reuseMetrics = array('storyfinish' => 'count_of_delivered_story_in_execution_when_closing', 'storylink' => 'count_of_story_in_execution_when_starting');

    public $reuseRule = '{storyfinish} / {storylink}';

    public function calculate($metrics)
    {
        $storyFinish = $metrics['storyfinish'];
        $storyLink   = $metrics['storylink'];
        if(empty($storyLink) || empty($storyFinish)) return false;

        $all = array_merge($storyLink, $storyFinish);

        $storyFinish = $this->generateUniqueKey($storyFinish);
        $storyLink   = $this->generateUniqueKey($storyLink);

        $executions = array_column($all, 'execution', 'execution');
        foreach($executions as $execution)
        {
            $finish = isset($storyFinish[$execution]) ? $storyFinish[$execution] : 0;
            $link   = isset($storyLink[$execution])   ? $storyLink[$execution]   : 0;

            $this->result[$execution] = $link == 0 ? 0 : round($finish / $link, 4);
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
