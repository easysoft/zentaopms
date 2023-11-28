<?php
declare(strict_types=1);
/**
 * The zen file of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
class mrZen extends mr
{
    /**
     * 获取合并请求的代码库项目信息。
     * Get the code base project information of the merge request.
     *
     * @param  object    $repo
     * @param  array     $MRList
     * @access protected
     * @return array
     */
    protected function getAllProjects(object $repo, array $MRList): array
    {
        $projects = array();
        if($repo->SCM == 'Gitlab')
        {
            $projectIds = array();
            foreach($MRList as $MR)
            {
                if($repo->id != $MR->repoID) continue;

                $projectIds[$MR->sourceProject] = $MR->sourceProject;
                $projectIds[$MR->targetProject] = $MR->targetProject;
            }
            $projects += $this->mr->getGitlabProjects($repo->serviceHost, $projectIds);
        }
        else
        {
            $projects += $this->mr->getAllProjects($repo->id, $repo->SCM);
        }
        return $projects;
    }

    /**
     * 向编辑合并请求页面添加数据。
     * Add data to the edit merge request page.
     *
     * @param  object    $MR
     * @param  string    $scm
     * @access protected
     * @return void
     */
    protected function assignEditData(object $MR, string $scm): void
    {
        $MR->canDeleteBranch = true;
        $branchPrivs = $this->loadModel($scm)->apiGetBranchPrivs($MR->hostID, $MR->sourceProject);
        foreach($branchPrivs as $priv)
        {
            if($MR->canDeleteBranch && $priv->name == $MR->sourceBranch) $MR->canDeleteBranch = false;
        }

        $targetBranchList = array();
        $branchList       = $this->loadModel($scm)->getBranches($MR->hostID, $MR->targetProject);
        foreach($branchList as $branch) $targetBranchList[$branch] = $branch;

        $jobList = array();
        if($MR->repoID)
        {
            $rawJobList = $this->loadModel('job')->getListByRepoID($MR->repoID);
            foreach($rawJobList as $rawJob) $jobList[$rawJob->id] = "[$rawJob->id] $rawJob->name";

            $this->view->repo = $this->loadModel('repo')->getByID($MR->repoID);
        }

        $this->view->title            = $this->lang->mr->edit;
        $this->view->MR               = $MR;
        $this->view->users            = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->jobList          = $jobList;
        $this->view->targetBranchList = $targetBranchList;

        $this->display();
    }
}
