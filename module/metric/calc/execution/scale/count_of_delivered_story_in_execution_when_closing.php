<?php
/**
 * 按执行统计的执行关闭时已交付研发需求数
 * Count of delivered story in execution when closing
 *
 * 范围：execution
 * 对象：story
 * 目的：scale
 * 度量名称：按执行统计的执行关闭时已交付研发需求数
 * 单位：个
 * 描述：按执行统计的执行关闭时已交付研发需求数表示执行关闭时需求阶段为已发布或状态为已关闭且关闭原因为已完成的研发需求的数量。该度量项反映了执行关闭时能够交付给用户的研发需求的数量，可以用于评估执行团队的研发需求交付能力。
 * 定义：执行关闭时，满足以下条件的执行中研发需求个数求和，条件为：所处阶段为已发布或关闭原因为已完成，过滤已删除的研发需求，过滤已删除的执行，过滤已删除的项目，过滤已删除的产品。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_delivered_story_in_execution_when_closing extends baseCalc
{
    public $dataset = 'getDevStoriesWithExecution';

    public $fieldList = array('t4.id as execution', 't1.id', "if(t4.multiple = '1', t4.closedDate, t5.closedDate) as executionClosed", 't1.closedDate as storyClosedDate', 't1.releasedDate as storyReleasedDate', 't1.closedReason');

    public $result = array();

    public $initRecord = false;

    public function calculate($row)
    {
        // 如果项目的关闭时间大于等于需求的发布时间
        $condition1 = (!helper::isZeroDate($row->executionClosed) && !helper::isZeroDate($row->storyReleasedDate) && $row->executionClosed >= $row->storyReleasedDate);
        // 如果项目的关闭时间大于等于需求的关闭时间且需求的关闭原因为已完成
        $condition2 = (!helper::isZeroDate($row->executionClosed) && !helper::isZeroDate($row->storyClosedDate) && $row->executionClosed >= $row->storyClosedDate && $row->closedReason == 'done');

        if($condition1 || $condition2)
        {
            if(!isset($this->result[$row->execution])) $this->result[$row->execution] = 0;
            $this->result[$row->execution] ++;
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
