<?php
/**
 * 按系统统计的每日代码提交次数
 * Count of daily commits.
 *
 * 范围：repo
 * 对象：commit
 * 目的：rate
 * 度量名称：按系统统计的每日代码提交次数
 * 单位：次
 * 描述：按系统统计的每日代码提交次数是指本系统每日的全部代码提交的数量，这个度量项可以反映组织的每日开发活动频率和代码更新情况。
 * 定义：所有代码库的代码提交次数求和，提交时间为某日。
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yang Li <liyang@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_code_commits extends baseCalc
{
    public $useSCM = true;

    public $result = array();

    /**
     * 通过API获取提交次数。
     * Get commits by API.
     *
     * @param  object $repo
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return object
     */
    public function getCommitCount($repo, $begin, $end)
    {
        $repo->client   = '';
        $repo->account  = '';
        $repo->encoding = 'utf-8';
        $repo->password = $repo->token;
        $repo->apiPath  = $repo->serverUrl . '/api/v1';
        $repo->SCM      = 'GitFox';
        $this->scm->setEngine($repo);

        $result = $this->scm->engine->getCodeFrequencyByRepo((string)$repo->gitfoxID, 'day', $begin, $end);
        if(empty($result)) return false;
        foreach($result->stats as $stats)
        {
            $repo->time        = $stats->key;
            $repo->commitCount = $stats->commits;
            $this->setResult($repo);
        }
    }

    /**
     * 设置结果集。
     * Set result set.
     *
     * @param  object $row
     * @access public
     * @return void
     */
    public function setResult($row)
    {
        $date = substr($row->time, 0, 10);
        list($year, $month, $day) = explode('-', $date);

        if(!isset($this->result[$year]))               $this->result[$year] = array();
        if(!isset($this->result[$year][$month]))       $this->result[$year][$month] = array();
        if(!isset($this->result[$year][$month][$day])) $this->result[$year][$month][$day] = 0;

        $this->result[$year][$month][$day] += $row->commitCount;
    }

    /**
     * 获取结果。
     * Get result.
     *
     * @param  array  $options
     * @access public
     * @return void
     */
    public function getResult($options = array())
    {
        if(empty($options))
        {
            $begin = date('Y-m-d', strtotime('-1 year'));
            $end   = date('Y-m-d');
        }
        else
        {
            $year  = (int)$options['year'];
            $month = (int)$options['month'];
            $day   = $options['day'];

            $begin = $end = $day;
            if(strpos($day, ',') !== false) list($end, $begin) = explode(',', $day);
            $begin = "{$year}-{$month}-{$begin}";
            $end   = "{$year}-{$month}-{$end}";
        }

        if(!empty($this->repos) && !empty($this->scm))
        {
            foreach($this->repos as $repo)
            {
                $this->getCommitCount($repo, $begin, $end);
            }
        }
        return $this->getRecords(array('year', 'month', 'day'));
    }
}
