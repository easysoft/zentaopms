<?php
/**
 * 按执行统计的研发需求总数。
 * Count of story in execution.
 *
 * 范围：execution
 * 对象：story
 * 目的：scale
 * 度量名称：按执行统计的研发需求总数
 * 单位：个
 * 描述：按执行统计的研发需求总数是指执行中创建和关联的所有研发需求的数量。该度量项反映了执行的规模和复杂度，为执行计划和资源分配提供了参考。
 * 定义：执行中研发需求个数求和;过滤已删除的研发需求;过滤已删除的执行;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_story_in_execution extends baseCalc
{
    public $dataset = 'getDevStoriesWithExecution';

    public $fieldList = array('t3.project');

    public $result = array();

    public function calculate($row)
    {
        $execution = $row->project;
        if(!isset($this->result[$execution])) $this->result[$execution] = 0;
        $this->result[$execution] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
