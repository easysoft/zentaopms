<?php
/**
 * 按项目统计的研发需求完成率。
 * Rate of finished story in project.
 *
 * 范围：project
 * 对象：story
 * 目的：rate
 * 度量名称：按项目统计的研发需求完成率
 * 单位：%
 * 描述：按项目统计的研发需求完成率表示按项目统计的已完成的研发需求数相对于按项目统计的有效研发需求数。衡量了项目研发团队完成需求的能力，完成率越高代表项目研发团队能够将需求交付给用户，实现正常发布的几率越大。
 * 定义：复用：;按项目统计的已完成研发需求数;按项目统计的有效研发需求数;公式：;按项目统计的研发需求完成率=按项目统计的已完成研发需求数/按项目统计的有效研发需求数*100%;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_finished_story_in_project extends baseCalc
{
    public $dataset = 'getDevStoriesWithProject';

    public $fieldList = array('t3.project', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        $project      = $row->project;
        $closedReason = $row->closedReason;

        if(!isset($this->result[$project]))
        {
            $this->result[$project]['finished'] = 0;
            $this->result[$project]['valid']    = 0;
        }
        if($closedReason == 'done') $this->result[$project]['finished'] += 1;
        if(!in_array($row->closedReason, array('duplicate','willnotdo','bydesign'))) $this->result[$project]['valid'] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $project => $value)
        {
            $finished = $value['finished'];
            $valid    = $value['valid'];
            $ratio    = $valid == 0 ? 0 : round($finished / $valid, 4);
            $records[] = array('project' => $project, 'value' => $ratio);
        }
        return $this->filterByOptions($records, $options);
    }
}
