<?php
/**
 * 按项目统计的未关闭研发需求数。
 * Count of unclosed story in project.
 *
 * 范围：project
 * 对象：story
 * 目的：scale
 * 度量名称：按项目统计的未关闭研发需求数
 * 单位：个
 * 描述：按项目统计的未关闭研发需求数是指项目中未关闭的研发需求的数量反映了项目团队在开发过程中的进行中的任务和计划，未关闭研发需求数越多，说明项目团队未完成的开发工作越多，需要进一步跟进从而完成。
 * 定义：复用：;按项目统计的研发需求总数;按项目统计的已关闭研发需求数;公式：;按项目统计的关闭研发需求数=按项目统计的研发需求总数-按项目统计的已关闭研发需求数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_unclosed_story_in_project extends baseCalc
{
    public $dataset = 'getDevStoriesWithProject';

    public $fieldList = array('t3.project', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        $status  = $row->status;
        if(!isset($this->result[$project])) $this->result[$project] = 0;
        if($status != 'closed') $this->result[$project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
