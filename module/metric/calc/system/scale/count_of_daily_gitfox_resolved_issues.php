<?php
/**
 * 按系统统计的每日解决的问题数。
 * Count of daily resolved issues.
 *
 * 范围：system
 * 对象：issue
 * 目的：scale
 * 度量名称：按系统统计的每日解决的问题数
 * 单位：个
 * 描述：按系统统计的每日解决的问题数是指每日解决的问题总量，不包含未确认和已忽略的问题，用于评估团队每日的问题处理情况。
 * 定义：所有代码问题个数求和;解决时间为某日;过滤未确认和已忽略的问题;过滤已删除的问题;
 *
 * @copyright Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package
 * @uses      func
 * @link      https://www.zentao.net
 */
class count_of_daily_gitfox_resolved_issues extends baseCalc
{
    public $dataset = 'getGitfoxResolvedIssues';

    public $fieldList = array('t1.`resolvedDate`');

    public $result = array();

    public function calculate($row)
    {
        $resolvedDate = $row->resolvedDate;

        $year = $this->getYear($resolvedDate);
        if(!$year) return false;

        $month = substr($resolvedDate, 5, 2);
        $day   = substr($resolvedDate, 8, 2);

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
