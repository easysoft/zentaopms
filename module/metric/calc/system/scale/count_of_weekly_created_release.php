<?php
/**
 * 按系统统计的每周新增发布数。
 * Count of weekly created release.
 *
 * 范围：system
 * 对象：release
 * 目的：scale
 * 度量名称：按系统统计的每周新增发布数
 * 单位：个
 * 描述：按系统统计的每周新增发布数表示每周新增加的发布数量。反映了组织每周增加的发布数量，用于评估组织产品发布的速度和规模。
 * 定义：所有的发布个数求和;发布时间为某周;过滤已删除的发布;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_weekly_created_release extends baseCalc
{
    public $dataset = 'getReleases';

    public $fieldList = array('t1.date');

    public $result = array();

    public function calculate($row)
    {
        $year = $this->getYear($row->date);
        $week = $this->getWeek($row->date);

        if(!$year) return false;

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$week])) $this->result[$year][$week] = 0;
        $this->result[$year][$week] ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'week', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
