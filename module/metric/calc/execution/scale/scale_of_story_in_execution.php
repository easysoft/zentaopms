<?php
/**
 * 按执行统计的研发需求规模数
 * Scale of story in execution.
 *
 * 范围：execution
 * 对象：story
 * 目的：scale
 * 度量名称：按执行统计的研发需求规模数
 * 单位：个
 * 描述：按执行统计的研发需求规模数表示执行中所有研发需求的总规模。这个度量项可以反映执行周期内团队需要进行研发的工作规模，可以用于评估执行团队的工作负载和研发成果。
 * 定义：执行中所有研发需求的规模数求和，过滤已删除的研发需求，过滤已删除的执行，过滤已删除的项目，过滤已删除的产品。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class scale_of_story_in_execution extends baseCalc
{
    public $dataset = 'getDevStoriesWithExecution';

    public $fieldList = array('t3.project', 't1.estimate', 't1.isParent');

    public $result = array();

    public function calculate($row)
    {
        if($row->isParent == '1') return;
        if(!isset($this->result[$row->project])) $this->result[$row->project] = 0;
        $this->result[$row->project] += $row->estimate;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
