<?php
/**
 * 按代码库统计的每日代码提交次数。
 * Count of daily commits in codebase.
 *
 * 范围：repo
 * 对象：commit
 * 目的：scale
 * 度量名称：按代码库统计的每日代码提交次数
 * 单位：个
 * 描述：按代码库统计的的每日代码提交次数是指代码库每日的代码提交数量。这个度量项可以反映代码库每日开发活动频率和代码更新情况。
 * 定义：代码库中代码提交次数求和，提交时间为某日。
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_code_commits_in_codebase extends baseCalc
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
        if(isset($this->result[$repo->id])) return false;
        $this->result[$repo->id] = array();

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

        if(!isset($this->result[$row->id]))                      $this->result[$row->id] = array();
        if(!isset($this->result[$row->id][$year]))               $this->result[$row->id][$year] = array();
        if(!isset($this->result[$row->id][$year][$month]))       $this->result[$row->id][$year][$month] = array();
        if(!isset($this->result[$row->id][$year][$month][$day])) $this->result[$row->id][$year][$month][$day] = 0;

        $this->result[$row->id][$year][$month][$day] = $row->commitCount;
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
                if(isset($this->result[$repo->id])) continue;
                $this->getCommitCount($repo, $begin, $end);
            }
        }
        return $this->getRecords(array('repo', 'year', 'month', 'day'));
    }
}
