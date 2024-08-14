<?php
/**
 * 按执行统计的执行关闭时验收通过的研发需求数。
 * Count of verified story in execution when the execution is closing.
 *
 * 范围：execution
 * 对象：story
 * 目的：scale
 * 度量名称：按执行统计的执行关闭时验收通过的研发需求数。
 * 单位：个
 * 描述：按执行统计的执行关闭时验收通过的研发需求数表示执行关闭时需求阶段为已验收、已发布或状态为已关闭且关闭原因为已完成的研发需求的数量。该度量项反映了执行关闭时能够验收通过的研发需求的数量，可以用于评估执行团队的研发效率和研发质量。
 * 定义：执行关闭时，满足以下条件的执行中研发需求个数求和，条件是：所处阶段为已验收、已发布或关闭原因为已完成的研发需求，过滤已删除的研发需求，过滤已删除的执行，过滤已删除的项目，过滤已删除的产品。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Dai Tingting <daitingting@xirangit.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_verified_story_in_execution_when_closing extends baseCalc
{
    public $dataset = 'getAllStoriesWithExecution';

    public $fieldList = array('t3.project', 't1.stage', 't1.closedReason', 't1.verifiedDate', 't1.releasedDate', 't1.closedDate AS storyClosedDate', "if(t4.multiple = '1', t4.closedDate, t5.closedDate) as executionClosed");

    public $result = array();

    public $initRecord = false;

    public function calculate($row)
    {
        if(!helper::isZeroDate($row->executionClosed))
        {
            $execution  = $row->project;
            $isVerified = $row->stage == 'verified' &&  $row->verifiedDate <= $row->executionClosed;
            $isReleased = $row->stage == 'released' && $row->releasedDate <= $row->executionClosed;
            $isClosed   = $row->closedReason == 'done' && $row->storyClosedDate <= $row->executionClosed;

            if($isVerified || $isReleased || $isClosed)
            {
                if(!isset($this->result[$execution])) $this->result[$execution] = 0;
                $this->result[$execution] += 1;
            }
        }
    }

    public function getResult($options = array())
    {
        $executions = $this->getExecutions();
        $closedExecutions = array_filter($executions, function($execution) { return $execution->status === 'closed'; });
        foreach($closedExecutions as $executionID => $executionInfo)
        {
            if(!isset($this->result[$executionID])) $this->result[$executionID] = 0;
        }

        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
