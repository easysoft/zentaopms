<?php
/**
 * 按系统统计的每日新增研发需求数。
 * Count of daily created story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的每日新增研发需求数
 * 单位：个
 * 描述：按系统统计的每日新增研发需求数表示每日新增加的研发需求的数量，可以用于评估组织的研发需求增长和规模扩展情况。
 * 定义：所有的研发需求个数求和;创建时间为某日;过滤已删除的研发需求;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_created_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.openedDate');

    public $result = array();

    public function calculate($row)
    {
        $openedDate = $row->openedDate;

        if(empty($openedDate)) return false;

        $year = substr($openedDate, 0, 4);
        if($year == '0000') return false;

        list($year, $month, $day) = explode('-', $openedDate);

        $day = substr($day, 0, 2);

        if(!isset($this->result[$year]))               $this->result[$year] = array();
        if(!isset($this->result[$year][$month]))       $this->result[$year][$month] = array();
        if(!isset($this->result[$year][$month][$day])) $this->result[$year][$month][$day] = 0;

        $this->result[$year][$month][$day] ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
