<?php
/**
 * 按项目统计的有效研发需求数。
 * Count of valid story in project.
 *
 * 范围：project
 * 对象：story
 * 目的：scale
 * 度量名称：按项目统计的有效研发需求数
 * 单位：个
 * 描述：按项目统计的有效研发需求数是指被确认为有效的研发需求数量。有效需求指的是符合项目策略和目标，可以实施并且对用户有价值的需求。通过对有效需求的统计，可以帮助项目团队评估项目需求的质量和重要性，并进行优先级排序和资源分配。较高的有效需求数量通常表示项目的功能和特性满足了用户和市场的期望，有利于实现项目的成功交付和用户满意度。
 * 定义：复用：;按项目统计的无效研发需求数;按项目统计的研发需求总数;公式：;按执行统计的有效研发需求数=按执行统计的研发需求总数-按执行统计的无效研发需求数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_valid_story_in_project extends baseCalc
{
    public $dataset = 'getDevStoriesWithProject';

    public $fieldList = array('t3.project', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        if(!isset($this->result[$project])) $this->result[$project] = 0;
        if(!in_array($row->closedReason, array('duplicate','willnotdo','bydesign'))) $this->result[$project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
