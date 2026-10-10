<?php
/**
 * 按系统统计的每日发起的代码评审数。
 * Count of daily created code reviews.
 *
 * 范围：system
 * 对象：review
 * 目的：scale
 * 度量名称：按系统统计的每日发起的代码评审数
 * 单位：个
 * 描述：按系统统计的每日发起的代码评审数是指每日所有用户发起的代码评审总量，用于评估团队每日的代码评审情况。
 * 定义：所有代码评审个数求和;评审创建时间为某日;过滤已删除的代码评审;
 *
 * @copyright Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package
 * @uses      func
 * @link      https://www.zentao.net
 */
class count_of_daily_gitfox_created_reviews extends baseCalc
{
    public $dataset = 'getGitfoxCreatedReviews';

    public $fieldList = array('t1.`createdDate`');

    public $result = array();

    public function calculate($row)
    {
        $createdDate = $row->createdDate;

        $year = $this->getYear($createdDate);
        if(!$year) return false;

        $month = substr($createdDate, 5, 2);
        $day   = substr($createdDate, 8, 2);

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = array();
        if(!isset($this->result[$year][$month][$day])) $this->result[$year][$month][$day] = 0;

        $this->result[$year][$month][$day] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
