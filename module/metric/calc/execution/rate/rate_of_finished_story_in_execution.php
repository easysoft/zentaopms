<?php
/**
 * 按执行统计的研发需求完成率。
 * Rate of finished story in execution.
 *
 * 范围：execution
 * 对象：story
 * 目的：rate
 * 度量名称：按执行统计的研发需求完成率
 * 单位：%
 * 描述：按执行统计的研发需求完成率表示按执行统计的已完成的研发需求数相对于按执行统计的有效研发需求数。这个度量项衡量了执行研发团队完成需求的能力。
 * 定义：复用：;按执行统计的已完成研发需求数;按执行统计的有效研发需求数;公式：;按执行统计的研发需求完成率=按执行统计的已完成研发需求数/按执行统计的有效研发需求数*100%;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_finished_story_in_execution extends baseCalc
{
    public $dataset = 'getDevStoriesWithExecution';

    public $fieldList = array('t3.project', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        $execution    = $row->project;
        $closedReason = $row->closedReason;

        if(!isset($this->result[$execution]))
        {
            $this->result[$execution]['finished'] = 0;
            $this->result[$execution]['valid']    = 0;
        }
        if($closedReason == 'done') $this->result[$execution]['finished'] += 1;
        if(!in_array($row->closedReason, array('duplicate','willnotdo','bydesign'))) $this->result[$execution]['valid'] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $execution => $value)
        {
            $finished = $value['finished'];
            $valid    = $value['valid'];
            $ratio    = $valid == 0 ? 0 : round($finished / $valid, 4);
            $records[] = array('execution' => $execution, 'value' => $ratio);
        }
        return $this->filterByOptions($records, $options);
    }
}
