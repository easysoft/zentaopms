<?php
/**
 * 按人员统计每日代码提交次数。
 * Count of daily commits in user.
 *
 * 范围：user
 * 对象：commit
 * 目的：rate
 * 度量名称：按人员统计每日代码提交次数
 * 单位：个
 * 描述：按提交人统计的每日代码提交次数是指单个提交人每日的全部代码提交操作的量。代码提交次数反映了提交人每日的开发活动频率和代码更新情况。
 * 定义：所有代码提交次数求和 提交人为某人 提交时间为某日。
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yang Li <liyang@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_code_commits_in_user extends baseCalc
{
    public $dataset = 'getUsers';

    public $fieldList = array('t1.account', 't1.email');

    public $useSCM = true;

    public $result = array();

    public $rows = array();

    public function calculate($row)
    {
        if(!$row->email) $row->email = $row->account . '@gitfox.io';
        $this->rows[] = $row;
    }

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
    public function getUserCommitCount($account, $begin, $end)
    {
        if(isset($this->result[$account->account])) return false;
        $this->result[$account->account] = array();

        $repo = current($this->repos);
        $repo->account  = $account->account;
        $repo->email    = $account->email;
        $repo->client   = '';
        $repo->account  = '';
        $repo->encoding = 'utf-8';
        $repo->password = $repo->token;
        $repo->apiPath  = $repo->serverUrl . '/api/v1';
        $repo->SCM      = 'GitFox';
        $this->scm->setEngine($repo);

        $result = $this->scm->engine->getCodeFrequencyByUser($account->email, 'day', $begin, $end);
        if(empty($result)) return false;
        foreach($result->stats as $stats)
        {
            $account->time        = $stats->key;
            $account->commitCount = $stats->commits;
            $this->setResult($account);
        }

        $this->setResult($repo);
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
        if(!isset($row->time)) return false;
        $date = substr($row->time, 0, 10);
        list($year, $month, $day) = explode('-', $date);

        if(!isset($this->result[$row->account]))                      $this->result[$row->account] = array();
        if(!isset($this->result[$row->account][$year]))               $this->result[$row->account][$year] = array();
        if(!isset($this->result[$row->account][$year][$month]))       $this->result[$row->account][$year][$month] = array();
        if(!isset($this->result[$row->account][$year][$month][$day])) $this->result[$row->account][$year][$month][$day] = 0;

        $this->result[$row->account][$year][$month][$day] = $row->commitCount;
    }
