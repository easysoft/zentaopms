<?php
/**
 * 按执行统计的执行关闭时测试缺陷密度。
 * Test concentration in executionw when the execution closing.
 *
 * 范围：execution
 * 对象：execution
 * 目的：qc
 * 度量名称：按执行统计的执行关闭时测试缺陷密度
 * 单位：%
 * 描述：按执行统计的执行测试缺陷密度是指执行产生的有效Bug数与执行交付的研发需求数的比率。该度量项反映了团队交付的研发需求的质量，可以帮助团队识别研发中存在的潜在问题。
 * 定义：复用：按执行统计的执行关闭时已交付的研发需求规模数、按执行统计的新增有效Bug数；公式：按执行统计的新增有效Bug数/按执行统计的执行关闭时已交付的研发需求规模数
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Daitingting <daitingting@xirangit.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class test_concentration_in_execution_when_closing extends baseCalc
{
    public $result = array();

    public $initRecord = false;

    public $reuse = true;

    public $reuseMetrics = array('story' => 'scale_of_delivered_story_in_execution_when_closing', 'bug' => 'count_of_effective_bug_in_execution');

    public $reuseRule = '{bug} / {story}';

    public function calculate($metrics)
    {
        $bugs    = $metrics['bug'];
        $stories = $metrics['story'];
        if(empty($bugs) || empty($stories)) return false;

        $all = array_merge($bugs, $stories);

        $bugs    = $this->generateUniqueKey($bugs);
        $stories = $this->generateUniqueKey($stories);

        $executions = array_column($all, 'execution', 'execution');
        foreach($executions as $execution)
        {
            if(!isset($stories[$execution])) continue;

            $bug   = isset($bugs[$execution]) ? $bugs[$execution] : 0;
            $story = $stories[$execution];
            $this->result[$execution] = $story == 0 ? 0 : round($bug / $story, 4);
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
