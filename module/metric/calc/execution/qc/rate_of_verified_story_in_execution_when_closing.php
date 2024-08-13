<?php
/**
 * 按执行统计的执行关闭时执行验收通过率。
 * The rate of verified story in execution when it closing.
 *
 * 范围：execution
 * 对象：execution
 * 目的：qc
 * 度量名称：按执行统计的执行关闭时执行验收通过率
 * 单位：%
 * 描述：按执行统计的执行验收通过率是指执行关闭时通过验收需求数量与执行所有需求的比率。该度量项反映了已完成的需求是否符合需求验收标准，可以帮助团队识别研发质量存在的潜在问题。
 * 定义：复用：按执行统计的执行关闭时验收通过的研发需求数、按执行统计的有效研发需求数；公式：按执行统计的执行关闭时已验收的研发需求数/按执行统计的有效研发需求数
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Daitingting <daitingting@xirangit.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_verified_story_in_execution_when_closing extends baseCalc
{
    public $result = array();

    public $reuse = true;

    public $reuseMetrics = array('verifiedStories' => 'count_of_verified_story_in_execution_when_closing', 'validStories' => 'count_of_valid_story_in_execution');

    public $reuseRule = '{verifiedStories} / {validStories}';

    public function calculate($metrics)
    {
        $verifiedStories = $metrics['verifiedStories'];
        $validStories    = $metrics['validStories'];

        if(empty($verifiedStories) || empty($validStories)) return false;

        $all = array_merge($verifiedStories, $validStories);

        $verifiedStories = $this->generateUniqueKey($verifiedStories);
        $validStories    = $this->generateUniqueKey($validStories);

        $executions = array_column($all, 'execution', 'execution');
        foreach($executions as $execution)
        {
            $verifiedStory = isset($verifiedStories[$execution]) ? $verifiedStories[$execution] : 0;
            $validStory    = isset($validStories[$execution]) ? $validStories[$execution] : 0;

            $this->result[$execution] = $validStory == 0 ? 0 : round($verifiedStory / $validStory, 4);
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
