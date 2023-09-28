<?php
/**
 * 按人员统计的每日评审研发需求数。
 * Count of daily review story in user.
 *
 * 范围：user
 * 对象：story
 * 目的：scale
 * 度量名称：按人员统计的每日评审研发需求数
 * 单位：个
 * 描述：按人员统计的日评审研发需求数表示每个人每日评审的研发需求数量之和。反映了每个人每日评审研发需求的规模。该数值越大，说明工作量越大。
 * 定义：所有研发需求个数求和;评审者为某人;评审时间为某日;过滤已删除的研发需求;过滤已删除产品的研发需求;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_review_story_in_user extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.reviewedBy', 't1.reviewedDate');

    public $result = array();

    public function calculate($row)
    {
        $reviewedDate = $row->reviewedDate;
        $reviewedBy   = $row->reviewedBy;

        if(empty($reviewedDate) || empty($reviewedBy)) return false;

        $year = substr($reviewedDate, 0, 4);
        if($year == '0000') return false;

        $date = date("Y-m-d", strtotime($reviewedDate));
        list($year, $month, $day) = explode('-', $date);

        if(!isset($this->result[$reviewedBy]))                      $this->result[$reviewedBy] = array();
        if(!isset($this->result[$reviewedBy][$year]))               $this->result[$reviewedBy][$year] = array();
        if(!isset($this->result[$reviewedBy][$year][$month]))       $this->result[$reviewedBy][$year][$month] = array();
        if(!isset($this->result[$reviewedBy][$year][$month][$day])) $this->result[$reviewedBy][$year][$month][$day] = 0;

        $this->result[$reviewedBy][$year][$month][$day] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('user', 'year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
