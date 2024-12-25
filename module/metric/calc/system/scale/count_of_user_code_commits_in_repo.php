<?php
/**
 * 按系统统计的代码提交人数。
 * count of user commit repo.
 *
 * 范围：system
 * 对象：repo
 * 目的：scale
 * 度量名称：按系统统计的代码提交人数
 * 单位：个
 * 描述：按系统统计的代码提交人数所有代码库提交过的用户之和，通过统计一定时间范围内进行代码提交的独立开发人员数量，团队能够有效评估整体开发活跃度和团队协作情况。
 * 定义：所有代码库提交过的人数
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    liyang <liyang@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_user_code_commits_in_repo extends baseCalc
{
    public function getResult($options = array())
    {
        global $app;

        $count = 0;

        $system = $app->control->loadModel('instance')->getSystemServer('GitFox');
        $repos  = $app->control->loadModel('repo')->getGitFoxRepos();
        if(!empty($repos) && !empty($system))
        {
            $result = $app->control->loadModel('gitfox')->apiCountActiveRepos($system->id, array_keys($repos), '', '');
            $count  = $result ? $result->user_count : 0;
        }
        $records = array(array('value' => $count));
        return $this->filterByOptions($records, $options);
    }
}
