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
    public $dataset = 'getRepoCommits';

    public $fieldList = array('t1.id', 't1.name', 't1.serviceHost', 't1.serviceProject', 't1.SCM', 't2.time', 't1.client', 't1.path', 't1.account', 't1.password', 't1.encoding', 't3.url', 't3.token');

    public $result = array();

    public $apiPath = array(
        'Gitlab' => '%s/api/v4/projects/%s/repository/',
        'GitFox' => '%s/api/v1/repos/%s/+/'
    );

    /**
     * 通过API获取提交次数。
     * Get commits by API.
     *
     * @param  object $repo
     * @access public
     * @return object
     */
    public function getCommitCount($repo)
    {
        if(isset($this->result[$repo->id])) return false;

        $repo->client   = '';
        $repo->account  = '';
        $repo->encoding = 'utf-8';
        $repo->password = $repo->token;
        $repo->apiPath  = sprintf($this->apiPath[$repo->SCM], $repo->url, $repo->serviceProject);
        $this->scm->setEngine($repo);

        $begin = '2023-01-01';
        $end   = '2023-02-28';
        return $this->scm->getCommitCountByDate($begin, $end);
    }

    public function calculate($row)
    {
        if(in_array($row->SCM, array('Gitlab', 'GitFox')))
        {
            if(isset($this->result[$row->id])) return false;

            $row = $this->getCommitCount($row);
        }

        $date = substr($row->time, 0, 10);
        list($year, $month, $day) = explode('-', $date);

        if(!isset($this->result[$row->id]))                      $this->result[$row->product] = array();
        if(!isset($this->result[$row->id][$year]))               $this->result[$row->product][$year] = array();
        if(!isset($this->result[$row->id][$year][$month]))       $this->result[$row->product][$year][$month] = array();
        if(!isset($this->result[$row->id][$year][$month][$day])) $this->result[$row->product][$year][$month][$day] = 0;

        $this->result[$row->id][$year][$month][$day] += isset($row->count) ? $row->count : 0;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('repo', 'year', 'month', 'day'));
        return $this->filterByOptions($records, $options);
    }
}
