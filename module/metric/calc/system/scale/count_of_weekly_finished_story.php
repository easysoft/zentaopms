<?php
/**
 * 按全局统计的每周完成需求数。
 * Count of weekly finished story.
 *
 * 范围：global
 * 对象：story
 * 目的：scale
 * 度量名称：按全局统计的每周完成需求数
 * 单位：个
 * 描述：按全局统计的每周完成需求数是指每周已关闭且关闭原因为已完成的研发需求数量。这个度量项可以反映团队在每周的开发效率和成果。完成研发需求数量的增加说明团队在该年度内取得了更多的开发成果和交付物。
 * 定义：所有研发需求的个数求和;关闭时间在某周;关闭原因为已完成;过滤已删除的研发需求;过滤已删除的产品;
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_weekly_finished_story extends baseCalc
{
    public $dataset = 'getStories';

    public $fieldList = array('t1.status', 't1.closedReason', 't1.closedDate');

    public $result = array();

    public function calculate($row)
    {
        $year = $this->getYear($row->closedDate);
        $week = $this->getWeek($row->closedDate);

        if(!$year) return false;

        if($row->status == 'closed' and $row->closedReason == 'done')
        {
            if(!isset($this->result[$year])) $this->result[$year] = array();
            if(!isset($this->result[$year][$week])) $this->result[$year][$week] = 0;
            $this->result[$year][$week] ++;
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'week', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
