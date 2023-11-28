<?php
/**
 * 按执行统计的研发完成需求占比。
 * Rate of developed story in execution.
 *
 * 范围：execution
 * 对象：story
 * 目的：rate
 * 度量名称：按执行统计的研发完成需求占比
 * 单位：%
 * 描述：按执行统计的研发完成需求占比表示按执行统计的研发完成的研发需求规数相对于按产品统计的研发需求总数的比例。这个度量项衡量了执行中研发团队完成需求的数量，可以衡量团队的研发进展，帮助团队更好的安排研发资源。
 * 定义：复用：;按执行统计的研发完成的研发需求数;按执行统计的研发需求总数;公式：;按执行统计的研发完成需求占比=按执行统计的研发完成的研发需求数/按执行统计的研发需求总数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_developed_story_in_execution extends baseCalc
{
    public $dataset = 'getDevStoriesWithExecution';

    public $fieldList = array('t3.project', 't1.stage', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        $execution    = $row->project;
        $stage        = $row->stage;
        $closedReason = $row->closedReason;

        if(!isset($this->result[$execution]))
        {
            $this->result[$execution] = array();
            $this->result[$execution]['developed'] = 0;
            $this->result[$execution]['all']       = 0;
        }

        if(!in_array($stage, array('developed', 'testing', 'tested', 'verified', 'released')) && $closedReason == 'done') $this->result[$execution]['developed'] += 1;
        $this->result[$execution]['all'] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $execution => $value)
        {
            $developed = $value['developed'];
            $all       = $value['all'];

            $rate = $all == 0 ? 0 : round($developed / $all, 4);
            $records[] = array('execution' => $execution, 'value' => $rate);
        }
        return $this->filterByOptions($records, $options);
    }
}
