<?php
/**
 * 按系统统计的每周完成研发需求规模数。
 * Scale of weekly finished story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的每周完成研发需求规模数
 * 单位：工时
 * 描述：按系统统计的每周完成研发需求规模数表示每周完成的研发需求的数量。反映了组织每周完成的研发需求数量，用于评估项目进度、资源规划、需求管理、团队绩效和质量控制的有用信息。它对于项目管理和团队协作具有重要意义，并可以帮助团队监控进度、优化资源利用和提高研发效率。
 * 定义：所有的研发需求个数求和;关闭时间为某周;关闭原因为已完成;过滤父需求;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class scale_of_weekly_finished_story extends baseCalc
{
    public $dataset = 'getStories';

    public $fieldList = array('t1.status', 't1.closedReason', 't1.closedDate', 't1.estimate', 't1.parent');

    public $result = array();

    public function calculate($row)
    {
        $parent = $row->parent;
        if($parent == '-1') return false;

        $year = $this->getYear($row->closedDate);
        $week = $this->getWeek($row->closedDate);

        if(!$year) return false;

        if($row->status == 'closed' and $row->closedReason == 'done')
        {
            if(!isset($this->result[$year])) $this->result[$year] = array();
            if(!isset($this->result[$year][$week])) $this->result[$year][$week] = 0;
            $this->result[$year][$week] += $row->estimate;
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'week', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
