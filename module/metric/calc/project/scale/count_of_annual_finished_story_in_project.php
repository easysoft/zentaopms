<?php
/**
 * 按项目统计的年度完成研发需求数。
 * Count of annual finished story in project.
 *
 * 范围：project
 * 对象：story
 * 目的：scale
 * 度量名称：按项目统计的年度完成研发需求数
 * 单位：个
 * 描述：按项目统计的年度完成研发需求数是指在某年度已关闭且关闭原因为已完成的研发需求数量。这个度量项可以反映项目团队在某年度的开发效率和成果。完成研发需求数量的增加说明项目团队在该年度内取得了更多的开发成果和交付物。
 * 定义：项目中研发需求的个数求和;关闭时间在某年;关闭原因为已完成;过滤已删除的研发需求;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_finished_story_in_project extends baseCalc
{
    public $dataset = 'getDevStoriesWithProject';

    public $fieldList = array('t3.project', 't1.closedDate', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        $project    = $row->project;
        $closedDate = $row->closedDate;

        if(empty($closedDate)) return false;
        $year = substr($closedDate, 0, 4);
        if($year == '0000') return false;

        if(!isset($this->result[$project])) $this->result[$project] = array();
        if(!isset($this->result[$project][$year])) $this->result[$project][$year] = 0;

        if($row->closedReason == 'done') $this->result[$project][$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'year', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
