<?php
/**
 * 按执行统计的有效研发需求数。
 * Count of valid story in execution.
 *
 * 范围：execution
 * 对象：story
 * 目的：scale
 * 度量名称：按执行统计的有效研发需求数
 * 单位：个
 * 描述：按执行统计的有效研发需求数是指被确认为有效的研发需求数量。有效需求指的是符合项目策略和目标，可以实施并且对用户有价值的需求。通过对有效需求的统计，可以帮助执行团队评估项目需求的质量和重要性，并进行优先级排序和资源分配。较高的有效需求数量通常表示执行的功能和特性满足了用户和市场的期望，有利于实现项目的成功交付和用户满意度。
 * 定义：复用：;按执行统计的无效研发需求数;按执行统计的研发需求总数;公式：;按执行统计的有效研发需求数=按执行统计的研发需求总数-按执行统计的无效研发需求数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_valid_story_in_execution extends baseCalc
{
    public $dataset = 'getDevStoriesWithExecution';

    public $fieldList = array('t3.project', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        $execution = $row->project;
        if(!isset($this->result[$execution])) $this->result[$execution] = 0;
        if(!in_array($row->closedReason, array('duplicate','willnotdo','bydesign'))) $this->result[$execution] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
