<?php
/**
 * 按代码库统计的年度代码新增行数。
 * Count of year add rows in codebase.
 *
 * 范围：repo
 * 对象：rows
 * 目的：rate
 * 度量名称：按代码库统计的年度代码新增行数
 * 单位：个
 * 描述：按代码库统计的的年度代码新增行数是指代码库在某个年度的代码提交新增的代码行数量。这个度量项可以反映代码库的年度开发活动频率和代码更新情况。
 * 定义：代码库中代码新增行数求和，提交时间为某年。
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Li Yang<liyang@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_yearly_add_rows_in_codebase extends baseCalc
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

        $result = $this->scm->engine->getCodeFrequencyByRepo((string)$repo->gitfoxID, 'month', $begin, $end);
        if(empty($result)) return false;
        foreach($result->stats as $stats)
        {
            $repo->time      = $stats->key;
            $repo->additions = $stats->additions;
            $this->setResult($repo);
        }

        $this->setResult($repo);
    }
}
