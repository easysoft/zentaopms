<?php
/**
 * 按系统统计的代码提交次数。
 * Count of committed repo.
 *
 * 范围：system
 * 对象：repo
 * 目的：scale
 * 度量名称：按系统统计的代码提交次数
 * 单位：个
 * 描述：按系统统计的代码提交次数所有代码库提交次数之和，是衡量开发团队工作活跃度和迭代频率的重要指标。通过统计一定时间范围内的代码提交数量，团队能够有效评估开发进展，识别工作模式，并促进持续改进。
 * 定义：所有代码库提交次数之和   不统计已删除代码库 不统计已删除提交
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    liyang <liyang@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_code_commits_in_repo extends baseCalc
{
    public $useSCM = true;

    public function getResult($options = array())
    {
        $count = 0;

        if(!empty($this->repos) && !empty($this->scm))
        {
            $repo = current($this->repos);
            $repo->client   = '';
            $repo->account  = '';
            $repo->encoding = 'utf-8';
            $repo->password = $repo->token;
            $repo->apiPath  = $repo->serverUrl . '/api/v1';
            $this->scm->setEngine($repo);

            $result = $this->scm->engine->countActiveRepos(array_column($this->repos, 'gitfoxID'), '', '');
            $count  = $result ? $result->commit_count : 0;
        }
        $records = array(array('value' => $count));
        return $this->filterByOptions($records, $options);
    }
}
