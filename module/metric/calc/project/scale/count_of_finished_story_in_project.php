<?php
/**
 * 按项目统计的已完成研发需求数。
 * Count of finished story in project.
 *
 * 范围：project
 * 对象：story
 * 目的：scale
 * 度量名称：按项目统计的已完成研发需求数
 * 单位：个
 * 描述：按项目统计的已完成研发需求数是指状态为已关闭且关闭原因为已完成的研发需求的数量。反映了项目团队在开发过程中的进展和交付能力，已完成研发需求数越多，说明项目团队在该时间段内取得了更多的开发成果。
 * 定义：项目中研发需求的个数求和;状态为已关闭;关闭原因为已完成;过滤已删除的研发需求;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_finished_story_in_project extends baseCalc
{
    public $dataset = 'getDevStoriesWithProject';

    public $fieldList = array('t3.project', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        $project      = $row->project;
        $closedReason = $row->closedReason;

        if(!isset($this->result[$project])) $this->result[$project] = 0;
        if($closedReason == 'done') $this->result[$project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
