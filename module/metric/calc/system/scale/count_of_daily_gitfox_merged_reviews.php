<?php
/**
 * 按系统统计的每日合并的代码评审数。
 * Count of daily merged code reviews.
 *
 * 范围：system
 * 对象：review
 * 目的：scale
 * 度量名称：按系统统计的每日合并的代码评审数
 * 单位：个
 * 描述：按系统统计的每日合并的代码评审数是指每日所有用户合并的代码评审总量，用于评估团队每日的代码合入情况。
 * 定义：所有代码评审个数求和;评审状态为已合并;合并时间为某日;
 *
 * @copyright Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package
 * @uses      func
 * @link      https://www.zentao.net
 */
class count_of_daily_gitfox_merged_reviews extends baseCalc
{
    public $dataset = 'getGitfoxMergedReviews';

    public $fieldList = array('t1.`mergedDate`');

    public $result = array();

    public function calculate($row)
    {
        $mergedDate = $row->mergedDate;

        $year = $this->getYear($mergedDate);
        if(!$year) return false;

        $month = substr($mergedDate, 5, 2);
        $day   = substr($mergedDate, 8, 2);

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
