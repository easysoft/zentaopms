<?php
/**
 * 按项目统计的所有研发需求规模数。
 * Scale of story in project.
 *
 * 范围：project
 * 对象：story
 * 目的：scale
 * 度量名称：按项目统计的所有研发需求规模数
 * 单位：工时
 * 描述：按项目统计的所有研发需求规模数表示研发需求的规模总数反映了项目研发需求的规模总数，可以用于评估项目团队的研发需求规模管理和成果。
 * 定义：项目中研发需求的规模数求和;过滤已删除的研发需求;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class scale_of_story_in_project extends baseCalc
{
    public $dataset = 'getDevStoriesWithProject';

    public $fieldList = array('t3.project', 't1.estimate', 't1.parent');

    public $result = array();

    public function calculate($row)
    {
        $project  = $row->project;
        $estimate = $row->estimate;
        $parent   = $row->parent;

        if($parent == '-1') return false;

        if(!isset($this->result[$project])) $this->result[$project] = 0;
        $this->result[$project] += $estimate;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
