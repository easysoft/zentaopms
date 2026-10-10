<?php
/**
 * 按系统统计的每日代码提交次数。
 * Count of daily code commits.
 *
 * 范围：system
 * 对象：commit
 * 目的：scale
 * 度量名称：按系统统计的每日代码提交次数
 * 单位：次
 * 描述：按系统统计的每日代码提交次数是指每日所有代码库的代码提交总量，基础数据通过 GitFox 统计接口获取。
 * 定义：所有代码库的代码提交次数求和;提交时间为某日;基础数据来源于GitFox代码提交统计接口;
 *
 * @copyright Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package
 * @uses      func
 * @link      https://www.zentao.net
 */
class count_of_daily_gitfox_commits extends baseCalc
{
    public $result = array();

    /**
     * 根据度量项的查询参数获取统计的起止日期。
     * Get the begin and end date by the options of the metric.
     *
     * @param  array  $options
     * @access public
     * @return array
     */
    public function getDateRange($options = array())
    {
        if(empty($options) || empty($options['year']) || empty($options['month']) || empty($options['day'])) return array('', '');

        $year  = (int)$options['year'];
        $month = (int)$options['month'];
        $day   = (string)$options['day'];

        $beginDay = $endDay = $day;
        if(strpos($day, ',') !== false) list($endDay, $beginDay) = explode(',', $day);

        $begin = sprintf('%04d-%02d-%02d', $year, $month, (int)$beginDay);
        $end   = sprintf('%04d-%02d-%02d', $year, $month, (int)$endDay);

        return array($begin, $end);
    }

    /**
     * 设置某天的提交次数。
     * Set the commit count of the given date.
     *
     * @param  string $date
     * @param  int    $count
     * @access public
     * @return void
     */
    public function setResult($date, $count)
    {
        $year  = $this->getYear($date);
        $month = substr($date, 5, 2);
        $day   = substr($date, 8, 2);

        if(!$year || empty($count)) return false;

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = array();

        $this->result[$year][$month][$day] = $count;
    }

    public function getResult($options = array())
    {
        global $app;

        list($begin, $end) = $this->getDateRange($options);
        if(empty($begin) || empty($end)) return false;

        /* 定时任务等场景下 $app->control 不是当前模块的 control，直接用 loadTarget 加载模型。 */
        $gitfox   = $app->loadTarget('gitfox');
        $response = $gitfox->requestSystem('/statistics/commits', 'GET', array('beginTime' => $begin, 'endTime' => $end));
        if(!empty($response->stats))
        {
            foreach($response->stats as $stat) $this->setResult($stat->date, $stat->count);
        }

        return $this->getRecords(array('year', 'month', 'day', 'value'));
    }
}
