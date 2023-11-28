<?php
/**
 * 按项目统计的已关闭研发需求数。
 * Count of closed story in project.
 *
 * 范围：project
 * 对象：story
 * 目的：scale
 * 度量名称：按项目统计的已关闭研发需求数
 * 单位：个
 * 描述：按项目统计的已关闭研发需求数是指项目中已经关闭的研发需求的数量反映了项目中已经关闭的研发需求的数量，提供了关于需求管理、项目进度、质量控制、用户满意度和绩效评估的有用信息。
 * 定义：项目中研发需求个数求和;过滤已删除的研发需求;状态为已关闭;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_closed_story_in_project extends baseCalc
{
    public $dataset = 'getDevStoriesWithProject';

    public $fieldList = array('t3.project', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        $status  = $row->status;

        if(!isset($this->result[$project])) $this->result[$project] = 0;
        if($status == 'closed') $this->result[$project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
